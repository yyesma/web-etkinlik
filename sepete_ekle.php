<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $kaynak = $_POST["kaynak"];

    if ($kaynak === "veritabani") {
        $etkinlik_id = intval($_POST["etkinlik_id"]);

        $kontrol = $conn->prepare("SELECT id FROM sepet WHERE kullanici_id = ? AND etkinlik_kaynak = 'veritabani' AND etkinlik_id = ?");
        $kontrol->bind_param("ii", $kullanici_id, $etkinlik_id);
        $kontrol->execute();
        $kontrol->store_result();

        if ($kontrol->num_rows === 0) {
            $ekle = $conn->prepare("INSERT INTO sepet (kullanici_id, etkinlik_kaynak, etkinlik_id) VALUES (?, 'veritabani', ?)");
            $ekle->bind_param("ii", $kullanici_id, $etkinlik_id);
            $ekle->execute();
            $mesaj = "✅ Veritabanı etkinliği sepete eklendi.";
        } else {
            $mesaj = "❗ Bu veritabanı etkinliği zaten sepette.";
        }
    }

    if ($kaynak === "api") {
        $etkinlik_adi = $_POST["etkinlik_adi"];
        $tarih = $_POST["tarih"];
        $link = $_POST["link"];

        $kontrol = $conn->prepare("SELECT id FROM sepet WHERE kullanici_id = ? AND etkinlik_kaynak = 'api' AND etkinlik_adi = ?");
        $kontrol->bind_param("is", $kullanici_id, $etkinlik_adi);
        $kontrol->execute();
        $kontrol->store_result();

        if ($kontrol->num_rows === 0) {
            $ekle = $conn->prepare("INSERT INTO sepet (kullanici_id, etkinlik_kaynak, etkinlik_adi, tarih, link) VALUES (?, 'api', ?, ?, ?)");
            $ekle->bind_param("isss", $kullanici_id, $etkinlik_adi, $tarih, $link);
            $ekle->execute();
            $mesaj = "✅ Konser sepete eklendi.";
        } else {
            $mesaj = "❗ Bu konser zaten sepette.";
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepete Ekleme Sonucu</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mesaj-kutu {
            background: white;
            padding: 30px 40px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .mesaj-kutu p {
            font-size: 18px;
            margin-bottom: 25px;
        }

        .mesaj-kutu a {
            text-decoration: none;
            background-color: #00796b;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            transition: background-color 0.3s;
        }

        .mesaj-kutu a:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
    <div class="mesaj-kutu">
        <p><?= $mesaj ?></p>
        <a href="etkinlikler.php">⬅️ Etkinliklere Geri Dön</a>
    </div>
</body>
</html>
