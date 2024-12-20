<?php
# Memulakan fungsi session
session_start();

# Memanggil fail header dan fail kawalan-admin.php
include('function/header.php');
include('function/kawalan-admin.php');
include('function/connection.php');

# Menyemak kewujudan data GET.
if (empty($_GET)) {
    die("<script>window.location.href='menu-senarai.php';</script>");
}
# Mendapatkan maklumat menu
$sql_menu       =   "select* from makanan where 
                    makanan.kod_makanan = '" . $_GET['id_menu'] . "'";
$lak_menu       =   mysqli_query($condb, $sql_menu);
$m              =   mysqli_fetch_array($lak_menu);


?>

<h3>kemaskini pengguna Baru</h3>
<form enctype="multipart/form-data" method='POST'
    action='menu-kemaskini-proses.php?id_menu=<?= $_GET['id_menu'] ?>'>

    Nama Menu
    <input required type='text' name='nama_menu' value='<?= $m['nama_makanan'] ?>'><br>

    Harga
    <input required type='number' name='harga' step='0.01' value='<?= $m['harga'] ?>'><br>

    Gambar
    <input type='file' name='gambar'><br>

    <input type='submit' value='Kemaskini'>
</form>

<?php include('footer.php'); ?>