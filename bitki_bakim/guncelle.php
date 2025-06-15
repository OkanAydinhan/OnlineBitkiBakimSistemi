<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$bitki = null;

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        $error = "Geçersiz bitki ID'si.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM bitkiler WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        $bitki = $stmt->fetch();
        if (!$bitki) {
            $error = "Bitki bulunamadı veya size ait değil.";
        }
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error) {
        $isim = trim($_POST['isim'] ?? '');
        $tur = trim($_POST['tur'] ?? '');
        $son_sulama_tarihi = $_POST['son_sulama_tarihi'] ?? '';
        $son_bakim_tarihi = $_POST['son_bakim_tarihi'] ?? '';
        $notlar = trim($_POST['notlar'] ?? '');

        if (empty($isim) || empty($tur) || empty($son_sulama_tarihi) || empty($son_bakim_tarihi)) {
            $error = "Lütfen tüm zorunlu alanları doldurun.";
        } else {
            try {
                $stmt = $pdo->prepare("UPDATE bitkiler SET isim = ?, tur = ?, son_sulama_tarihi = ?, son_bakim_tarihi = ?, notlar = ? WHERE id = ? AND user_id = ?");
                $stmt->execute([$isim, $tur, $son_sulama_tarihi, $son_bakim_tarihi, $notlar, $id, $_SESSION['user_id']]);
                header("Location: listele.php");
                exit;
            } catch (PDOException $e) {
                $error = "Hata oluştu: " . $e->getMessage();
            }
        }
    }
}
?>
<div class="container">
    <div class="card p-4 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-success text-center mb-4">Bitki Güncelle</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php elseif (!$bitki): ?>
                <div class="alert alert-warning">Bitki bulunamadı.</div>
                <a href="listele.php" class="btn btn-primary mt-3">Geri Dön</a>
            <?php else: ?>
                <form method="POST" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="isim" class="form-label">İsim</label>
                        <input type="text" name="isim" id="isim" class="form-control" value="<?php echo htmlspecialchars($bitki['isim']); ?>" required>
                        <div class="invalid-feedback">İsim zorunludur.</div>
                    </div>
                    <div class="mb-3">
                        <label for="tur" class="form-label">Tür</label>
                        <input type="text" name="tur" id="tur" class="form-control" value="<?php echo htmlspecialchars($bitki['tur']); ?>" required>
                        <div class="invalid-feedback">Tür zorunludur.</div>
                    </div>
                    <div class="mb-3">
                        <label for="son_sulama_tarihi" class="form-label">Son Sulama Tarihi</label>
                        <input type="date" name="son_sulama_tarihi" id="son_sulama_tarihi" class="form-control" value="<?php echo htmlspecialchars($bitki['son_sulama_tarihi']); ?>" required>
                        <div class="invalid-feedback">Son sulama tarihi zorunludur.</div>
                    </div>
                    <div class="mb-3">
                        <label for="son_bakim_tarihi" class="form-label">Son Bakım Tarihi</label>
                        <input type="date" name="son_bakim_tarihi" id="son_bakim_tarihi" class="form-control" value="<?php echo htmlspecialchars($bitki['son_bakim_tarihi']); ?>" required>
                        <div class="invalid-feedback">Son bakım tarihi zorunludur.</div>
                    </div>
                    <div class="mb-3">
                        <label for="notlar" class="form-label">Notlar</label>
                        <textarea name="notlar" id="notlar" class="form-control"><?php echo htmlspecialchars($bitki['notlar'] ?? ''); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Güncelle</button>
                </form>
                <a href="listele.php" class="btn btn-primary mt-3">Geri Dön</a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php include 'includes/footer.php'; ?>
