<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "etkinlik_sistemi";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Veritabanına bağlanılamadı: " . $conn->connect_error);
}
?>
