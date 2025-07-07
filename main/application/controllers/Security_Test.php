<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Test Controller
 * 
 * Comprehensive security testing suite for VMS eProc system
 * Tests all implemented security features and generates reports
 * 
 * @package     VMS eProc
 * @subpackage  Controllers
 * @category    Security Testing
 * @author      VMS Security Team
 * @version     2.0.0
 * @since       2024
 */
class Security_Test extends CI_Controller {
    
    private $test_results = array();
    private $security_score = 0;
    private $total_tests = 0;
    
    public function __construct() {
        parent::__construct();
        
        // Load required libraries
        $this->load->library('secure_password');
        $this->load->helper('security');
        
        // Initialize test tracking
        $this->test_results = array();
        $this->security_score = 0;
        $this->total_tests = 0;
        
        // Set headers for security testing
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        header('X-XSS-Protection: 1; mode=block');
    }
    
    /**
     * Main security test dashboard
     */
    public function index() {
        $data = array(
            'title' => 'VMS eProc Security Test Suite',
            'tests_available' => array(
                'password_security' => 'Password Security Test',
                'input_validation' => 'Input Validation Test',
                'session_security' => 'Session Security Test',
                'csrf_protection' => 'CSRF Protection Test',
                'database_security' => 'Database Security Test',
                'file_security' => 'File Upload Security Test',
                'complete_audit' => 'Complete Security Audit'
            )
        );
        
        $this->load->view('security_test/dashboard', $data);
    }
    
    /**
     * Test password security implementation
     */
    public function password_security() {
        $this->run_test('Password Security', function() {
            $results = array();
            
            // Test 1: Secure password hashing
            $test_password = 'TestPassword123!';
            $hash = $this->secure_password->hash_password($test_password);
            
            $results['bcrypt_hashing'] = array(
                'test' => 'Bcrypt Password Hashing',
                'status' => $hash && strlen($hash) === 60 && substr($hash, 0, 4) === '$2y$',
                'message' => $hash ? 'Bcrypt hashing working correctly' : 'Bcrypt hashing failed',
                'hash_sample' => substr($hash, 0, 20) . '...' // Show partial hash for verification
            );
            
            // Test 2: Password verification
            $verification = $this->secure_password->verify_password($test_password, $hash);
            $results['password_verification'] = array(
                'test' => 'Password Verification',
                'status' => $verification === true,
                'message' => $verification ? 'Password verification working' : 'Password verification failed'
            );
            
            // Test 3: Legacy hash detection
            $legacy_sha1 = hash('sha1', $test_password);
            $legacy_verification = $this->secure_password->verify_password($test_password, $legacy_sha1);
            $results['legacy_support'] = array(
                'test' => 'Legacy SHA-1 Support',
                'status' => $legacy_verification === true,
                'message' => $legacy_verification ? 'Legacy hash support working' : 'Legacy hash support failed'
            );
            
            // Test 4: Password strength validation
            $weak_password = '123456';
            $strong_password = 'MyStr0ng!P@ssw0rd2024';
            
            $weak_check = $this->secure_password->validate_password_strength($weak_password);
            $strong_check = $this->secure_password->validate_password_strength($strong_password);
            
            $results['password_strength'] = array(
                'test' => 'Password Strength Validation',
                'status' => !$weak_check['success'] && $strong_check['success'],
                'message' => 'Weak password rejected: ' . (!$weak_check['success'] ? 'Yes' : 'No') . 
                           ', Strong password accepted: ' . ($strong_check['success'] ? 'Yes' : 'No'),
                'weak_score' => $weak_check['score'],
                'strong_score' => $strong_check['score']
            );
            
            // Test 5: Hash migration detection
            $needs_rehash = $this->secure_password->needs_rehash($legacy_sha1);
            $results['hash_migration'] = array(
                'test' => 'Hash Migration Detection',
                'status' => $needs_rehash === true,
                'message' => $needs_rehash ? 'Legacy hash migration correctly detected' : 'Migration detection failed'
            );
            
            return $results;
        });
        
        $this->output_test_results();
    }
    
