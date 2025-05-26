<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$mesaj = "";
$yonlendirme = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $yeni_sifre = $_POST["yeni_sifre"];

    if (strlen($yeni_sifre) < 6) {
        $mesaj = "❗ Yeni şifre en az 6 karakter olmalıdır.";
    } else {
        $hashli_yeni_sifre = password_hash($yeni_sifre, PASSWORD_DEFAULT);
        $sql = "UPDATE kullanicilar SET sifre = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashli_yeni_sifre, $_SESSION["kullanici_id"]);
        if ($stmt->execute()) {
            $mesaj = "✅ Şifre başarıyla değiştirildi. Ana sayfaya yönlendiriliyorsunuz...";
            $yonlendirme = true;
        } else {
            $mesaj = "❗ Şifre değiştirilirken hata oluştu.";
        }
        $stmt->close();
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Şifre Değiştir</title>
    <meta http-equiv="refresh" content="<?php echo $yonlendirme ? '2;url=etkinlikler.php' : ''; ?>">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(to right, #e0f7fa, #ffffff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: white;
            padding: 35px 45px;
            border-radius: 15px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00796b;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 14px;
        }

        input[type="submit"] {
            width: 100%;
            background: #00796b;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        input[type="submit"]:hover {
            background: #004d40;
        }

        .mesaj {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px 15px;
            border-radius: 8px;
            font-size: 14px;
        }

        .mesaj.success {
            background-color: #d4edda;
            color: #155724;
        }

        .mesaj.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Şifre Değiştir</h2>
        <?php
        if (!empty($mesaj)) {
            $class = (strpos($mesaj, "✅") !== false) ? "success" : "error";
            echo "<div class='mesaj $class'>$mesaj</div>";
        }
        ?>
        <form method="POST">
            <label for="yeni_sifre">Yeni Şifre:</label>
            <input type="password" name="yeni_sifre" required>

            <input type="submit" value="Şifreyi Güncelle">
        </form>
    </div>
</body>
</html>
