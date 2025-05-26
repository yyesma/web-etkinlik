<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: admin_giris.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM etkinlikler WHERE id = $id");
header("Location: admin_etkinlik_listele.php");
exit;
?>
