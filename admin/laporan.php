<?php
include('../function/autoKeluarAdmin.php');
include('../function/connection.php');

if (isset($_GET['tarikh_semasa'])) {
    $tarikhsemasa = $_GET['tarikh_semasa'];
} else {
    $tarikhsemasa = date("Y-m-d");
}

# Dapatkan Senarai tarikh
$sqltarikh = "SELECT DATE(tarikh) AS tarikh, count(*) as bilangan
FROM tempahan
GROUP BY DATE(tarikh)
ORDER BY DATE(tarikh) DESC";
$laktarikh = mysqli_query($condb, $sqltarikh);

# dapatkan semua senarai tempahan
$sql = "SELECT t.email, 
               t.tarikh,
               SUM(t.kuantiti * m.harga) AS jumlah_harga
        FROM tempahan t
        JOIN makanan m ON t.kod_makanan = m.kod_makanan
        WHERE t.tarikh LIKE '%$tarikhsemasa%'
        GROUP BY t.email, t.tarikh
        ORDER BY t.tarikh DESC";
$laksql = mysqli_query($condb, $sql);


?>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sejarah Laporan KafeLip</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-solid.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-regular.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-light.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/duotone.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/brands.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
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
    </style>

</head>

<body class="font-roboto bg-gray-100 ">
    <div class="flex min-h-screen flex-col">
        <!-- Header -->
        <header class="bg-[#588157] text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-[#3a5a40] text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Sejarah Laporan Tempahan KafeLip</div>
            <div class="w-12"></div>
        </header>

        <div class="flex flex-1 pt-16">
            <!-- Sidebar -->
            <div id="drawer"
                class="w-64 bg-[#588157] text-white flex flex-col fixed h-full transition-transform duration-300 drawer-closed z-10">
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
            <div id="mainContent" class="flex-1 p-6 transition-all duration-300 content-expanded">
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
                        <form action="laporan.php" method="GET" class="py-5 flex items-center space-x-2 w-full">
                            <select name='tarikh_semasa'>
                                <option value='<?= $tarikhsemasa ?>' class="border rounded p-2 w-2/5">
                                    <?= date_format(date_create($tarikhsemasa), "d/m/Y"); ?>
                                </option>
                                <option disabled>Pilih Tarikh Lain Di bawah</option>
                                <?php while ($mm = mysqli_fetch_array($laktarikh)): ?>
                                    <option value='<?= $mm['tarikh'] ?>'>
                                        <?= date_format(date_create($mm['tarikh']), "d/m/Y") ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                            <button type="submit" class="bg-[#588157] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center">
                                <i class="fas fa-search mr-1"></i> Cari
                            </button>
                        </form>

                        <div class="flex space-x-2">
                            <span class="font-bold text-lg p-2 rounded flex items-center whitespace-nowrap">Laporan pada
                                Tarikh : <?= date_format(date_create($tarikhsemasa), "d/m/Y"); ?> </span>
                        </div>


                        <div class="table-container">
                            <table class="w-full table-auto rounded-lg overflow-hidden">
                                <thead>
                                    <tr class="bg-[#a3b18a] font-bold text-black">
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
                                                    <?php echo htmlspecialchars($m['jumlah_harga']); ?></td>
                                                <td class='px-4 py-2 text-center'>
                                                    <?php
                                                    $tarikh = date_create($m['tarikh']);
                                                    echo date_format($tarikh, "g:i:s A");
                                                    ?>
                                                </td>
                                                <td class='px-4 py-2 text-center'>
                                                    <div class="flex flex-col items-center space-y-4">
                                                        <button onclick="bukaResit('<?= $m['email'] ?>', '<?= $m['tarikh'] ?>')"
                                                            class="bg-[#588157] text-white hover:bg-[#68B0AB] py-2 px-4 rounded flex items-center justify-center">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer -->
        <footer class="bg-[#588157] text-white p-4 text-center bottom-0 w-full">
            &copy; 2024 Kedai KafeLip. All rights reserved.
        </footer>

    </div>
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
    <script>
        const drawerToggle = document.getElementById('drawerToggle');
        const drawer = document.getElementById('drawer');
        const mainContent = document.getElementById('mainContent');
        const currentDate = document.getElementById('currentDate');
        const currentTime = document.getElementById('currentTime');

        drawerToggle.addEventListener('click', () => {
            drawer.classList.toggle('drawer-open');
            drawer.classList.toggle('drawer-closed');
            mainContent.classList.toggle('content-expanded');
            mainContent.classList.toggle('content-collapsed');
        });

        function updateDateTime() {
            const now = new Date();

            // Extract day, month, year
            const day = String(now.getDate()).padStart(2, '0'); // Add leading zero if needed
            const month = String(now.getMonth() + 1).padStart(2, '0'); // Months are 0-indexed
            const year = now.getFullYear();

            // Format to day/month/year
            currentDate.textContent = `${day}/${month}/${year}`;
            currentTime.textContent = now.toLocaleTimeString();
        }


        // Update date and time every second
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call to set the date and time immediately

        function bukaResit(email, tarikh) {
            // Buka window baru dengan saiz yang ditetapkan
            let popupWindow = window.open(`semak-resit.php?email=${email}&tarikh=${tarikh}`, 'Resit',
                'width=800,height=600,resizable=yes,scrollbars=yes');
        }
    </script>

</body>

</html>