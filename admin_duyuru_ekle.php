<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: admin_giris.php");
    exit;
}

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $baslik = $_POST['baslik'];
    $icerik = $_POST['icerik'];
    $tarih = $_POST['yayin_tarihi'];

    $stmt = $conn->prepare("INSERT INTO duyurular (baslik, icerik, yayin_tarihi) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $baslik, $icerik, $tarih);

    if ($stmt->execute()) {
        $mesaj = "✅ Duyuru başarıyla eklendi.";
    } else {
        $mesaj = "❗ Hata oluştu: " . $stmt->error;
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Duyuru Ekle</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background-color: #e3f2fd;
            padding: 40px 20px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
        }

        h2 {
            text-align: center;
            color: #1565c0;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="text"],
        textarea,
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #1565c0;
            color: white;
            padding: 12px;
            width: 100%;
            border: none;
            border-radius: 6px;
            margin-top: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        input[type="submit"]:hover {
            background-color: #0d47a1;
        }

        .mesaj {
            text-align: center;
            margin-bottom: 15px;
            padding: 10px;
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
    <h2>📢 Duyuru Ekle</h2>

    <?php if (!empty($mesaj)): ?>
        <div class="mesaj <?= strpos($mesaj, '✅') !== false ? 'basari' : 'hata' ?>">
            <?= $mesaj ?>
        </div>
    <?php endif; ?>

    <form method="POST">
        <label>Başlık:</label>
        <input type="text" name="baslik" required>

        <label>İçerik:</label>
        <textarea name="icerik" rows="4" required></textarea>

        <label>Yayın Tarihi:</label>
        <input type="date" name="yayin_tarihi" required>

        <input type="submit" value="📨 Duyuru Ekle">
    </form>
</div>

</body>
</html>
