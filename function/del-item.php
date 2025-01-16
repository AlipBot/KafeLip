<?php
session_start(); # mula session
# semak pengguna dah login ke belum
if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = '../index.php'; </script>";
}
// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);
$menuId = $data['menuId'];

// Buang item dari session
$_SESSION['orders'] = array_filter($_SESSION['orders'], function ($item) use ($menuId) {
    return $item !== $menuId;
});

// Jika cart kosong, set session info
if (empty($_SESSION['orders'])) {
    $_SESSION['info'] = "Cart Anda Kosong";
    header("Location: ../cart.php");
    exit;
}

echo json_encode(['success' => true]);
