<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("function/autoKeluar.php");
include('function/connection.php');
//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\
# memaparkan bilanggan senarai makanan
if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}
# setkan tarikh semasa kepada harini jika tiada get tarikh_semasa
if (isset($_GET['tarikh_semasa'])) {
    $tarikhsemasa = $_GET['tarikh_semasa'];
} else {
    $tarikhsemasa = date("Y-m-d");
}

# Query Dapatkan Senarai tarikh
$sqltarikh = "SELECT DATE(tarikh) AS tarikh, count(*) as bilangan 
FROM tempahan 
WHERE email = '" . $_SESSION['email'] . "'
GROUP BY DATE(tarikh)
ORDER BY DATE(tarikh) DESC";
$laktarikh = mysqli_query($condb, $sqltarikh);

# Query dapatkan semua senarai tempahan
$sql = "SELECT t.email, 
               t.tarikh,
               TIMESTAMPDIFF(SECOND, t.tarikh, NOW()) as seconds_passed,
               SUM(t.jumlah_harga) AS jumlah_harga_semua
        FROM tempahan t
        JOIN makanan m ON t.kod_makanan = m.kod_makanan
        WHERE t.tarikh LIKE '%$tarikhsemasa%' 
        AND t.email = '" . $_SESSION['email'] . "'
        GROUP BY t.email, t.tarikh
        ORDER BY t.tarikh DESC";
$laksql = mysqli_query($condb, $sql);

?>

