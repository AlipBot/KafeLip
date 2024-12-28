<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = 'index.php'; </script>";
} else {

        if($_SESSION['tahap'] != "ADMIN") {
         die("<script>alert('sila login');
         window.location.href='../logout.php';</script>");
         }


    include("connection.php");

    $email = $_SESSION['email'];
    $notel = $_SESSION['notel'];

    $cari = "select * from pelanggan
              where email = '$email'
              and notel = '$notel' limit 1";

    $cek = mysqli_query($condb, $cari);

    if (mysqli_num_rows($cek) != 1) {
        echo " <script> window.location.href = '../logout.php'; </script>";
    }
}
?>