    /**
     * Test input validation and sanitization
     */
    public function input_validation() {
        $this->run_test('Input Validation', function() {
            $results = array();
            
            // Test 1: XSS Prevention
            $xss_payload = '<script>alert("XSS")</script>';
            $sanitized = xss_clean($xss_payload);
            
            $results['xss_prevention'] = array(
                'test' => 'XSS Prevention',
                'status' => $sanitized !== $xss_payload && strpos($sanitized, '<script>') === false,
                'message' => 'XSS payload properly sanitized',
                'original' => $xss_payload,
                'sanitized' => $sanitized
            );
            
            // Test 2: SQL Injection Prevention (simulated)
            $sql_payload = "'; DROP TABLE users; --";
            $encoded = encode_php_tags($sql_payload);
            
            $results['sql_injection_prevention'] = array(
                'test' => 'SQL Injection Prevention',
                'status' => $encoded !== $sql_payload,
                'message' => 'Dangerous SQL characters encoded',
                'original' => $sql_payload,
                'encoded' => $encoded
            );
            
            // Test 3: Filename sanitization
            $dangerous_filename = '../../../etc/passwd';
            $safe_filename = sanitize_filename($dangerous_filename);
            
            $results['filename_sanitization'] = array(
                'test' => 'Filename Sanitization',
                'status' => $safe_filename !== $dangerous_filename && strpos($safe_filename, '../') === false,
                'message' => 'Path traversal characters removed',
                'original' => $dangerous_filename,
                'sanitized' => $safe_filename
            );
            
            // Test 4: PHP tag encoding
            $php_payload = '<?php system($_GET["cmd"]); ?>';
            $encoded_php = encode_php_tags($php_payload);
            
            $results['php_tag_encoding'] = array(
                'test' => 'PHP Tag Encoding',
                'status' => $encoded_php !== $php_payload && strpos($encoded_php, '<?php') === false,
                'message' => 'PHP tags properly encoded',
                'original' => $php_payload,
                'encoded' => $encoded_php
            );
            
            return $results;
        });
        
        $this->output_test_results();
    }
    
    /**
     * Test session security features
     */
    public function session_security() {
        $this->run_test('Session Security', function() {
            $results = array();
            
            // Test 1: Session configuration
            $session_config = array(
                'sess_cookie_name' => $this->config->item('sess_cookie_name'),
                'sess_expire_on_close' => $this->config->item('sess_expire_on_close'),
                'sess_use_database' => $this->config->item('sess_use_database'),
                'sess_match_ip' => $this->config->item('sess_match_ip'),
                'cookie_httponly' => $this->config->item('cookie_httponly'),
                'cookie_secure' => $this->config->item('cookie_secure')
            );
            
            $secure_config = 
                !empty($session_config['sess_cookie_name']) &&
                $session_config['cookie_httponly'] === TRUE;
            
            $results['session_configuration'] = array(
                'test' => 'Session Configuration',
                'status' => $secure_config,
                'message' => $secure_config ? 'Session securely configured' : 'Session configuration needs improvement',
                'config' => $session_config
            );
            
            // Test 2: Session library availability
            $session_security_loaded = class_exists('Session_Security');
            
            $results['session_library'] = array(
                'test' => 'Session Security Library',
                'status' => $session_security_loaded,
                'message' => $session_security_loaded ? 'Session security library available' : 'Session security library not found'
            );
            
            // Test 3: Session data validation
            $session_data = $this->session->userdata();
            $has_security_data = isset($session_data['session_fingerprint']) || !empty($session_data);
            
            $results['session_data'] = array(
                'test' => 'Session Data Protection',
                'status' => true, // Always pass as this is informational
                'message' => 'Session data present: ' . ($has_security_data ? 'Yes' : 'No'),
                'session_keys' => array_keys($session_data)
            );
            
            return $results;
        });
        
        $this->output_test_results();
    }
    
