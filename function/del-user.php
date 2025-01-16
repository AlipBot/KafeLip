<?php

# memanggil fail kawalan admin
include('autoKeluarAdmin.php');

# menyemak kewujudan data GET notel pengguna
if (!empty($_GET)) {
    # memanggil fail connection
    include('connection.php');

    # arahan SQL untuk memadam data pengguna berdasarkan notel yang dihantar
    $arahan     =   "delete from pelanggan where notel='" . $_GET['notel'] . "'";

    # melaksanakan arahan SQL padam data dan menguji proses padam data
    if (mysqli_query($condb, $arahan)) {
        $_SESSION['success'] = "Padam Data Berjaya";
        header("Location: ../admin/list-user.php");
        exit();
    } else {
        $_SESSION['success'] = "Kemaskini Berjaya";
        header("Location: ../admin/list-user.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Ralat Berlaku";
    header("Location: ../admin/list-user.php");
    exit();
}
