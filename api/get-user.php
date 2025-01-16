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
$sql        =   "select* from pelanggan where notel = '" . $_GET['notel'] . "'";
$laksana    =   mysqli_query($condb, $sql);
$m          =   mysqli_fetch_array($laksana);

header('Content-Type: application/json');
echo json_encode([
    'Owner' => 'Alipje29',
    'nama' => $m['nama'],
    'email' => $m['email'],
    'notel' => $m['notel'],
    'password' => $m['password'],
    'tahap' => $m['tahap']
]);

?>