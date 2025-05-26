<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: admin_giris.php");
    exit;
}

$id = $_GET['id'];
$sorgu = $conn->query("SELECT * FROM etkinlikler WHERE id = $id");
$etkinlik = $sorgu->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ad'];
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];
    $tur = $_POST['tur'];
    $fiyat = $_POST['fiyat'];
    $mekan = $_POST['mekan'];
    $kontenjan = $_POST['kontenjan'];

    $guncelle = $conn->prepare("UPDATE etkinlikler SET ad=?, tarih=?, saat=?, tur=?, fiyat=?, mekan=?, kontenjan=? WHERE id=?");
    $guncelle->bind_param("ssssdsii", $ad, $tarih, $saat, $tur, $fiyat, $mekan, $kontenjan, $id);
    $guncelle->execute();
    echo "✅ Güncellendi.";
}
?>

<h2>✏️ Etkinlik Düzenle</h2>
<form method="POST">
    Ad: <input type="text" name="ad" value="<?= $etkinlik['ad'] ?>"><br>
    Tarih: <input type="date" name="tarih" value="<?= $etkinlik['tarih'] ?>"><br>
    Saat: <input type="time" name="saat" value="<?= $etkinlik['saat'] ?>"><br>
    Tür: <input type="text" name="tur" value="<?= $etkinlik['tur'] ?>"><br>
    Fiyat: <input type="number" name="fiyat" value="<?= $etkinlik['fiyat'] ?>"><br>
    Mekan: <input type="text" name="mekan" value="<?= $etkinlik['mekan'] ?>"><br>
    Kontenjan: <input type="number" name="kontenjan" value="<?= $etkinlik['kontenjan'] ?>"><br><br>
    <input type="submit" value="Güncelle">
</form>
