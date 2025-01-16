<?php
session_start(); # mula session

// Terima data JSON dari request
$data = json_decode(file_get_contents('php://input'), true);
$response = ['success' => false];
# semak ada data ke tak parameter post
if (!empty($data)) {
    // Kosongkan cart sedia ada
    $_SESSION['orders'] = array();

    // Kemaskini cart dengan kuantiti baru
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