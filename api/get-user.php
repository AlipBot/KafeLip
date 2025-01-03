<?php
include('../function/autoKeluarAdmin.php');
# Memanggil fail header dan fail kawalan-admin.php
include('../function/connection.php');

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
    'nama' => $m['nama'],
    'email' => $m['email'],
    'notel' => $m['notel'],
    'password' => $m['password'],
    'tahap' => $m['tahap']
]);

?>