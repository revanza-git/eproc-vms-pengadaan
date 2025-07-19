<?php
echo "PHP is working through web server!<br>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Current time: " . date('Y-m-d H:i:s') . "<br>";
echo "Server: " . $_SERVER['SERVER_NAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";

// Test if .env file is accessible
if (file_exists('../.env')) {
    echo ".env file exists in parent directory<br>";
} else {
    echo ".env file NOT found in parent directory<br>";
}

// Test basic CodeIgniter files
$ci_files = [
    'system/core/CodeIgniter.php',
    'application/config/config.php',
    'application/modules/main/controllers/Main.php'
];

echo "<h3>CodeIgniter Files Check:</h3>";
foreach ($ci_files as $file) {
    if (file_exists($file)) {
        echo "✅ $file exists<br>";
    } else {
        echo "❌ $file missing<br>";
    }
}
?> 