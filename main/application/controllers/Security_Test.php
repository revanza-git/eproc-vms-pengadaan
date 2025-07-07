<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Security Test Controller
 * 
 * This controller provides endpoints to test security features
 * Remove this file in production environment
 */
class Security_Test extends MY_Controller {
    
    public function __construct() {
        parent::__construct();
        
        // Only allow in development/testing environment
        if (ENVIRONMENT === 'production') {
            show_404();
        }
        
        // Require admin login
        $this->check_permission('admin');
    }
    
    /**
     * Security test dashboard
     */
    public function index() {
        $data = array(
            'title' => 'Security Test Dashboard',
            'tests' => $this->getSecurityTests()
        );
        
        $this->load->view('template/header', $data);
        $this->load->view('security_test/dashboard', $data);
        $this->load->view('template/footer');
    }
    
    /**
     * Test input validation
     */
    public function test_input_validation() {
        $results = array();
        
        // Test different input types
        $test_inputs = array(
            'email' => array(
                'valid@example.com' => true,
                'invalid-email' => false,
                '<script>alert("xss")</script>@evil.com' => false
            ),
            'username' => array(
                'validuser123' => true,
                'user_name.test' => true,
                'user<script>' => false,
                'a' => false, // too short
                str_repeat('a', 100) => false // too long
            ),
            'text' => array(
                'Normal text' => true,
                '<script>alert("xss")</script>' => false,
                'SELECT * FROM users' => false
            ),
            'number' => array(
                '123' => true,
                '123.45' => false, // decimal not allowed by default
                'abc' => false,
                '-5' => true
            )
        );
        
        foreach ($test_inputs as $type => $inputs) {
            $results[$type] = array();
            foreach ($inputs as $input => $expected) {
                $result = $this->input_security->validate($input, $type);
                $results[$type][] = array(
                    'input' => $input,
                    'expected' => $expected,
                    'result' => ($result !== false),
                    'passed' => ($result !== false) === $expected,
                    'validated_value' => $result
                );
            }
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'results' => $results
            )));
    }
    
    /**
     * Test session security
     */
    public function test_session_security() {
        $session_info = $this->session_security->getSessionInfo();
        $security_log = $this->session_security->getSecurityLog();
        
        $results = array(
            'session_valid' => $this->session_security->isSessionValid(),
            'session_info' => $session_info,
            'remaining_time' => $this->session_security->getRemainingTime(),
            'recent_security_events' => array_slice($security_log, -10) // Last 10 events
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'results' => $results
            )));
    }
    
    /**
     * Test CSRF protection
     */
    public function test_csrf() {
        // Generate CSRF token
        $csrf_token = $this->security->get_csrf_hash();
        $csrf_name = $this->security->get_csrf_token_name();
        
        $results = array(
            'csrf_enabled' => $this->config->item('csrf_protection'),
            'csrf_token_name' => $csrf_name,
            'csrf_token' => $csrf_token,
            'regenerate_on_submit' => $this->config->item('csrf_regenerate'),
            'exclude_uris' => $this->config->item('csrf_exclude_uris')
        );
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'results' => $results
            )));
    }
    
    /**
     * Test security headers
     */
    public function test_headers() {
        $headers = array();
        
        // Check if headers are set
        $security_headers = array(
            'X-Content-Type-Options',
            'X-Frame-Options',
            'X-XSS-Protection',
            'Referrer-Policy',
            'Content-Security-Policy'
        );
        
        foreach ($security_headers as $header) {
            $headers[$header] = isset($_SERVER['HTTP_' . str_replace('-', '_', strtoupper($header))]);
        }
        
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode(array(
                'status' => 'success',
                'results' => array(
                    'https_enabled' => $this->input->is_https(),
                    'security_headers' => $headers,
                    'all_headers' => getallheaders()
                )
            )));
    }
    
    /**
     * Test file upload security
     */
    public function test_file_upload() {
        if ($this->input->method() === 'post') {
            // Test file upload validation
            $test_filenames = array(
                'document.pdf' => true,
                'image.jpg' => true,
                'script.php' => false,
                'malware.exe' => false,
                'safe_file.txt' => true,
                '../../../etc/passwd' => false
            );
            
            $results = array();
            foreach ($test_filenames as $filename => $expected) {
                $result = $this->input_security->validate($filename, 'filename');
                $results[] = array(
                    'filename' => $filename,
                    'expected' => $expected,
                    'result' => ($result !== false),
                    'passed' => ($result !== false) === $expected
                );
            }
            
            $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode(array(
                    'status' => 'success',
                    'results' => $results
                )));
        } else {
            // Show upload form
            $data['title'] = 'File Upload Security Test';
            $this->load->view('template/header', $data);
            $this->load->view('security_test/file_upload', $data);
            $this->load->view('template/footer');
        }
    }
    
    /**
     * Security audit report
     */
    public function audit_report() {
        $audit_results = array(
            'csrf_protection' => $this->config->item('csrf_protection'),
            'xss_protection' => true, // Always enabled in CI
            'session_security' => class_exists('Session_Security'),
            'input_validation' => class_exists('Input_Security'),
            'https_enabled' => $this->input->is_https(),
            'encryption_key_set' => !empty($this->config->item('encryption_key')),
            'database_security' => $this->testDatabaseSecurity(),
            'file_permissions' => $this->checkFilePermissions(),
            'error_reporting' => $this->checkErrorReporting()
        );
        
        // Calculate security score
        $total_checks = count($audit_results);
        $passed_checks = count(array_filter($audit_results));
        $security_score = round(($passed_checks / $total_checks) * 100);
        
        $data = array(
            'title' => 'Security Audit Report',
            'audit_results' => $audit_results,
            'security_score' => $security_score,
            'passed_checks' => $passed_checks,
            'total_checks' => $total_checks
        );
        
        $this->load->view('template/header', $data);
        $this->load->view('security_test/audit_report', $data);
        $this->load->view('template/footer');
    }
    
    /**
     * Get list of available security tests
     */
    private function getSecurityTests() {
        return array(
            array(
                'name' => 'Input Validation Test',
                'description' => 'Test input validation and sanitization',
                'url' => site_url('security_test/test_input_validation'),
                'type' => 'api'
            ),
            array(
                'name' => 'Session Security Test',
                'description' => 'Test session management and security',
                'url' => site_url('security_test/test_session_security'),
                'type' => 'api'
            ),
            array(
                'name' => 'CSRF Protection Test',
                'description' => 'Test CSRF token generation and validation',
                'url' => site_url('security_test/test_csrf'),
                'type' => 'api'
            ),
            array(
                'name' => 'Security Headers Test',
                'description' => 'Test security headers implementation',
                'url' => site_url('security_test/test_headers'),
                'type' => 'api'
            ),
            array(
                'name' => 'File Upload Security Test',
                'description' => 'Test file upload validation',
                'url' => site_url('security_test/test_file_upload'),
                'type' => 'page'
            ),
            array(
                'name' => 'Security Audit Report',
                'description' => 'Complete security audit and score',
                'url' => site_url('security_test/audit_report'),
                'type' => 'page'
            )
        );
    }
    
    /**
     * Test database security configuration
     */
    private function testDatabaseSecurity() {
        $config = $this->db->database;
        
        return array(
            'debug_disabled' => !$config['db_debug'],
            'strict_mode' => $config['stricton'],
            'save_queries_disabled' => !$config['save_queries']
        );
    }
    
    /**
     * Check file permissions
     */
    private function checkFilePermissions() {
        $files_to_check = array(
            APPPATH . 'config/database.php',
            APPPATH . 'config/config.php',
            APPPATH . 'logs/'
        );
        
        $results = array();
        foreach ($files_to_check as $file) {
            if (file_exists($file)) {
                $perms = fileperms($file);
                $results[basename($file)] = array(
                    'permissions' => substr(sprintf('%o', $perms), -4),
                    'readable' => is_readable($file),
                    'writable' => is_writable($file)
                );
            }
        }
        
        return $results;
    }
    
    /**
     * Check error reporting configuration
     */
    private function checkErrorReporting() {
        return array(
            'environment' => ENVIRONMENT,
            'error_reporting' => error_reporting(),
            'display_errors' => ini_get('display_errors'),
            'log_errors' => ini_get('log_errors')
        );
    }
} 