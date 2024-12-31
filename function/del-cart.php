<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

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
