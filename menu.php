<?php
include("function/autoKeluar.php");

if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

// --- Koneksi Database ---
include("function/connection.php"); // Pastikan path file koneksi benar


?>




<html>

<head>
    <title>
        KAFELIP
    </title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #FAF3DD;
            color: #333;
        }



        .menu-container {
            padding: 9%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1%;
            justify-items: center;
        }


        .menu-item {
            opacity: 1 !important;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1rem;
            text-align: center;
            width: 100%;
            max-width: 100%;
        }

        .menu-item .w-32 {
            width: 8rem;
            /* 128px */
        }

        .menu-item .h-32 {
            height: 8rem;
            /* 128px */
        }

        .menu-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .menu-item h2 {
            margin: 0;
            font-size: 20px;
        }

        .menu-item p {
            margin: 2% 2% 2%;
            color: #666;
        }

        .menu-item .price {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-top: 10px;
        }

        .menu-item .add-to-cart {
            background-color: #4A7C59;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10%;
        }

        .menu-item .add-to-cart:hover {
            background-color: #68B0AB;
        }

        @media (max-width: 768px) {
            .menu-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .menu-container {
                grid-template-columns: 1fr;
            }
        }

        .slidebro {
            max-width: 1800px;
            background-color: #FAF3DD;
            margin: 0px auto;
            padding: 5rem;

        }

        .top-slider {
            position: relative;
            height: 400px;
            overflow: hidden;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .slides {
            display: flex;
            height: 100%;
            transition: transform 0.5s cubic-bezier(0.645, 0.045, 0.355, 1);
        }

        .slide {
            flex: 0 0 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            background-size: 100% 100%;
        }


        .slide h2 {
            font-family: Acme;
            font-size: 3rem;
            color: #fff;
            margin-bottom: 1rem;
            text-shadow: 2px 7px 53px rgba(6, 6, 6, 0.81);
        }

        .slide p {
            font-family: Acme;
            font-size: 1.5rem;
            color: #fff;
            text-shadow: 2px 7px 53px rgba(6, 6, 6, 0.81);
        }

        .slider-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .slider-arrow:hover {
            background-color: rgba(255, 255, 255, 0.4);
        }

        .slider-arrow.left {
            left: 20px;
        }

        .slider-arrow.right {
            right: 20px;
        }

        .slider-nav {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 10px;
        }

        .slider-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background-color: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .slider-dot.active {
            background-color: white;
            transform: scale(1.2);
        }

        @media (max-width: 768px) {
            .top-slider {
                height: 300px;
            }

            .slide h2 {
                font-size: 2rem;
            }

            .slide p {
                font-size: 1rem;
            }

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

        .add-to-cart:hover,
        #scrollToTopBtn:hover {
            background-color: #68B0AB;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 10px 0;
        }

        .quantity-btn {
            background-color: #4A7C59;
            color: white;
            border: none;
            width: 30px;
            height: 30px;
            border-radius: 50%;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background-color: #68B0AB;
        }

        .quantity-value {
            font-size: 18px;
            font-weight: bold;
            min-width: 30px;
            text-align: center;
        }

        /* Dropdown animation */
        #dropdownMenu {
            transition: all 0.2s ease-in-out;
            transform-origin: top right;
        }

        #dropdownMenu:not(.hidden) {
            animation: slideDown 0.2s ease-in-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-10px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Hover effect untuk menu items */
        #dropdownMenu a:hover {
            background-color: #f8f9fa;
        }

        #dropdownMenu a:active {
            background-color: #e9ecef;
        }
    </style>

</head>

