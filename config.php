<?php
session_start();

$host = "localhost";
$db   = "checkin_activity";
$user = "root";
$pass = "";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลไม่สำเร็จ: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

function require_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}

function require_admin() {
    require_login();
    if ($_SESSION['role'] !== 'admin') {
        die("ไม่มีสิทธิ์เข้าหน้านี้");
    }
}
?>