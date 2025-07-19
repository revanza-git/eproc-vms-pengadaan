<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>PHP Test Page</h1>";
echo "<p><strong>PHP Version:</strong> " . phpversion() . "</p>";
echo "<p><strong>Server Time:</strong> " . date('Y-m-d H:i:s') . "</p>";
echo "<p><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</p>";

// Test database connection
echo "<h2>Database Connection Test</h2>";
try {
    // Using same database config as the app (.env file)
    $host = 'localhost';
    $port = '3307';
    $dbname = 'eproc';
    $username = 'root';
    $password = 'Nusantara1234';
    
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<p style='color: green;'>✅ Database connection successful!</p>";
    
    // Test a simple query
    $stmt = $pdo->query("SELECT 1 as test");
    $result = $stmt->fetch();
    echo "<p style='color: green;'>✅ Database query test successful!</p>";
    
} catch(PDOException $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

// Test required PHP extensions
echo "<h2>Required PHP Extensions</h2>";
$required_extensions = ['mysqli', 'pdo', 'pdo_mysql', 'mbstring', 'json', 'curl', 'gd'];

foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>✅ $ext - Loaded</p>";
    } else {
        echo "<p style='color: red;'>❌ $ext - NOT Loaded</p>";
    }
}

// Test file permissions
echo "<h2>File Permissions Test</h2>";
$app_path = dirname(__FILE__) . '/app';
if (is_dir($app_path)) {
    echo "<p>App directory exists: $app_path</p>";
    if (is_readable($app_path)) {
        echo "<p style='color: green;'>✅ App directory is readable</p>";
    } else {
        echo "<p style='color: red;'>❌ App directory is NOT readable</p>";
    }
} else {
    echo "<p style='color: red;'>❌ App directory not found: $app_path</p>";
}

// Test index.php file
$index_file = dirname(__FILE__) . '/index.php';
if (file_exists($index_file)) {
    echo "<p style='color: green;'>✅ index.php exists</p>";
    if (is_readable($index_file)) {
        echo "<p style='color: green;'>✅ index.php is readable</p>";
    } else {
        echo "<p style='color: red;'>❌ index.php is NOT readable</p>";
    }
} else {
    echo "<p style='color: red;'>❌ index.php not found</p>";
}

echo "<h2>PHP Configuration</h2>";
echo "<p><strong>Memory Limit:</strong> " . ini_get('memory_limit') . "</p>";
echo "<p><strong>Max Execution Time:</strong> " . ini_get('max_execution_time') . " seconds</p>";
echo "<p><strong>Upload Max Filesize:</strong> " . ini_get('upload_max_filesize') . "</p>";
echo "<p><strong>Post Max Size:</strong> " . ini_get('post_max_size') . "</p>";

phpinfo();
?> 