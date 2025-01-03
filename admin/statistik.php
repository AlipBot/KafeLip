<html lang="en">

</html>
<?php
include('../function/autoKeluarAdmin.php');
include('../function/connection.php');


?>




<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel KafeLip</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/all.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-solid.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-regular.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/sharp-light.css">
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/duotone.css" />
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.5.1/css/brands.css" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .notif {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .notif-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .DaftarMenu,
        .KemaskiniMenu,
        .menu {
            display: none;
            position: fixed;
            z-index: 50;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .DaftarMenu-content,
        .KemaskiniMenu-content,
        .menu-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .dropzone {
            border: 2px dashed #ccc;
            border-radius: 4px;
            padding: 20px;
            text-align: center;
            background: #f9f9f9;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .dropzone.dragover {
            background: #e1f5fe;
            border-color: #2196f3;
        }

        .dropzone p {
            margin: 0;
            color: #666;
        }

        .dropzone i {
            font-size: 2em;
            color: #666;
            margin-bottom: 10px;
        }

        .hidden {
            display: none;
        }

        #fileDisplay {
            border: 1px solid #e2e8f0;
            margin-top: 8px;
        }

        #removeFile {
            padding: 4px 8px;
            border-radius: 4px;
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
            <div class="text-[150%] font-bold mx-auto">Statistik KafeLip</div>
            <div class="w-12"></div>
        </header>

        <div class="flex flex-1 pt-16">
            <!-- Sidebar -->
            <div id="drawer" class="w-64 bg-blue-800 text-white flex flex-col fixed h-full transition-transform duration-300 drawer-closed z-10">
                <div class="p-4 text-center text-2xl font-bold border-b border-blue-700">
                    Admin
                </div>
                <nav class="flex-1 p-4 overflow-y-auto">
                    <ul>
                        <li class="mb-4">
                            <a href="panel.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-tachometer-alt mr-2"></i> Panel Admin
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="list-user.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-users mr-2"></i> Senarai Pengguna
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="list-menu.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-utensils mr-2"></i> Senarai Makanan
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="laporan.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-file-alt mr-2"></i> Sejarah Laporan
                            </a>
                        </li>
                        <li class="mb-4">
                            <a href="statistik.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
                                <i class="fas fa-analytics mr-2"></i> Statistik
                            </a>
                        </li>
                        <div class="p-4 text-center text-2xl font-bold border-b border-blue-700">
                            Pelanggan
                        </div>
                        <li class="mb-4">
                            <a href="../menu.php" class="flex items-center p-2 hover:bg-blue-700 rounded">
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
                        <span>Statistik</span>
                    </div>

                    <!-- Tambah form pemilihan bulan -->
                    <div class="mb-6">
                        <form id="filterForm" class="flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="bulan">
                                    Bulan
                                </label>
                                <select id="bulan" name="bulan" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <?php
                                    $bulan_array = [
                                        "01" => "Januari",
                                        "02" => "Februari",
                                        "03" => "Mac",
                                        "04" => "April",
                                        "05" => "Mei",
                                        "06" => "Jun",
                                        "07" => "Julai",
                                        "08" => "Ogos",
                                        "09" => "September",
                                        "10" => "Oktober",
                                        "11" => "November",
                                        "12" => "Disember"
                                    ];
                                    foreach ($bulan_array as $value => $label) {
                                        $selected = ($value == date('m')) ? 'selected' : '';
                                        echo "<option value='$value' $selected>$label</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="flex-1">
                                <label class="block text-gray-700 text-sm font-bold mb-2" for="tahun">
                                    Tahun
                                </label>
                                <select id="tahun" name="tahun" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <?php
                                    $tahun_sekarang = date('Y');
                                    for ($tahun = $tahun_sekarang; $tahun >= $tahun_sekarang - 5; $tahun--) {
                                        $selected = ($tahun == $tahun_sekarang) ? 'selected' : '';
                                        echo "<option value='$tahun' $selected>$tahun</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Tunjuk Statistik
                            </button>
                        </form>
                    </div>

                    <!-- Container untuk graf -->
                    <div class="w-full h-[400px]">
                        <canvas id="salesChart"></canvas>
                    </div>

                    <!-- Tambah container baharu untuk graf kekerapan menu -->
                    <div class="mt-8">
                        <h2 class="text-xl font-bold mb-4">Kekerapan Menu Dibeli</h2>
                        <div class="w-full h-[400px]">
                            <canvas id="menuChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-blue-800 text-white p-4 text-center w-full">
            &copy; 2024 Kedai KafeLip. All rights reserved.
        </footer>
    </div>


    <script>
        const drawerToggle = document.getElementById('drawerToggle');
        const drawer = document.getElementById('drawer');
        const mainContent = document.getElementById('mainContent');


        drawerToggle.addEventListener('click', () => {
            drawer.classList.toggle('drawer-open');
            drawer.classList.toggle('drawer-closed');
            mainContent.classList.toggle('content-expanded');
            mainContent.classList.toggle('content-collapsed');
        });




        document.addEventListener('DOMContentLoaded', function() {
            let salesChart = null; // Variable untuk simpan instance graf
            let currentInterval = null; // Variable untuk simpan interval semasa
            let menuChart = null; // Variable untuk simpan instance graf menu

            // Fungsi untuk muat dan papar graf
            function loadChart(bulan, tahun, isRealtime = false) {
                // Buat URL dengan parameter
                const url = `../api/get-laporan-bulanan.php?bulan=${bulan}&tahun=${tahun}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.data.map(item => `Hari ${item.hari}`);
                        const dataTempahan = data.data.map(item => item.jumlah_tempahan);
                        const dataJualan = data.data.map(item => item.jumlah_jualan);

                        if (!isRealtime) {
                            // Hapus graf lama jika wujud dan bukan update realtime
                            if (salesChart) {
                                salesChart.destroy();
                            }

                            // Cipta graf baru
                            const ctx = document.getElementById('salesChart').getContext('2d');
                            salesChart = new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                            label: 'Jumlah Tempahan',
                                            data: dataTempahan,
                                            borderColor: 'rgb(75, 192, 192)',
                                            tension: 0.1,
                                            fill: false,
                                            yAxisID: 'y'
                                        },
                                        {
                                            label: 'Jumlah Jualan (RM)',
                                            data: dataJualan,
                                            borderColor: 'rgb(255, 99, 132)',
                                            tension: 0.1,
                                            fill: false,
                                            yAxisID: 'y1'
                                        }
                                    ]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            type: 'linear',
                                            display: true,
                                            position: 'left',
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Jumlah Tempahan'
                                            }
                                        },
                                        y1: {
                                            type: 'linear',
                                            display: true,
                                            position: 'right',
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Jumlah Jualan (RM)'
                                            },
                                            grid: {
                                                drawOnChartArea: false
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                maxRotation: 45,
                                                minRotation: 45
                                            }
                                        }
                                    },
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: `Statistik Tempahan dan Jualan Bulan ${data.bulan}/${data.tahun}`,
                                            font: {
                                                size: 16
                                            }
                                        }
                                    }
                                }
                            });
                        } else {
                            // Update data sedia ada
                            salesChart.data.labels = labels;
                            salesChart.data.datasets[0].data = dataTempahan;
                            salesChart.data.datasets[1].data = dataJualan;
                            salesChart.update('none'); // Update tanpa animasi
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (!isRealtime) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ralat',
                                text: 'Gagal memuat data statistik'
                            });
                        }
                    });
            }

            // Fungsi untuk muat dan papar graf menu
            function loadMenuChart(bulan, tahun, isRealtime = false) {
                const url = `../api/get-mod-menu.php?bulan=${bulan}&tahun=${tahun}`;

                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        const labels = data.menu.map(item => item.nama_makanan);
                        const values = data.menu.map(item => item.jumlah);

                        if (!isRealtime) {
                            // Hapus graf lama jika wujud dan bukan update realtime
                            if (menuChart) {
                                menuChart.destroy();
                            }

                            // Cipta graf baru
                            const ctx = document.getElementById('menuChart').getContext('2d');
                            menuChart = new Chart(ctx, {
                                type: 'bar',
                                data: {
                                    labels: labels,
                                    datasets: [{
                                        label: 'Jumlah Pesanan',
                                        data: values,
                                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                        borderColor: 'rgba(54, 162, 235, 1)',
                                        borderWidth: 1
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    scales: {
                                        y: {
                                            beginAtZero: true,
                                            title: {
                                                display: true,
                                                text: 'Jumlah Pesanan'
                                            }
                                        },
                                        x: {
                                            ticks: {
                                                maxRotation: 45,
                                                minRotation: 45
                                            }
                                        }
                                    },
                                    plugins: {
                                        title: {
                                            display: true,
                                            text: `10 Menu Paling Popular Bulan ${data.bulan}/${data.tahun}`,
                                            font: {
                                                size: 16
                                            }
                                        }
                                    }
                                }
                            });
                        } else {
                            // Update data sedia ada tanpa animasi
                            menuChart.data.labels = labels;
                            menuChart.data.datasets[0].data = values;
                            menuChart.update('none');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        if (!isRealtime) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Ralat',
                                text: 'Gagal memuat data kekerapan menu'
                            });
                        }
                    });
            }

            // Fungsi untuk mulakan realtime updates untuk kedua-dua graf
            function startRealtimeUpdates(bulan, tahun) {
                // Hentikan interval sedia ada jika wujud
                if (currentInterval) {
                    clearInterval(currentInterval);
                }

                // Semak jika bulan dan tahun adalah masa sekarang
                const sekarang = new Date();
                const bulanSemasa = String(sekarang.getMonth() + 1).padStart(2, '0');
                const tahunSemasa = sekarang.getFullYear().toString();

                // Hanya mulakan interval jika bulan dan tahun adalah masa sekarang
                if (bulan === bulanSemasa && tahun === tahunSemasa) {
                    currentInterval = setInterval(() => {
                        loadChart(bulan, tahun, true);
                        loadMenuChart(bulan, tahun, true); // Tambah update untuk graf menu
                    }, 3000); // Update setiap 3 saat
                }
            }

            // Update event listener form
            document.getElementById('filterForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const bulan = document.getElementById('bulan').value;
                const tahun = document.getElementById('tahun').value;

                loadChart(bulan, tahun, false);
                loadMenuChart(bulan, tahun, false);
                startRealtimeUpdates(bulan, tahun);
            });

            // Muat kedua-dua graf pada permulaan
            const bulanSemasa = document.getElementById('bulan').value;
            const tahunSemasa = document.getElementById('tahun').value;
            loadChart(bulanSemasa, tahunSemasa, false);
            loadMenuChart(bulanSemasa, tahunSemasa, false);
            startRealtimeUpdates(bulanSemasa, tahunSemasa);

            // Bersihkan interval apabila pengguna meninggalkan halaman
            window.addEventListener('beforeunload', function() {
                if (currentInterval) {
                    clearInterval(currentInterval);
                }
            });

            const notifsuccess = new Audio('../lib/audio/notif.mp3'); // Tukar path ke fail audio anda
            const notiferror = new Audio('../lib/audio/error.mp3'); // Tukar path ke fail audio anda
            const notifinfo = new Audio('../lib/audio/info.mp3'); // Tukar path ke fail audio anda
            const notifwarning = new Audio('../lib/audio/warning.mp3'); // Tukar path ke fail audio anda


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

</body>

</html>