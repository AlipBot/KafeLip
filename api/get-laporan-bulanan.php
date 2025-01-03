<?php
include('../function/connection.php');
include('../function/autoKeluarAdmin.php');

// Dapatkan bulan dan tahun semasa
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Query untuk mendapatkan data harian
$sql = "
    SELECT 
        DAY(tarikh) as hari,
        COUNT(DISTINCT CONCAT(tarikh, '-', email)) as jumlah_tempahan,
        SUM(jumlah_harga) as jumlah_jualan
    FROM tempahan 
    WHERE MONTH(tarikh) = ? 
    AND YEAR(tarikh) = ?
    GROUP BY DAY(tarikh)
    ORDER BY hari
";

$stmt = mysqli_prepare($condb, $sql);
mysqli_stmt_bind_param($stmt, "ss", $bulan, $tahun);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Dapatkan jumlah hari dalam bulan tersebut
$jumlah_hari = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);

// Sediakan array untuk simpan data
$laporan_bulanan = [];

// Isikan data untuk setiap hari
for ($hari = 1; $hari <= $jumlah_hari; $hari++) {
    $laporan_bulanan[$hari] = [
        'hari' => $hari,
        'jumlah_tempahan' => 0,
        'jumlah_jualan' => 0
    ];
}

// Masukkan data sebenar dari database
while ($row = mysqli_fetch_assoc($result)) {
    $hari = (int)$row['hari'];
    $laporan_bulanan[$hari] = [
        'hari' => $hari,
        'jumlah_tempahan' => (int)$row['jumlah_tempahan'],
        'jumlah_jualan' => (float)$row['jumlah_jualan']
    ];
}

// Tukar array associative kepada array biasa
$laporan_bulanan = array_values($laporan_bulanan);

// Return JSON response
header('Content-Type: application/json');
echo json_encode([
    'bulan' => $bulan,
    'tahun' => $tahun,
    'jumlah_hari' => $jumlah_hari,
    'data' => $laporan_bulanan
]); 