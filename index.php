<?php
# Set masa session biar tahan lama dan bila tutup browser dat session masih ada
$lifetime = 60 * 60 * 24 * 30; # Setkan 30 hari atau 1 bulan
session_set_cookie_params($lifetime);
session_start();
?>
<html lang="ms">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title> KafeLip </title>
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
    <style>
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

        h1,
        h2,
        .fontkafelip {
            font-family: 'LobsterTwo', sans-serif;
        }

        p {
            font-family: 'Merriweather', sans-serif;
        }

        .explore-menu:hover,
        .get-started:hover,
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

        .goMenu a::after {
            content: '';
            display: block;
            width: 0;
            height: 2px;
            background: #4A7C59;
            transition: width 0.3s;
        }


        .goMenu a:hover::after {
            width: 100%;
        }
    </style>
</head>

<body class="bg-[#FAF3DD] ">
    <!-- Header -->
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-[#4A7C59] mr-2">
                </i>
                <span class="text-black fontkafelip ">Kafe</span>
                <span class="text-black fontkafelip ">Lip</span>
            </div>
            <?php if (!empty($_SESSION['tahap'])) { ?>
                <!-- Jika data tahap session pengguna dah ada -->
                <div class="goMenu flex gap-6">
                    <a class="text-black font-medium" href="login.php">
                        <i class="fas fa-sign-in-alt text-[#4A7C59] mr-1"></i>
                        <span>LOG MASUK </span>
                    </a>
                </div>
            <?php } else { ?>
                <!-- Jika data tahap session pengguna Tiada  -->
                <div class="goMenu flex gap-6">
                    <a class="text-black font-medium" href="login.php">
                        <i class="fas fa-sign-in-alt text-[#4A7C59] mr-1"></i>
                        <span> LOG MASUK </span>
                    </a>
                    <a class="text-black font-medium" href="signup.php">
                        <i class="fas fa-user-plus text-[#4A7C59] mr-1"> </i>
                        <span> DAFTAR MASUK </span>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Muka depan 1 -->
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center py-12 px-10">
            <div class="text-content max-w-lg">
                <h1 class="text-5xl lg:text-7xl font-bold text-black">
                    Selamat Datang
                </h1>
                <p class="text-xl text-gray-600 my-6">
                    Are you hungry? Let's go makan roti canai
                </p>
                <div class="buttons flex gap-6">

                    <?php if (!empty($_SESSION['tahap'])) { ?>
                        <a class="get-started bg-[#4A7C59] text-white py-3 px-6 rounded-lg font-bold" href="login.php">
                            LOG MASUK
                        </a>
                    <?php } else { ?>
                        <a class="get-started bg-[#4A7C59] text-white py-3 px-6 rounded-lg font-bold" href="signup.php">
                            DAFTAR MASUK
                        </a>
                        <a class="explore-menu border-2 border-[#4A7C59] text-[#4A7C59] py-3 px-6 rounded-lg font-bold" href="login.php">
                            LOG MASUK
                        </a>
                    <?php } ?>
                </div>
            </div>
            <div class="image-content relative mt-10 lg:mt-0">
                <img alt="A bowl of dessert with raspberries on top" class="rounded-full w-80 h-80 object-cover" height="400" src="lib\image\RotiCanai1.jpg" width="400" />
                <div class="rating absolute top-5 right-5 bg-white py-2 px-4 rounded-full shadow-lg flex items-center gap-2">
                    <span class="font-bold">
                        5.0 Ratings
                    </span>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                    <i class="fas fa-star text-orange-500">
                    </i>
                </div>
                <div class="comment comment-1 absolute bottom-[-20px] left-[-10] bg-white p-[8px] rounded-lg shadow-lg flex items-center gap-2">
                    <img class="rounded-full w-10 h-10 object-cover" height="40" src="lib/image/izz.jpg" width="40" />
                    <div class="text">
                        <div class="name font-bold">
                            Izz
                        </div>
                        <div class="time text-sm text-gray-600">
                            Today at 12.00 PM
                        </div>
                        <div class="message mt-1">
                            Roti sedap dan lembut.
                        </div>
                    </div>
                </div>
                <div class="comment comment-2 absolute bottom-12 -right-4 bg-white p-[8px] rounded-lg shadow-lg flex items-center gap-2">
                    <img class="rounded-full w-10 h-10 object-cover" height="40" src="lib/image/rafiq.jpg" width="40" />
                    <div class="text">
                        <div class="name font-bold">
                            Rafiq
                        </div>
                        <div class="time text-sm text-gray-600">
                            Today at 1.00 PM
                        </div>
                        <div class="message mt-1">
                            Roti canai paling strong.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Muka depan 2 -->
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto py-12 px-10">
            <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-black">
                Menu Menarik
                <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
                </span>
            </h2>
            <div class="flex flex-col gap-6">
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row-reverse items-center">
                    <img class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="lib/image/RotiCanai.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:mr-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Canai 🥞
                        </h3>
                        <p class="text-[#151A00]">
                            RM 1.20
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti canai biasa yang lembut dan rangup, sesuai dimakan dengan kuah dhal atau kari </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row items-center">
                    <img class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="lib/image/rotitelur.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Canai Telur 🍳
                        </h3>
                        <p class="text-[#151A00]">
                            RM 2.50
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti canai yang diisi dengan telur, memberikan rasa yang lebih enak dan berkhasiat. </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row-reverse items-center">
                    <img class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="lib/image/bom.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:mr-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Bom 💣
                        </h3>
                        <p class="text-[#151A00]">
                            RM 2.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti bersaiz kecil tetapi tebal dengan tekstur lembut dan sedikit manis.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row items-center">
                    <img class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="lib/image/roti-canai-banjir.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Canai Banjir 🌊
                        </h3>
                        <p class="text-[#151A00]">
                            RM 1.50
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti canai yang disiram dengan kuah dhal atau kari, memberikan rasa lebih pekat dan menyelerakan. </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row-reverse items-center">
                    <img class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="lib/image/rotiplanta.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:mr-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Planta 🧈
                        </h3>
                        <p class="text-[#151A00]">
                            RM 2.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti canai yang disapu dengan planta untuk rasa lebih lemak dan wangi.
                        </p>
                    </div>
                </div>
                <div class="bg-[#A1CCA5] p-6 rounded-lg shadow-lg flex flex-col lg:flex-row items-center">
                    <img class="w-full lg:w-1/2 h-48 object-cover rounded-lg" height="300" src="lib/image/rotitisu.jpg" width="400" />
                    <div class="mt-4 lg:mt-0 lg:ml-6">
                        <h3 class="text-2xl font-bold text-[#151A00]">
                            Roti Tissue 🍯 </h3>
                        <p class="text-[#151A00]">
                            RM 2.00
                        </p>
                        <p class="text-[#151A00] mt-2">
                            Roti nipis dan rangup, berbentuk kon tinggi dengan lapisan mentega dan susu pekat. </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Maklumat Kedai -->
    <div class="w-full bg-[#FAF3DD]">
        <div class="container mx-auto py-12 px-10">
            <div class="bg-[#A1CCA5] p-8 rounded-lg">
                <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-[#151A00]">
                    Maklumat Kedai KafeLip
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
                    </span>
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Maklumat Asas -->
                    <div class="text-[#151A00]">
                        <h3 class="text-2xl font-bold mb-4">Waktu Operasi</h3>
                        <ul class="space-y-2">
                            <li><i class="far fa-clock mr-2"></i>Isnin - Jumaat: 7:00 AM - 11:00 PM</li>
                            <li><i class="far fa-clock mr-2"></i>Sabtu & Ahad: 8:00 AM - 12:00 AM</li>
                        </ul>

                        <h3 class="text-2xl font-bold mt-6 mb-4">Hubungi Kami</h3>
                        <ul class="space-y-2">
                            <li><i class="fas fa-phone-alt mr-2"></i>03-1234 5678</li>
                            <li><i class="fas fa-map-marker-alt mr-2"></i>Jln Dahlia 4/2, Bandar Baru Salak Tinggi, 43900 Sepang, Selangor</li>
                            <li class="flex gap-4 mt-4">
                                <a href="https://wa.me/60123456789" target="_blank" class="bg-[#25D366] text-white px-4 py-2 rounded-lg inline-flex items-center">
                                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp Kami
                                </a>
                                <a href="https://maps.google.com/?q=2.827419065339865,101.71984258012314" target="_blank" class="bg-[#4285F4] text-white px-4 py-2 rounded-lg inline-flex items-center">
                                    <i class="fas fa-map-marked-alt mr-2"></i>Google Maps
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Peta Google -->
                    <div class="w-full h-[300px] rounded-lg overflow-hidden">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d996.2403459096192!2d101.72002796350226!3d2.8273941730754073!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cdc76fde59ba83%3A0xca347d0f20a49286!2sSMK%20Bandar%20Baru%20Salak%20Tinggi!5e0!3m2!1sen!2smy!4v1739517356157!5m2!1sen!2smy"
                            width="100%"
                            height="100%"
                            style="border:0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
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
                <a class="text-[#4A7C59]" href="https://www.instagram.com/alipje29/#">
                    <i class="fab fa-instagram">
                    </i>
                </a>
            </div>
        </div>
    </footer>

    <!-- Butang scroll ke atas -->
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up">
        </i>
    </button>

    <script>
        // Script untung muncul butang scroll keatas
        window.onscroll = function() {
            var scrollToTopBtn = document.getElementById("scrollToTopBtn");
            if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                scrollToTopBtn.style.display = "block";
            } else {
                scrollToTopBtn.style.display = "none";
            }
        };

        // Scroll ke atasss
        function scrollToTop() {
            document.body.scrollTop = 0;
            document.documentElement.scrollTop = 0;
        }
    </script>
</body>

</html>