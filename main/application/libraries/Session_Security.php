<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Session Security Library for VMS eProc
 * 
 * Provides advanced session management and security features
 * Compatible with PHP 5.6 and CodeIgniter 3.x
 */
class Session_Security {
    
    private $CI;
    private $session_timeout = 7200; // 2 hours
    private $regenerate_interval = 300; // 5 minutes
    
    public function __construct($params = array()) {
        $this->CI =& get_instance();
        
        // Configure session timeout
        if (isset($params['timeout'])) {
            $this->session_timeout = $params['timeout'];
        }
        
        // Configure regeneration interval
        if (isset($params['regenerate_interval'])) {
            $this->regenerate_interval = $params['regenerate_interval'];
        }
        
        log_message('info', 'Session_Security Library Initialized');
        
        // Start enhanced session management
        $this->initializeSecureSession();
    }
    
    /**
     * Initialize secure session with enhanced security measures
     */
    private function initializeSecureSession() {
        // Check if session is valid
        $this->validateSession();
        
        // Check for session timeout
        $this->checkSessionTimeout();
        
        // Regenerate session ID periodically
        $this->checkSessionRegeneration();
        
        // Validate session fingerprint
        $this->validateSessionFingerprint();
    }
    
    /**
     * Validate current session
     */
    private function validateSession() {
        // Check if user is logged in
        $admin = $this->CI->session->userdata('admin');
        $user = $this->CI->session->userdata('user');
        
        if (!$admin && !$user) {
            return; // No active session
        }
        
        // Validate session data integrity
        if ($admin && !$this->validateSessionData($admin)) {
            $this->destroySession('Invalid session data');
            return;
        }
        
        if ($user && !$this->validateSessionData($user)) {
            $this->destroySession('Invalid session data');
            return;
        }
    }
    
    /**
     * Check session timeout
     */
    private function checkSessionTimeout() {
        $last_activity = $this->CI->session->userdata('last_activity');
        
        if ($last_activity) {
            $time_elapsed = time() - $last_activity;
            
            if ($time_elapsed > $this->session_timeout) {
                $this->destroySession('Session timeout');
                return;
            }
        }
        
        // Update last activity
        $this->CI->session->set_userdata('last_activity', time());
    }
    
    /**
     * Check if session ID should be regenerated
     */
    private function checkSessionRegeneration() {
        $last_regeneration = $this->CI->session->userdata('last_regeneration');
        
        if (!$last_regeneration) {
            $last_regeneration = time();
            $this->CI->session->set_userdata('last_regeneration', $last_regeneration);
        }
        
        $time_elapsed = time() - $last_regeneration;
        
        if ($time_elapsed > $this->regenerate_interval) {
            $this->regenerateSession();
        }
    }
    
    /**
     * Validate session fingerprint to prevent session hijacking
     */
    private function validateSessionFingerprint() {
        $current_fingerprint = $this->generateSessionFingerprint();
        $stored_fingerprint = $this->CI->session->userdata('session_fingerprint');
        
        if (!$stored_fingerprint) {
            // First time - store fingerprint
            $this->CI->session->set_userdata('session_fingerprint', $current_fingerprint);
        } else if ($stored_fingerprint !== $current_fingerprint) {
            // Fingerprint mismatch - potential session hijacking
            $this->logSecurityEvent('session_hijacking_attempt', array(
                'stored_fingerprint' => $stored_fingerprint,
                'current_fingerprint' => $current_fingerprint,
                'ip' => $this->CI->input->ip_address(),
                'user_agent' => $this->CI->input->user_agent()
            ));
            
            $this->destroySession('Session fingerprint mismatch');
        }
    }
    
    /**
     * Generate session fingerprint based on user characteristics
     */
    private function generateSessionFingerprint() {
        $components = array(
            $this->CI->input->ip_address(),
            $this->CI->input->user_agent(),
            // Add more components as needed but be careful not to make it too strict
        );
        
        return md5(implode('|', $components));
    }
    
