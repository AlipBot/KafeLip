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

if(!isset($_SESSION['orders'])){ 
    $_SESSION['orders'] = array();
}

if (isset($_GET['id_menu']) && isset($_GET['quantity'])) {
    $id_menu = $_GET['id_menu'];
    $quantity = intval($_GET['quantity']);
    
    // Tambah item sebanyak quantity yang dipilih
    for ($i = 0; $i < $quantity; $i++) {
        array_push($_SESSION['orders'], $id_menu);
    }
    
    $_SESSION['success'] = "$quantity item telah ditambah ke troli";
    
    if($_GET['page'] == "menu"){
        header("Location: ../menu.php");
    } else {
        header( "Location: ../cart.php");
    }
    exit;
}

if (isset($_GET['ajax']) && $_GET['ajax'] === 'true') {
    exit; // Hentikan eksekusi tanpa redirect jika ini adalah permintaan AJAX
}
?>