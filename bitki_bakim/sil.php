<?php
session_start();
include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    if ($id === false) {
        header("Location: listele.php?error=Geçersiz bitki ID'si");
        exit;
    }

    try {
        $stmt = $pdo->prepare("DELETE FROM bitkiler WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        if ($stmt->rowCount() > 0) {
            header("Location: listele.php?success=Bitki başarıyla silindi");
        } else {
            header("Location: listele.php?error=Bitki bulunamadı veya size ait değil");
        }
    } catch (PDOException $e) {
        header("Location: listele.php?error=Hata oluştu: " . urlencode($e->getMessage()));
    }
} else {
    header("Location: listele.php?error=Bitki ID'si belirtilmedi");
}
exit;
?>
