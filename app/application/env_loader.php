<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Define ENVIRONMENT constant early if not already defined
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'production');
}

/*
|--------------------------------------------------------------------------
| Environment Variables Loader (PHP 5.6 Compatible)
|--------------------------------------------------------------------------
|
| This file loads environment variables from .env file without external
| dependencies. Compatible with PHP 5.6 and CodeIgniter 3.
|
*/

/**
 * Simple .env file parser for PHP 5.6
 */
if (!function_exists('load_env_file')) {
function load_env_file() {
    // Find .env file in possible locations
    // More robust path resolution for different environments
    $current_dir = dirname(__FILE__); // application directory
    $app_dir = dirname($current_dir); // app directory
    $root_dir = dirname($app_dir); // project root
    
    $possible_paths = array(
        $root_dir . '/.env',  // Project root .env
        $app_dir . '/.env',   // App .env
    );
    
    // If FCPATH is defined, try additional paths
    if (defined('FCPATH')) {
        $possible_paths[] = FCPATH . '../.env';  // From app perspective
        $possible_paths[] = FCPATH . '.env';     // App .env
    }
    
    $env_file = null;
    foreach ($possible_paths as $path) {
        if (file_exists($path)) {
            $env_file = $path;
            break;
        }
    }
    
    if (!$env_file) {
        return false; // No .env file found
    }
    
    $lines = file($env_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($lines === false) {
        return false;
    }
    
    foreach ($lines as $line) {
        // Skip comments and empty lines
        $line = trim($line);
        if (empty($line) || strpos($line, '#') === 0) {
            continue;
        }
        
        // Parse KEY=VALUE format
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            
            // Remove quotes if present
            if ((substr($value, 0, 1) === '"' && substr($value, -1) === '"') ||
                (substr($value, 0, 1) === "'" && substr($value, -1) === "'")) {
                $value = substr($value, 1, -1);
            }
            
            // Set environment variable if not already set
            if (!getenv($key) && !isset($_ENV[$key])) {
                putenv("$key=$value");
                $_ENV[$key] = $value;
            }
        }
    }
    
    return true;
}
} // End function_exists check

// Load environment variables  
if (function_exists('load_env_file')) {
    load_env_file();
}

/**
 * Get environment variable with fallback
 * 
 * @param string $key Environment variable name
 * @param mixed $default Default value if not found
 * @return mixed
 */
if (!function_exists('env')) {
    function env($key, $default = null) {
        // Try getenv first
        $value = getenv($key);
        if ($value !== false) {
            // Convert string boolean values
            if (is_string($value)) {
                $lower = strtolower($value);
                if ($lower === 'true' || $lower === '(true)') {
                    return true;
                } elseif ($lower === 'false' || $lower === '(false)') {
                    return false;
                } elseif ($lower === 'null' || $lower === '(null)') {
                    return null;
                } elseif ($lower === 'empty' || $lower === '(empty)') {
                    return '';
                }
            }
            return $value;
        }
        
        // Try $_ENV
        if (isset($_ENV[$key])) {
            $value = $_ENV[$key];
            // Apply same conversions
            if (is_string($value)) {
                $lower = strtolower($value);
                if ($lower === 'true' || $lower === '(true)') {
                    return true;
                } elseif ($lower === 'false' || $lower === '(false)') {
                    return false;
                } elseif ($lower === 'null' || $lower === '(null)') {
                    return null;
                } elseif ($lower === 'empty' || $lower === '(empty)') {
                    return '';
                }
            }
            return $value;
        }
        
        return $default;
    }
} 