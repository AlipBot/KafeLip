<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include('../function/autoKeluarAdmin.php'); # fail function auto logout jika pengguna belum login dan bukan admin
include('../function/connection.php');  # Sambung Ke database
//――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\
$rekodSehalaman = 10; # Tetapkan bilangan rekod per halaman
# setkan tarikh harini jika tiada data get parameter 
if (isset($_GET['tarikh_semasa'])) {
    $tarikhsemasa = $_GET['tarikh_semasa'];
} else {
    $tarikhsemasa = date("Y-m-d");
}

# Dapatkan halaman semasa
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;

# Kira offset untuk query (formula kira untuk data pertama di halaman data ke berapa perlu bermula)
$offset = ($halaman - 1) * $rekodSehalaman;

# Query Dapatkan Senarai tarikh yang ada pelanggan buat tempahan
$sqltarikh = "SELECT DATE(tarikh) AS tarikh, count(*) as bilangan
              FROM tempahan
              GROUP BY DATE(tarikh)
              ORDER BY DATE(tarikh) DESC";
$laktarikh = mysqli_query($condb, $sqltarikh);

# Query dapatkan semua senarai tempahan
$sql = "SELECT t.email, 
               t.tarikh,
               SUM(t.jumlah_harga) AS jumlah_harga_semua
        FROM tempahan t
        JOIN makanan m ON t.kod_makanan = m.kod_makanan
        WHERE t.tarikh LIKE '%$tarikhsemasa%'
        GROUP BY t.email, t.tarikh
        ORDER BY t.tarikh DESC";
$laksqltotal = mysqli_query($condb, $sql);
$jumlahRekod = mysqli_num_rows($laksqltotal);

# Kira jumlah halaman 
$jumlahHalaman = ceil($jumlahRekod / $rekodSehalaman);

# Tambah LIMIT dan OFFSET pada query utama 
# limit untuk berapa data je keluar dalam satu halaman dan  offset untuk data ke berapa yang perlu dimula di halaman tersebut
$sql .= " LIMIT $rekodSehalaman OFFSET $offset";
$laksql = mysqli_query($condb, $sql);

?>

