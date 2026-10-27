<?php
// Kết nối MySQL XAMPP. Máy của bạn đang dùng cổng 3307.
$host = '127.0.0.1';
$port = '3307';
$dbname = 'quan_ly_ky_thi';
$username = 'root';
$password = '';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
} catch (PDOException $e) {
    http_response_code(500);
    exit("Không kết nối được MySQL. Hãy kiểm tra MySQL XAMPP đang chạy cổng 3307 và tài khoản trong config/database.php. Chi tiết: " .
        htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
}