<body class="bg-[#FAF3DD] font-poppins">
    <!-- Header -->
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


    <div class="slidebro">
        <div class="top-slider">
            <div class="slides">

                <div style="background-image: url('lib/image/welcome.jpg');" class="slide iklan">
                    <h2>SELAMAT KEMBALI, <br> <?= $_SESSION['nama'] ?> </h2>
                    <p>Sudah lapar ke?</p>
                </div>

                <div style="background-image: url('lib/image/banner2.jpg');" class="slide iklan">

                    <h2 id="tarikhSlide"></h2>
                    <p id="hariSlide"></p>
                    <p id="masaSlide"></p>


                </div>

                <div style="background-image: url('lib/image/rotitelur.jpg');" class="slide iklan">
                    <h2>BUKA KEDAI</h2>
                    <p>Pukul 8:00 AM - 10:00 PM</p>
                </div>
            </div>

            <button class="slider-arrow left" aria-label="Previous slide">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="slider-arrow right" aria-label="Next slide">
                <i class="fas fa-chevron-right"></i>
            </button>
            <div class="slider-nav"></div>
        </div>
    </div>
    <div class="mx-auto px-10">
        <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-[#111811]">
            Menu
            <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
            </span>
        </h2>
    </div>
    <div class="bg-[#A1CCA5] overflow-auto	my-5 mx-[90px]  rounded-2xl p-6 shadow-lg items-center">
        <div class="menu-container">
            <?php
            // Query untuk mendapatkan semua data dari tabel 'makanan'
            $sql = "SELECT * FROM makanan";
            $result = mysqli_query($condb, $sql);

            // Periksa apakah query berhasil dijalankan
            if (!$result) {
                die("Query gagal: " . mysqli_error($condb));
            }

            // Tampilkan menu-item
            if (mysqli_num_rows($result) > 0) {
                while ($m = mysqli_fetch_assoc($result)): ?>
                    <div class="menu-item">
                        <div class="w-32 h-32 overflow-hidden rounded-lg mb-4">
                            <img src='menu-images/<?php echo htmlspecialchars($m['gambar']); ?>'
                                alt='Gambar menu <?php echo htmlspecialchars($m['nama_makanan']); ?>'
                                class="w-full h-full object-cover">
                        </div>
                        <div class="flex flex-col items-center">
                            <h2 class="text-lg font-semibold mb-2"><?= htmlspecialchars($m['nama_makanan']) ?></h2>
                            <p class="price mb-3">RM <?= $m['harga'] ?></p>
                            <div class="quantity-controls">
                                <button class="quantity-btn minus"
                                    onclick="updateQuantity('<?= $m['kod_makanan'] ?>', 'decrease')">-</button>
                                <span id="quantity-<?= $m['kod_makanan'] ?>" class="quantity-value">1</span>
                                <button class="quantity-btn plus"
                                    onclick="updateQuantity('<?= $m['kod_makanan'] ?>', 'increase')">+</button>
                            </div>
                            <button class="add-to-cart"
                                onclick="addToCartWithQuantity('<?= htmlspecialchars($m['kod_makanan']) ?>')">
                                Tambah ke Troli
                            </button>
                        </div>
                    </div>
                <?php endwhile;
            } else {
                echo "<p style='color: red;'>TIADA MAKANAN TERSEDIA SEKARANG</p>";
            } ?>
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
        window.onscroll = function () {
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

        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelector('.slides');
            const sliderNav = document.querySelector('.slider-nav');
            const leftArrow = document.querySelector('.slider-arrow.left');
            const rightArrow = document.querySelector('.slider-arrow.right');
            const totalSlides = document.querySelectorAll('.slide').length;
            let currentSlide = 0;

            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('span');
                dot.classList.add('slider-dot');
                if (i === 0) dot.classList.add('active');
                sliderNav.appendChild(dot);
            }

            const dots = document.querySelectorAll('.slider-dot');

            function showSlide(index) {
                slides.style.transform = `translateX(-${index * 100}%)`;
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === index);
                });
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            }

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });

            leftArrow.addEventListener('click', prevSlide);
            rightArrow.addEventListener('click', nextSlide);

            let startX, moveX;
            let isDragging = false;

            slides.addEventListener('touchstart', (e) => {
                startX = e.touches[0].pageX;
                isDragging = true;
            });

            slides.addEventListener('touchmove', (e) => {
                if (!isDragging) return;
                moveX = e.touches[0].pageX;
                const diff = moveX - startX;
                slides.style.transform = `translateX(calc(-${currentSlide * 100}% + ${diff}px))`;
            });

            slides.addEventListener('touchend', (e) => {
                isDragging = false;
                const diff = moveX - startX;
                if (Math.abs(diff) > 100) {
                    if (diff > 0) prevSlide();
                    else nextSlide();
                } else {
                    showSlide(currentSlide);
                }
            });

            slides.addEventListener('mousedown', (e) => {
                startX = e.pageX;
                isDragging = true;
            });

            slides.addEventListener('mousemove', (e) => {
                if (!isDragging) return;
                moveX = e.pageX;
                const diff = moveX - startX;
                slides.style.transform = `translateX(calc(-${currentSlide * 100}% + ${diff}px))`;
            });

            slides.addEventListener('mouseup', () => {
                isDragging = false;
                const diff = moveX - startX;
                if (Math.abs(diff) > 100) {
                    if (diff > 0) prevSlide();
                    else nextSlide();
                } else {
                    showSlide(currentSlide);
                }
            });

            slides.addEventListener('mouseleave', () => {
                if (isDragging) {
                    isDragging = false;
                    showSlide(currentSlide);
                }
            });

            let autoSlideInterval = setInterval(nextSlide, 6000);

            slides.addEventListener('mouseenter', () => {
                clearInterval(autoSlideInterval);
            });

            slides.addEventListener('mouseleave', () => {
                autoSlideInterval = setInterval(nextSlide, 6000);
            });

            const options = document.querySelectorAll('.option');
            options.forEach(option => {
                option.addEventListener('mouseenter', () => {
                    option.style.transform = 'translateY(-10px) scale(1.05)';
                });
                option.addEventListener('mouseleave', () => {
                    option.style.transform = 'translateY(0) scale(1)';
                });
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <script>
        const notifsuccess = new Audio('lib/audio/notif.mp3'); // Tukar path ke fail audio anda
        const notiferror = new Audio('lib/audio/error.mp3'); // Tukar path ke fail audio anda
        const notifinfo = new Audio('lib/audio/info.mp3'); // Tukar path ke fail audio anda
        const notifwarning = new Audio('lib/audio/warning.mp3'); // Tukar path ke fail audio anda


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

        document.addEventListener('DOMContentLoaded', function () {
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
        function updateQuantity(menuId, action) {
            const quantityElement = document.getElementById(`quantity-${menuId}`);
            let quantity = parseInt(quantityElement.textContent);

            if (action === 'increase') {
                quantity++;
            } else if (action === 'decrease' && quantity > 1) {
                quantity--;
            }

            quantityElement.textContent = quantity;
        }

        function addToCartWithQuantity(menuId) {
            const quantity = parseInt(document.getElementById(`quantity-${menuId}`).textContent);

            // Hantar permintaan ke add-cart.php menggunakan format URL yang betul
            window.location.href = `function/add-cart.php?id_menu=${menuId}&quantity=${quantity}&page=menu`;
        }
    </script>
    <script>
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
    <script>
        function kemaskiniMasaTarikh() {
            const masa = new Date();
            const hari = String(masa.getDate()).padStart(2, '0');
            const bulan = String(masa.getMonth() + 1).padStart(2, '0');
            const tahun = masa.getFullYear();

            // Array untuk nama-nama hari dalam Bahasa Melayu
            const namaHari = ['Ahad', 'Isnin', 'Selasa', 'Rabu', 'Khamis', 'Jumaat', 'Sabtu'];
            const hariSemasa = namaHari[masa.getDay()];

            document.getElementById('tarikhSlide').textContent = `${hari}/${bulan}/${tahun}`;
            document.getElementById('masaSlide').textContent = masa.toLocaleTimeString('ms-MY', {
                hour: 'numeric',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            });
            document.getElementById('hariSlide').textContent = hariSemasa;
        }

        setInterval(kemaskiniMasaTarikh, 1000);
        kemaskiniMasaTarikh();
    </script>
</body>

</html>