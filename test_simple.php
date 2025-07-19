<?php
echo "Simple PHP test working!\n";

// Test if we can load CodeIgniter basics
define('BASEPATH', 'app/system/');
define('APPPATH', 'app/application/');
define('ENVIRONMENT', 'development');

echo "Constants defined.\n";

// Try to load the config
if (file_exists('app/application/config/config.php')) {
    echo "Config file exists.\n";
} else {
    echo "Config file NOT found.\n";
}

// Test env function
require_once('app/application/env_loader.php');
echo "Env loaded.\n";

$base_url = env('BASE_URL', 'default');
echo "Base URL from env: " . $base_url . "\n"; 