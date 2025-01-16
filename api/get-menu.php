<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("../function/autoKeluar.php");  # fail function auto logout jika pengguna belum login
include("../function/connection.php"); # Sambung Ke database
//――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\


# Menyemak kewujudan data GET. Jika data GET empty, buka fail pengguna-senarai.php
if (empty($_GET)) {
    die("<script>window.location.href='../admin/list-user.php';</script>");
}

# Mendapatkan data daripada pangkalan data
$sql_menu       =   "select* from makanan where kod_makanan = '" . $_GET['kod_menu'] . "'";
$lak_menu       =   mysqli_query($condb, $sql_menu);
$m              =   mysqli_fetch_array($lak_menu);

header('Content-Type: application/json');
echo json_encode([
    'Owner' => 'Alipje29',
    'nama_makanan' => $m['nama_makanan'],
    'harga' => $m['harga']
]);

?>