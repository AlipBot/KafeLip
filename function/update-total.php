<?php
session_start();
if(isset($_GET['total'])) {
    $_SESSION['jumlah_harga'] = floatval($_GET['total']);
    echo "success";
}else{
    header( "Location: ../cart.php");
}
?> 