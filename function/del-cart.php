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


# Mencari id menu pada tatasusunan SESSION['order']
$key = array_search($_GET['id_menu'], $_SESSION['orders']);

# Jika ada, buang id menu tersebut menggunakan unset
# jika tiada id-menu dijumpai maka key = false jika false !== false maka jadi false
if ($key !== false) {
    unset($_SESSION['orders'][$key]);
}
# menyusun semula indeks elemen dalam tatasusunan SESSION['order']
$_SESSION['orders'] = array_values($_SESSION['orders']);
$_SESSION['success'] = "1 item telah dikeluarkan ke troli";
header("Location: ../cart.php");
exit;
