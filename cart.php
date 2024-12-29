<?php
include("function/autoKeluar.php");
include('function/connection.php');

$jumlah_harga = 0;

if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

# menyemak jika tatasusunan order kosong
if (!isset($_SESSION['orders']) or count($_SESSION['orders']) == 0) {
    die("<script>
    alert('Cart anda kosong');
    window.location.href='menu.php';
    </script>");
} else {

    # dapatkan bilangan setiap elemen 
    $bilangan = array_count_values($_SESSION['orders']);
    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($bilangan, function ($count) {
        return $count >= 1;
    });

?>
    <html>

    <head>
        <title>CART</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
        <style>
            .content {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            .container {
                max-width: 1200px;
                /* Menghadkan lebar maksimum jadual */
                width: 100%;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead th {
                text-align: center;
            }

            .overflow-x-auto {
                overflow-x: auto;
                /* Untuk responsif jika jadual terlalu besar */
            }

            .table-auto {
                width: 100%;
                /* Pastikan jadual memenuhi ruang */
                max-width: 900px;
                /* Hadkan lebar maksimum jadual */
                margin: 0 auto;
                /* Pusatkan jadual */
            }


            .container-wrapper {
                display: flex;
                flex-direction: column;
                min-height: 20vh;
            }

            .custom-font {
                font-family: 'Roboto', sans-serif;
            }

            #scrollToTopBtn:hover {
                background-color: #68B0AB;
                color: #fff;
                transition: background-color 0.3s ease, color 0.3s ease;
            }


            @media (max-width: 768px) {

                .nav a span,
                .goMenu a span {
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
        <div class="container-wrapper">
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
                        <a class="text-black font-medium active:text-[#4A7C59]" href="cart.php">
                            <i class="fas fa-shopping-cart text-[#4A7C59] mr-1"></i>
                            <span>CART <?= $bil ?></span>
                        </a>
                        <a class="text-black font-medium active:text-[#4A7C59]" href="sejarah-tempah.php">
                            <i class="fas fa-history text-[#4A7C59] mr-1"></i>
                            <span>Sejarah Tempahan</span>
                        </a>
                    </div>
                    <div class="goMenu flex gap-6">
                        <?php if ($_SESSION['tahap'] == "ADMIN"): ?>
                            <a class="text-black font-medium active:text-[#4A7C59]" href="admin/panel.php">
                                <i class="fa fa-list-alt mr-1 text-[#4A7C59]"></i>
                                <span> PANEL ADMIN</span>
                            </a>
                        <?php endif; ?>
                        <a class="text-black font-medium active:text-[#4A7C59]" href="logout.php">
                            <i class="fas fa-sign-out-alt mr-1 text-[#4A7C59]"></i>
                            </i>
                            <span>
                                LOG KELUAR
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="content">
            <div class="container mx-auto text-center py-8 px-4  rounded-lg">
                <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-black">
                    SENARAI TEMPAHAN
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
                    </span>
                </h2>
                <div class="overflow-x-auto">
                    <table class="border-2 border-[#4A7C59] table-auto  min-w-ful border-separate shadow-lg  rounded-lg bg-[#4A7C59]">
                        <thead>
                            <tr class="text-white">
                                <th width="30%" class="bg-[#4A7C59] px-4 py-2">Menu</th>
                                <th width="15%" class="bg-[#4A7C59] px-4 py-2">Kuantiti</th>
                                <th width="15%" class="bg-[#4A7C59] px-4 py-2">Harga seunit (RM)</th>
                                <th width="15%" class="bg-[#4A7C59] px-4 py-2">Harga (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($sama as $key => $bil) {
                                $sql = "select* from makanan where kod_makanan = '$key'";
                                $lak = mysqli_query($condb, $sql);
                                $m = mysqli_fetch_array($lak);
                            ?>
                                <tr class="bg-[#FAF3DD] hover:bg-white">
                                    <td class="shadow-lg px-4 py-2 font-semibold custom-font"><?= $m['nama_makanan'] ?></td>
                                    <td class="shadow-lg px-4 py-2 flex justify-center font-semibold items-center space-x-2">
                                        <button onclick="location.href='function/add-cart.php?page=cart&id_menu=<?= $m['kod_makanan'] ?>';" class="bg-[#48bd4e] text-white px-2.5 py-1 rounded ">+</button>
                                        <span><?= $bil ?></span>
                                        <button onclick="location.href='function/del-cart.php?id_menu=<?= $m['kod_makanan'] ?>';" class="bg-[#CA0000D9] text-white px-3 py-1 rounded ">-</button>
                                    </td>
                                    <td class="shadow-lg text-center  px-4 py-2 font-semibold custom-font"><?= $m['harga'] ?></td>
                                    <td class="shadow-lg text-center  px-4 py-2 font-semibold custom-font">
                                        <?php
                                        $harga = $bil * $m['harga'];
                                        $jumlah_harga = $jumlah_harga + $harga;
                                        echo number_format($harga, 2);
                                        $_SESSION['jumlah_harga'] = $jumlah_harga;
                                        ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr class="bg-[#FAF3DD] hover:bg-white">
                                <td class="shadow-lg px-4 py-2 text-right font-semibold custom-font" colspan="3">Jumlah Bayaran (RM)</td>
                                <td class="shadow-lg text-center  px-4 py-2 font-semibold custom-font"> <?php echo number_format($jumlah_harga, 2) ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    <button onclick="location.href='sah-tempah.php';" class="bg-[#4A7C59] text-white px-4 py-2 rounded hover:bg-[#68B0AB]">
                        Sahkan Tempahan
                    </button>
                </div>
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
            function adjustFooter() {
                const content = document.querySelector('.content');
                const footer = document.querySelector('footer');
                const windowHeight = window.innerHeight;
                const contentHeight = content.offsetHeight;
                const footerHeight = footer.offsetHeight;

                // Kira margin top footer supaya sentiasa berada di bawah
                const marginTop = windowHeight - (contentHeight + footerHeight);

                if (marginTop > 0) {
                    footer.style.marginTop = marginTop + 'px';
                } else {
                    footer.style.marginTop = '0px';
                }
            }

            // Panggil fungsi setiap kali halaman dimuat
            window.onload = adjustFooter;

            // Panggil fungsi semasa resize window
            window.onresize = adjustFooter;

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

<?php } ?>