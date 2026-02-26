<?php
$host = '127.0.0.1';
$db = 'elitefintaxme-db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Check if column exists
    $stmt = $pdo->query("SHOW COLUMNS FROM `sheets` LIKE 'status'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $pdo->exec("ALTER TABLE `sheets` ADD `status` VARCHAR(20) DEFAULT 'live' AFTER `link`");
        echo "Column 'status' added successfully to sheets table.\n";
    } else {
        // If it exists but is integer (from docblock), maybe change it to varchar for live/dead
        $pdo->exec("ALTER TABLE `sheets` MODIFY `status` VARCHAR(20) DEFAULT 'live'");
        echo "Column 'status' modified to VARCHAR in sheets table.\n";
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
