<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include('function/autoKeluar.php');
include('function/connection.php');
//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\

# memaparkan bilangan senarai tempahan
if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

#set email berdasakan di dalama data di session yang sudah disetkan di login
$email = $_SESSION['email'];
# query untuk dapatkan maklumat pelanggan

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
# Cara  execute sql lebih selamat dan mengelak injection SQL daripada digodam 
mysqli_stmt_bind_param($stmt, "sssssss", $email, $email, $email, $email, $email, $email, $email,);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user_data = mysqli_fetch_assoc($result);

#query dapatkan list top 1 - 3 yang paling banyak dibeli oleh pelanggan
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
    <title>User Profile</title>
    <link rel="stylesheet" href="lib/css/all.css">
    <link rel="stylesheet" href="lib/css/sharp-solid.css">
    <link rel="stylesheet" href="lib/css/sharp-regular.css">
    <link rel="stylesheet" href="lib/css/sharp-light.css">
    <link rel="stylesheet" href="lib/css/duotone.css" />
    <link rel="stylesheet" href="lib/css/brands.css" />
    <link href="lib/css/css2.css" rel="stylesheet" />
    <script src="lib/js/tailwind.js"></script>
    <link rel="stylesheet" href="lib/css/sweetalert2.min.css">
    <script src="lib/js/sweetalert2@11.js"></script>
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

        @media (max-width: 768px) {

            .nav a span,
            .goMenu a span {
                display: none;
            }

        }

        /* Custom scrollbar styles */
        ::-webkit-scrollbar {
            width: 12px;
        }

        ::-webkit-scrollbar-track {
            background: #FAF3DD;
        }

        ::-webkit-scrollbar-thumb {
            background-color: #000;
            border-radius: 6px;
            border: 3px solid #FAF3DD;
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #333;
        }

        /* Scroll to top button */
        #scrollToTopBtn {
            display: none;
            position: fixed;
            bottom: 60px;
            right: 20px;
            z-index: 100;
            background-color: #4A7C59;
            color: white;
            border: none;
            border-radius: 50%;
            padding: 10px 15px;
            cursor: pointer;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
        }

        /* Hover effect for header navigation links */
        .nav a::after,
        .goMenu a::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #4A7C59;
            transition: width 0.3s;
        }

        .nav a:hover::after,
        .goMenu a:hover::after {
            width: 100%;
        }


        #scrollToTopBtn:hover {
            background-color: #68B0AB;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>

