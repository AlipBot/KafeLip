<?php
include('autoKeluarAdmin.php');


# menyemak kewujudan data POST
if (!empty($_POST)) {
    # memanggil fail connection.php
    include('connection.php');

    # pengesahan data (validation) notel pengguna
    if (strlen($_POST['notel']) < 10 or strlen($_POST['notel']) > 15) {
        die("<script>alert('Ralat notel');
        window.history.back();</script>");
    }

    # arahan SQL (query) untuk kemaskini maklumat pelanggan
    $arahan         =   "update pelanggan set
    nama            =   '" . $_POST['nama'] . "' ,
    notel           =   '" . $_POST['notel'] . "' ,
    password      =   '" . $_POST['katalaluan'] . "' ,
    tahap           =   '" . $_POST['tahap'] . "'
    where       
    notel           =   '" . $_POST['notel_lama'] . "' ";

    # melaksana dan menyemak proses kemaskini
    if (mysqli_query($condb, $arahan)) {
        $_SESSION['success'] = "Kemaskini Berjaya";
        header( "Location: ../admin/list-user.php");
        exit();
    } else {
        $_SESSION['error'] = "Kemaskini Gagal";
        header( "Location: ../admin/list-user.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Sila Lengkapkan Data";
    header( "Location: ../admin/list-user.php");
    exit();
}
