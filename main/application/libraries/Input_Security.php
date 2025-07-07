<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Enhanced Input Security Library for VMS eProc
 * 
 * Provides comprehensive input validation, sanitization, and security features
 * Compatible with PHP 5.6 and CodeIgniter 3.x
 */
class Input_Security {
    
    private $CI;
    private $security_log = array();
    
    public function __construct() {
        $this->CI =& get_instance();
        log_message('info', 'Input_Security Library Initialized');
    }
    
    /**
     * Comprehensive input validation and sanitization
     * 
     * @param string $input The input to validate
     * @param string $type Validation type (email, text, number, etc.)
     * @param array $options Additional validation options
     * @return mixed Validated input or FALSE on failure
     */
    public function validate($input, $type = 'text', $options = array()) {
        if (empty($input) && !isset($options['allow_empty'])) {
            return false;
        }
        
        // Log validation attempt for security monitoring
        $this->logSecurityEvent('input_validation', array(
            'type' => $type,
            'length' => strlen($input),
            'ip' => $this->CI->input->ip_address()
        ));
        
        // Pre-sanitization
        $input = $this->preSanitize($input);
        
        switch ($type) {
            case 'email':
                return $this->validateEmail($input);
            
            case 'username':
                return $this->validateUsername($input, $options);
            
            case 'password':
                return $this->validatePassword($input, $options);
            
            case 'number':
                return $this->validateNumber($input, $options);
            
            case 'currency':
                return $this->validateCurrency($input);
            
            case 'text':
                return $this->validateText($input, $options);
            
            case 'textarea':
                return $this->validateTextarea($input, $options);
            
            case 'filename':
                return $this->validateFilename($input);
            
            case 'url':
                return $this->validateUrl($input);
            
            case 'date':
                return $this->validateDate($input);
            
            case 'phone':
                return $this->validatePhone($input);
            
            case 'npwp':
                return $this->validateNPWP($input);
            
            default:
                return $this->validateText($input, $options);
        }
    }
    
    /**
     * Pre-sanitization for all inputs
     */
    private function preSanitize($input) {
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);
        