<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Tempahan Makanan Roti</title>
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

        .content {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
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

    <!-- Content -->
    <div class="content">
        <div class="container mx-auto text-center py-8 px-4">
            <h2 class="text-2xl font-bold mb-6 relative inline-block text-center w-full text-black">
                <i class="fas fa-history text-[#4A7C59] mr-1"></i> Sejarah Tempahan
            </h2>
            <form action="sejarah-tempah.php" method="GET"
                class="py-5 flex items-center space-x-2 w-full justify-center">
                <select name='tarikh_semasa' class="border rounded p-2 w-1/4">
                    <option value='<?= $tarikhsemasa ?>'>
                        <?= date_format(date_create($tarikhsemasa), "d/m/Y"); ?>
                    </option>
                    <option disabled>Pilih Tarikh Lain Di bawah</option>
                    <?php while ($mm = mysqli_fetch_array($laktarikh)): ?>
                        <option value='<?= $mm['tarikh'] ?>'>
                            <?= date_format(date_create($mm['tarikh']), "d/m/Y") ?>
                        </option>
                    <?php endwhile; ?>
                </select>
                <button type="submit" class="bg-[#4A7C59] text-white p-2 rounded flex items-center hover:bg-[#68B0AB]">
                    <i class="fas fa-search mr-1"></i> Cari
                </button>
                <a href="sejarah-tempah.php"
                    class="bg-red-500 text-white p-2 rounded flex items-center hover:bg-red-600">
                    <i class="fas fa-undo mr-1"></i> Reset
                </a>
            </form>
            <div class="overflow-x-auto">
                <?php if (mysqli_num_rows($laksql) > 0): ?>
                    <table
                        class="table-auto mx-auto border-collapse border-2 border-[#4A7C59] border-separate shadow-lg w-full sm:w-auto rounded-lg">
                        <thead>
                            <tr>
                                <th class="px-4 py-2 bg-[#4A7C59] text-white rounded-tl-lg"><i
                                        class="fas fa-calendar-alt"></i> Tarikh</th>
                                <th class="px-4 py-2 bg-[#4A7C59] text-white"><i class="fas fa-money-bill-wave"></i> Jumlah
                                    Bayaran (RM)</th>
                                <th class="px-4 py-2 bg-[#4A7C59] text-white rounded-tr-lg"><i class="fas fa-receipt"></i>
                                    Semak Resit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($m = mysqli_fetch_array($laksql)):
                                $tarikh = date_create($m['tarikh']);
                            ?>
                                <tr class="bg-[#FAF3DD] hover:bg-[#A3B18A]">
                                    <td class="border-0 shadow-lg  px-4 py-2 	">
                                        <i class="fas fa-calendar-day"></i> Tarikh: <?php echo date_format($tarikh, "d/m/Y") ?>
                                        <br>
                                        <i class="fas fa-clock"></i> Masa: <?php echo date_format($tarikh, "g:i:s A") ?> <br>
                                        <?php
                                        $hari = date_format($tarikh, "l");
                                        $hariMelayu = "";
                                        switch ($hari) {
                                            case 'Sunday':
                                                $hariMelayu = "Ahad";
                                                break;
                                            case 'Monday':
                                                $hariMelayu = "Isnin";
                                                break;
                                            case 'Tuesday':
                                                $hariMelayu = "Selasa";
                                                break;
                                            case 'Wednesday':
                                                $hariMelayu = "Rabu";
                                                break;
                                            case 'Thursday':
                                                $hariMelayu = "Khamis";
                                                break;
                                            case 'Friday':
                                                $hariMelayu = "Jumaat";
                                                break;
                                            case 'Saturday':
                                                $hariMelayu = "Sabtu";
                                                break;
                                        }
                                        ?>
                                        <i class="fas fa-calendar-week"></i> Hari: <?php echo $hariMelayu ?> <br>
                                    </td>
                                    <td class="border-0 shadow-lg  px-4 py-2 text-center">RM
                                        <?= number_format($m['jumlah_harga_semua'], 2) ?> </td>
                                    <td class="border-0 shadow-lg  px-4 py-2 text-center 	">
                                        <?php $masa = date_format($tarikh, "Y-m-d H:i:s"); ?>
                                        <div class="flex flex-col gap-2">
                                            <button onclick="location.href='resit.php?tarikh=<?= $masa ?>';"
                                                class="SemakResit bg-[#4A7C59] hover:bg-[#68B0AB] text-white px-4 py-2 rounded-md w-full">
                                                <i class="fas fa-search"></i> Semak
                                            </button>
                                            <?php if ($m['seconds_passed'] <= 60): ?>
                                                <div>
                                                    <button data-id="<?= $masa ?>"
                                                        class="batal-btn bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-md w-full">
                                                        <i class="fas fa-trash"></i> Batal
                                                    </button>
                                                    <div class="text-sm text-red-500 mt-1">
                                                        Masa tinggal: <span class="countdown"
                                                            data-seconds-passed="<?= $m['seconds_passed'] ?>">60</span> saat
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <p class="text-2xl text-white bg-[#A3B18A] p-4 rounded-lg shadow-lg inline-block">
                            <i class="fas fa-exclamation-circle"></i> Tiada Tempahan Pada Tarikh
                            <?= date_format(date_create($tarikhsemasa), "d/m/Y") ?>
                        </p>
                    </div> <?php endif; ?>
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

    <!-- Butang scroll ke atas -->
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <script>
        // Tunjukkan dan sorokkan butang scroll up
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
    <script>
        // script auto ajdust footer biar ke bawah sekali
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

        // Fungsi untuk mengira masa yang tinggal
        document.addEventListener('DOMContentLoaded', function() {
            const countdowns = document.querySelectorAll('.countdown');

            countdowns.forEach(countdown => {
                const secondsPassed = parseInt(countdown.dataset.secondsPassed);
                let timeLeft = 60 - secondsPassed;

                const timer = setInterval(() => {
                    if (timeLeft <= 0) {
                        clearInterval(timer);
                        // Sembunyikan button batal dan kiraan masa
                        const parentDiv = countdown.closest('div').parentElement;
                        parentDiv.style.display = 'none';
                    } else {
                        countdown.textContent = timeLeft;
                        timeLeft--;
                    }
                }, 1000);
            });
        });
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


        document.querySelectorAll('.batal-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                const tarikh = this.dataset.id;
                e.preventDefault();
                notifwarning.play();

                Swal.fire({
                    title: 'Anda pasti?',
                    text: "Batalkan pesanan anda",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = `function/batal-tempah.php?tarikh=${tarikh}`;
                    }
                });
            });
        });

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