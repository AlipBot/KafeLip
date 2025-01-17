<?php
session_start(); # mula session
# semak pengguna dah login ke belum
if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo json_encode(['success' => false]);
    exit;
}

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);
$menuId = $data['menuId'];

if (empty($menuId)) {
    echo json_encode(['success' => false]);
    exit;
}

// Buang item dari session
$_SESSION['orders'] = array_filter($_SESSION['orders'], function ($item) use ($menuId) {
    return $item !== $menuId;
});

// Semak jika cart kosong
$isEmpty = empty($_SESSION['orders']);
if ($isEmpty) {
    $_SESSION['info'] = "Cart Anda Kosong";
}

// Hantar respons dengan status cart
echo json_encode([
    'success' => true,
    'isEmpty' => $isEmpty
]);

