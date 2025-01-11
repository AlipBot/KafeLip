<?php
session_start();

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);
$menuId = $data['menuId'];
$quantity = $data['quantity'];

// Kosongkan orders yang sedia ada untuk menu ini
$_SESSION['orders'] = array_filter($_SESSION['orders'], function($item) use ($menuId) {
    return $item !== $menuId;
});

// Tambah quantity yang baru
for ($i = 0; $i < $quantity; $i++) {
    $_SESSION['orders'][] = $menuId;
}

// Jika cart kosong selepas update
if (empty($_SESSION['orders'])) {
    $_SESSION['info'] = "Cart Anda Kosong";
    echo json_encode(['success' => true, 'empty' => true]);
    exit;
}

echo json_encode(['success' => true]); 