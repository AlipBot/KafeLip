<?php
session_set_cookie_params($lifetime);
session_start();

if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = '../index.php'; </script>";
} else {
    include("connection.php");

    $email = $_SESSION['email'];
    $notel = $_SESSION['notel'];

    $cari = "select * from pelanggan
              where email = '$email'
              and notel = '$notel' limit 1";

    $cek = mysqli_query($condb, $cari);
    $m = mysqli_fetch_array($cek);
    

    if (mysqli_num_rows($cek) != 1) {
        die("<script> window.location.href='../logout.php';</script>");
    }elseif ($_SESSION['tahap'] != $m['tahap']){
        $_SESSION['tahap'] = $m['tahap'];
    }
}

unset($_SESSION['orders']);
unset($_SESSION['jumlah_harga']);
$_SESSION['success'] = "Berjaya Kosongkan Senarai Tempahan";
header("Location: ../menu.php");
exit(); 