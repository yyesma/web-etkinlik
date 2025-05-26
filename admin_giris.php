<?php
session_start();
include("baglanti.php");

$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $sifre = $_POST["sifre"];

    $stmt = $conn->prepare("SELECT * FROM kullanicilar WHERE email = ? AND onay_durumu = 'onayli'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $sonuc = $stmt->get_result();

    if ($sonuc->num_rows === 1) {
        $kullanici = $sonuc->fetch_assoc();

        if (password_verify($sifre, $kullanici["sifre"]) && $email === "admin@example.com") {
            $_SESSION["kullanici_adi"] = "admin";
            $_SESSION["kullanici_email"] = $email;
            header("Location: admin_panel.php");
            exit;
        } else {
            $mesaj = "❗ Giriş reddedildi. Bilgiler yanlış veya yetkiniz yok.";
        }
    } else {
        $mesaj = "❗ Kullanıcı bulunamadı ya da onaylı değil.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Admin Giriş</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f1f8e9;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #33691e;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }

        input[type="submit"] {
            background-color: #558b2f;
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
            background-color: #33691e;
        }

        .mesaj {
            text-align: center;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .hata {
            background-color: #ffebee;
            color: #c62828;
            border: 1px solid #ef5350;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>🔐 Admin Giriş</h2>

    <?php if (!empty($mesaj)): ?>
        <div class="mesaj hata"><?= $mesaj ?></div>
    <?php endif; ?>

    <form method="POST">
        <label>E-posta:</label>
        <input type="email" name="email" required>

        <label>Şifre:</label>
        <input type="password" name="sifre" required>

        <input type="submit" value="Giriş Yap">
    </form>
</div>

</body>
</html>
