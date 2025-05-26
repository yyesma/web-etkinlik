<?php
session_start();
include("baglanti.php");

// Sadece admin erişebilsin
if (!isset($_SESSION['kullanici_email']) || $_SESSION['kullanici_email'] != 'admin@example.com') {
    echo "❗ Bu sayfaya sadece yönetici erişebilir.";
    exit;
}

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST["ad"];
    $tur = $_POST["tur"];
    $tarih = $_POST["tarih"];
    $saat = $_POST["saat"];
    $mekan = $_POST["mekan"];
    $aciklama = $_POST["aciklama"];
    $fiyat = $_POST["fiyat"];
    $kontenjan = $_POST["kontenjan"];

    $sql = "INSERT INTO etkinlikler (ad, tur, tarih, saat, mekan, aciklama, fiyat, kontenjan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssdi", $ad, $tur, $tarih, $saat, $mekan, $aciklama, $fiyat, $kontenjan);

    if ($stmt->execute()) {
        $mesaj = "✅ Etkinlik başarıyla eklendi.";
    } else {
        $mesaj = "❗ Hata: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlik Ekle</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #e0f7fa;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #00796b;
        }

        form label {
            font-weight: bold;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #00796b;
            color: white;
            border: none;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        input[type="submit"]:hover {
            background-color: #004d40;
        }

        .mesaj {
            text-align: center;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
        }

        .basari {
            background-color: #e0fbe0;
            color: #2e7d32;
            border: 1px solid #a5d6a7;
        }

        .hata {
            background-color: #ffecec;
            color: #c62828;
            border: 1px solid #f44336;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>➕ Yönetici Etkinlik Ekleme Paneli</h2>

    <?php if (!empty($mesaj)): ?>
        <div class="mesaj <?= strpos($mesaj, '✅') !== false ? 'basari' : 'hata' ?>">
            <?= $mesaj ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Etkinlik Adı:</label>
        <input type="text" name="ad" required>

        <label>Tür:</label>
        <select name="tur">
            <option value="konser">Konser</option>
            <option value="tiyatro">Tiyatro</option>
            <option value="stand-up">Stand-up</option>
            <option value="çevrim içi">Çevrim İçi</option>
            <option value="diğer">Diğer</option>
        </select>

        <label>Tarih:</label>
        <input type="date" name="tarih" required>

        <label>Saat:</label>
        <input type="time" name="saat">

        <label>Mekan:</label>
        <input type="text" name="mekan">

        <label>Açıklama:</label>
        <textarea name="aciklama" rows="3"></textarea>

        <label>Fiyat (₺):</label>
        <input type="number" name="fiyat" step="0.01" required>

        <label>Kontenjan:</label>
        <input type="number" name="kontenjan" value="100" required>

        <input type="submit" value="➕ Etkinlik Ekle">
    </form>
</div>
</body>
</html>
