<?php
# Memulakan fungsi session
session_start();

include('function/header.php');
include('function/connection.php');
$jumlah_harga = 0;

# Dapatkan email dan tarikh daripada URL
$email = $_SESSION['email'];
$tarikh = $_GET['tarikh'];

# Mendapatkan data tempahan berdasarkan email dan tarikh
$sql_pilih = "SELECT tempahan.*, makanan.nama_makanan, makanan.harga
              FROM tempahan
              JOIN makanan ON tempahan.kod_makanan = makanan.kod_makanan
              WHERE tempahan.email = '$email' AND tempahan.tarikh = '$tarikh'";

$laksana = mysqli_query($condb, $sql_pilih);
?>

<!-- Memaparkan data tempahan pada resit -->
<h3>Resit Tempahan</h3>

<table id='saiz' align='center' border='1' width='50%'>
    <tr>
        <td colspan='4'><?php include('function/butang-saiz.php'); ?></td>
    </tr>
    <tr align='center' bgcolor='#f4f87e'>
        <td>Menu</td>
        <td>Kuantiti</td>
        <td>Harga<br>seunit</td>
        <td>Harga</td>
    </tr>

    <?php while($m = mysqli_fetch_array($laksana)){ ?>
    <tr>
        <td><?= $m['nama_makanan'] ?></td>
        <td align='center'><?= $m['kuantiti'] ?></td>
        <td align='right'><?= number_format($m['harga'], 2) ?></td>
        <td align='right'><?php 
            $harga = $m['kuantiti'] * $m['harga'];
            $jumlah_harga += $harga;
            echo number_format($harga, 2);
        ?></td>
    </tr>
    <?php } ?>
    <tr align='right' bgcolor='#f4f87e'>
        <td colspan='3'>Jumlah Bayaran (RM)</td>
        <td><?= number_format($jumlah_harga, 2) ?></td>
    </tr>
</table>
