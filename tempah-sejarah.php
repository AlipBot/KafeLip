<?php
# Memulakan fungsi session
session_start();
include('function/header.php');
include('function/connection.php');
$menu = "<br>";

# Mendapatkan semua data tempahan pengguna yang ada dari jadual tempahan
$sql = "SELECT tempahan.email, tempahan.kod_makanan, tempahan.tarikh
        SUM(tempahan.kuantiti * makanan.harga) AS jum
        FROM tempahan
        JOIN makanan ON tempahan.kod_makanan = makanan.kod_makanan
        WHERE tempahan.email = '".$_SESSION['email']."'
        GROUP BY tempahan.email, tempahan.kod_makanan, tempahan.tarikh
        ORDER BY tempahan.tarikh DESC";
$laksql = mysqli_query($condb, $sql);

?>

<!-- Memaparkan sejarah tempahan individu -->
<h3>Sejarah Tempahan</h3>

<?php if (mysqli_num_rows($laksql) > 0): ?>
<table align='center' border='1' width='50%'>
    <tr align='center'>
        <td>No Tempahan</td>
        <td>Tarikh</td>
        <td>Status<br>Tempahan</td>
        <td>Jumlah<br>Bayaran (RM)</td>
    </tr>

    <?php while ($m = mysqli_fetch_array($laksql)): ?>
    <tr align='center'>
        <td align='left'>
            <?php 
                # Gunakan tarikh dan email sebagai pengenalan tempahan
                echo "<a href='tempah-resit.php?tarikh=".$m['tarikh']."'>".$m['tarikh']."</a>";
            ?>
        </td>
        <td>
            <?php 
                $tarikh = date_create($m['tarikh']);
                echo "Tarikh : ".date_format($tarikh, "d/m/Y")."<br>
                      Masa : ".date_format($tarikh, "H:i:s");
            ?>
        </td>
    </tr>
    <?php endwhile; ?>

<?php else: ?>
    <p align='center'>Tiada Sejarah Tempahan</p>
<?php endif; ?>
</table>
