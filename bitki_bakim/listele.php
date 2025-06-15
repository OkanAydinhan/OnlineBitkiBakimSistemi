<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Oturum kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$bitkiler = [];

try {
    // Veritabanı sorgusu
    $stmt = $pdo->prepare("SELECT id, isim, tur, son_sulama_tarihi, son_bakim_tarihi, notlar FROM bitkiler WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $bitkiler = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Veritabanı hatası: " . htmlspecialchars($e->getMessage());
}
?>
<div class="container">
    <div class="card p-4 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-success text-center mb-4">Bitkileri Listele</h2>
            <?php if (isset($_GET['success'])): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($_GET['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['error']); ?></div>
            <?php endif; ?>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php elseif (empty($bitkiler)): ?>
                <div class="alert alert-info">Henüz bitki eklenmemiş. <a href="ekle.php" class="alert-link">Bitki ekle</a>.</div>
            <?php else: ?>
                <table class="table table-striped custom-table">
                    <thead>
                        <tr>
                            <th>İsim</th>
                            <th>Tür</th>
                            <th>Son Sulama Tarihi</th>
                            <th>Son Bakım Tarihi</th>
                            <th>Notlar</th>
                            <th>İşlem</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bitkiler as $bitki): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($bitki['isim'] ?: 'Bilinmiyor'); ?></td>
                            <td><?php echo htmlspecialchars($bitki['tur'] ?: 'Bilinmiyor'); ?></td>
                            <td><?php echo htmlspecialchars($bitki['son_sulama_tarihi'] ?: 'Yok'); ?></td>
                            <td><?php echo htmlspecialchars($bitki['son_bakim_tarihi'] ?: 'Yok'); ?></td>
                            <td><?php echo htmlspecialchars($bitki['notlar'] ?: 'Yok'); ?></td>
                            <td>
                                <a href="guncelle.php?id=<?php echo intval($bitki['id']); ?>" class="btn btn-warning btn-sm me-2">Güncelle</a>
                                <a href="sil.php?id=<?php echo intval($bitki['id']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Emin misiniz?');">Sil</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <a href="index.php" class="btn btn-primary mt-3">Ana Sayfaya Dön</a>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
