<?php
include("function/autoKeluar.php");
include('function/connection.php');

if (!isset($_SESSION['orders'])) {
    $_SESSION['info'] = "Cart Anda Kosong";
    header("Location: menu.php");
    exit();
} else {
    # dapatkan bilangan setiap elemen
    $frekuensi = array_count_values($_SESSION['orders']);

    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($frekuensi, function ($count) {
        return $count >= 1;
    });


    $tarikh = date('Y-m-d H:i:s');
    # Mendapatkan data menu dan menyimpankannya dalam jadual tempahan
    foreach ($sama as $key => $bil) {

        $sqltempah  =   "insert into tempahan set
                        email              = '" . $_SESSION['email'] . "',
                        kod_makanan        =   '$key',
                        tarikh             =  '$tarikh',
                        jumlah_harga      =   '" . $_SESSION['jumlah_harga'] . "',
                        kuantiti        =   '$bil' ";
        $laktempah  =   mysqli_query($condb, $sqltempah);
    }

    # Memadam nilai pembolehubah session
    unset($_SESSION['orders']);
    unset($_SESSION['jumlah_harga']);
    $_SESSION['success'] = "Tempahan Selesai";
    header("Location: resit.php?tarikh=$tarikh");
    exit();

}
