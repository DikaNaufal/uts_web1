<?php
// includes/db.php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'uts_web1';
$DB_USER = 'root';
$DB_PASS = ''; // isi jika perlu

try {
    $pdo = new PDO("mysql:host={$DB_HOST};dbname={$DB_NAME};charset=utf8mb4",
                   $DB_USER, $DB_PASS, [
                       PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                       PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                   ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo "DB Connection failed: " . $e->getMessage();
    exit;
}
