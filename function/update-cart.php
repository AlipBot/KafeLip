<?php
session_start();

// Terima data JSON dari request
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];

if (!empty($data)) {
    // Kosongkan cart sedia ada
    $_SESSION['orders'] = array();

    // Kemas kini cart dengan kuantiti baru
    foreach ($data as $menuId => $quantity) {
        for ($i = 0; $i < $quantity; $i++) {
            array_push($_SESSION['orders'], $menuId);
        }
    }

    $response['success'] = true;
} elseif (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = '../index.php'; </script>";
} else {
    header("Location: ../menu.php");
    exit();
}

header('Content-Type: application/json');
echo json_encode($response);