<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start(); # mula session

# Semak pengguna login ke belum
if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = '../index.php'; </script>";
} else {

    include("connection.php"); # sambung ke database

    $email = $_SESSION['email'];
    $notel = $_SESSION['notel'];
    # Query untuk semak pelanggan wujud ke tak 
    $cari = "select * from pelanggan
              where email = '$email'
              and notel = '$notel' limit 1";

    $cek = mysqli_query($condb, $cari);
    $m = mysqli_fetch_array($cek);

    if (mysqli_num_rows($cek) != 1) {
        #jika tak wujud logout terus
        die("<script> window.location.href='../logout.php';</script>");
    }elseif ($m['tahap'] != "ADMIN"){
        #jika tahap admin ditukar pelanggan di page panel  admin  terus redirect logout terus
        die("<script> window.location.href='../logout.php';</script>");
    }
}
?>