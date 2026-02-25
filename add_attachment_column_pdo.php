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
    $stmt = $pdo->query("SHOW COLUMNS FROM `customer_email_logs` LIKE 'attachment'");
    $exists = $stmt->fetch();

    if (!$exists) {
        $pdo->exec("ALTER TABLE `customer_email_logs` ADD `attachment` VARCHAR(255) NULL AFTER `message`");
        echo "Column 'attachment' added successfully.\n";
    } else {
        echo "Column 'attachment' already exists.\n";
    }
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int) $e->getCode());
}
