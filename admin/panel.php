<?php
session_start();
include('../function/connection.php');
include('../function/admin-only.php');


?>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel KafeLip</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
    </style>
</head>

<body class="font-roboto bg-gray-100">
    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-blue-800 text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-blue-700 text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Panel KafeLip</div>
            <div class="w-12"></div>
        </header>

        <div class="flex flex-1 pt-16">
            <!-- Sidebar -->
            <div id="drawer" class="w-64 bg-blue-800 text-white flex flex-col fixed h-full transition-transform duration-300 drawer-closed z-10">
                <div class="p-4 text-center text-2xl font-bold border-b border-blue-700">
                    Kedai Kafelip
                </div>
                <nav class="flex-1 p-4 overflow-y-auto">
                    <ul>
                        <li class="mb-4">
                            <a href="#" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-users mr-2"></i> Senarai Pengguna
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="#" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-utensils mr-2"></i> Senarai Makanan
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>

            <!-- Main Content -->
            <div id="mainContent" class="flex-1 p-6 transition-all duration-300 content-expanded">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-calendar-day text-blue-800 text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Tempah Hari Ini</div>
                            <div class="TempahHari text-2xl font-bold">0
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-calendar-alt text-blue-800 text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Tempah Bulan Ini</div>
                            <div class="TempahBulan text-2xl font-bold">0
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-dollar-sign text-blue-800 text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Keuntungan Hari Ini</div>
                            <div class="UntungHari text-2xl font-bold">0
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-coins text-blue-800 text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Keuntungan Bulan Ini</div>
                            <div class="UntungBulan text-2xl font-bold">0
                            </div>
                        </div>
                    </div>
                    <div class="bg-white p-4 rounded shadow flex items-center">
                        <i class="fas fa-user-friends text-blue-800 text-3xl mr-4"></i>
                        <div>
                            <div class="text-gray-600">Jumlah Pelanggan</div>
                            <div class="Pelanggan text-2xl font-bold">0
                            </div>
                        </div>
                    </div>
                </div>

                <div class="laporan-pelanggan bg-white p-6 rounded-lg shadow relative">
                    <div class="text-xl font-bold mb-4 flex justify-between items-center">
                        <span>Laporan Tempahan Pelanggan Hari ini</span>
                        <button id="fullscreenToggle" class="bg-blue-700 text-white p-2 rounded">
                            <i class="fas fa-expand"></i> Full Screen
                        </button>
                    </div>
                    <table class="w-full table-auto rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-blue-200 text-blue-800">
                                <th class="px-4 py-2">Nama Pelanggan</th>
                                <th class="px-4 py-2">Pesanan</th>
                                <th class="px-4 py-2">Junlah Harga (RM)</th>
                                <th class="px-4 py-2">Masa</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-blue-800 text-white p-4 text-center">
            &copy; 2024 Kedai KafeLip. All rights reserved.
        </footer>
    </div>

    <script>
        const drawerToggle = document.getElementById('drawerToggle');
        const drawer = document.getElementById('drawer');
        const mainContent = document.getElementById('mainContent');
        const fullscreenToggle = document.getElementById('fullscreenToggle');
        const laporanTempahan = document.querySelector('.laporan-pelanggan');

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
            } else {
                document.exitFullscreen();
                fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i> Full Screen';
            }
        });

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                fullscreenToggle.innerHTML = '<i class="fas fa-expand"></i> Full Screen';
            }
        });

        function updateRealtimeData() {
            fetch('../function/api/get-laporan.php')
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
                    document.querySelector('.Pelanggan').innerHTML = `<strong>${data.total_pelanggan} Orang</strong>`;

                    // Update Laporan Tempahan Pelanggan Hari Ini
                    let laporanTable = document.querySelector('.laporan-pelanggan tbody');
                    laporanTable.innerHTML = ''; // Clear table

                    data.laporan_hari_ini.forEach(tempahan => {
                        let row = `<tr class="bg-white border-b hover:bg-blue-50">
                        <td class="px-4 py-2">${tempahan.nama}</td>
                        <td class="px-4 py-2">${tempahan.senarai_makanan}</td>
                        <td class="px-4 text-center py-2">RM ${parseFloat(tempahan.jumlah_harga).toFixed(2)}</td>
                        <td class="px-4 text-center	py-2"> ${tempahan.masa} </td>
                    </tr>`;
                        laporanTable.innerHTML += row;
                    });
                })
                .catch(error => {
                    document.querySelector('.laporan-pelanggan tbody').innerHTML = "<p style='color: red;'>TIADA PELANGGAN SEKARANG</p>";
                });
        }

        function formatDate(dateString) {
            let date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
        }

        // Panggil setiap 5 saat
        setInterval(updateRealtimeData, 500);
        updateRealtimeData(); // Panggil sekali bila page load
    </script>

</body>

</html>