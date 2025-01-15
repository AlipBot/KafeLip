<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include('../function/autoKeluarAdmin.php'); # fail function auto logout jika pengguna belum login dan bukan admin
include('../function/connection.php');  # Sambung Ke database
//――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\

// jika kawalan get email tidak wujud
if (!isset($_GET['email'])) {
    die("Email tidak diterima");
}

$email = $_GET['email'];
#  Query dapatkan data pelanggan daripada email
$sql = "SELECT p.*, 
       (SELECT COUNT(DISTINCT CONCAT(t.email, '-', t.tarikh)) 
        FROM tempahan t 
        WHERE DATE(t.tarikh) = CURDATE() 
        AND t.email = ?) AS tempahan_hari,
       (SELECT COUNT(DISTINCT CONCAT(t.email, '-', t.tarikh)) 
        FROM tempahan t 
        WHERE MONTH(t.tarikh) = MONTH(CURDATE()) 
        AND YEAR(t.tarikh) = YEAR(CURDATE()) 
        AND t.email = ?) AS tempahan_bulan,
       (SELECT COUNT(DISTINCT CONCAT(t.email, '-', t.tarikh)) 
        FROM tempahan t 
        WHERE YEAR(t.tarikh) = YEAR(CURDATE()) 
        AND t.email = ?) AS tempahan_tahun,
       (SELECT SUM(jumlah_harga) 
        FROM tempahan t 
        WHERE DATE(t.tarikh) = CURDATE() 
        AND t.email = ?) AS harga_hari,
       (SELECT SUM(jumlah_harga) 
        FROM tempahan t 
        WHERE MONTH(t.tarikh) = MONTH(CURDATE()) 
        AND YEAR(t.tarikh) = YEAR(CURDATE()) 
        AND t.email = ?) AS harga_bulan,
       (SELECT SUM(jumlah_harga) 
        FROM tempahan t 
        WHERE YEAR(t.tarikh) = YEAR(CURDATE()) 
        AND t.email = ?) AS harga_tahun
FROM pelanggan p
WHERE p.email = ?;
";

$stmt = mysqli_prepare($condb, $sql);
mysqli_stmt_bind_param($stmt, "sssssss", $email, $email, $email, $email, $email, $email, $email,);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

$sql_top_menu = "SELECT m.kod_makanan, m.nama_makanan, m.gambar, SUM(t.kuantiti) as jumlah
                 FROM tempahan t 
                 JOIN makanan m ON t.kod_makanan = m.kod_makanan
                 WHERE t.email = ?
                 GROUP BY m.kod_makanan
                 ORDER BY jumlah DESC
                 LIMIT 3";

$stmt_menu = mysqli_prepare($condb, $sql_top_menu);
mysqli_stmt_bind_param($stmt_menu, "s", $email);
mysqli_stmt_execute($stmt_menu);
$result_menu = mysqli_stmt_get_result($stmt_menu);
$top_menus = [];
while ($row = mysqli_fetch_assoc($result_menu)) {
    $top_menus[] = $row;
}
?>

<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna</title>
    <link rel="stylesheet" href="../lib/css/all.css">
    <link rel="stylesheet" href="../lib/css/sharp-solid.css">
    <link rel="stylesheet" href="../lib/css/sharp-regular.css">
    <link rel="stylesheet" href="../lib/css/sharp-light.css">
    <link rel="stylesheet" href="../lib/css/duotone.css" />
    <link rel="stylesheet" href="../lib/css/brands.css" />
    <link href="../lib/css/css2.css" rel="stylesheet" />
    <script src="../lib/js/tailwind.js"></script>
    <link rel="stylesheet" href="../lib/css/sweetalert2.min.css">
    <script src="../lib/js/sweetalert2@11.js"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }

        .stat-box {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
        }

        .menu-box {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            margin: 0.5rem;
            width: 200px;
        }
    </style>
</head>

<body class="bg-[#FAF3DD] min-h-screen">
    <div class="container mx-auto py-8">
        <div class="w-full max-w-4xl mx-auto">
            <div class="w-full flex flex-col items-center">
                <!-- Profil Header -->
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold"><?= htmlspecialchars($user_data['nama']) ?></h2>
                    <p class="text-lg"><i class="fas fa-envelope mr-2"></i><?= htmlspecialchars($user_data['email']) ?></p>
                    <p class="text-lg"><i class="fas fa-phone-alt mr-2"></i><?= htmlspecialchars($user_data['notel']) ?></p>
                    <p class="text-lg"><i class="fas fa-medal mr-2"></i><?= htmlspecialchars($user_data['tahap']) ?></p>
                </div>

                <!-- Statistik -->
                <div class="w-full">
                    <h3 class="text-xl font-semibold mb-4">Statistik Pengguna</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-calendar-day mr-2"></i>Tempahan Hari Ini</h4>
                            <p class="text-2xl font-bold"><?= $user_data['tempahan_hari'] ?? 0 ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-calendar-alt mr-2"></i>Tempahan Bulan Ini</h4>
                            <p class="text-2xl font-bold"><?= $user_data['tempahan_bulan'] ?? 0 ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-calendar mr-2"></i>Tempahan Tahun Ini</h4>
                            <p class="text-2xl font-bold"><?= $user_data['tempahan_tahun'] ?? 0 ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah (Hari)</h4>
                            <p class="text-2xl font-bold">RM <?= number_format($user_data['harga_hari'] ?? 0, 2) ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah (Bulan)</h4>
                            <p class="text-2xl font-bold">RM <?= number_format($user_data['harga_bulan'] ?? 0, 2) ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah (Tahun)</h4>
                            <p class="text-2xl font-bold">RM <?= number_format($user_data['harga_tahun'] ?? 0, 2) ?></p>
                        </div>
                    </div>

                    <!-- Top Menu -->
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Menu Paling Kerap Dibeli</h3>
                        <div class="flex justify-center flex-wrap">
                            <?php foreach ($top_menus as $index => $menu): ?>
                                <div class="menu-box">
                                    <h4 class="text-lg font-semibold mb-2">Top <?= $index + 1 ?></h4>
                                    <img src="../menu-images/<?= htmlspecialchars($menu['gambar']) ?>"
                                        alt="<?= htmlspecialchars($menu['nama_makanan']) ?>"
                                        class="w-24 h-24 rounded-full mx-auto mb-2 object-cover">
                                    <p class="text-lg font-bold"><?= htmlspecialchars($menu['nama_makanan']) ?></p>
                                    <p class="text-md">Jumlah Dibeli: <?= $menu['jumlah'] ?></p>
                                </div>
                            <?php endforeach; ?>
                            <?php if (empty($top_menus)): ?>
                                <p class="text-gray-500 text-center w-full">Tiada rekod pembelian</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>