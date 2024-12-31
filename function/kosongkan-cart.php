<?php
session_start();
unset($_SESSION['orders']);
unset($_SESSION['jumlah_harga']);
$_SESSION['success'] = "Berjaya Kosongkan Senarai Tempahan";
header("Location: ../cart.php");
exit(); 