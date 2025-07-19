<?php
echo "<h1>Debug Information for local.eproc.vms.com</h1>";
echo "<h2>Server Information</h2>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";
echo "<p><strong>Script Name:</strong> " . $_SERVER['SCRIPT_NAME'] . "</p>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Current Working Directory:</strong> " . getcwd() . "</p>";

echo "<h2>File System Check</h2>";
$files_to_check = [
    'index.php' => 'Main index.php',
    'system/core/CodeIgniter.php' => 'CodeIgniter core',
    'application/config/config.php' => 'Config file',
    'application/modules/main/controllers/Main.php' => 'Main controller',
    '../.env' => 'Environment file (parent dir)',
    '.env' => 'Environment file (current dir)'
];

foreach ($files_to_check as $file => $description) {
    $exists = file_exists($file);
    $status = $exists ? '✅ EXISTS' : '❌ MISSING';
    echo "<p><strong>$description:</strong> $status ($file)</p>";
}

echo "<h2>Directory Listing</h2>";
echo "<p><strong>Current directory contents:</strong></p>";
$files = scandir('.');
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $type = is_dir($file) ? '[DIR]' : '[FILE]';
        echo "<li>$type $file</li>";
    }
}
echo "</ul>";

echo "<h2>Environment Variables Test</h2>";
if (file_exists('../.env') || file_exists('.env')) {
    echo "<p>✅ .env file found, attempting to load...</p>";
    
    // Try to load environment manually
    $env_file = file_exists('../.env') ? '../.env' : '.env';
    $env_content = file_get_contents($env_file);
    
    if ($env_content) {
        echo "<p>✅ .env file readable</p>";
        
        // Parse BASE_URL from .env
        if (preg_match('/BASE_URL=(.+)/', $env_content, $matches)) {
            $base_url = trim($matches[1]);
            echo "<p><strong>BASE_URL from .env:</strong> $base_url</p>";
        } else {
            echo "<p>❌ BASE_URL not found in .env</p>";
        }
    } else {
        echo "<p>❌ .env file not readable</p>";
    }
} else {
    echo "<p>❌ .env file not found</p>";
}

echo "<h2>CodeIgniter Test</h2>";
echo "<p>Attempting to load CodeIgniter manually...</p>";

try {
    // Set basic constants
    if (!defined('ENVIRONMENT')) {
        define('ENVIRONMENT', 'development');
    }
    
    if (!defined('BASEPATH')) {
        define('BASEPATH', 'system/');
    }
    
    if (!defined('APPPATH')) {
        define('APPPATH', 'application/');
    }
    
    echo "<p>✅ Constants defined</p>";
    
    // Try to load config
    if (file_exists('application/config/config.php')) {
        $config = array();
        include 'application/config/config.php';
        echo "<p>✅ Config loaded</p>";
        
        if (isset($config['base_url'])) {
            echo "<p><strong>Config base_url:</strong> " . $config['base_url'] . "</p>";
        }
        
        if (isset($config['modules_locations'])) {
            echo "<p>✅ HMVC modules_locations configured</p>";
        } else {
            echo "<p>❌ HMVC modules_locations NOT configured</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error loading CodeIgniter: " . $e->getMessage() . "</p>";
}

echo "<h2>Recommendations</h2>";
echo "<p>If you see this page, PHP is working through the web server.</p>";
echo "<p>Next, try accessing: <a href='index.php'>index.php directly</a></p>";

echo "<hr>";
echo "<p><em>Generated at: " . date('Y-m-d H:i:s') . "</em></p>";
?> 