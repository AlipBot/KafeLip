<?php
# Memulakan fungsi session 
session_start();
include('function/header.php');
include('function/connection.php');

# Mendapatkan semua data tempahan pengguna berdasarkan email dan tarikh
$sql = "SELECT email, tarikh, 
               SUM(kuantiti * makanan.harga) AS jum
        FROM tempahan
        JOIN makanan ON tempahan.kod_makanan = makanan.kod_makanan
        WHERE email = '" . $_SESSION['email'] . "'
        GROUP BY email, tarikh
        ORDER BY tarikh DESC";

$laksql = mysqli_query($condb, $sql);
?>

<!-- Memaparkan sejarah tempahan individu -->
<h3>Sejarah Tempahan</h3>

<?php if (mysqli_num_rows($laksql) > 0): ?>
    <table align='center' border='1' width='50%'>
        <tr align='center'>
            <td>Tarikh</td>
            <td>Jumlah Bayaran (RM)</td>
            <td>Cek Resit</td>
        </tr>

        <?php while ($m = mysqli_fetch_array($laksql)): ?>
            <tr align='center'>
                <td>
                    <?php
                    $tarikh = date_create($m['tarikh']);
                    echo "Tarikh: " . date_format($tarikh, "d/m/Y") . "<br>";
                    echo "Masa: " . date_format($tarikh, "H:i:s");
                    ?>
                </td>
                <td>
                    <?= number_format($m['jum'], 2) ?>
                </td>
                <td>
                 <?php $masa = date_format($tarikh, "Y-m-d H:i:s");// Format timestamp semasa?>
                <button onclick="location.href='tempah-resit.php?tarikh=<?=$masa?>';" >
                    Cek
                </button>
                </td>
            </tr>
        <?php endwhile; ?>

    <?php else: ?>
        <p align='center'>Tiada Sejarah Tempahan</p>
    <?php endif; ?>
    </table>