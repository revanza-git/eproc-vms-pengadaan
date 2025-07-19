<?php
echo "Simple test from app directory<br>";
echo "PHP is working: " . phpversion() . "<br>";
echo "Current dir: " . getcwd() . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";

if (file_exists('index.php')) {
    echo "✅ index.php exists in app directory<br>";
} else {
    echo "❌ index.php missing in app directory<br>";
}

if (file_exists('../.env')) {
    echo "✅ .env exists in parent directory<br>";
} else {
    echo "❌ .env missing in parent directory<br>";
}

echo "<p><a href='index.php'>Try index.php</a></p>";
?> 