<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

include('../function/connection.php');
include('../function/kawalan-admin.php');

$tambahan = "";
$searchTerm = "";

# Handle GET request for displaying filtered results
if (!empty($_GET['nama_menu'])) {
    $searchTerm = $_GET['nama_menu'];
    $tambahan = " WHERE makanan.nama_makanan LIKE ?";
}

# SQL query to fetch data
$arahan_papar = "SELECT * FROM makanan" . $tambahan . " ORDER BY makanan.nama_makanan";
$stmt = mysqli_prepare($condb, $arahan_papar);

if (!empty($searchTerm)) {
    $searchTerm = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
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
    </style>
</head>

<body class="font-roboto bg-gray-100">
    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-blue-800 text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-blue-700 text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Senarai Makanan KafeLip</div>
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
                        <span>Senarai Makanan</span>
                    </div>
                    <div class="text-center text-gray-600 mb-4">
                        <span class="font-bold text-lg">Tarikh: </span>
                        <span id="currentDate" class="font-bold text-lg"></span>
                        <br>
                        <span class="font-bold text-lg">Masa: </span>
                        <span id="currentTime" class="font-bold text-lg"></span>
                        <div class="flex items-center justify-between space-x-5">
                            <form action="list-menu.php" method="GET" class="py-5 flex items-center space-x-2 w-full">
                                <input type="text" name="nama_menu" placeholder="Carian Menu" value="<?php echo htmlspecialchars($_GET['nama_menu'] ?? ''); ?>" class="border rounded p-2 w-2/5">
                                <button type="submit" class="bg-blue-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                <button type="button" onclick="window.location.href='list-menu.php';" class="bg-red-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-times mr-1"></i> Padam
                                </button>
                            </form>
                            <div class="flex space-x-2">
                                <a href="add-menu.php" class="bg-blue-800 text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Daftar Menu
                                </a>
                                <a href="upload-menu.php" class="bg-blue-800 text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-upload mr-1"></i> Upload Menu
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-blue-200 text-blue-800">
                                    <th width='13%' class="px-[47px] py-2">Kod Menu</th>
                                    <th width='30%' class="px-[47px] py-2">Gambar</th>
                                    <th width='30%' class="px-[47px] py-2">Nama Menu</th>
                                    <th width='15%' class="px-[47px] py-2">Harga (RM)</th>
                                    <th width='20%' class="px-[47px] py-2">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody class="text-[20px] font-bold">
                                <?php if (mysqli_num_rows($result) > 0) { ?>
                                    <?php while ($m = mysqli_fetch_assoc($result)) { ?>
                                        <tr class='bg-white border-b hover:bg-blue-50'>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['kod_makanan']); ?></td>
                                            <td class='px-8 py-4 flex justify-center items-center'>
                                                <img src='../menu-images/<?php echo htmlspecialchars($m['gambar']); ?>' alt='Gambar menu <?php echo htmlspecialchars($m['nama_makanan']); ?>' width='50%'>
                                            </td>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['nama_makanan']); ?></td>
                                            <td class='px-4 py-2 text-center'>RM <?php echo htmlspecialchars($m['harga']); ?></td>
                                            <td class='px-4 py-2 text-center'>
                                                <div class="flex flex-col items-center space-y-4">
                                                    <button onclick="location.href='tukar-menu.php?id_menu=<?php echo urlencode($m['kod_makanan']); ?>'" class="bg-blue-800 text-white py-2 px-4 rounded flex items-center justify-center">
                                                        <i class="fas fa-edit mr-1"></i> Kemaskini
                                                    </button>
                                                    <button onclick="if(confirm('Anda pasti anda ingin memadam data ini?')) location.href='../function/del-menu.php?id_menu=<?php echo urlencode($m['kod_makanan']); ?>'" class="bg-red-800 text-white py-2 px-9 rounded flex items-center justify-center">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-6 text-gray-500">
                                            <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                                            <p class="text-lg font-semibold">Tiada dalam senarai</p>
                                            <p class="text-sm">Sila cuba kata kunci lain.</p>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
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
            currentDate.textContent = now.toLocaleDateString();
            currentTime.textContent = now.toLocaleTimeString();
        }

        // Update date and time every second
        setInterval(updateDateTime, 1000);
        updateDateTime(); // Initial call to set the date and time immediately
    </script>

</body>

</html>