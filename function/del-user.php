<?php

# memanggil fail kawalan admin
include('autoKeluarAdmin.php');

# menyemak kewujudan data GET notel pengguna
if (!empty($_GET['notel'])) {
    # memanggil fail connection
    include('connection.php');

    # Dapatkan parameter URL
    $redirect_params = [];

    if (!empty($_GET['current_page'])) {
        $redirect_params[] = "halaman=" . $_GET['current_page'];
    }
    if (!empty($_GET['search_query'])) {
        $redirect_params[] = "nama=" . urlencode($_GET['search_query']);
    }
    if (!empty($_GET['filter_tahap'])) {
        $redirect_params[] = "tapis_tahap=" . $_GET['filter_tahap'];
    }

    $redirect_url = "../admin/list-user.php";
    if (!empty($redirect_params)) {
        $redirect_url .= "?" . implode("&", $redirect_params);
    }

    # arahan SQL untuk memadam data pengguna berdasarkan notel yang dihantar
    $arahan = "delete from pelanggan where notel='" . $_GET['notel'] . "'";

    # melaksanakan arahan SQL padam data dan menguji proses padam data
    if (mysqli_query($condb, $arahan)) {
        $_SESSION['success'] = "Padam Data Berjaya";
        header("Location: $redirect_url");
        exit();
    } else {
        $_SESSION['error'] = "Padam Data Gagal";
        header("Location: $redirect_url");
        exit();
    }
} else {
    $_SESSION['error'] = "Ralat Berlaku";
    header("Location: ../admin/list-user.php");
    exit();
}
