<?php
session_start();
include("baglanti.php");

$mesaj = "";

// URL'den gelen mesaj varsa göster
if (isset($_GET["mesaj"]) && $_GET["mesaj"] == "onaybekleniyor") {
    $mesaj = "✅ Kayıt başarılı! Yönetici onayı sonrası giriş yapabilirsiniz.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $sifre = $_POST["sifre"];

    $sql = "SELECT id, sifre, onay_durumu FROM kullanicilar WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hashli_sifre, $onay_durumu);
        $stmt->fetch();

        if ($onay_durumu != 'onayli') {
            $mesaj = "❗ Hesabınız henüz yönetici tarafından onaylanmadı.";
        } elseif (password_verify($sifre, $hashli_sifre)) {
            $_SESSION["kullanici_id"] = $id;
            $_SESSION["kullanici_email"] = $email;
            header("Location: sifre_degistir.php");
            exit;
        } else {
            $mesaj = "❗ Şifre yanlış.";
        }
    } else {
        $mesaj = "❗ Bu e-posta ile kayıtlı kullanıcı bulunamadı.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #f0f2f5, #dfe4ea);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .form-container {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        label {
            display: block;
            text-align: left;
            margin: 10px 0 5px;
            color: #555;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        input[type="submit"] {
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 25px;
            font-size: 16px;
            border-radius: 8px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #0056b3;
        }

        .mesaj {
            color: #d8000c;
            background: #ffdddd;
            border: 1px solid #d8000c;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 6px;
        }

        .kaydol-link {
            margin-top: 15px;
            font-size: 14px;
        }

        .kaydol-link a {
            color: #007bff;
            text-decoration: none;
        }

        .kaydol-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Giriş Yap</h2>
        <?php if (!empty($mesaj)) echo "<div class='mesaj'>$mesaj</div>"; ?>
        
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="sifre">Şifre:</label>
            <input type="password" name="sifre" required>

            <input type="submit" value="Giriş Yap">
        </form>

        <div class="kaydol-link">
            Hesabınız yok mu? <a href="register.php">Kayıt Ol</a>
        </div>
    </div>
</body>
</html>
