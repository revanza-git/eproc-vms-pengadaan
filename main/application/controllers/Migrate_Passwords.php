<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Password Migration Controller
 * 
 * Safely migrates existing SHA-1 passwords to secure bcrypt hashing
 * 
 * ⚠️ SECURITY NOTICE: This controller should only be run by authorized administrators
 * and should be removed from production after migration is complete.
 * 
 * @package     VMS eProc
 * @subpackage  Controllers
 * @category    Security Migration
 * @author      VMS Security Team
 * @version     1.0.0
 * @since       2024
 */
class Migrate_Passwords extends CI_Controller {
    
    private $migration_log = array();
    private $processed_count = 0;
    private $migrated_count = 0;
    private $error_count = 0;
    
    public function __construct() {
        parent::__construct();
        
        // Load required libraries
        $this->load->library('secure_password');
        $this->load->database('eproc', TRUE); // Use eproc database
        
        // Security check - only allow migration in development/staging
        if (ENVIRONMENT === 'production') {
            show_error('Password migration is disabled in production for security. Use the automatic migration during login instead.', 403);
        }
        
        // Initialize migration tracking
        $this->migration_log = array();
        $this->processed_count = 0;
        $this->migrated_count = 0;
        $this->error_count = 0;
        
        log_message('info', 'Password Migration Controller initialized');
    }
    
    /**
     * Main migration dashboard
     */
    public function index() {
        // Check if running via CLI for security
        if (!$this->input->is_cli_request()) {
            show_error('This migration script should only be run via command line for security.', 403);
        }
        
        echo "VMS eProc Password Migration Tool\n";
        echo "==================================\n\n";
        echo "Available commands:\n";
        echo "1. analyze    - Analyze existing passwords\n";
        echo "2. migrate    - Migrate SHA-1 passwords to bcrypt\n";
        echo "3. verify     - Verify migration results\n";
        echo "4. report     - Generate migration report\n\n";
        echo "Usage: php index.php migrate_passwords [command]\n\n";
        echo "⚠️  WARNING: This will modify password data. Ensure you have backups!\n";
    }
    
