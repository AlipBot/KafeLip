<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include('../function/autoKeluarAdmin.php'); # fail function auto logout jika pengguna belum login dan bukan admin
include('../function/connection.php');  # Sambung Ke database
?>

<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin KafeLip</title>
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

        .fullscreen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: white;
            z-index: 50;
            overflow: auto;
        }

        .laporan-pelanggan.fullscreen-mode table {
            max-height: 80vh;
            overflow-y: auto;
            display: block;
        }

        .laporan-pelanggan.fullscreen-mode table thead,
        .laporan-pelanggan.fullscreen-mode table tbody {
            display: table;
            width: 100%;
        }

        .laporan-pelanggan.fullscreen-mode table tbody {
            display: block;
            overflow-y: auto;
            max-height: 70vh;
        }

        .laporan-pelanggan.fullscreen-mode table tbody tr {
            display: table;
            width: 100%;
            table-layout: fixed;
        }

        .laporan-pelanggan.fullscreen-mode .px-\[47px\] {
            padding-left: 45px;
            padding-right: 106px;
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

<bod class="font-roboto bg-gray-100">
    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-[#588157] text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-[#3a5a40] text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Panel KafeLip</div>
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-calendar-day text-[#588157] text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Tempah Hari Ini</div>
                            <div class="TempahHari text-2xl font-bold">0</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-calendar-alt text-[#588157] text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Tempah Bulan Ini</div>
                            <div class="TempahBulan text-2xl font-bold">0</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-dollar-sign text-[#588157] text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Keuntungan Hari Ini</div>
                            <div class="UntungHari text-2xl font-bold">0</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-coins text-[#588157] text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Keuntungan Bulan Ini</div>
                            <div class="UntungBulan text-2xl font-bold">0</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-user-friends text-[#588157] text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Pelanggan</div>
                            <div class="Pelanggan text-2xl font-bold">0</div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-user-tie text-[#588157] text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Pekerja</div>
                            <div class="Pekerja text-2xl font-bold">0</div>
                        </div>
                    </div>
                </div>

                <div class="laporan-pelanggan bg-white p-6 rounded-lg shadow relative">
                    <div class="text-[30px] font-bold mb-4 flex justify-between items-center">
                        <span>Laporan Langsung Hari ini</span>
                        <div class="flex items-center space-x-4">
                            <button id="fullscreenToggle"
                                class="text-xl bg-[#588157] hover:bg-[#68B0AB] text-white p-2 rounded">
                                <i class="fas fa-expand"></i> Full Screen
                            </button>
                            <button onclick="playNotificationSound()"
                                class="text-xl bg-[#588157] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center">
                                <i class="fas fa-volume-up"></i>
                                <span class="ml-2">Sound</span>
                            </button>
                        </div>
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
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-[#a3b18a] text-black font-bold">
                                    <th width='30%' class="text-black px-[47px] py-2">Nama Pelanggan</th>
                                    <th width='30%' class="text-black px-[47px] py-2">Pesanan</th>
                                    <th width='20%' class="text-black px-[47px] py-2">Jumlah Harga (RM)</th>
                                    <th width='20%' class="text-black px-[47px] py-2">Masa</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center text-xl text-red-500 py-4 no-data">TIADA
                                        PELANGGAN SEKARANG</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-[#588157] text-white p-4 text-center bottom-0 w-full">
            &copy; © 2025 KAFELIP. Semua hak terpelihara.
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
        // drawer script
        const drawerToggle = document.getElementById('drawerToggle');
        const drawer = document.getElementById('drawer');
        const mainContent = document.getElementById('mainContent');
        const fullscreenToggle = document.getElementById('fullscreenToggle');
        const laporanTempahan = document.querySelector('.laporan-pelanggan');
        const currentDate = document.getElementById('currentDate');
        const currentTime = document.getElementById('currentTime');
        const currentDay = document.getElementById('currentDay');
        const soundToggle = document.getElementById('soundToggle');

        drawerToggle.addEventListener('click', () => {
            drawer.classList.toggle('drawer-open');
            drawer.classList.toggle('drawer-closed');
            mainContent.classList.toggle('content-expanded');
            mainContent.classList.toggle('content-collapsed');
        });

        fullscreenToggle.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                laporanTempahan.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
                fullscreenToggle.innerHTML = '<i class="fas fa-compress"></i> Minimize';
                laporanTempahan.classList.add('fullscreen-mode');
            } else {
                document.exitFullscreen();
                fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i> Full Screen';
                laporanTempahan.classList.remove('fullscreen-mode');
            }
        });

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i> Full Screen';
                laporanTempahan.classList.remove('fullscreen-mode');
            }
        });

        // function dapatkan masa yang lalu
        function timeSince(date) {
            const seconds = Math.floor((new Date() - new Date(date)) / 1000);
            let minutes = Math.floor(seconds / 60);
            let hours = Math.floor(minutes / 60);
            let days = Math.floor(hours / 24);

            if (minutes < 1) return `${seconds} Baru sahaja`;
            if (minutes < 60) return `${minutes} minit yang lalu`;
            if (hours < 24) {
                let remainingMinutes = minutes % 60;
                return `${hours} jam ${remainingMinutes} minit yang lalu`;
            }
            return `${days} hari yang lalu`;
        }
       
        // gunakan fecth data tempahan daripada api untuk mendapatkan data secara realtime
        function updateRealtimeData() {
            fetch('../api/get-laporan.php')
                .then(response => response.json())
                .then(data => {
                    // Update Jumlah Tempahan Hari Ini
                    document.querySelector('.TempahHari').innerHTML = `<strong>${data.jumlahHarini}</strong>`;

                    // Update Jumlah Tempahan Bulan Ini
                    document.querySelector('.TempahBulan').innerHTML = `<strong>${data.jumlahSebulan}</strong>`;

                    // Update Keuntungan Hari Ini
                    document.querySelector('.UntungHari').innerHTML = `<strong>RM ${data.total_harian}</strong>`;

                    // Update Keuntungan Bulan Ini
                    document.querySelector('.UntungBulan').innerHTML = `<strong>RM ${data.total_bulanan}</strong>`;

                    // Update Jumlah Pelanggan
                    document.querySelector('.Pelanggan').innerHTML = `<strong>${data.total_pelanggan}</strong>`;

                    // Update Jumlah Pekerja
                    document.querySelector('.Pekerja').innerHTML = `<strong>${data.total_pekerja}</strong>`;

                    // Update Laporan Tempahan Pelanggan Hari Ini
                    let laporanTable = document.querySelector('.laporan-pelanggan tbody');
                    laporanTable.innerHTML = ''; // Clear table

                    if (data.laporan_hari_ini.length === 0) {
                        laporanTable.innerHTML = `<tr><td colspan="4" class="text-center text-xl text-red-500 py-4 no-data">TIADA PELANGGAN SEKARANG</td>`;
                    } else {
                        data.laporan_hari_ini.forEach(tempahan => {
                            let masaLalu = timeSince(tempahan.timestap);
                            let row = `<tr class="bg-white border-b hover:bg-blue-50">
                                <td class="px-4 py-2 ">${tempahan.nama}</td>
                                <td class="px-4 py-2">${tempahan.senarai_makanan}</td>
                                <td class="px-4 py-2 text-center">RM ${parseFloat(tempahan.jumlah_harga_semua).toFixed(2)}</td>
                                <td class="px-4 py-2 text-center">${tempahan.masa} <br> ${masaLalu}</td>
                            </tr>`;
                            laporanTable.innerHTML += row;
                        });
                    }
                })
                .catch(error => {
                    document.querySelector('.laporan-pelanggan tbody').innerHTML = `<tr><td colspan="4" class="text-center text-xl text-red-500 py-4 no-data">TIADA PELANGGAN SEKARANG</td></tr>`;
                });
        }
        
        // function memamparkan masa , tarikh dan hari
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

        // Panggil setiap 1 saat
        setInterval(updateRealtimeData, 1000);
        updateRealtimeData(); // Panggil sekali bila page load



        function playNotificationSound() {
            const audio = document.getElementById('notifSound');
            audio.play();
        }

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

        let lastOrderCount = null;
        let isFirstLoad = true;
        // function keluarkan bunyi dan toast jika ada perubahan di jadual tempahan
        function checkOrderChanges() {
            fetch('../api/get-laporan.php')
                .then(response => response.json())
                .then(data => {
                    const currentOrderCount = data.jumlahHarini;

                    // Skip notification on first load
                    if (isFirstLoad) {
                        lastOrderCount = currentOrderCount;
                        isFirstLoad = false;
                        return;
                    }

                    // Only check if we have a previous value
                    if (lastOrderCount !== null) {
                        if (currentOrderCount < lastOrderCount) {
                            // Pembatalan pesanan
                            playCancelSound();
                            Toast.fire({
                                icon: "warning",
                                title: "Tempahan telah dibatalkan!"
                            });
                        } else if (currentOrderCount > lastOrderCount) {
                            // Pesanan baru
                            playNotificationSound();
                            Toast.fire({
                                icon: "success",
                                title: "Tempahan baru diterima!"
                            });
                        }
                    }

                    lastOrderCount = currentOrderCount;
                })
                .catch(error => console.error('Error:', error));
        }

        function playCancelSound() {
            const audio = document.getElementById('cancelSound');
            audio.play();
        }

        // Panggil fungsi setiap 3 saat (lebih selamat daripada 1 saat)
        setInterval(checkOrderChanges, 3000);

        // Dapatkan nilai awal
        checkOrderChanges();
    </script>
    <audio id="notifSound" src="../lib/audio/tutru.mp3"></audio>
    <!-- Tambah audio untuk pembatalan -->
    <audio id="cancelSound" src="../lib/audio/order-batal.mp3"></audio>
    </body>

</html>