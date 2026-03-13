<?php
$host = "localhost";
$user = "root"; // Default user XAMPP
$pass = "";     // Default password XAMPP (kosong)
$db   = "qa_generator"; // Sesuai nama database yang Anda buat

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>