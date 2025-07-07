<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| SECURE DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file contains database settings using environment variables
| for better security. Copy your existing database.php settings here
| and replace hardcoded values with environment variables.
|
| To use this file, rename it to database.php after setting up your .env file
| -------------------------------------------------------------------
*/

// Load environment variables (you may need to implement a simple .env loader)
function loadEnv($file = '.env') {
    if (!file_exists($file)) {
        return false;
    }
    
    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        
        if (!array_key_exists($name, $_ENV)) {
            $_ENV[$name] = $value;
        }
    }
    return true;
}

// Load environment variables from root directory
loadEnv(FCPATH . '../.env');

// Helper function to get environment variable with default
function env($key, $default = null) {
    return isset($_ENV[$key]) ? $_ENV[$key] : $default;
}

$active_group = 'default';
$query_builder = TRUE;

$db['default'] = array(
    'dsn'       => '',
    'hostname'  => env('DB_HOSTNAME', 'localhost'),
    'port'      => env('DB_PORT', '3307'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'database'  => env('DB_DATABASE_PLANNING', 'eproc_perencanaan'),
    'dbdriver'  => 'mysqli',
    'dbprefix'  => '',
    'pconnect'  => FALSE,
    'db_debug'  => (env('ENVIRONMENT', 'development') !== 'production'),
    'cache_on'  => env('CACHE_ENABLED', 'false') === 'true',
    'cachedir'  => '',
    'char_set'  => 'utf8',
    'dbcollat'  => 'utf8_general_ci',
    'swap_pre'  => '',
    'encrypt'   => env('DB_SSL_ENABLED', 'false') === 'true',
    'compress'  => FALSE,
    'stricton'  => TRUE,  // Enable strict mode for better data integrity
    'failover'  => array(),
    'save_queries' => env('ENVIRONMENT', 'development') === 'development'
);

$db['eproc'] = array(
    'dsn'       => '',
    'hostname'  => env('DB_HOSTNAME', 'localhost'),
    'port'      => env('DB_PORT', '3307'),
    'username'  => env('DB_USERNAME', 'root'),
    'password'  => env('DB_PASSWORD', ''),
    'database'  => env('DB_DATABASE_MAIN', 'eproc'),
    'dbdriver'  => 'mysqli',
    'dbprefix'  => '',
    'pconnect'  => FALSE,
    'db_debug'  => (env('ENVIRONMENT', 'development') !== 'production'),
    'cache_on'  => env('CACHE_ENABLED', 'false') === 'true',
    'cachedir'  => '',
    'char_set'  => 'utf8',
    'dbcollat'  => 'utf8_general_ci',
    'swap_pre'  => '',
    'encrypt'   => env('DB_SSL_ENABLED', 'false') === 'true',
    'compress'  => FALSE,
    'stricton'  => TRUE,
    'failover'  => array(),
    'save_queries' => env('ENVIRONMENT', 'development') === 'development'
);

// Remove other database configurations for security
// Only keep what's necessary for your application 