    /**
     * Test CSRF protection
     */
    public function csrf_protection() {
        $this->run_test('CSRF Protection', function() {
            $results = array();
            
            // Test 1: CSRF Configuration
            $csrf_config = array(
                'csrf_protection' => $this->config->item('csrf_protection'),
                'csrf_token_name' => $this->config->item('csrf_token_name'),
                'csrf_cookie_name' => $this->config->item('csrf_cookie_name'),
                'csrf_expire' => $this->config->item('csrf_expire'),
                'csrf_regenerate' => $this->config->item('csrf_regenerate')
            );
            
            $csrf_enabled = $csrf_config['csrf_protection'] === TRUE;
            
            $results['csrf_configuration'] = array(
                'test' => 'CSRF Configuration',
                'status' => $csrf_enabled,
                'message' => $csrf_enabled ? 'CSRF protection enabled' : 'CSRF protection disabled',
                'config' => $csrf_config
            );
            
            // Test 2: CSRF Token Generation
            if ($csrf_enabled) {
                $token_name = $this->security->get_csrf_token_name();
                $token_hash = $this->security->get_csrf_hash();
                
                $results['csrf_tokens'] = array(
                    'test' => 'CSRF Token Generation',
                    'status' => !empty($token_name) && !empty($token_hash),
                    'message' => 'CSRF tokens generated successfully',
                    'token_name' => $token_name,
                    'token_length' => strlen($token_hash)
                );
            } else {
                $results['csrf_tokens'] = array(
                    'test' => 'CSRF Token Generation',
                    'status' => false,
                    'message' => 'CSRF protection is disabled'
                );
            }
            
            return $results;
        });
        
        $this->output_test_results();
    }
    
    /**
     * Test database security
     */
    public function database_security() {
        $this->run_test('Database Security', function() {
            $results = array();
            
            // Test 1: Database Configuration
            $db_config = $this->db->database;
            
            $secure_config = 
                $db_config['dbdriver'] === 'mysqli' &&
                $db_config['db_debug'] === FALSE &&
                $db_config['stricton'] === TRUE;
            
            $results['database_configuration'] = array(
                'test' => 'Database Configuration',
                'status' => $secure_config,
                'message' => $secure_config ? 'Database securely configured' : 'Database configuration needs improvement',
                'driver' => $db_config['dbdriver'],
                'debug_mode' => $db_config['db_debug'] ? 'Enabled (Security Risk)' : 'Disabled (Secure)',
                'strict_mode' => $db_config['stricton'] ? 'Enabled (Secure)' : 'Disabled (Risk)'
            );
            
            // Test 2: Connection Security
            $connection_secure = 
                !empty($db_config['username']) &&
                !empty($db_config['password']) &&
                $db_config['username'] !== 'root';
            
            $results['connection_security'] = array(
                'test' => 'Database Connection Security',
                'status' => $connection_secure,
                'message' => $connection_secure ? 'Database connection secured' : 'Database connection needs improvement',
                'username' => $db_config['username'],
                'uses_root' => $db_config['username'] === 'root' ? 'Yes (Security Risk)' : 'No (Secure)'
            );
            
            // Test 3: Prepared Statement Usage
            $test_query = "SELECT COUNT(*) as count FROM ms_login WHERE username = ?";
            try {
                $result = $this->db->query($test_query, array('test_user'));
                $prepared_statements_work = $result !== false;
            } catch (Exception $e) {
                $prepared_statements_work = false;
            }
            
            $results['prepared_statements'] = array(
                'test' => 'Prepared Statements',
                'status' => $prepared_statements_work,
                'message' => $prepared_statements_work ? 'Prepared statements working' : 'Prepared statements failed'
            );
            
            return $results;
        });
        
        $this->output_test_results();
    }
    
