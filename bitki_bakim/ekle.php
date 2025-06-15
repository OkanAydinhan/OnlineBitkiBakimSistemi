<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $isim = trim($_POST['isim'] ?? '');
    $tur = trim($_POST['tur'] ?? '');
    $son_sulama_tarihi = $_POST['son_sulama_tarihi'] ?? '';
    $son_bakim_tarihi = $_POST['son_bakim_tarihi'] ?? '';
    $notlar = trim($_POST['notlar'] ?? '');

    if ($tur === 'Diğer') {
        $tur = trim($_POST['other_tur'] ?? '');
        if (empty($tur)) {
            $error = "Diğer tür seçildiğinde tür adı zorunludur.";
        }
    }

    if (empty($isim) || empty($tur) || empty($son_sulama_tarihi) || empty($son_bakim_tarihi)) {
        $error = "Lütfen tüm zorunlu alanları doldurun.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO bitkiler (user_id, isim, tur, son_sulama_tarihi, son_bakim_tarihi, notlar) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $isim, $tur, $son_sulama_tarihi, $son_bakim_tarihi, $notlar]);
            header("Location: listele.php");
            exit;
        } catch (PDOException $e) {
            $error = "Hata oluştu: " . $e->getMessage();
        }
    }
}
?>
<div class="container">
    <div class="card p-4 shadow-sm">
        <div class="card-body">
            <h2 class="card-title text-success text-center mb-4">Bitki Ekle</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" class="needs-validation" novalidate>
                <div class="mb-3">
                    <label for="isim" class="form-label">İsim</label>
                    <input type="text" name="isim" id="isim" class="form-control" value="<?php echo isset($_POST['isim']) ? htmlspecialchars($_POST['isim']) : ''; ?>" required>
                    <div class="invalid-feedback">İsim zorunludur.</div>
                </div>
                <div class="mb-3">
                    <label for="tur" class="form-label">Tür</label>
                    <select name="tur" id="tur" class="form-control" onchange="toggleOtherInput()" required>
                        <option value="">Tür seçiniz</option>
                        <option value="Gül">Gül</option>
                        <option value="Orkide">Orkide</option>
                        <option value="Kaktüs">Kaktüs</option>
                        <option value="Sukulent">Sukulent</option>
                        <option value="Menekşe">Menekşe</option>
                        <option value="Fesleğen">Fesleğen</option>
                        <option value="Lavanta">Lavanta</option>
                        <option value="Aloe Vera">Aloe Vera</option>
                        <option value="Begonya">Begonya</option>
                        <option value="Zambak">Zambak</option>
                        <option value="Diğer">Diğer</option>
                    </select>
                    <div class="invalid-feedback">Tür seçimi zorunludur.</div>
                </div>
                <div class="mb-3" id="other-tur-div" style="display: none;">
                    <label for="other_tur" class="form-label">Diğer Tür</label>
                    <input type="text" name="other_tur" id="other_tur" class="form-control" value="<?php echo isset($_POST['other_tur']) ? htmlspecialchars($_POST['other_tur']) : ''; ?>">
                    <div class="invalid-feedback">Diğer tür adı zorunludur.</div>
                </div>
                <div class="mb-3">
                    <label for="son_sulama_tarihi" class="form-label">Son Sulama Tarihi</label>
                    <input type="date" name="son_sulama_tarihi" id="son_sulama_tarihi" class="form-control" value="<?php echo isset($_POST['son_sulama_tarihi']) ? htmlspecialchars($_POST['son_sulama_tarihi']) : ''; ?>" required>
                    <div class="invalid-feedback">Son sulama tarihi zorunludur.</div>
                </div>
                <div class="mb-3">
                    <label for="son_bakim_tarihi" class="form-label">Son Bakım Tarihi</label>
                    <input type="date" name="son_bakim_tarihi" id="son_bakim_tarihi" class="form-control" value="<?php echo isset($_POST['son_bakim_tarihi']) ? htmlspecialchars($_POST['son_bakim_tarihi']) : ''; ?>" required>
                    <div class="invalid-feedback">Son bakım tarihi zorunludur.</div>
                </div>
                <div class="mb-3">
                    <label for="notlar" class="form-label">Notlar</label>
                    <textarea name="notlar" id="notlar" class="form-control"><?php echo isset($_POST['notlar']) ? htmlspecialchars($_POST['notlar']) : ''; ?></textarea>
                </div>
                <button type="submit" class="btn btn-success w-100">Ekle</button>
            </form>
        </div>
    </div>
</div>
<script>
function toggleOtherInput() {
    const turSelect = document.getElementById('tur');
    const otherDiv = document.getElementById('other-tur-div');
    const otherInput = document.getElementById('other_tur');
    if (turSelect.value === 'Diğer') {
        otherDiv.style.display = 'block';
        otherInput.setAttribute('required', 'required');
    } else {
        otherDiv.style.display = 'none';
        otherInput.removeAttribute('required');
    }
}
</script>
<?php include 'includes/footer.php'; ?>
