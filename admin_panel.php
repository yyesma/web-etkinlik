<?php
session_start();
if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: admin_giris.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Paneli</title>
    <style>
        body { font-family: Arial; margin: 20px; background: #f4f4f4; }
        h2 { color: #0b6fa4; }
        ul { list-style: none; padding: 0; }
        li { margin-bottom: 10px; }
        a {
            text-decoration: none;
            background: #0b6fa4;
            color: white;
            padding: 10px 15px;
            border-radius: 5px;
            display: inline-block;
        }
        a:hover {
            background: #094e75;
        }
    </style>
</head>
<body>

    <h2>👑 Yönetici Paneli</h2>
    <ul>
        <li><a href="admin_onay.php">👤 Kullanıcı Onayı</a></li>
        <li><a href="admin_etkinlik_ekle.php">➕ Etkinlik Ekle</a></li>
        <li><a href="admin_etkinlik_listele.php">📋 Etkinlik Listele / Düzenle / Yayınla</a></li>
        <li><a href="admin_duyuru_ekle.php">📢 Duyuru Ekle</a></li>
        <li><a href="admin_duyuru_listele.php">🔔 Duyuru Listele / Sil</a></li>
    </ul>

</body>
</html>
