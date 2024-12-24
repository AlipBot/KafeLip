<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();
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

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tempahan Makanan Roti</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-[#DAD7CD] text-[#344E41]">
    <header class="bg-[#A3B18A] text-white py-4 mb-4">
        <nav class="container mx-auto text-center">
            <a href="menu.php" class="text-white hover:underline mx-2">
                <i class="fas fa-home"></i>
                <span class="hidden sm:inline">MENU</span>
            </a> |
            <a href="cart.php" class="text-white hover:underline mx-2">
                <i class="fas fa-shopping-cart"></i>
                <span class="hidden sm:inline">Cart</span>
            </a> |

            <!-- bahagian admin  -->
            <?php if ($_SESSION['tahap'] == "ADMIN"): ?>

                <a href="admin/list-user.php" class="text-white hover:underline mx-2">
                    <i class="fas fa-users"></i>
                    <span class="hidden sm:inline">Senarai Pengguna</span>
                </a> |
                <a href="admin/list-menu.php" class="text-white hover:underline mx-2">
                    <i class="fas fa-list"></i>
                    <span class="hidden sm:inline">Senarai menu</span>
                </a> |
                <a href="admin/laporan.php" class="text-white hover:underline mx-2">
                    <i class="fas fa-file-alt"></i>
                    <span class="hidden sm:inline">Laporan Tempahan</span>
                </a> |
            <?php endif; ?>

            <a href="logout.php" class="text-white hover:underline mx-2">
                <i class="fas fa-sign-out-alt"></i>
                <span class="hidden sm:inline">Logout</span>
            </a>
        </nav>
    </header>
    <div class="container mx-auto text-center py-8 px-4">
        <h2 class="text-2xl font-semibold mb-4 text-[#588157]"><i class="fas fa-history"></i> Sejarah Tempahan</h2>
        <div class="overflow-x-auto">
            <?php if (mysqli_num_rows($laksql) > 0): ?>
                <table class="table-auto mx-auto border-collapse border-2 border-[#A3B18A] border-separate shadow-lg w-full sm:w-auto rounded-lg">
                    <thead>
                        <tr>
                            <th class="border-3 border-[#A3B18A] px-4 py-2 bg-[#A3B18A] text-white rounded-tl-lg"><i class="fas fa-calendar-alt"></i> Tarikh</th>
                            <th class="border-3 border-[#A3B18A] px-4 py-2 bg-[#A3B18A] text-white"><i class="fas fa-money-bill-wave"></i> Jumlah Bayaran (RM)</th>
                            <th class="border-3 border-[#A3B18A] px-4 py-2 bg-[#A3B18A] text-white rounded-tr-lg"><i class="fas fa-receipt"></i> Cek Resit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($m = mysqli_fetch_array($laksql)):
                            $tarikh = date_create($m['tarikh']);
                        ?>
                            <tr class="bg-[#DAD7CD] hover:bg-[#A3B18A]">
                                <td class="border-0 shadow-lg	 border-[#A3B18A] px-4 py-2 rounded-md	">
                                    <i class="fas fa-calendar-day"></i> Tarikh: <?php echo date_format($tarikh, "d/m/Y") ?> <br>
                                    <i class="fas fa-clock"></i> Masa: <?php echo date_format($tarikh, "H:i:s") ?> <br>
                                </td>
                                <td class="border-0 shadow-lg  rounded-md bborder-[#A3B18A] px-4 py-2 text-center"> <?= number_format($m['jum'], 2) ?> </td>
                                <td class="border-0 shadow-lg border-[#A3B18A] px-4 py-2 text-center rounded-md	">
                                    <?php $masa = date_format($tarikh, "Y-m-d H:i:s"); ?>
                                    <button onclick="location.href='resit.php?tarikh=<?= $masa ?>';" class="bg-[#3A5A40] text-white px-4 py-2 border border-[#344E41] rounded-md	"><i class="fas fa-search"></i> Cek</button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <p class="text-2xl text-white bg-[#A3B18A] p-4 rounded-lg shadow-lg inline-block">
                        <i class="fas fa-exclamation-circle"></i> Tiada tempahan atau kosong
                    </p>
                </div> <?php endif; ?>
        </div>
    </div>
</body>

</html>