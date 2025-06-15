Şunu dedin:
<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$username = $_SESSION['username'] ?? 'Kullanıcı';
?>
<div class="card text-center p-4 mb-4">
    <div class="card-body">
        <h3 class="card-title text-info">Hoş Geldiniz, <?php echo htmlspecialchars($username); ?>!</h3>
        <p class="card-text">Bitkilerinizle ilgili işlemleri aşağıdaki menüden yapabilirsiniz.</p>
    </div>
</div>
<?php include 'includes/footer.php'; ?>