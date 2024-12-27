<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();
include('function/connection.php');

if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

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
    <style>
        .custom-font {
            font-family: 'Roboto', sans-serif;
        }

        #scrollToTopBtn:hover {
            background-color: #68B0AB;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .footerkaki {
            padding: 2%;
            display: grid;
            background-color: #FAF3DD;
            grid-template-columns: repeat(3, 1fr);
            justify-items: center;
        }

        @media (max-width: 768px) {
            .nav a span {
                display: none;
            }

            .comment {
                padding: 2px;
                gap: 2px;
            }

            .comment img {
                width: 20px;
                height: 20px;
            }

            .comment .text .name {
                font-size: 12px;
            }

            .comment .text .time {
                font-size: 10px;
            }

            .comment .text .message {
                font-size: 10px;
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

        .add-to-cart:hover,
        #scrollToTopBtn:hover {
            background-color: #68B0AB;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }
    </style>
</head>

<body class="bg-[#FAF3DD] text-gray-800">
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-[#4A7C59] mr-2">
                </i>
                <span class="text-black">
                    Kafe
                </span>
                <span class="text-black">
                    lip
                </span>
            </div>
            <div class="nav flex gap-6 mx-auto">
                <a class="text-black font-medium active:text-[#4A7C59]" href="menu.php">
                    <i class="fas fa-home text-[#4A7C59] mr-1"></i>
                    <span>MENU</span>
                    </a>
                <a class="text-black font-medium" href="cart.php">
                    <i class="fas fa-shopping-cart text-[#4A7C59] mr-1"></i>
                    <span>CART <?= $bil ?></span>
                </a>
                <a class="text-black font-medium" href="sejarah-tempah.php">
                    <i class="fas fa-history text-[#4A7C59] mr-1"></i>
                    <span>Sejarah Tempahan</span>
                </a>
            </div>
            <div class="goMenu flex gap-6">
                <?php if ($_SESSION['tahap'] == "ADMIN"): ?>
                    <a class="text-black font-medium" href="admin/panel.php">
                        <i class="fa fa-list-alt mr-1 text-[#4A7C59]"></i>
                        <span> PANEL ADMIN</span>
                    </a>
                <?php endif; ?>
                <a class="text-black font-medium" href="logout.php">
                    <i class="fas fa-sign-out-alt mr-1 text-[#4A7C59]"></i>
                    </i>
                    <span>
                        LOG KELUAR
                    </span>
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto text-center py-8 px-4">
        <h2 class="text-2xl font-bold mb-6 relative inline-block text-center w-full text-black">
        <i class="fas fa-history text-[#4A7C59] mr-1"></i> Sejarah Tempahan
                
            </h2> 
        <div class="overflow-x-auto">
            <?php if (mysqli_num_rows($laksql) > 0): ?>
                <table class="table-auto mx-auto border-collapse border-2 border-[#4A7C59] border-separate shadow-lg w-full sm:w-auto rounded-lg">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 bg-[#4A7C59] text-white rounded-tl-lg"><i class="fas fa-calendar-alt"></i> Tarikh</th>
                            <th class="px-4 py-2 bg-[#4A7C59] text-white"><i class="fas fa-money-bill-wave"></i> Jumlah Bayaran (RM)</th>
                            <th class="px-4 py-2 bg-[#4A7C59] text-white rounded-tr-lg"><i class="fas fa-receipt"></i> Semak Resit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($m = mysqli_fetch_array($laksql)):
                            $tarikh = date_create( $m['tarikh']);
                        ?>
                            <tr class="bg-[#FAF3DD] hover:bg-[#A3B18A]">
                                <td class="border-0 shadow-lg  px-4 py-2 	">
                                    <i class="fas fa-calendar-day"></i> Tarikh: <?php echo date_format($tarikh, "d/m/Y") ?> <br>
                                    <i class="fas fa-clock"></i> Masa: <?php echo date_format($tarikh, "g:i:s A") ?> <br>
                                </td>
                                <td class="border-0 shadow-lg  px-4 py-2 text-center">RM <?= number_format($m['jum'], 2) ?> </td>
                                <td class="border-0 shadow-lg  px-4 py-2 text-center 	">
                                    <?php $masa = date_format($tarikh, "Y-m-d H:i:s"); ?>
                                    <button onclick="location.href='resit.php?tarikh=<?= $masa ?>';" class="SemakResit bg-[#4A7C59]  hover:bg-[#68B0AB] text-white px-4 py-2 rounded-md"><i class="fas fa-search"></i> Semak</button>
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
    <footer class="w-full bg-[#FAF3DD] text-black py-6 px-10">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
            <div class="mb-4 lg:mb-0">
                © 2023 KAFELIP. All rights reserved.
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
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up">
        </i>
    </button>

    <script>
        // Show or hide the scroll to top button
        window.onscroll = function() {
            var scrollToTopBtn = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // Scroll to top function
        function scrollToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>

</html>