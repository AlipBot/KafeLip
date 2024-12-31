<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

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
?>