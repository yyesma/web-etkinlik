<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Etkinlikler</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", sans-serif;
            background-image: url("arkaplan.jpg");
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .header-links {
            text-align: right;
            padding: 20px;
        }

        .header-links a {
            margin-left: 10px;
            font-weight: bold;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 8px 12px;
            border-radius: 6px;
            text-decoration: none;
        }

        .header-links a:hover {
            background-color: rgba(255, 255, 255, 0.7);
            color: #000;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.85);
            max-width: 1000px;
            margin: 30px auto;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: #003c5a;
            margin-top: 0;
        }

        .kutucuk {
            background: #e6f7ff;
            border-left: 5px solid #0b6fa4;
            padding: 15px;
            margin-bottom: 25px;
            border-radius: 8px;
        }

        .duyurular {
            background: #fff9e6;
            padding: 15px;
            border: 1px solid #ffd42a;
            border-radius: 8px;
        }

        .uyari-yesil {
            color: green;
            font-weight: bold;
        }

        .uyari-kirmizi {
            color: red;
            font-weight: bold;
        }

        a[target="_blank"] {
            text-decoration: none;
            color: #0b6fa4;
        }

        .etkinlik-kart {
            background: white;
            border-radius: 10px;
            width: 240px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .etkinlik-kart img {
            width: 100%;
            height: 160px;
            object-fit: cover;
        }

        .etkinlik-kart .icerik {
            padding: 15px;
        }

        .etkinlik-kart .icerik a {
            text-decoration: none;
            color: #0b6fa4;
            display: block;
            margin-top: 5px;
        }

        .etkinlikler-flex {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 15px;
        }

        button {
            padding: 6px 10px;
            border: none;
            background-color: #00796b;
            color: white;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #004d40;
        }
    </style>
</head>
<body>
<div class="header-links">
    <a href="sepetim.php">🛒 Sepetim</a>
    <a href="cikis.php">🔓 Çıkış Yap</a>
</div>

<div class="container">
<?php
$weather_url = "https://api.openweathermap.org/data/2.5/weather?q=Istanbul&appid=c3f21f12f641dddcbb134d99847cca44&lang=tr&units=metric";
$weather_json = file_get_contents($weather_url);
$weather_data = json_decode($weather_json, true);

$durum = $weather_data["weather"][0]["description"];
$sicaklik = $weather_data["main"]["temp"];
$durum_ikonu = $weather_data["weather"][0]["icon"];
$uygun_mu = (strpos($durum, "yağmur") !== false || strpos($durum, "fırtına") !== false) ? false : true;
?>

<div class="kutucuk">
    <p><strong>📍 İstanbul Hava Durumu:</strong><br>
        <?= ucfirst($durum) ?>, <?= $sicaklik ?>°C
        <img src="https://openweathermap.org/img/wn/<?= $durum_ikonu ?>.png" style="vertical-align: middle;">
    </p>
    <p class="<?= $uygun_mu ? 'uyari-yesil' : 'uyari-kirmizi' ?>">
        <?= $uygun_mu ? "✅ Etkinlik dış mekanda planlanabilir." : "❗ Hava uygun değil, dış mekan etkinliği önerilmez." ?>
    </p>
</div>

<h2>🔔 Duyurular</h2>
<?php
$duyuru_sorgu = "SELECT * FROM duyurular ORDER BY yayin_tarihi DESC LIMIT 5";
$duyuru_sonuc = $conn->query($duyuru_sorgu);

if ($duyuru_sonuc->num_rows > 0) {
    echo "<ul class='duyurular'>";
    while ($duyuru = $duyuru_sonuc->fetch_assoc()) {
        echo "<li><strong>{$duyuru['baslik']}</strong> ({$duyuru['yayin_tarihi']})<br>{$duyuru['icerik']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>📭 Henüz duyuru yok.</p>";
}
?>

<h2>🎤 Ticketmaster Konser Etkinlikleri</h2>
<?php
$apiKey = "jXZ4G6T1D4LHVr6ZepD6eTi57Jd3GekA";
$city = "Istanbul";
$url = "https://app.ticketmaster.com/discovery/v2/events.json?apikey=$apiKey&city=$city&classificationName=music";

$response = file_get_contents($url);
$data = json_decode($response, true);

if (isset($data['_embedded']['events'])) {
    $etkinlikler = $data['_embedded']['events'];

    usort($etkinlikler, function($a, $b) {
        return strtotime($a['dates']['start']['localDate']) - strtotime($b['dates']['start']['localDate']);
    });

    echo "<div class='etkinlikler-flex'>";

    foreach ($etkinlikler as $etkinlik) {
        $isim = $etkinlik['name'];
        $tarih = $etkinlik['dates']['start']['localDate'];
        $link = $etkinlik['url'];
        $resim = $etkinlik['images'][0]['url'];

        echo "
        <div class='etkinlik-kart'>
            <img src='$resim' alt='Etkinlik Görseli'>
            <div class='icerik'>
                <strong>$isim</strong><br>
                <small>$tarih</small><br>
                <a href='$link' target='_blank'>🔗 Detay</a>
                <form action='sepete_ekle.php' method='POST'>
                    <input type='hidden' name='kaynak' value='api'>
                    <input type='hidden' name='etkinlik_adi' value='" . htmlspecialchars($isim, ENT_QUOTES) . "'>
                    <input type='hidden' name='tarih' value='$tarih'>
                    <input type='hidden' name='link' value='$link'>
                    <button type='submit'>🛒 Sepete Ekle</button>
                </form>
            </div>
        </div>
        ";
    }

    echo "</div>";
} else {
    echo "<p>🎵 Ticketmaster'dan konser bulunamadı.</p>";
}
?>

<hr>

<h2>📌 Yönetici Tarafından Eklenen Etkinlikler</h2>
<?php
$sql = "SELECT * FROM etkinlikler WHERE aktif = 1 ORDER BY tarih ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='etkinlikler-flex'>";
    while ($row = $result->fetch_assoc()) {
        $ad = $row['ad'];
        $tarih = $row['tarih'];
        $saat = $row['saat'];
        $tur_raw = strtolower(trim($row['tur']));
        $fiyat = $row['fiyat'];
        $mekan = $row['mekan'];
        $id = $row['id'];

        $gorsel_map = [
            "konser" => "konser.jpg",
            "tiyatro" => "tiyatro.jpg",
            "stand-up" => "standup.jpg",
            "standup" => "standup.jpg",
            "çevrim içi" => "online.jpg",
            "online" => "online.jpg",
            "resim sergisi" => "diger.jpg",
            "yazılım atölyesi" => "online.jpg",
            "kodlama kampı" => "diger.jpg",
            "diğer" => "diger.jpg"
        ];

        $gorsel_dosya = isset($gorsel_map[$tur_raw]) ? $gorsel_map[$tur_raw] : "diger.jpg";
       $gorsel_yolu = "./gorseller/$gorsel_dosya";


        echo "
        <div class='etkinlik-kart'>
            <img src='$gorsel_yolu' alt='$tur_raw'>
            <div class='icerik'>
                <strong>$ad</strong><br>
                <small>$tarih $saat</small><br>
                <small>{$row['tur']} - ₺$fiyat - $mekan</small><br>
                <form action='sepete_ekle.php' method='POST' style='margin-top:10px;'>
                    <input type='hidden' name='kaynak' value='veritabani'>
                    <input type='hidden' name='etkinlik_id' value='$id'>
                    <button type='submit' style='margin-top:8px; width:100%; background:#00796b; color:white; border:none; border-radius:5px; padding:6px;'>🛒 Sepete Ekle</button>
                </form>
            </div>
        </div>
        ";
    }
    echo "</div>";
} else {
    echo "<p>Henüz etkinlik eklenmemiş.</p>";
}

$conn->close();
?>
</div>
</body>
</html>
