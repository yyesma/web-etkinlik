Şunu dedin:
<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION["kullanici_id"])) {
    header("Location: login.php");
    exit;
}

$kullanici_id = $_SESSION["kullanici_id"];
$etkinlik_idler = $_POST["etkinlik_id"] ?? [];
$bilet_turleri = $_POST["bilet_turu"] ?? [];
$odeme_yontemi = $_POST["odeme_yontemi"] ?? "";

if (empty($etkinlik_idler) || empty($bilet_turleri)) {
    echo "❗ Sepet boş ya da bilgiler eksik.";
    exit;
}

$total = 0;
define("TEMEL_FIYAT", 120); // Tüm etkinlikler için sabit fiyat

?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ödeme Tamamlandı</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 20px;
        }
        h2 {
            color: #0b6fa4;
        }
        ul {
            list-style: none;
            padding: 0;
            background: white;
            border: 1px solid #ddd;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        li {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 18px;
        }
        a {
            text-decoration: none;
            color: #0b6fa4;
            font-weight: bold;
        }
    </style>
</head>
<body>

<h2>✅ Ödeme Tamamlandı</h2>
<ul>

<?php
foreach ($etkinlik_idler as $index => $sepet_id) {
    $bilet_turu = $bilet_turleri[$index];

    $stmt = $conn->prepare("SELECT * FROM sepet WHERE id = ? AND kullanici_id = ?");
    $stmt->bind_param("ii", $sepet_id, $kullanici_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $etkinlik_adi = $row["etkinlik_adi"];
        $fiyat = TEMEL_FIYAT;

        if ($row["etkinlik_kaynak"] === "veritabani") {
            $sorgu = $conn->prepare("SELECT ad, kontenjan FROM etkinlikler WHERE id = ?");
            $sorgu->bind_param("i", $row["etkinlik_id"]);
            $sorgu->execute();
            $sorgu->bind_result($etkinlik_adi, $kontenjan);
            $sorgu->fetch();
            $sorgu->close();

            if ($kontenjan > 0) {
                $guncelle = $conn->prepare("UPDATE etkinlikler SET kontenjan = kontenjan - 1 WHERE id = ?");
                $guncelle->bind_param("i", $row["etkinlik_id"]);
                $guncelle->execute();
                $guncelle->close();
            } else {
                echo "<li><strong>$etkinlik_adi</strong> - Kontenjan dolu ❗</li>";
                continue;
            }
        }

        // Bilet türüne göre fiyat hesapla
        switch ($bilet_turu) {
            case "ogrenci":
                $fiyat *= 0.80;
                break;
            case "vip":
                $fiyat *= 1.50;
                break;
        }

        $total += $fiyat;

        echo "<li><strong>$etkinlik_adi</strong> - " . ucfirst($bilet_turu) . " bileti - ₺" . number_format($fiyat, 2) . "</li>";

        // Sepetten sil
        $sil = $conn->prepare("DELETE FROM sepet WHERE id = ?");
        $sil->bind_param("i", $sepet_id);
        $sil->execute();
        $sil->close();
    }

    $stmt->close();
}

$conn->close();
?>

</ul>

<div class="footer">
    <p>💳 Seçilen ödeme yöntemi: <strong><?= htmlspecialchars($odeme_yontemi) ?></strong></p>
    <p>💰 Toplam Ödeme Tutarı: <strong>₺<?= number_format($total, 2) ?></strong></p>
    <p><a href="etkinlikler.php">🔙 Ana Sayfaya Dön</a></p>
</div>

</body>
</html>