<body class="bg-[#FAF3DD] min-h-screen flex flex-col">
    <!-- Header -->
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-[#4A7C59] mr-2">
                </i>
                <span class="text-black">Kafe</span>
                <span class="text-black">Lip</span>
            </div>
            <div class="nav flex gap-6 -ml-10 mr-20">
                <a class="text-black font-bold active:text-[#4A7C59]" href="menu.php">
                    <i class="fas fa-utensils text-[#4A7C59] mr-1"></i>
                    <span>MENU</span>
                </a>
                <a class="text-black font-bold active:text-[#4A7C59]" href="cart.php">
                    <i class="fas fa-shopping-cart text-[#4A7C59] mr-1"></i>
                    <span>CART <?= $bil ?></span>
                </a>
                <a class="text-black font-bold active:text-[#4A7C59]" href="sejarah-tempah.php">
                    <i class="fas fa-history text-[#4A7C59] mr-1"></i>
                    <span>SEJARAH TEMPAHAN</span>
                </a>
            </div>
            <div class="relative">
                <button id="menuButton" class="p-2 hover:bg-gray-100 rounded-full">
                    <i class="fas fa-bars text-[#4A7C59] text-xl"></i>
                </button>
                <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                    <?php if ($_SESSION['tahap'] == "ADMIN"): ?>
                        <a href="admin/panel.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fa fa-list-alt mr-2 text-[#4A7C59]"></i>Panel Admin
                        </a>
                    <?php endif; ?>
                    <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-user mr-2 text-[#4A7C59]"></i>Profil
                    </a>
                    <a href="account.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-cog mr-2 text-[#4A7C59]"></i>Akaun
                    </a>
                    <hr class="my-1">
                    <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                        <i class="fas fa-sign-out-alt mr-2 text-[#4A7C59]"></i>Log Keluar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content -->
    <div class="flex-grow flex items-center justify-center py-8">
        <div class="w-full max-w-4xl">
            <div class="w-full flex flex-col items-center">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-semibold"><?= $user_data['nama'] ?></h2>
                    <p class="text-lg"><i class="fas fa-phone-alt mr-2"></i><?= $user_data['notel'] ?></p>
                    <p class="text-lg"><i class="fas fa-medal mr-2"></i><?= $user_data['tahap'] ?></p>
                </div>
                <div class="w-full">
                    <h3 class="text-xl font-semibold mb-4">Statistik</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-calendar-day mr-2"></i>Jumlah Tempahan Hari Ini</h4>
                            <p class="text-2xl font-bold"><?= $user_data['tempahan_hari'] ?? 0 ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-calendar-alt mr-2"></i>Jumlah Tempahan Bulan Ini</h4>
                            <p class="text-2xl font-bold"><?= $user_data['tempahan_bulan'] ?? 0 ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-calendar mr-2"></i>Jumlah Tempahan Tahun Ini</h4>
                            <p class="text-2xl font-bold"><?= $user_data['tempahan_tahun'] ?? 0 ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah Harga Hari Ini</h4>
                            <p class="text-2xl font-bold">RM <?= number_format($user_data['harga_hari'] ?? 0, 2) ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah Harga Bulan Ini</h4>
                            <p class="text-2xl font-bold">RM <?= number_format($user_data['harga_bulan'] ?? 0, 2) ?></p>
                        </div>
                        <div class="stat-box text-center">
                            <h4 class="text-lg font-semibold mb-2"><i class="fas fa-money-bill-wave mr-2"></i>Jumlah Harga Tahun Ini</h4>
                            <p class="text-2xl font-bold">RM <?= number_format($user_data['harga_tahun'] ?? 0, 2) ?></p>
                        </div>
                    </div>
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-4">Top 3 Menu Paling Banyak Dibeli</h3>
                        <div class="flex justify-center">
                            <?php foreach ($top_menus as $index => $menu): ?>
                                <div class="menu-box">
                                    <h4 class="text-lg font-semibold mb-2">Top <?= $index + 1 ?></h4>
                                    <img alt="Image of <?= htmlspecialchars($menu['nama_makanan']) ?>"
                                        class="w-24 h-24 rounded-full mx-auto mb-2"
                                        src="menu-images/<?= htmlspecialchars($menu['gambar']) ?>">
                                    <p class="text-2xl font-bold"><?= htmlspecialchars($menu['nama_makanan']) ?></p>
                                    <p class="text-lg">Jumlah: <?= $menu['jumlah'] ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full bg-[#FAF3DD] text-black py-6 px-10">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
            <div class="mb-4 lg:mb-0">
                © 2025 KAFELIP. Semua hak terpelihara.
            </div>
            <div class="flex gap-6">
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-facebook-f">
                    </i>
                </a>
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-twitter">
                    </i>
                </a>
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-instagram">
                    </i>
                </a>
            </div>
        </div>
    </footer>

    <!-- butang scroll keatas-->
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Scrip butang keatas
        window.onscroll = function() {
            var scrollToTopBtn = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // scroll ketas
        function scrollToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <script>
        // script butang garis tiga di header
        const menuButton = document.getElementById('menuButton');
        const dropdownMenu = document.getElementById('dropdownMenu');

        menuButton.addEventListener('click', () => {
            dropdownMenu.classList.toggle('hidden');
        });

        // Tutup dropdown bila klik di luar
        document.addEventListener('click', (event) => {
            if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>