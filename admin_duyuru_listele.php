<?php
session_start();
include("baglanti.php");

if (!isset($_SESSION['kullanici_adi']) || $_SESSION['kullanici_adi'] !== 'admin') {
    header("Location: admin_giris.php");
    exit;
}

if (isset($_GET['sil'])) {
    $id = $_GET['sil'];
    $conn->query("DELETE FROM duyurular WHERE id = $id");
}

$result = $conn->query("SELECT * FROM duyurular ORDER BY yayin_tarihi DESC");
?>

<h2>📋 Duyuru Listesi</h2>
<ul>
<?php while ($row = $result->fetch_assoc()): ?>
    <li>
        <strong><?= $row['baslik'] ?></strong> (<?= $row['yayin_tarihi'] ?>) -
        <a href="?sil=<?= $row['id'] ?>" onclick="return confirm('Silinsin mi?')">❌ Sil</a><br>
        <?= $row['icerik'] ?>
    </li><br>
<?php endwhile; ?>
</ul>
