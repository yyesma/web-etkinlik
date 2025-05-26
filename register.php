<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $sifre_raw = $_POST["sifre"];

    if (strlen($sifre_raw) < 6) {
        echo "<p class='mesaj'>❗ Şifre en az 6 karakter olmalı.</p>";
    } else {
        $check_sql = "SELECT id FROM kullanicilar WHERE email = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $email);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            echo "<p class='mesaj'>❗ Bu e-posta adresi zaten kayıtlı.</p>";
        } else {
            $sifre = password_hash($sifre_raw, PASSWORD_DEFAULT);
            $sql = "INSERT INTO kullanicilar (email, sifre) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $email, $sifre);

            if ($stmt->execute()) {
                header("Location: login.php?mesaj=onaybekleniyor");
                exit;
            } else {
                echo "<p class='mesaj'>❗ Hata: " . $stmt->error . "</p>";
            }

            $stmt->close();
        }

        $check_stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(135deg, #f8f9fa, #e3e3e3);
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
            text-align: center;
            color: red;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Kayıt Formu</h2>
        <form method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" required>

            <label for="sifre">Şifre:</label>
            <input type="password" name="sifre" required>

            <input type="submit" value="Kayıt Ol">
        </form>
    </div>
</body>
</html>
