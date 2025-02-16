<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("function/autoKeluar.php");
include('function/connection.php');
//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\

$jumlah_harga = 0;
#  memaparkan bilangan senarai tempahan
if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

# Dapatkan email dan tarikh daripada URL dan session
$email = $_SESSION['email'];
$tarikh = $_GET['tarikh'];

# Query Mendapatkan data tempahan berdasarkan email dan tarikh
$sql_pilih = "SELECT tempahan.*, makanan.nama_makanan, makanan.harga
              FROM tempahan
              JOIN makanan ON tempahan.kod_makanan = makanan.kod_makanan
              WHERE tempahan.email = '$email' AND tempahan.tarikh = '$tarikh'";

$laksana = mysqli_query($condb, $sql_pilih);
?>

<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resit</title>
    <link rel="apple-touch-icon" sizes="180x180" href="lib/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="lib/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="lib/icon/favicon-16x16.png">
    <link rel="manifest" href="lib/icon/site.webmanifest">
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
        @font-face {
            font-family: 'BebasNeue';
            src: url('lib/fonts/BebasNeue-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'LilitiaOne';
            src: url('lib/fonts/LilitaOne-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'LobsterTwo';
            src: url('lib/fonts/LobsterTwo-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        @font-face {
            font-family: 'Merriweather';
            src: url('lib/fonts/Merriweather-Regular.ttf') format('truetype');
            font-weight: normal;
            font-size: 60px;
            font-style: normal;
        }

        @font-face {
            font-family: 'Teko';
            src: url('lib/fonts/Teko-Regular.ttf') format('truetype');
            font-weight: normal;
            font-style: normal;
        }

        .fontkafelip,
        h2 {
            font-family: 'LobsterTwo', sans-serif;
        }

        .nav {
            font-family: 'Teko', sans-serif;
            font-size: 20px;

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

        @media print {
            body * {
                visibility: hidden;
            }

            .print-area,
            .print-area * {
                visibility: visible;
            }

            .print-area {
                position: absolute;
                left: 50%;
                top: 10px;
                transform: translate(-50%, 0) scale(0.95);
                width: 100%;
                max-width: 900px;
                padding: 0;
                margin: 0;
            }

            table {
                font-size: 13px;
                width: 100%;
                margin-bottom: 6px;
            }

            h2 {
                font-size: 20px;
                margin-bottom: 8px;
            }

            .text-sm {
                font-size: 12px;
            }

            .text-lg {
                font-size: 15px;
            }

            .p-6 {
                padding: 0.8rem;
            }

            .mb-4 {
                margin-bottom: 0.6rem;
            }
            
            .text-left.mb-4.flex {
                gap: 0.8rem;
                margin-bottom: 0.4rem;
            }
        }
    </style>
</head>

<body class="bg-[#FAF3DD] text-gray-800">
    <!-- Header -->
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-[#4A7C59] mr-2">
                </i>
                <span class="text-black fontkafelip">Kafe</span>
                <span class="text-black fontkafelip">Lip</span>
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
                <div id="dropdownMenu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
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

    <!-- Content  Resit -->
    <div class="conten container mx-auto text-center py-8 px-4 print-area">
        <h2 class="text-2xl font-bold mb-4 text-black"><i class="fas fa-receipt text-[#4A7C59] mr-1"></i> Resit</h2>
        <div class="bg-white shadow-md rounded-lg p-6 max-w-[700px] mx-auto ">
            <div class="text-left mb-4 flex justify-between">
                <div>
                    <p class="text-lg font-semibold mb-2"><i class="fas fa-user-circle text-[#4A7C59] mr-1"></i>
                        Maklumat Pelanggan</p>
                    <p class="text-sm mb-1"><i class="fas fa-user text-[#4A7C59] mr-1"></i> Nama :
                        <?= $_SESSION['nama'] ?>
                    </p>
                    <p class="text-sm mb-1"><i class="fas fa-phone text-[#4A7C59] mr-1"></i> No. Telefon :
                        <?= $_SESSION['notel'] ?>
                    </p>
                    <p class="text-sm"><i class="fas fa-envelope text-[#4A7C59] mr-1"></i> Email :
                        <?= $email ?>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold mb-2"><i class="fas fa-store text-[#4A7C59] mr-1"></i> KafeLip</p>
                    <p class="text-sm mb-1"><i class="fas fa-calendar-alt text-[#4A7C59] mr-1"></i> Tarikh:
                        <?php
                        $masa = date_create($tarikh);
                        echo date_format($masa, "d/m/Y") ?>
                    </p>
                    <p class="text-sm mb-1"><i class="fas fa-clock text-[#4A7C59] mr-1"></i> Masa:
                        <?php
                        $masa = date_create($tarikh);
                        echo date_format($masa, "g:i:s A") ?>
                    </p>
                    <p class="text-sm"><i class="fas fa-calendar-week text-[#4A7C59] mr-1"></i> Hari:
                        <?php
                        $hari = date('l', strtotime($tarikh));
                        $hari_melayu = [
                            'Sunday' => 'Ahad',
                            'Monday' => 'Isnin',
                            'Tuesday' => 'Selasa',
                            'Wednesday' => 'Rabu',
                            'Thursday' => 'Khamis',
                            'Friday' => 'Jumaat',
                            'Saturday' => 'Sabtu'
                        ];
                        echo $hari_melayu[$hari];
                        ?>
                    </p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border-2 border-gray-300 rounded-lg">
                    <thead>
                        <tr>
                            <th width="40%" class="p-2 border border-gray-300 rounded-tl-lg">Menu</th>
                            <th width="10%" class="p-2 border border-gray-300 rounded-tl-lg">Kuantiti</th>
                            <th width="20%" class="p-2 border border-gray-300 rounded-tl-lg">Harga Seunit (RM)</th>
                            <th width="20%" class="p-2 border border-gray-300 rounded-tl-lg">Jumlah (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($m = mysqli_fetch_array($laksana)) { ?>
                            <tr>
                                <td class="p-2 border border-gray-300">
                                    <?= $m['nama_makanan'] ?>
                                </td>
                                <td class="p-2 text-center border border-gray-300">
                                    <?= $m['kuantiti'] ?>
                                </td>
                                <td class="p-2 text-center border border-gray-300">
                                    <?= number_format($m['harga'], 2) ?>
                                </td>
                                <td class="p-2 text-center border border-gray-300">
                                    <?php
                                    $harga = $m['jumlah_harga'];
                                    $jumlah_harga += $harga;
                                    echo number_format($harga, 2);
                                    ?>
                                </td>
                            </tr>
                        <?php } ?>
                        <tr class="font-semibold">
                            <td colspan="3" class="p-2 text-right border border-gray-300">Jumlah Bayaran (RM)</td>
                            <td class="p-2 text-center border border-gray-300">
                                <?= number_format($jumlah_harga, 2) ?>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <button onclick="window.print()"
                class="mt-4 bg-[#4A7C59] text-white py-2 px-4 rounded-lg hover:bg-[#68B0AB] transition duration-300">
                <i class="fas fa-print mr-2"></i> Cetak
            </button>
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
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a class="text-[#4A7C59]" href="#">
                    <i class="fab fa-twitter"></i>
                </a>
                <a class="text-[#4A7C59]" href="https://www.instagram.com/alipje29/#">
                <i class="fab fa-instagram"></i>
                </a>
            </div>
        </div>
    </footer>

    <!-- Butang scroll ke atas -->
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Tunjukkan dan sorokkan kata laluan
        window.onscroll = function() {
            var scrollToTopBtn = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // Scroll ke atas
        function scrollToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
    <script>
        // function toast dan popup
        const notifsuccess = new Audio('lib/audio/notif.mp3'); // Path fail audio success
        const notiferror = new Audio('lib/audio/error.mp3'); // Path fail audio ralat
        const notifinfo = new Audio('lib/audio/info.mp3'); //  Path fail audio info
        const notifwarning = new Audio('lib/audio/warning.mp3'); // Path fail audio amaran


        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['success'])): ?>
                Toast.fire({
                    icon: "success",
                    title: "<?= $_SESSION['success'] ?>"
                });
                notifsuccess.play(); // Main bunyi bila toast muncul
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['info'])): ?>
                Toast.fire({
                    icon: "info",
                    title: "<?= $_SESSION['info'] ?>"
                });
                notifinfo.play();
                <?php unset($_SESSION['info']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['warning'])): ?>
                Toast.fire({
                    icon: "warning",
                    title: "<?= $_SESSION['warning'] ?>"
                });
                notifwarning.play();
                <?php unset($_SESSION['warning']); ?>
            <?php endif; ?>

            // Untuk popup error
            <?php if (isset($_SESSION['error'])): ?>
                Toast.fire({
                    icon: "error",
                    title: "<?= $_SESSION['error'] ?>"
                });
                notiferror.play();
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        })
    </script>
    <script>
        // function kat header untuk tunjuk menu tambahan garis tiga di kanan
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