    /**
     * Analyze existing passwords in the database
     */
    public function analyze() {
        echo "Analyzing existing passwords...\n";
        echo "=============================\n\n";
        
        $analysis = array(
            'total_users' => 0,
            'bcrypt_hashes' => 0,
            'sha1_hashes' => 0,
            'md5_hashes' => 0,
            'unknown_hashes' => 0,
            'empty_passwords' => 0
        );
        
        try {
            $query = "SELECT id, username, password FROM ms_login WHERE del != 1 OR del IS NULL";
            $users = $this->db->query($query)->result_array();
            
            $analysis['total_users'] = count($users);
            
            foreach ($users as $user) {
                if (empty($user['password'])) {
                    $analysis['empty_passwords']++;
                    continue;
                }
                
                $hash_type = $this->identify_hash_type($user['password']);
                $analysis[$hash_type . '_hashes']++;
                
                echo sprintf("User %-20s: %s\n", $user['username'], ucfirst($hash_type));
            }
            
            echo "\n" . str_repeat("=", 50) . "\n";
            echo "ANALYSIS SUMMARY\n";
            echo str_repeat("=", 50) . "\n";
            echo sprintf("Total Users:       %d\n", $analysis['total_users']);
            echo sprintf("Bcrypt (Secure):   %d\n", $analysis['bcrypt_hashes']);
            echo sprintf("SHA-1 (Needs Migration): %d\n", $analysis['sha1_hashes']);
            echo sprintf("MD5 (Needs Migration):   %d\n", $analysis['md5_hashes']);
            echo sprintf("Unknown Format:    %d\n", $analysis['unknown_hashes']);
            echo sprintf("Empty Passwords:   %d\n", $analysis['empty_passwords']);
            
            $needs_migration = $analysis['sha1_hashes'] + $analysis['md5_hashes'];
            echo sprintf("\nPasswords needing migration: %d\n", $needs_migration);
            
            if ($needs_migration > 0) {
                echo "\n⚠️  SECURITY WARNING: Legacy hashes detected!\n";
                echo "Run 'migrate' command to upgrade to secure bcrypt hashing.\n";
            } else {
                echo "\n✅ All passwords are using secure hashing!\n";
            }
            
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            log_message('error', 'Password analysis failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Migrate legacy passwords to bcrypt
     * 
     * NOTE: This only migrates the hash format. Users will still need to enter
     * their original password for the first login after migration.
     */
    public function migrate() {
        echo "Password Migration Process\n";
        echo "=========================\n\n";
        
        echo "⚠️  IMPORTANT NOTICE:\n";
        echo "This migration converts hash formats but does NOT change user passwords.\n";
        echo "Users will be prompted to re-enter their password on first login.\n";
        echo "Legacy hashes will be preserved as 'legacy_password' for verification.\n\n";
        
        echo "Starting migration...\n\n";
        
        try {
            // Start transaction for safety
            $this->db->trans_start();
            
            // Get all users with legacy hashes
            $query = "SELECT id, username, password FROM ms_login WHERE del != 1 OR del IS NULL";
            $users = $this->db->query($query)->result_array();
            
            foreach ($users as $user) {
                $this->processed_count++;
                
                if (empty($user['password'])) {
                    $this->log_migration($user['username'], 'SKIPPED', 'Empty password');
                    continue;
                }
                
                $hash_type = $this->identify_hash_type($user['password']);
                
                if ($hash_type === 'bcrypt') {
                    $this->log_migration($user['username'], 'ALREADY_SECURE', 'Already using bcrypt');
                    continue;
                }
                
                if ($hash_type === 'sha1' || $hash_type === 'md5') {
                    // Preserve legacy hash for verification
                    $update_data = array(
                        'legacy_password' => $user['password'],
                        'password' => null, // Clear password to force reset
                        'requires_password_reset' => 1,
                        'migration_date' => date('Y-m-d H:i:s')
                    );
                    
                    $updated = $this->db->where('id', $user['id'])->update('ms_login', $update_data);
                    
                    if ($updated) {
                        $this->migrated_count++;
                        $this->log_migration($user['username'], 'MIGRATED', 'Legacy hash preserved, password reset required');
                        echo sprintf("✓ Migrated: %-20s\n", $user['username']);
                    } else {
                        $this->error_count++;
                        $this->log_migration($user['username'], 'ERROR', 'Database update failed');
                        echo sprintf("✗ Failed:   %-20s\n", $user['username']);
                    }
                } else {
                    $this->log_migration($user['username'], 'UNKNOWN', 'Unknown hash format: ' . substr($user['password'], 0, 20));
                    echo sprintf("? Unknown:  %-20s\n", $user['username']);
                }
            }
            
            // Complete transaction
            $this->db->trans_complete();
            
            if ($this->db->trans_status() === FALSE) {
                echo "\n❌ Migration failed - all changes rolled back!\n";
                log_message('error', 'Password migration transaction failed');
            } else {
                echo "\n✅ Migration completed successfully!\n";
                echo "\nMIGRATION SUMMARY:\n";
                echo "==================\n";
                echo sprintf("Processed:  %d users\n", $this->processed_count);
                echo sprintf("Migrated:   %d users\n", $this->migrated_count);
                echo sprintf("Errors:     %d users\n", $this->error_count);
                echo sprintf("Skipped:    %d users\n", $this->processed_count - $this->migrated_count - $this->error_count);
                
                echo "\nNEXT STEPS:\n";
                echo "===========\n";
                echo "1. Users will be prompted to reset passwords on next login\n";
                echo "2. Run 'verify' command to check migration results\n";
                echo "3. Monitor login logs for any issues\n";
                echo "4. Remove this migration script after verification\n";
                
                log_message('info', 'Password migration completed successfully');
            }
            
        } catch (Exception $e) {
            $this->db->trans_rollback();
            echo "\n❌ Migration failed: " . $e->getMessage() . "\n";
            log_message('error', 'Password migration failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Verify migration results
     */
    public function verify() {
        echo "Verifying Migration Results\n";
        echo "===========================\n\n";
        
        try {
            // Check migration status
            $query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN requires_password_reset = 1 THEN 1 ELSE 0 END) as needs_reset,
                        SUM(CASE WHEN legacy_password IS NOT NULL THEN 1 ELSE 0 END) as has_legacy,
                        SUM(CASE WHEN password IS NULL THEN 1 ELSE 0 END) as null_passwords
                      FROM ms_login 
                      WHERE del != 1 OR del IS NULL";
                      
            $result = $this->db->query($query)->row_array();
            
            echo "VERIFICATION RESULTS:\n";
            echo "=====================\n";
            echo sprintf("Total Users:              %d\n", $result['total']);
            echo sprintf("Requiring Password Reset: %d\n", $result['needs_reset']);
            echo sprintf("With Legacy Hash Backup:  %d\n", $result['has_legacy']);
            echo sprintf("With Null Passwords:      %d\n", $result['null_passwords']);
            
            // Check for any remaining insecure hashes
            $insecure_query = "SELECT id, username, password 
                              FROM ms_login 
                              WHERE (del != 1 OR del IS NULL) 
                              AND password IS NOT NULL 
                              AND LENGTH(password) = 40"; // SHA-1 length
                              
            $insecure_users = $this->db->query($insecure_query)->result_array();
            
            if (count($insecure_users) > 0) {
                echo sprintf("\n⚠️  WARNING: %d users still have insecure hashes!\n", count($insecure_users));
                foreach ($insecure_users as $user) {
                    echo sprintf("  - %s: %s\n", $user['username'], substr($user['password'], 0, 20) . '...');
                }
            } else {
                echo "\n✅ No insecure hashes detected!\n";
            }
            
            // Check system readiness
            echo "\nSYSTEM READINESS:\n";
            echo "=================\n";
            echo "Secure_Password Library: " . (class_exists('Secure_Password') ? '✅ Loaded' : '❌ Missing') . "\n";
            echo "Bcrypt Support: " . (function_exists('password_hash') ? '✅ Available' : '⚠️ Using fallback') . "\n";
            echo "CSRF Protection: " . ($this->config->item('csrf_protection') ? '✅ Enabled' : '❌ Disabled') . "\n";
            
        } catch (Exception $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            log_message('error', 'Migration verification failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Generate detailed migration report
     */
    public function report() {
        $report_file = APPPATH . 'logs/password_migration_' . date('Y-m-d_H-i-s') . '.log';
        
        $report_content = "VMS eProc Password Migration Report\n";
        $report_content .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $report_content .= str_repeat("=", 50) . "\n\n";
        
        // Add migration log
        $report_content .= "MIGRATION LOG:\n";
        $report_content .= "==============\n";
        foreach ($this->migration_log as $entry) {
            $report_content .= sprintf("[%s] %s: %s - %s\n", 
                $entry['timestamp'], 
                $entry['username'], 
                $entry['status'], 
                $entry['message']
            );
        }
        
        // Add security recommendations
        $report_content .= "\nSECURITY RECOMMENDATIONS:\n";
        $report_content .= "==========================\n";
        $report_content .= "1. Remove this migration script after successful migration\n";
        $report_content .= "2. Monitor user login attempts for any issues\n";
        $report_content .= "3. Implement password complexity requirements\n";
        $report_content .= "4. Consider implementing multi-factor authentication\n";
        $report_content .= "5. Regular security audits and password policy reviews\n";
        
        // Write report to file
        if (file_put_contents($report_file, $report_content)) {
            echo "Migration report generated: " . $report_file . "\n";
        } else {
            echo "Failed to generate report file.\n";
        }
        
        // Display summary
        echo "\nREPORT SUMMARY:\n";
        echo "===============\n";
        echo sprintf("Processed: %d users\n", $this->processed_count);
        echo sprintf("Migrated:  %d users\n", $this->migrated_count);
        echo sprintf("Errors:    %d users\n", $this->error_count);
    }
    
    /**
     * Identify hash type based on format
     */
    private function identify_hash_type($hash) {
        if (empty($hash)) {
            return 'empty';
        }
        
        // Bcrypt hash pattern
        if (preg_match('/^\$2[axy]?\$\d{2}\$/', $hash)) {
            return 'bcrypt';
        }
        
        // SHA-1 hash pattern (40 hex characters)
        if (preg_match('/^[a-f0-9]{40}$/i', $hash)) {
            return 'sha1';
        }
        
        // MD5 hash pattern (32 hex characters)
        if (preg_match('/^[a-f0-9]{32}$/i', $hash)) {
            return 'md5';
        }
        
        return 'unknown';
    }
    
    /**
     * Log migration activity
     */
    private function log_migration($username, $status, $message) {
        $this->migration_log[] = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'username' => $username,
            'status' => $status,
            'message' => $message
        );
        
        log_message('info', "Password Migration - $username: $status - $message");
    }
}

/* End of file Migrate_Passwords.php */
/* Location: ./application/controllers/Migrate_Passwords.php */ 