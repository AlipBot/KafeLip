<?php
// Memulai sesi untuk menyimpan data pengguna seperti cart
session_start();
if (empty($_SESSION['tahap'])): ?>
    <script>
        window.location.href = 'index.php';
    </script>
<?php endif;


if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

// --- Koneksi Database ---
include("function/connection.php"); // Pastikan path file koneksi benar


// Query untuk mengambil semua data dari tabel 'makanan'
$sql = "SELECT * FROM makanan";
$caridata = mysqli_query($condb, $sql);

// Periksa apakah query berhasil dijalankan
if (!$caridata) {
    die("Query gagal: " . mysqli_error($condb)); // Menampilkan pesan error jika query gagal
}
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

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #A1CCA5;
            color: #333;
        }



        .container {
            padding: 5%;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 5%;
            justify-items: center;
        }

        .menu-item {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 5px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 7%;
            text-align: center;
            transition: transform 0.3s ease-in-out, opacity 0.3s ease-in-out;
            opacity: 0;
            transform: translateY(20px);
            width: 100%;
            max-width: 90%;
            height: 100%;
        }

        .menu-item img {
            box-shadow: 2px 9px 41px 3px rgba(0, 0, 0, 0.2), 0 7px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 8px;
            width: auto;
            height: auto;
            object-fit: cover;
            margin-bottom: 10%;
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
            background-color: #ffcc00;
            color: #333;
            padding: 10px 20px;
            border: none;
            border-radius: 7px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10%;
            transition: transform 0.2s;
        }

        .menu-item .add-to-cart:hover {
            transform: scale(1.1);
        }



        .menu-item.visible {
            opacity: 1;
            transform: translateY(0);
            animation: fadeInUp 0.3s ease-in-out;
        }

        @media (max-width: 768px) {
            .container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .container {
                grid-template-columns: 1fr;
            }
        }

        .slidebro {
            max-width: 1800px;
            background-color: #709775;
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
        .footerkaki {
            padding: 2%;
            display: grid;
            background-color: #111D13;
            grid-template-columns: repeat(3, 1fr);
            justify-items: center;
        }
    </style>
</head>

<body>
    <div class="w-full bg-green-100 fade-in">
        <div class="container mx-auto flex justify-between items-center py-6 px-4">
            <div class="logo text-2xl font-bold flex items-center mr-4">
                <i class="fas fa-coffee text-green-500 mr-2">
                </i>
                Kafe
                <span class="text-green-500">
                    lip
                </span>
            </div>
            <div class="nav flex gap-6">
                <a class="text-black font-medium active:text-green-500" href="index.php">
                    <i class="fas fa-home mr-1">
                    </i>
                    <span>
                        LAMAN UTAMA
                    </span>
                </a>
                <a class="text-black font-medium" href="#">
                    <i class="fas fa-info-circle mr-1">
                    </i>
                    <span>
                        INFO
                    </span>
                </a>
                <a class="text-black font-medium" href="login.php">
                    <i class="fas fa-sign-in-alt mr-1">
                    </i>
                    <span>
                        LOG MASUK
                    </span>
                </a>
                <a class="text-black font-medium" href="signup.php">
                    <i class="fas fa-user-plus mr-1">
                    </i>
                    <span>
                        DAFTAR MASUK
                    </span>
                </a>
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
                <div style="background-image: url('lib/image/rotitelur.jpg');" class="slide iklan">
                    <h2>REWARDS</h2>
                    <p>BONUS UP TO <span>45%!</span></p>
                </div>
                <div style="background-image: url('lib/image/rotitelur.jpg');" class="slide iklan">
                    <h2>AFFILIATE PROGRAM</h2>
                    <p>Earn while you play!</p>
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

    <div class="container">

        <?php if (mysqli_num_rows($caridata) > 0): ?>
            <!-- Loop untuk menampilkan setiap baris makanan dari database -->
            <?php while ($m = mysqli_fetch_assoc($caridata)): ?>

                <div class="menu-item">
                    <img src="lib/imagemenu/<?= htmlspecialchars($m['gambar']) ?>" />
                    <div>
                        <h2>
                            <?= htmlspecialchars($m['nama_makanan']) ?>
                        </h2>

                        <p class="price">
                            RM <?= htmlspecialchars($m['harga']) ?>
                        </p>
                    </div>
                    <button class="add-to-cart" onclick="location.href='function/add-cart.php?page=menu&id_menu=<?= htmlspecialchars($m['kod_makanan']) ?>';">
                        Add to Cart
                    </button>
                </div>

            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td align='center' colspan='3'>
                    <p style='color: red;'>TIADA MAKANAN TERSEDIA SEKARANG</p>
                </td>
            </tr>
        <?php endif; ?>
    </div>
    </div>

    <footer class="w-full bg-gray-800 text-white py-[0px] px-[0px] fade-in">
        <div class="footerkaki mx-auto flex flex-col lg:flex-row justify-between items-center">
            <div class="mb-4 lg:mb-0">
                © 2023 KAFELIP. All rights reserved.
            </div>
            <div class="flex gap-6">
                <a class="text-white" href="#">
                    <i class="fab fa-facebook-f">
                    </i>
                </a>
                <a class="text-white" href="#">
                    <i class="fab fa-twitter">
                    </i>
                </a>
                <a class="text-white" href="#">
                    <i class="fab fa-instagram">
                    </i>
                </a>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuItems = document.querySelectorAll('.menu-item');
            const observer = new IntersectionObserver(entries => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, {
                threshold: 0.1
            });

            menuItems.forEach(item => {
                observer.observe(item);
            });
        });
    </script>
    <script>
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


</body>

</html>