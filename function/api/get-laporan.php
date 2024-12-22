<?php
session_start();
include('../connection.php');

// Jumlah Tempah Hari Ini
$sql_kiraharini = "SELECT COUNT(*) AS jumlah_pelanggan_harini
FROM (
    SELECT DISTINCT t.email, DATE_FORMAT(t.tarikh, '%Y-%m-%d %H:%i:%s') AS unik_masa
    FROM tempahan t
    WHERE DATE(t.tarikh) = CURDATE()
) AS unik_pelanggan;
";
$kira_harini = mysqli_fetch_assoc(mysqli_query($condb, $sql_kiraharini));

// Jumlah Tempah Bulan Ini
$sql_kirabulanini = "SELECT COUNT(*) AS jumlahSebulan FROM tempahan WHERE MONTH(tarikh) = MONTH(CURDATE()) AND YEAR(tarikh) = YEAR(CURDATE())";
$kira_bulanini = mysqli_fetch_assoc(mysqli_query($condb, $sql_kirabulanini));

// Jumlah Keuntungan Hari Ini
$sql_untungharini = "SELECT SUM(jumlah_harga) AS total_harian FROM tempahan WHERE DATE(tarikh) = CURDATE()";
$untung_hari = mysqli_fetch_assoc(mysqli_query($condb, $sql_untungharini));

// Jumlah Keuntungan Bulan Ini
$sql_untungbulani = "SELECT SUM(jumlah_harga) AS total_bulanan FROM tempahan WHERE MONTH(tarikh) = MONTH(CURDATE()) AND YEAR(tarikh) = YEAR(CURDATE())";
$untung_bulan = mysqli_fetch_assoc(mysqli_query($condb, $sql_untungbulani));

// Jumlah Pelanggan
$sql_pelanggan = "SELECT COUNT(*) AS total_pelanggan FROM pelanggan";
$total_pelanggan = mysqli_fetch_assoc(mysqli_query($condb, $sql_pelanggan));

// Laporan Tempahan Pelanggan Hari Ini
// Laporan Tempahan Pelanggan Hari Ini
$sql_tempahan = "
    SELECT 
        p.nama, 
        t.email, 
        t.tarikh, 
        GROUP_CONCAT(
            CONCAT(m.nama_makanan, ' ( RM ', FORMAT(m.harga, 2), ' ) X ', t.kuantiti)
            SEPARATOR '<br>'
        ) AS senarai_makanan,
        SUM(t.kuantiti * m.harga) AS jumlah_harga
    FROM tempahan t
    JOIN makanan m ON t.kod_makanan = m.kod_makanan
    JOIN pelanggan p ON t.email = p.email
    WHERE DATE(t.tarikh) = CURDATE()
    GROUP BY t.email, t.tarikh
    ORDER BY t.tarikh DESC";


$laptoday = mysqli_query($condb, $sql_tempahan);

$laptoday_data = [];
while ($row = mysqli_fetch_assoc($laptoday)) {
    $tarikh = date_create($row['tarikh']);
    $laptoday_data[] = [
        'nama' => $row['nama'],
        'email' => $row['email'],
        'tarikh' => date_format($tarikh, "d/m/Y"),
        'masa' => date_format($tarikh, "g:i:s A"),
        'senarai_makanan' => $row['senarai_makanan'],
        'jumlah_harga' => $row['jumlah_harga']
    ];
}

// Return JSON
echo json_encode([
    'jumlahHarini' => $kira_harini['jumlah_pelanggan_harini'],
    'jumlahSebulan' => $kira_bulanini['jumlahSebulan'],
    'total_harian' => $untung_hari['total_harian'] ?? 0,
    'total_bulanan' => $untung_bulan['total_bulanan'] ?? 0,
    'total_pelanggan' => $total_pelanggan['total_pelanggan'],
    'laporan_hari_ini' => $laptoday_data
]);
