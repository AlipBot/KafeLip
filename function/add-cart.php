<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

# mengistihar tatasusun session['orders'] jika belum wujud
if(!isset($_SESSION['orders'])){ 
    $_SESSION['orders']=array();
}

# menambah elemen ke dalam session['orders']
array_push($_SESSION['orders'],$_GET['id_menu']);
if($_GET['page']=="menu"){
    echo"<script>window.location.href='../menu.php';</script>";
} else {
    echo"<script>window.location.href='../cart.php';</script>";
}

?>