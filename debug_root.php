<?php
echo "<h1>Root Directory Debug</h1>";
echo "<p><strong>Current Directory:</strong> " . getcwd() . "</p>";
echo "<p><strong>Server Name:</strong> " . $_SERVER['SERVER_NAME'] . "</p>";
echo "<p><strong>Request URI:</strong> " . $_SERVER['REQUEST_URI'] . "</p>";
echo "<p><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</p>";

echo "<h2>Directory Contents:</h2>";
$files = scandir('.');
echo "<ul>";
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        $type = is_dir($file) ? '[DIR]' : '[FILE]';
        echo "<li>$type $file</li>";
    }
}
echo "</ul>";

echo "<h2>App Directory Check:</h2>";
if (is_dir('app')) {
    echo "<p>✅ app directory exists</p>";
    if (file_exists('app/index.php')) {
        echo "<p>✅ app/index.php exists</p>";
    } else {
        echo "<p>❌ app/index.php missing</p>";
    }
} else {
    echo "<p>❌ app directory missing</p>";
}

echo "<h2>Environment File Check:</h2>";
if (file_exists('.env')) {
    echo "<p>✅ .env exists in root</p>";
} else {
    echo "<p>❌ .env missing in root</p>";
}

echo "<p><a href='app/debug.php'>Test app/debug.php</a></p>";
echo "<p><a href='app/'>Try app/ directory</a></p>";
?> 