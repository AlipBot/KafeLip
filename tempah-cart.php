<?php
# Memulakan fungsi session dan memanggil fail header.php
session_start();
include('function/header.php');
include('function/connection.php');
$jumlah_harga = 0;

# menyemak jika tatasusunan order kosong
if (!isset($_SESSION['orders']) or count($_SESSION['orders']) == 0) {
    die("<script>
    alert('Cart anda kosong');
    window.location.href='menu.php';
</script>");
} else {

    # dapatkan bilangan setiap elemen 
    $bilangan = array_count_values($_SESSION['orders']);

    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($bilangan, function ($count) {
        return $count >= 1;
    });

    # Memaparkan menu yang ditempah
    echo "<table align='center' border='1' width='50%'>
      <tr align='center'>
        <td>Menu</td>
        <td>Kuantiti</td>
        <td>Harga<br>seunit</td>
        <td>Harga</td>
     </tr>";

    foreach ($sama as $key => $bil) {
        $sql = "select* from makanan where kod_makanan = '$key'";
        $lak = mysqli_query($condb, $sql);
        $m = mysqli_fetch_array($lak);
?>
        <tr>
            <td><?= $m['nama_makanan'] ?></td>
            <td align='center'>
                <!-- butang menambah bilangan menu -->
                <a href='function/add-cart.php?page=cart&id_menu=<?= $m['kod_makanan'] ?>'>
                    [ + ]</a>

                <?= $bil ?>

                <!-- butang membuang bilangan menu -->
                <a href='function/del-cart.php?id_menu=<?= $m['kod_makanan'] ?>'>
                    [ - ]</a>

            </td>
            <td align='right'><?= $m['harga'] ?></td>
            <td align='right'><?php
                                $harga = $bil * $m['harga'];
                                $jumlah_harga = $jumlah_harga + $harga;
                                echo number_format($harga, 2) ?></td>
        </tr>
    <?php  }
    # memaparkan jenis tempahan
    echo "<tr align='right' bgcolor='cyan'>
        <td colspan='3' >Jumlah Bayaran (RM) </td>
        <td >" . number_format($jumlah_harga, 2) . "</td>
      </tr>";
    echo "</table>";
    ?> <br>
    <form action='tempah-sah.php' method='POST'>
        <table align='center' border='1' width='50%'>

            <tr>
                <td>Sahkan Tempahan</td>
                <td><input type='submit' value='Sah tempahan'></td>
            </tr>
        </table>
    </form>

<?php } ?>