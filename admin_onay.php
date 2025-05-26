<?php
include("baglanti.php");

$mesaj = "";
if (isset($_GET['onayla'])) {
    $id = intval($_GET['onayla']);
    $onayla = $conn->prepare("UPDATE kullanicilar SET onay_durumu = 'onayli' WHERE id = ?");
    $onayla->bind_param("i", $id);
    $onayla->execute();
    $mesaj = "✅ Kullanıcı onaylandı!";
}

$sql = "SELECT id, email FROM kullanicilar WHERE onay_durumu = 'bekliyor'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yönetici Onay Paneli</title>
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

        .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .mesaj {
            background-color: #d4edda;
            color: #155724;
            padding: 10px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-weight: 500;
            font-size: 15px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 500px;
            text-align: center;
        }

        .container {
            background: white;
            padding: 35px 45px;
            border-radius: 15px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.12);
            width: 100%;
            max-width: 500px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #00796b;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            background: #f1f1f1;
            margin-bottom: 12px;
            padding: 14px 18px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 15px;
        }

        a {
            background: #00796b;
            color: white;
            padding: 7px 14px;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
            font-size: 14px;
        }

        a:hover {
            background: #004d40;
        }

        .bos {
            text-align: center;
            background: #eee;
            padding: 14px;
            border-radius: 10px;
            color: #666;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <?php if (!empty($mesaj)) { echo "<div class='mesaj'>$mesaj</div>"; } ?>

        <div class="container">
            <h2>Onay Bekleyen Kullanıcılar</h2>
            <ul>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<li><span>" . htmlspecialchars($row['email']) . "</span>" .
                             "<a href='admin_onay.php?onayla=" . $row['id'] . "'>Onayla</a></li>";
                    }
                } else {
                    echo "<div class='bos'>Bekleyen kullanıcı yok.</div>";
                }
                ?>
            </ul>
        </div>
    </div>
</body>
</html>

