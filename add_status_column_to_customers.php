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
    $stmt = $pdo->query("SHOW COLUMNS FROM `customers` LIKE 'status'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $pdo->exec("ALTER TABLE `customers` ADD `status` VARCHAR(20) DEFAULT 'live' AFTER `case_number`");
        echo "Column 'status' added successfully to customers table.\n";
    } else {
        echo "Column 'status' already exists in customers table.\n";
    }
} catch (\PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
