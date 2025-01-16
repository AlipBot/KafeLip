<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("../function/autoKeluar.php");  # fail function auto logout jika pengguna belum login
include("../function/connection.php"); # Sambung Ke database
//――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\

# dapatkan tahun dan bulan semasa
$bulan = $_GET['bulan'] ?? date('m');
$tahun = $_GET['tahun'] ?? date('Y');

$query = "SELECT m.nama_makanan, SUM(t.kuantiti) as jumlah
          FROM tempahan t
          JOIN makanan m ON t.kod_makanan = m.kod_makanan
          WHERE MONTH(t.tarikh) = ? AND YEAR(t.tarikh) = ?
          GROUP BY m.kod_makanan, m.nama_makanan
          ORDER BY jumlah DESC
          LIMIT 10";

$stmt = $condb->prepare($query);
$stmt->bind_param("ss", $bulan, $tahun);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
header('Content-Type: application/json');
echo json_encode([
    'Owner' => 'Alipje29',
    'menu' => $data,
    'bulan' => $bulan,
    'tahun' => $tahun
]);