    /**
     * Validate session data structure
     */
    private function validateSessionData($session_data) {
        if (!is_array($session_data)) {
            return false;
        }
        
        // Check required fields for admin session
        $required_admin_fields = array('id', 'username', 'name');
        
        foreach ($required_admin_fields as $field) {
            if (!isset($session_data[$field]) || empty($session_data[$field])) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * Regenerate session ID
     */
    private function regenerateSession() {
        // Store old session data
        $old_session_data = $this->CI->session->all_userdata();
        
        // Regenerate session ID
        $this->CI->session->sess_regenerate(TRUE);
        
        // Restore session data
        foreach ($old_session_data as $key => $value) {
            if (strpos($key, 'ci_') !== 0) { // Skip CodeIgniter internal data
                $this->CI->session->set_userdata($key, $value);
            }
        }
        
        // Update regeneration timestamp
        $this->CI->session->set_userdata('last_regeneration', time());
        
        log_message('info', 'Session ID regenerated for security');
    }
    
    /**
     * Destroy session with logging
     */
    private function destroySession($reason = 'Unknown') {
        $this->logSecurityEvent('session_destroyed', array(
            'reason' => $reason,
            'ip' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent()
        ));
        
        $this->CI->session->sess_destroy();
        
        // Redirect to login page
        redirect(base_url());
    }
    
    /**
     * Enhanced login with security measures
     */
    public function secureLogin($user_data, $type = 'admin') {
        // Clear any existing session
        $this->CI->session->sess_destroy();
        
        // Start new session
        $this->CI->session->sess_regenerate(TRUE);
        
        // Set user data
        $this->CI->session->set_userdata($type, $user_data);
        
        // Set security metadata
        $this->CI->session->set_userdata(array(
            'login_time' => time(),
            'last_activity' => time(),
            'last_regeneration' => time(),
            'session_fingerprint' => $this->generateSessionFingerprint(),
            'login_ip' => $this->CI->input->ip_address(),
            'login_user_agent' => $this->CI->input->user_agent()
        ));
        
        // Log successful login
        $this->logSecurityEvent('secure_login', array(
            'user_id' => isset($user_data['id']) ? $user_data['id'] : 'unknown',
            'username' => isset($user_data['username']) ? $user_data['username'] : 'unknown',
            'type' => $type,
            'ip' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent()
        ));
        
        return true;
    }
    
    /**
     * Secure logout with cleanup
     */
    public function secureLogout() {
        $admin = $this->CI->session->userdata('admin');
        $user = $this->CI->session->userdata('user');
        
        // Log logout
        $this->logSecurityEvent('secure_logout', array(
            'user_id' => $admin ? (isset($admin['id']) ? $admin['id'] : 'unknown') : 
                        ($user ? (isset($user['id']) ? $user['id'] : 'unknown') : 'unknown'),
            'ip' => $this->CI->input->ip_address(),
            'session_duration' => time() - $this->CI->session->userdata('login_time')
        ));
        
        // Destroy session completely
        $this->CI->session->sess_destroy();
        
        // Optional: Clear session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        return true;
    }
    
    /**
     * Check if session is still valid
     */
    public function isSessionValid() {
        $admin = $this->CI->session->userdata('admin');
        $user = $this->CI->session->userdata('user');
        
        if (!$admin && !$user) {
            return false;
        }
        
        // Check session timeout
        $last_activity = $this->CI->session->userdata('last_activity');
        if ($last_activity && (time() - $last_activity) > $this->session_timeout) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get session information
     */
    public function getSessionInfo() {
        return array(
            'login_time' => $this->CI->session->userdata('login_time'),
            'last_activity' => $this->CI->session->userdata('last_activity'),
            'login_ip' => $this->CI->session->userdata('login_ip'),
            'current_ip' => $this->CI->input->ip_address(),
            'session_age' => time() - $this->CI->session->userdata('login_time'),
            'time_until_timeout' => $this->session_timeout - (time() - $this->CI->session->userdata('last_activity'))
        );
    }
    
    /**
     * Force session timeout for user
     */
    public function forceTimeout($user_id = null) {
        if ($user_id) {
            // In a real implementation, you might want to store active sessions
            // in database and invalidate specific user sessions
            $this->logSecurityEvent('forced_timeout', array('user_id' => $user_id));
        }
        
        $this->destroySession('Forced timeout');
    }
    
    /**
     * Update session activity
     */
    public function updateActivity() {
        $this->CI->session->set_userdata('last_activity', time());
    }
    
    /**
     * Check for concurrent sessions (if implemented with database storage)
     */
    public function checkConcurrentSessions($user_id) {
        // This would require database-based session storage
        // For now, just log the attempt
        $this->logSecurityEvent('concurrent_session_check', array('user_id' => $user_id));
        return true; // Allow for now
    }
    
    /**
     * Log security events
     */
    private function logSecurityEvent($event_type, $data = array()) {
        $log_entry = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'event_type' => $event_type,
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'data' => $data
        );
        
        // Write to security log
        $log_file = APPPATH . 'logs/session_security_' . date('Y-m-d') . '.log';
        $log_message = date('Y-m-d H:i:s') . ' - ' . $event_type . ' - ' . 
                      $this->CI->input->ip_address() . ' - ' . json_encode($data) . "\n";
        
        file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX);
        
        // Also log to CodeIgniter log
        log_message('info', 'Session Security: ' . $event_type . ' - ' . json_encode($data));
    }
    
    /**
     * Clean up expired sessions (if using database storage)
     */
    public function cleanupExpiredSessions() {
        // This would be implemented with database-based session storage
        $this->logSecurityEvent('session_cleanup', array('timestamp' => time()));
    }
    
    /**
     * Set session timeout dynamically
     */
    public function setSessionTimeout($timeout) {
        $this->session_timeout = $timeout;
        $this->CI->session->set_userdata('session_timeout', $timeout);
    }
    
    /**
     * Get remaining session time
     */
    public function getRemainingTime() {
        $last_activity = $this->CI->session->userdata('last_activity');
        if (!$last_activity) {
            return 0;
        }
        
        $elapsed = time() - $last_activity;
        $remaining = $this->session_timeout - $elapsed;
        
        return max(0, $remaining);
    }
} 