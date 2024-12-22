<?php
# Memulakan fungsi session dan memanggil fail connection & header.php
session_start();
include('../function/header.php');
include('../function/connection.php');
include('../function/admin-only.php');

if (isset($_POST['tarikh_semasa'])) {
    $tarikhsemasa = $_POST['tarikh_semasa'];
} else {
    $tarikhsemasa = date("Y-m-d");
}

# Dapatkan Senarai tarikh
$sqltarikh = "SELECT DATE(tarikh) AS tarikh, count(*) as bilangan
FROM tempahan
GROUP BY DATE(tarikh)
ORDER BY DATE(tarikh) DESC";
$laktarikh = mysqli_query($condb, $sqltarikh);

# dapatkan semua senarai tempahan
$sql = "SELECT t.email, 
               t.tarikh,
               SUM(t.kuantiti * m.harga) AS jumlah_harga
        FROM tempahan t
        JOIN makanan m ON t.kod_makanan = m.kod_makanan
        WHERE t.tarikh LIKE '%$tarikhsemasa%'
        GROUP BY t.email, t.tarikh
        ORDER BY t.tarikh DESC";
$laksql = mysqli_query($condb, $sql);


?>

<h3>Laporan Tempahan</h3>
<form action='' method='POST'>
    Pilih Tarikh
    <select name='tarikh_semasa'>
        <option value='<?= $tarikhsemasa ?>'>
            <?= date_format(date_create($tarikhsemasa), "d/m/Y"); ?></option>
        <option disabled>Pilih Tarikh Lain Di bawah</option>
        <?php while ($mm = mysqli_fetch_array($laktarikh)): ?>
            <option value='<?= $mm['tarikh'] ?>'>
                <?= date_format(date_create($mm['tarikh']), "d/m/Y") ?>
            </option>
        <?php endwhile; ?>
    </select>
    <input type='submit' value='PAPAR'>
</form>

<!-- Memaparkan senarai tempahan berdasarkan tarikh -->
<table align='center' border='1' width='50%'>
    <tr align='center'>
        <td>Pelanggan</td>
        <td>Tarikh</td>
        <td>Jumlah<br>Bayaran (RM)</td>
    </tr>
    <?php while ($m = mysqli_fetch_array($laksql)) { ?>
    <tr align='center'>
        <td align='left'>
            <?php
            echo "<b><u>" . $m['email'] . "</u></b><br>";

            # Dapatkan butiran tempahan bagi setiap email dan tarikh (termasuk masa dan saat)
            $sqlpaparmenu = "SELECT m.nama_makanan, t.kuantiti, m.harga
                             FROM tempahan t
                             JOIN makanan m ON t.kod_makanan = m.kod_makanan
                             WHERE t.email = '" . $m['email'] . "'
                             AND t.tarikh = '" . $m['tarikh'] . "'";
            $lakpaparmenu = mysqli_query($condb, $sqlpaparmenu);

            while ($mm = mysqli_fetch_array($lakpaparmenu)) {
                echo $mm['nama_makanan'] . " ( " . $mm['kuantiti'] . " X RM " . number_format($mm['harga'], 2) . " )<br>";
            }
            ?>
        </td>
        <td>
            <?php
            $tarikh = date_create($m['tarikh']);
            echo "Tarikh : " . date_format($tarikh, "d/m/Y") . "<br>";
            echo "Masa : " . date_format($tarikh, "H:i:s");
            ?>
        </td>
        <td><?= number_format($m['jumlah_harga'], 2) ?></td>
    </tr>
<?php } ?>
