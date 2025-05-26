<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];

$sql = "SELECT * FROM sepet WHERE kullanici_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $kullanici_id);
$stmt->execute();
$result = $stmt->get_result();

$sepet_icerik = [];
while ($row = $result->fetch_assoc()) {
    $sepet_icerik[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepetim</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: linear-gradient(to right, #e0f7fa, #e0f2f1);
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 1000px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            color: #00796b;
        }

        .kart {
            background: #fafafa;
            border-radius: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            margin-bottom: 20px;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        .kart p {
            margin: 5px 0;
            font-size: 15px;
        }

        .select-wrapper {
            margin: 10px 0;
        }

        .btn {
            background: #00796b;
            color: white;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 20px;
        }

        .btn:hover {
            background: #004d40;
        }

        .toplam {
            text-align: center;
            margin-top: 30px;
        }

        select {
            padding: 6px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        .bos-sepet {
            text-align: center;
            font-size: 18px;
            color: #666;
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>🛒 Sepetiniz</h2>

    <?php if (count($sepet_icerik) === 0): ?>
        <p class="bos-sepet">Sepetiniz boş.</p>
    <?php else: ?>
        <form action="odeme_tamamla.php" method="POST">
            <?php foreach ($sepet_icerik as $item): ?>
                <?php
                $etkinlik_adi = $item["etkinlik_kaynak"] === "veritabani" ? "" : $item["etkinlik_adi"];
                $tarih = $item["tarih"];
                $fiyat = 120;

                if ($item["etkinlik_kaynak"] === "veritabani") {
                    $etkinlik_adi = "Veritabanı Etkinliği #" . $item["etkinlik_id"];
                }
                ?>

                <div class="kart">
                    <div>
                        <p><strong>Etkinlik:</strong> <?= htmlspecialchars($etkinlik_adi) ?></p>
                        <p><strong>Tarih:</strong> <?= $tarih ?: "Belirtilmemiş" ?></p>
                        <p><strong>Fiyat:</strong> ₺<?= number_format($fiyat, 2) ?></p>
                    </div>
                    <div class="select-wrapper">
                        <label><strong>Bilet Türü:</strong></label><br>
                        <select name="bilet_turu[]">
                            <option value="tam">Tam</option>
                            <option value="ogrenci">Öğrenci (-%20)</option>
                            <option value="vip">VIP (+%50)</option>
                        </select>
                        <input type="hidden" name="etkinlik_id[]" value="<?= $item['id'] ?>">
                    </div>
                </div>
            <?php endforeach; ?>

            <div class="toplam">
                <label><strong>Ödeme Yöntemi:</strong></label><br>
                <select name="odeme_yontemi" required>
                    <option value="nakit">Nakit</option>
                    <option value="kredi_karti">Kredi Kartı</option>
                    <option value="havale">Havale</option>
                </select>
                <br><br>
                <button type="submit" class="btn">💳 Satın Al</button>
            </div>
        </form>
    <?php endif; ?>
</div>
</body>
</html>
