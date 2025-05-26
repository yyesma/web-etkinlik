<?php
session_start();
include("baglanti.php");

// Giriş kontrolü (admin değilse yönlendirme)
if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: admin_giris.php");
    exit;
}

if (isset($_GET['id']) && isset($_GET['durum'])) {
    $id = $_GET['id'];
    $yeni_durum = $_GET['durum'] == 1 ? 0 : 1;
    $conn->query("UPDATE etkinlikler SET aktif = $yeni_durum WHERE id = $id");
}

$result = $conn->query("SELECT * FROM etkinlikler ORDER BY tarih ASC");
?>

<h2>📋 Etkinlik Listesi</h2>
<table border="1" cellpadding="5">
<tr>
    <th>Ad</th><th>Tarih</th><th>Tür</th><th>Fiyat</th><th>Aktif</th><th>İşlem</th>
</tr>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td><?= $row['ad'] ?></td>
    <td><?= $row['tarih'] ?></td>
    <td><?= $row['tur'] ?></td>
    <td><?= $row['fiyat'] ?> TL</td>
    <td><?= $row['aktif'] ? '🟢 Yayında' : '🔴 Pasif' ?></td>
    <td>
        <a href="admin_etkinlik_duzenle.php?id=<?= $row['id'] ?>">✏️ Düzenle</a> | 
        <a href="admin_etkinlik_listele.php?id=<?= $row['id'] ?>&durum=<?= $row['aktif'] ?>">🟢/🔴 Değiştir</a> | 
        <a href="admin_etkinlik_sil.php?id=<?= $row['id'] ?>" onclick="return confirm('Silinsin mi?')">❌ Sil</a>
    </td>
</tr>
<?php endwhile; ?>
</table>