        // Remove control characters except common ones
        $input = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $input);
        
        // Trim whitespace
        $input = trim($input);
        
        return $input;
    }
    
    /**
     * Email validation with security checks
     */
    private function validateEmail($email) {
        // Basic format validation
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        
        // Additional security checks
        if (strlen($email) > 255) {
            return false;
        }
        
        // Check for suspicious patterns
        $suspicious_patterns = array(
            '/script/i',
            '/javascript/i',
            '/vbscript/i',
            '/onload/i',
            '/onerror/i'
        );
        
        foreach ($suspicious_patterns as $pattern) {
            if (preg_match($pattern, $email)) {
                $this->logSecurityEvent('suspicious_email', array('email' => $email));
                return false;
            }
        }
        
        return strtolower($email);
    }
    
    /**
     * Username validation
     */
    private function validateUsername($username, $options = array()) {
        $min_length = isset($options['min_length']) ? $options['min_length'] : 3;
        $max_length = isset($options['max_length']) ? $options['max_length'] : 50;
        
        // Length check
        if (strlen($username) < $min_length || strlen($username) > $max_length) {
            return false;
        }
        
        // Allow only alphanumeric, underscore, dot, and hyphen
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $username)) {
            return false;
        }
        
        // Check for SQL injection patterns
        if ($this->detectSQLInjection($username)) {
            return false;
        }
        
        return $username;
    }
    
    /**
     * Password validation with strength requirements
     */
    private function validatePassword($password, $options = array()) {
        $min_length = isset($options['min_length']) ? $options['min_length'] : 8;
        $require_complex = isset($options['require_complex']) ? $options['require_complex'] : false;
        
        // Length check
        if (strlen($password) < $min_length) {
            return false;
        }
        
        // Complexity requirements
        if ($require_complex) {
            $has_upper = preg_match('/[A-Z]/', $password);
            $has_lower = preg_match('/[a-z]/', $password);
            $has_number = preg_match('/[0-9]/', $password);
            $has_special = preg_match('/[^a-zA-Z0-9]/', $password);
            
            if (!($has_upper && $has_lower && $has_number && $has_special)) {
                return false;
            }
        }
        
        return $password;
    }
    
    /**
     * Number validation
     */
    private function validateNumber($number, $options = array()) {
        // Allow decimals if specified
        $allow_decimal = isset($options['allow_decimal']) ? $options['allow_decimal'] : false;
        $min = isset($options['min']) ? $options['min'] : null;
        $max = isset($options['max']) ? $options['max'] : null;
        
        if ($allow_decimal) {
            if (!is_numeric($number)) {
                return false;
            }
            $number = floatval($number);
        } else {
            if (!ctype_digit(str_replace('-', '', $number))) {
                return false;
            }
            $number = intval($number);
        }
        
        // Range validation
        if ($min !== null && $number < $min) {
            return false;
        }
        
        if ($max !== null && $number > $max) {
            return false;
        }
        
        return $number;
    }
    
    /**
     * Currency validation (Indonesian Rupiah format)
     */
    private function validateCurrency($currency) {
        // Remove common currency formatting
        $currency = str_replace(array('Rp', '.', ',', ' '), '', $currency);
        
        return $this->validateNumber($currency, array('min' => 0));
    }
    
    /**
     * Text validation with XSS protection
     */
    private function validateText($text, $options = array()) {
        $max_length = isset($options['max_length']) ? $options['max_length'] : 255;
        $allow_html = isset($options['allow_html']) ? $options['allow_html'] : false;
        
        // Length check
        if (strlen($text) > $max_length) {
            return false;
        }
        
        // XSS protection
        if (!$allow_html) {
            $text = $this->xssClean($text);
        }
        
        // SQL injection detection
        if ($this->detectSQLInjection($text)) {
            return false;
        }
        
        return $text;
    }
    
    /**
     * Textarea validation with enhanced security
     */
    private function validateTextarea($text, $options = array()) {
        $max_length = isset($options['max_length']) ? $options['max_length'] : 5000;
        $allow_basic_html = isset($options['allow_basic_html']) ? $options['allow_basic_html'] : false;
        
        // Length check
        if (strlen($text) > $max_length) {
            return false;
        }
        
        if ($allow_basic_html) {
            // Allow only safe HTML tags
            $allowed_tags = '<p><br><strong><em><u><ul><ol><li>';
            $text = strip_tags($text, $allowed_tags);
        } else {
            $text = $this->xssClean($text);
        }
        
        return $text;
    }
    
    /**
     * Filename validation for uploads
     */
    private function validateFilename($filename) {
        // Remove path traversal attempts
        $filename = basename($filename);
        
        // Check for dangerous file extensions
        $dangerous_extensions = array(
            'php', 'php3', 'php4', 'php5', 'phtml', 
            'asp', 'aspx', 'jsp', 'js', 'exe', 
            'bat', 'cmd', 'com', 'scr', 'vbs'
        );
        
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($extension, $dangerous_extensions)) {
            $this->logSecurityEvent('dangerous_file_upload', array('filename' => $filename));
            return false;
        }
        
        // Validate filename format
        if (!preg_match('/^[a-zA-Z0-9._-]+$/', $filename)) {
            return false;
        }
        
        return $filename;
    }
    
    /**
     * URL validation
     */
    private function validateUrl($url) {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return false;
        }
        
        // Check for dangerous protocols
        $allowed_protocols = array('http', 'https', 'ftp', 'ftps');
        $protocol = parse_url($url, PHP_URL_SCHEME);
        
        if (!in_array($protocol, $allowed_protocols)) {
            return false;
        }
        
        return $url;
    }
    
    /**
     * Date validation
     */
    private function validateDate($date, $format = 'Y-m-d') {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) === $date ? $date : false;
    }
    
    /**
     * Phone number validation (Indonesian format)
     */
    private function validatePhone($phone) {
        // Remove formatting
        $phone = preg_replace('/[^0-9+]/', '', $phone);
        
        // Indonesian phone number patterns
        if (preg_match('/^(\+62|62|0)[0-9]{8,12}$/', $phone)) {
            return $phone;
        }
        
        return false;
    }
    
    /**
     * NPWP validation (Indonesian tax number)
     */
    private function validateNPWP($npwp) {
        // Remove formatting
        $npwp = preg_replace('/[^0-9]/', '', $npwp);
        
        // NPWP should be 15 digits
        if (strlen($npwp) === 15 && ctype_digit($npwp)) {
            return $npwp;
        }
        
        return false;
    }
    
    /**
     * XSS cleaning function
     */
    private function xssClean($str) {
        // Use CodeIgniter's security library if available
        if (isset($this->CI->security)) {
            return $this->CI->security->xss_clean($str);
        }
        
        // Basic XSS protection
        $str = htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
        
        // Remove potentially dangerous patterns
        $dangerous_patterns = array(
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/<iframe\b[^<]*(?:(?!<\/iframe>)<[^<]*)*<\/iframe>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload\s*=/i',
            '/onerror\s*=/i',
            '/onclick\s*=/i'
        );
        
        foreach ($dangerous_patterns as $pattern) {
            $str = preg_replace($pattern, '', $str);
        }
        
        return $str;
    }
    
    /**
     * SQL injection detection
     */
    private function detectSQLInjection($str) {
        $sql_patterns = array(
            '/(\b(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|EXEC|UNION)\b)/i',
            '/(\'|(\\x27)|(\\x2D)(-){2})/i',
            '/((\\x3C)|<)(\\x2F|\/)*[a-z0-9\\%]+(\\x3E|>)/i',
            '/(\\x3C|<)(\\x69|i|\\x49|I)(\\x6D|m|\\x4D|M)(\\x67|g|\\x47|G)/i',
            '/(\\x3C|<)[^\\n]+(\\x3E|>)/i'
        );
        
        foreach ($sql_patterns as $pattern) {
            if (preg_match($pattern, $str)) {
                $this->logSecurityEvent('sql_injection_attempt', array(
                    'input' => substr($str, 0, 100),
                    'ip' => $this->CI->input->ip_address(),
                    'user_agent' => $this->CI->input->user_agent()
                ));
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Security event logging
     */
    private function logSecurityEvent($event_type, $data = array()) {
        $log_entry = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'event_type' => $event_type,
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'data' => $data
        );
        
        // Add to memory log
        $this->security_log[] = $log_entry;
        
        // Write to file if critical event
        $critical_events = array('sql_injection_attempt', 'dangerous_file_upload', 'suspicious_email');
        if (in_array($event_type, $critical_events)) {
            $this->writeSecurityLog($log_entry);
        }
    }
    
    /**
     * Write security log to file
     */
    private function writeSecurityLog($log_entry) {
        $log_file = APPPATH . 'logs/security_' . date('Y-m-d') . '.log';
        $log_message = date('Y-m-d H:i:s') . ' - ' . $log_entry['event_type'] . ' - ' . 
                      $log_entry['ip_address'] . ' - ' . json_encode($log_entry['data']) . "\n";
        
        file_put_contents($log_file, $log_message, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Get security log for review
     */
    public function getSecurityLog() {
        return $this->security_log;
    }
    
    /**
     * Batch validation for forms
     */
    public function validateForm($data, $rules) {
        $validated = array();
        $errors = array();
        
        foreach ($rules as $field => $rule) {
            $value = isset($data[$field]) ? $data[$field] : '';
            $type = isset($rule['type']) ? $rule['type'] : 'text';
            $options = isset($rule['options']) ? $rule['options'] : array();
            $required = isset($rule['required']) ? $rule['required'] : true;
            
            if (empty($value) && $required) {
                $errors[$field] = isset($rule['label']) ? $rule['label'] . ' is required' : 'Field is required';
                continue;
            }
            
            if (!empty($value)) {
                $validated_value = $this->validate($value, $type, $options);
                if ($validated_value === false) {
                    $errors[$field] = isset($rule['label']) ? 'Invalid ' . $rule['label'] : 'Invalid field';
                } else {
                    $validated[$field] = $validated_value;
                }
            }
        }
        
        return array(
            'validated' => $validated,
            'errors' => $errors,
            'success' => empty($errors)
        );
    }
} 