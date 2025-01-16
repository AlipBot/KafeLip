<?php
session_start(); # mula session
# semak pengguna dah login ke belum
if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = '../index.php'; </script>";
} else {
    include("connection.php"); # sambung ke database

    $email = $_SESSION['email'];
    $notel = $_SESSION['notel'];
    # Query semak pelanggan wujud di dalam database
    $cari = "select * from pelanggan
              where email = '$email'
              and notel = '$notel' limit 1";

    $cek = mysqli_query($condb, $cari);
    $m = mysqli_fetch_array($cek);
    
    if (mysqli_num_rows($cek) != 1) {
        # jika tak wujud logut pengguna
        die("<script> window.location.href='../logout.php';</script>");
    }elseif ($_SESSION['tahap'] != $m['tahap']){
        # setkan session tahap baharu jika ada perubahan
        $_SESSION['tahap'] = $m['tahap'];
    }
}
# buang file session tempahan
unset($_SESSION['orders']);
# hantar toast succes dan redirect ke halaman menu 
$_SESSION['success'] = "Berjaya Kosongkan Senarai Tempahan";
header("Location: ../menu.php");
exit(); 