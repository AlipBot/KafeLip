<?php
session_start();

// Terima data JSON
$data = json_decode(file_get_contents('php://input'), true);
$menuId = $data['menuId'];

// Buang item dari session
$_SESSION['orders'] = array_filter($_SESSION['orders'], function($item) use ($menuId) {
    return $item !== $menuId;
});

// Jika cart kosong, set session info
if (empty($_SESSION['orders'])) {
    $_SESSION['info'] = "Cart Anda Kosong";
}

echo json_encode(['success' => true]); 