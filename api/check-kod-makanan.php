<?php
include('../function/autoKeluarAdmin.php');
include('../function/connection.php');
if (isset($_GET['kod_makanan'])) {
    $kod_makanan = $_GET['kod_makanan'];
    $sql = "SELECT kod_makanan FROM makanan WHERE kod_makanan = ?";
    $stmt = mysqli_prepare($condb, $sql);
    mysqli_stmt_bind_param($stmt, "s", $kod_makanan);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $exists = mysqli_stmt_num_rows($stmt) > 0;

    header('Content-Type: application/json');
    echo json_encode(['wujud' => $exists]);
} else {
    header('HTTP/1.1 400 Bad Request');
    echo json_encode(['error' => 'Tiada parameter kod_makanan diterima']);
}
?>