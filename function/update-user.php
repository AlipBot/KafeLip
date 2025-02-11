<?php
include('autoKeluarAdmin.php'); # kawalan admin

# menyemak kewujudan data POST
if (isset($_POST['KemaskiniDataPengguna'])) {

    include('connection.php');    # memanggil fail connection.php

    # pengesahan data (validation) notel pengguna
    if (strlen($_POST['notel']) < 10 or strlen($_POST['notel']) > 11) {
        $_SESSION['error'] = "Ralat Nombor Telefon mesti 10>No.Tel<11 nombor";
        header("Location: ../admin/list-user.php");
        exit();
    }
    // Pengesahan format nombor telefon Malaysia
    if (!preg_match("/^(01)[0-46-9][0-9]{7,8}$/", $_POST['notel'])) {
        $_SESSION['error'] = "SILA MASUKKAN NOMBOR TELEFON MALAYSIA YANG SAH";
        header("Location: ../admin/list-user.php");
        exit();
    }
    if ($_POST['notel'] != $_POST['notel_lama']) {
        $notel = mysqli_query($condb, "select* from pelanggan where notel = '" . $_POST['notel'] . "'");
        if (mysqli_num_rows($notel) == 1) {
            $_SESSION['error'] = "No.Tel " . $_POST['notel'] . " telah digunakan. Sila Tukar No.Tel Lain";
            header("Location:  ../admin/list-user.php");
            exit();
        }
    }
    if ($_POST['email'] != $_POST['email_lama']) {
        $email = mysqli_query($condb, "select* from pelanggan where email = '" . $_POST['email'] . "'");
        if (mysqli_num_rows($email) == 1) {
            $_SESSION['error'] = "email " . $_POST['email'] . " telah digunakan. Sila Tukar Email Lain";
            header("Location: ../admin/list-user.php");
            exit();
        }
    }



    $password = $_POST['katalaluan'];

    if (strlen($password) < 8) {
        $_SESSION['error'] = "KATA LAUAN MESTI 8 AKSARA KE ATAS";
        header("Location: ../admin/list-user.php");
        exit();
    }

    if (strlen($password) > 12) {
        $_SESSION['error'] = "KATA LAUAN MESTI TIDAK BOLEH LEBIH 12 AKSARA";
        header("Location: ../admin/list-user.php");
        exit();
    }


    # arahan SQL (query) untuk kemaskini maklumat pelanggan
    $arahan = "update pelanggan set
    nama            =   '" . $_POST['nama'] . "' ,
    email            =   '" . $_POST['email'] . "' ,
    notel           =   '" . $_POST['notel'] . "' ,
    password      =   '" . $_POST['katalaluan'] . "' ,
    tahap           =   '" . $_POST['tahap'] . "'
    where       
    notel           =   '" . $_POST['notel_lama'] . "' ";

    # melaksana dan menyemak proses kemaskini
    if (mysqli_query($condb, $arahan)) {
        $_SESSION['success'] = "Kemaskini Berjaya";
        header("Location: ../admin/list-user.php");
        exit();
    } else {
        $_SESSION['error'] = "Kemaskini Gagal";
        header("Location: ../admin/list-user.php");
        exit();
    }
} else {
    $_SESSION['error'] = "Sila Lengkapkan Data";
    header("Location: ../admin/list-user.php");
    exit();
}
