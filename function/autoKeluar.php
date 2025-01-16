<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start(); # mula baca session

# semak pengguna belum login ke belum
if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = 'index.php'; </script>";
} else {

    include("connection.php"); # sambung ke database

    $email = $_SESSION['email'];
    $notel = $_SESSION['notel'];
    # Query semak pelanggan wujud ke tak
    $cari = "select * from pelanggan
              where email = '$email'
              and notel = '$notel' limit 1";

    $cek = mysqli_query($condb, $cari);
    $m = mysqli_fetch_array($cek);
    
    if (mysqli_num_rows($cek) != 1) {
        # tak wujud logout
        die("<script> window.location.href='logout.php';</script>");
    }elseif ($_SESSION['tahap'] != $m['tahap']){
        # wujud tapi tahap tak sama dengan session tukar baru 
        # berlaku apabila admin tukar tahap di panel
        $_SESSION['tahap'] = $m['tahap'];
    }
}
?>