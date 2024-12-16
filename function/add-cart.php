<?php
# memulakan session 
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
    echo"<script>window.location.href='../tempah-cart.php';</script>";
}

?>