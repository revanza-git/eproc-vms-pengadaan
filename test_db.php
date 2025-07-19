<?php
echo "Testing database connection...\n";

// Load environment
require_once('app/application/env_loader.php');
echo "Environment loaded.\n";

// Get database settings from environment
$host = env('DB_HOSTNAME', 'localhost');
$port = env('DB_PORT', 3307);
$user = env('DB_USERNAME', 'root');
$pass = env('DB_PASSWORD', '');
$database = env('DB_DATABASE_MAIN', 'eproc');

echo "DB Settings:\n";
echo "Host: $host\n";
echo "Port: $port\n";
echo "User: $user\n";
echo "Database: $database\n";

// Test basic connection
echo "Testing MySQL connection...\n";

try {
    $mysqli = @new mysqli($host, $user, $pass, '', $port);
    
    if ($mysqli->connect_error) {
        echo "MySQL connection FAILED: " . $mysqli->connect_error . "\n";
        echo "Error number: " . $mysqli->connect_errno . "\n";
    } else {
        echo "MySQL connection SUCCESSFUL!\n";
        
        // Test if database exists
        echo "Testing database '$database'...\n";
        $result = $mysqli->select_db($database);
        if ($result) {
            echo "Database '$database' exists and accessible!\n";
        } else {
            echo "Database '$database' NOT accessible: " . $mysqli->error . "\n";
        }
        
        $mysqli->close();
    }
} catch (Exception $e) {
    echo "Exception during MySQL connection: " . $e->getMessage() . "\n";
}

echo "Database test completed.\n"; 