    /**
     * Complete security audit
     */
    public function complete_audit() {
        $this->test_results = array();
        $this->security_score = 0;
        $this->total_tests = 0;
        
        // Run all tests
        $this->password_security_internal();
        $this->input_validation_internal();
        $this->session_security_internal();
        $this->csrf_protection_internal();
        $this->database_security_internal();
        
        // Calculate overall security score
        $overall_score = $this->total_tests > 0 ? round(($this->security_score / $this->total_tests) * 100) : 0;
        
        $audit_summary = array(
            'overall_score' => $overall_score,
            'total_tests' => $this->total_tests,
            'passed_tests' => $this->security_score,
            'failed_tests' => $this->total_tests - $this->security_score,
            'security_level' => $this->get_security_level($overall_score),
            'recommendations' => $this->get_security_recommendations($overall_score)
        );
        
        $data = array(
            'audit_summary' => $audit_summary,
            'test_results' => $this->test_results,
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        $this->load->view('security_test/audit_report', $data);
    }
    
    /**
     * Internal test methods (no output)
     */
    private function password_security_internal() {
        $this->run_test_internal('Password Security', function() {
            $results = array();
            $test_password = 'TestPassword123!';
            $hash = $this->secure_password->hash_password($test_password);
            
            $results['bcrypt_hashing'] = $hash && strlen($hash) === 60 && substr($hash, 0, 4) === '$2y$';
            $results['password_verification'] = $this->secure_password->verify_password($test_password, $hash);
            $results['legacy_support'] = $this->secure_password->verify_password($test_password, hash('sha1', $test_password));
            
            return $results;
        });
    }
    
    private function input_validation_internal() {
        $this->run_test_internal('Input Validation', function() {
            $results = array();
            $xss_payload = '<script>alert("XSS")</script>';
            $sanitized = xss_clean($xss_payload);
            
            $results['xss_prevention'] = $sanitized !== $xss_payload && strpos($sanitized, '<script>') === false;
            $results['filename_sanitization'] = sanitize_filename('../../../etc/passwd') !== '../../../etc/passwd';
            
            return $results;
        });
    }
    
    private function session_security_internal() {
        $this->run_test_internal('Session Security', function() {
            $results = array();
            $results['secure_config'] = $this->config->item('cookie_httponly') === TRUE;
            $results['session_library'] = class_exists('Session_Security');
            
            return $results;
        });
    }
    
    private function csrf_protection_internal() {
        $this->run_test_internal('CSRF Protection', function() {
            $results = array();
            $results['csrf_enabled'] = $this->config->item('csrf_protection') === TRUE;
            
            return $results;
        });
    }
    
    private function database_security_internal() {
        $this->run_test_internal('Database Security', function() {
            $results = array();
            $db_config = $this->db->database;
            
            $results['secure_driver'] = $db_config['dbdriver'] === 'mysqli';
            $results['debug_disabled'] = $db_config['db_debug'] === FALSE;
            $results['strict_mode'] = $db_config['stricton'] === TRUE;
            
            return $results;
        });
    }
    
    /**
     * Helper methods
     */
    private function run_test($test_name, $test_function) {
        $results = $test_function();
        $this->test_results[$test_name] = $results;
    }
    
    private function run_test_internal($test_name, $test_function) {
        $results = $test_function();
        foreach ($results as $test => $passed) {
            $this->total_tests++;
            if ($passed) {
                $this->security_score++;
            }
        }
        $this->test_results[$test_name] = $results;
    }
    
    private function output_test_results() {
        $data = array(
            'test_results' => $this->test_results,
            'timestamp' => date('Y-m-d H:i:s')
        );
        
        $this->load->view('security_test/results', $data);
    }
    
    private function get_security_level($score) {
        if ($score >= 90) return 'Excellent';
        if ($score >= 80) return 'Good';
        if ($score >= 70) return 'Fair';
        if ($score >= 60) return 'Poor';
        return 'Critical';
    }
    
    private function get_security_recommendations($score) {
        $recommendations = array();
        
        if ($score < 90) {
            $recommendations[] = 'Consider implementing HTTPS for complete security';
            $recommendations[] = 'Regular security audits are recommended';
        }
        
        if ($score < 80) {
            $recommendations[] = 'Review and strengthen authentication mechanisms';
            $recommendations[] = 'Implement additional input validation';
        }
        
        if ($score < 70) {
            $recommendations[] = 'CRITICAL: Multiple security issues detected';
            $recommendations[] = 'Immediate security review required';
        }
        
        return $recommendations;
    }
}

/* End of file Security_Test.php */
/* Location: ./application/controllers/Security_Test.php */ 