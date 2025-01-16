<?php
# Mula session
session_set_cookie_params($lifetime);
session_start();

# function semak pengguna wujud sebelum tambah makanan ke dalam troli
# Semak session tahap atau nama untuk mengelakkan pengguna belum login dah masuk page ini
if (empty($_SESSION['tahap']) || empty($_SESSION['nama'])) {
    echo " <script> window.location.href = '../index.php'; </script>";
} else {
    # sambung ke database
    include("connection.php");

    $email = $_SESSION['email'];
    $notel = $_SESSION['notel'];
    # query semak pelanggan wujud ke dalam database
    $cari = "select * from pelanggan
              where email = '$email'
              and notel = '$notel' limit 1";

    $cek = mysqli_query($condb, $cari);
    $m = mysqli_fetch_array($cek);
    
    if (mysqli_num_rows($cek) != 1) {
        #jika tak wujud auto logout
        die("<script> window.location.href='../logout.php';</script>");
    }elseif ($_SESSION['tahap'] != $m['tahap']){
        #jika wujud tapi tahap tidak sama dengan session ubah tahap baru 
        # jika admin tukar tahap pengguna ini perlu refresh sahaja
        $_SESSION['tahap'] = $m['tahap'];
    }
}

# Semak session tempahan wujud ke tak
if(!isset($_SESSION['orders'])){ 
    #kalau tak wujud buat session baru
    $_SESSION['orders'] = array();
}

#functin add to cart
if (isset($_GET['id_menu']) && isset($_GET['quantity'])) {
    $id_menu = $_GET['id_menu'];
    $quantity = intval($_GET['quantity']);
    
    // Tambah item sebanyak quantity yang dipilih
    for ($i = 0; $i < $quantity; $i++) {
        array_push($_SESSION['orders'], $id_menu);
    }
    # papar toast susccess
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