<?php
include('../function/autoKeluarAdmin.php');
# Memanggil fail header dan fail kawalan-admin.php
include('../function/connection.php');

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
    'nama_makanan' => $m['nama_makanan'],
    'harga' => $m['harga']
]);

?>