<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah Laporan KafeLip</title>
    <link rel="apple-touch-icon" sizes="180x180" href="../lib/icon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="../lib/icon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="../lib/icon/favicon-16x16.png">
    <link rel="manifest" href="../lib/icon/site.webmanifest">
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
    <!-- Tambah CSS dan JS Flatpickr -->
    <link rel="stylesheet" href="../lib/css/flatpickr.min.css">
    <script src="../lib/js/flatpickr.js"></script>
    <style>
        .drawer-open {
            transform: translateX(0);
        }

        .drawer-closed {
            transform: translateX(-100%);
        }

        .content-expanded {
            margin-left: 0;
        }

        .content-collapsed {
            margin-left: 16rem;
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

        #scrollToTopBtn:hover {
            background-color: #68B0AB;
            color: #fff;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Gaya untuk Flatpickr */
        .flatpickr-calendar {
            background: #FAF3DD;
            border: 2px solid #428D41;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .flatpickr-day {
            color: #333;
            border-radius: 5px;
        }

        .flatpickr-day.selected {
            background: #428D41 !important;
            border-color: #428D41 !important;
            color: white !important;
        }

        .flatpickr-day:hover {
            background: #A4D153 !important;
            border-color: #A4D153 !important;
            color: black !important;
        }

        .flatpickr-day.disabled {
            color: #ccc !important;
            background: #f5f5f5 !important;
            cursor: not-allowed;
        }

        .flatpickr-months .flatpickr-month {
            background: #428D41;
            color: white;
        }

        .flatpickr-months .flatpickr-prev-month,
        .flatpickr-months .flatpickr-next-month {
            color: white !important;
            fill: white !important;
        }

        .flatpickr-months .flatpickr-prev-month:hover,
        .flatpickr-months .flatpickr-next-month:hover {
            color: #A4D153 !important;
            fill: #A4D153 !important;
        }

        .flatpickr-weekdays {
            background: #428D41;
        }

        .flatpickr-weekday {
            color: white !important;
            font-weight: bold;
            background: #428D41;
        }

        .flatpickr-current-month {
            color: white;
            font-weight: bold;
        }

        .flatpickr-current-month .flatpickr-monthDropdown-months .flatpickr-monthDropdown-month {
            background-color: #428d41;
            outline: none;
            padding: 0
        }

        .flatpickr-day.today {
            border: 2px solid #428D41 !important;
            color: rgb(0, 0, 0) ;
        }

        .flatpickr-day.today:hover {
            background: #A4D153 !important;
            color: black !important;
        }
    </style>

</head>

<body class="font-roboto bg-gray-100 ">
    <div class="flex min-h-screen flex-col">
        <!-- Header -->
        <header class="bg-[#428D41] text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-[#3a5a40] text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Sejarah Laporan Tempahan KafeLip</div>
            <div class="w-12"></div>
        </header>

        <!-- Content -->
        <div class="flex flex-1 pt-16">
            <!-- Sidebar -->
            <div id="drawer"
                class="w-64 bg-[#428D41] text-white flex flex-col fixed h-full transition-transform duration-300 drawer-closed z-10">
                <nav class="flex-1 p-4 overflow-y-auto">
                    <ul>
                        <li class="mb-4">
                            <div class="p-4 text-center text-2xl font-bold border-b border-[#68B0AB]">
                                Admin
                            </div>
                            <a href="panel.php" class="flex items-center p-2 hover:bg-[#68B0AB] rounded">
                                <i class="fas fa-tachometer-alt mr-2"></i> Panel Admin
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="list-user.php" class="flex items-center p-2 hover:bg-[#68B0AB] rounded">
                                <i class="fas fa-users mr-2"></i> Senarai Pengguna
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="list-menu.php" class="flex items-center p-2 hover:bg-[#68B0AB] rounded">
                                <i class="fas fa-utensils mr-2"></i> Senarai Makanan
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="laporan.php" class="flex items-center p-2 hover:bg-[#68B0AB] rounded">
                                <i class="fas fa-file-alt mr-2"></i> Sejarah Laporan
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="statistik.php" class="flex items-center p-2 hover:bg-[#68B0AB] rounded">
                                <i class="fas fa-analytics mr-2"></i> Statistik
                            </a>
                        </li>
                        <div class="p-4 text-center text-2xl font-bold border-b border-[#68B0AB]">
                            Pelanggan
                        </div>
                        <li class="mb-4">
                            <a href="../menu.php" class="flex items-center p-2 hover:bg-[#68B0AB] rounded">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali Ke Menu
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div id="mainContent" class="bg-[#FAF3DD] flex-1 p-6 transition-all duration-300 content-expanded">
                <div class="senarai-menu bg-white p-6 rounded-lg shadow relative">
                    <div class="text-[30px] font-bold mb-4 flex justify-between items-center">
                        <span>Sejarah Laporan Tempahan</span>
                    </div>
                    <div class="text-center text-gray-600 mb-4">
                        <span class="font-bold text-lg">Tarikh: </span>
                        <span id="currentDate" class="font-bold text-lg"></span>
                        <br>
                        <span class="font-bold text-lg">Masa: </span>
                        <span id="currentTime" class="font-bold text-lg"></span>
                        <br>
                        <span class="font-bold text-lg">Hari: </span>
                        <span id="currentDay" class="font-bold text-lg"></span>
                        <form action="laporan.php" method="GET" class="py-5 flex items-center space-x-2 w-full">
                            <input type="text" id="tarikh_semasa" name="tarikh_semasa" value="<?= $tarikhsemasa ?>"
                                class="border-2 rounded p-2" placeholder="Pilih Tarikh">
                            <button type="submit"
                                class="bg-[#428D41] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>
                            <button type="button" onclick="window.location.href='laporan.php'"
                                class="bg-red-800 hover:bg-red-600 text-white p-2 rounded flex items-center">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </button>
                        </form>
                        <div class="flex space-x-2">
                            <span class="font-bold text-lg p-2 rounded flex items-center whitespace-nowrap">Laporan pada
                                Tarikh : <?= date_format(date_create($tarikhsemasa), "d/m/Y"); ?> </span>
                            <span class="font-bold text-lg p-2 rounded flex items-center whitespace-nowrap">
                                Hari : <?php
                                $tarikh = date_create($tarikhsemasa);
                                $hari = date_format($tarikh, "l");
                                $hari_melayu = [
                                    'Sunday' => 'Ahad',
                                    'Monday' => 'Isnin',
                                    'Tuesday' => 'Selasa',
                                    'Wednesday' => 'Rabu',
                                    'Thursday' => 'Khamis',
                                    'Friday' => 'Jumaat',
                                    'Saturday' => 'Sabtu'
                                ];
                                echo $hari_melayu[$hari]; // Paparkan nama hari dalam Bahasa Melayu
                                ?>
                            </span>
                        </div>
                        <!-- Jadual laporan tempahan -->
                        <div class="table-container">
                            <table class="w-full table-auto rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-[#A4D153] font-bold text-black">
                                        <th width='30%' class="px-[47px] py-2">Pelanggan</th>
                                        <th width='35%' class="px-[47px] py-2">Pesanan</th>
                                        <th width='20%' class="px-[47px] py-2">Jumlah Harga (RM)</th>
                                        <th width='20%' class="px-[47px] py-2">Masa</th>
                                        <th width='10%' class="px-[47px] py-2">Resit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (mysqli_num_rows($laksql) > 0) { ?>
                                        <?php while ($m = mysqli_fetch_array($laksql)) { ?>

                                            <tr class='bg-white border-b hover:bg-green-50'>
                                                <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['email']); ?>
                                                </td>
                                                <td class='px-4 py-2 '><?php
                                                $sqlpaparmenu = "SELECT m.nama_makanan, t.kuantiti, m.harga
                                              FROM tempahan t
                                              JOIN makanan m ON t.kod_makanan = m.kod_makanan
                                              WHERE t.email = '" . $m['email'] . "'
                                              AND t.tarikh = '" . $m['tarikh'] . "'";
                                                $lakpaparmenu = mysqli_query($condb, $sqlpaparmenu);

                                                while ($mm = mysqli_fetch_array($lakpaparmenu)) {
                                                    echo $mm['nama_makanan'] . " ( RM" . number_format($mm['harga'], 2) . " ) X" . $mm['kuantiti'] . "<br>";
                                                }
                                                ?></td>
                                                <td class='px-4 py-2 text-center'>RM
                                                    <?php echo htmlspecialchars($m['jumlah_harga_semua']); ?>
                                                </td>
                                                <td class='px-4 py-2 text-center'>
                                                    <?php
                                                    $tarikh = date_create($m['tarikh']);
                                                    echo date_format($tarikh, "g:i:s A");
                                                    ?>
                                                </td>
                                                <td class='px-4 py-2 text-center'>
                                                    <div class="flex flex-col items-center space-y-4">
                                                        <button onclick="bukaResit('<?= $m['email'] ?>', '<?= $m['tarikh'] ?>')"
                                                            class="bg-[#428D41] text-white hover:bg-[#68B0AB] py-2 px-4 rounded flex items-center justify-center">
                                                            <i class="fas fa-search mr-1"></i> Semak
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    <?php } else { ?>
                                        <tr>
                                            <td colspan="5
                                            " class="text-center py-6 text-gray-500">
                                                <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                                                <p class="text-lg font-semibold">Tiada dalam senarai</p>
                                                <p class="text-sm">Sila cuba pilih tarikh lain.</p>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                            <div class="pagination-container flex justify-center items-center space-x-2 mt-4">
                                <?php if ($jumlahHalaman > 1): ?>
                                    <!-- First Page -->
                                    <?php if ($halaman > 1): ?>
                                        <a href="?halaman=1"
                                            class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                            <i class="fas fa-angle-double-left"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Previous Page -->
                                    <?php if ($halaman > 1): ?>
                                        <a href="?halaman=<?= $halaman - 1 ?>"
                                            class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                            <i class="fas fa-angle-left"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Page Numbers -->
                                    <?php
                                    $start = max(1, $halaman - 2);
                                    $end = min($jumlahHalaman, $halaman + 2);

                                    for ($i = $start; $i <= $end; $i++): ?>
                                        <a href="?halaman=<?= $i ?>"
                                            class="px-3 py-1 <?= $i == $halaman ? 'bg-[#68B0AB] text-white' : 'bg-[#428D41] text-white hover:bg-[#68B0AB]' ?> rounded">
                                            <?= $i ?>
                                        </a>
                                    <?php endfor; ?>

                                    <!-- Next Page -->
                                    <?php if ($halaman < $jumlahHalaman): ?>
                                        <a href="?halaman=<?= $halaman + 1 ?>"
                                            class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                            <i class="fas fa-angle-right"></i>
                                        </a>
                                    <?php endif; ?>

                                    <!-- Last Page -->
                                    <?php if ($halaman < $jumlahHalaman): ?>
                                        <a href="?halaman=<?= $jumlahHalaman ?>"
                                            class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                            <i class="fas fa-angle-double-right"></i>
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>

                            </div>
                            <!-- Page Info -->
                            <span class="flex mt-5 justify-center text-gray-600">
                                Halaman <?= $halaman ?> dari <?= $jumlahHalaman ?> (<?= $jumlahRekod ?> rekod)
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-[#428D41] text-white p-4 text-center bottom-0 w-full">
            &copy; 2025 KAFELIP. Semua hak terpelihara.
        </footer>
    </div>
    <!-- Butang scroll keatas -->
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
    </script>
    <script>
        //  script drawer
        const drawerToggle = document.getElementById('drawerToggle');
        const drawer = document.getElementById('drawer');
        const mainContent = document.getElementById('mainContent');
        const currentDate = document.getElementById('currentDate');
        const currentDay = document.getElementById('currentDay');
        const currentTime = document.getElementById('currentTime');

        drawerToggle.addEventListener('click', () => {
            drawer.classList.toggle('drawer-open');
            drawer.classList.toggle('drawer-closed');
            mainContent.classList.toggle('content-expanded');
            mainContent.classList.toggle('content-collapsed');
        });

        // function memamparkan masa
        function updateDateTime() {
            const now = new Date();

            // Extract day, month, year
            const day = String(now.getDate()).padStart(2, '0'); // Add leading zero if needed
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const year = now.getFullYear();

            // Format to day/month/year
            currentDate.textContent = `${day}/${month}/${year}`;
            currentTime.textContent = now.toLocaleTimeString();
            currentDay.textContent = now.toLocaleDateString('ms-MY', {
                weekday: 'long'
            });
        }
        // Update date and time every second
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call to set the date and time immediately

        // function semak reset
        function bukaResit(email, tarikh) {
            // Buka window baru dengan saiz yang ditetapkan
            let popupWindow = window.open(`semak-resit.php?email=${email}&tarikh=${tarikh}`, 'Resit',
                'width=800,height=600,resizable=yes,scrollbars=yes');
        }
    </script>
    <!-- Tambah script untuk setup flatpickr -->
    <script>
        // Dapatkan array tarikh yang ada tempahan
        <?php
        mysqli_data_seek($laktarikh, 0); // Reset pointer result set
        $tarikhAda = [];
        while ($row = mysqli_fetch_array($laktarikh)) {
            $tarikhAda[] = $row['tarikh'];
        }
        ?>

        const tarikhAdaTempahan = <?= json_encode($tarikhAda) ?>;

        // Setup flatpickr
        flatpickr("#tarikh_semasa", {
            dateFormat: "Y-m-d",
            enable: tarikhAdaTempahan,
            inline: false,
            locale: {
                firstDayOfWeek: 1,
                weekdays: {
                    shorthand: ["Ahd", "Isn", "Sel", "Rab", "Kha", "Jum", "Sab"],
                    longhand: ["Ahad", "Isnin", "Selasa", "Rabu", "Khamis", "Jumaat", "Sabtu"]
                },
                months: {
                    shorthand: ["Jan", "Feb", "Mac", "Apr", "Mei", "Jun", "Jul", "Ogo", "Sep", "Okt", "Nov", "Dis"],
                    longhand: ["Januari", "Februari", "Mac", "April", "Mei", "Jun", "Julai", "Ogos", "September", "Oktober", "November", "Disember"]
                }
            },
            animate: true,
            // Tambah animasi dan styling
            onOpen: function (selectedDates, dateStr, instance) {
                instance.calendarContainer.classList.add('open');
            },
            onClose: function (selectedDates, dateStr, instance) {
                instance.calendarContainer.classList.remove('open');
            }
        });
    </script>

</body>

</html>