<?php
include('../function/autoKeluarAdmin.php');
include('../function/connection.php');

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


// Tambah pembolehubah untuk jenis notifikasi
$notification_type = "";  // 'success', 'error', atau 'warning'

if (isset($_POST['DaftarMenu'])) {

    # Mengambil data daripada borang (form)
    $kod_makanan      =   $_POST['kod_makanan'];
    $nama_makanan     =   $_POST['nama_makanan'];
    $harga          =   $_POST['harga'];

    # Mengambil data gambar
    $file_extension    =   pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    # Buang jarak dan tukar kepada huruf kecil
    $nama_fail_baru    =   strtolower(str_replace(' ', '', $nama_makanan)) . '.' . $file_extension;
    $lokasi           =   $_FILES['gambar']['tmp_name'];

    # Data validation : had atas
    if (!is_numeric($harga) and $harga > 0) {
        $_SESSION['error'] = "Ralat: Sila masukkan harga yang sah";
        header("Location: list-menu.php");
        exit();
    }
    # Semak id_menu dah wujud atau belum
    $sql_semak  =   "select kod_makanan from makanan where kod_makanan = '$kod_makanan' ";
    $laksana_semak  =   mysqli_query($condb, $sql_semak);
    if (mysqli_num_rows($laksana_semak) == 1) {
        $_SESSION['error'] = "ID Menu telah digunakan. Sila guna kod makanan yang lain";
        header("Location: list-menu.php");
        exit();
    }

    # proses menyimpan data
    $sql_simpan =   "insert into makanan set
                    kod_makanan     = '$kod_makanan',
                    nama_makanan   = '$nama_makanan',
                    harga       = '$harga',
                    gambar      = '$nama_fail_baru'
                ";
    $laksana    =   mysqli_query($condb, $sql_simpan);


    # Pengujian proses menyimpan data 
    if ($laksana) {
        copy($lokasi, "../menu-images/" . $nama_fail_baru);

        $_SESSION['success'] = "Pendaftaran Berjaya";
        header("Location: list-menu.php");
        exit();
    } else {
        $_SESSION['error'] = "Pendaftaran Gagal: " . mysqli_error($condb);
        header("Location: list-menu.php");
        exit();
    }
}



if (isset($_POST['upload'])) {

    # mengambil nama sementara fail
    $namafailsementara  =   $_FILES['data']['tmp_name'];
    $namafail           =   $_FILES['data']['name'];
    $jenisfail          =   $_FILES['data']['type'];

    # menguji jenis fail dan sail fail 
    if ($_FILES["data"]["size"] > 0 and $jenisfail == "text/plain") {
        # membuka fail yang diambil
        $fail_data = fopen($namafailsementara, "r");

        $bil = 0;

        # mendapatkan data dari fail baris demi baris
        while (!feof($fail_data)) {
            # mengambil data sebaris sahaja bg setiap pusingan 
            $ambilbarisdata = fgets($fail_data);

            # memecahkan baris data mengikut tanda pipe
            $data = explode("|", $ambilbarisdata);

            # Umpukkan data yang dipecahkan
            $id_menu        = trim($data[0]);
            $nama_menu      = trim($data[1]);
            $harga          = trim($data[2]);
            # semak jika id menu telah ada dalam  pangkalan data
            $pilih = mysqli_query($condb, "select* from makanan where kod_makanan='" . $id_menu . "'");
            if (mysqli_num_rows($pilih) == 1) {
                $_SESSION['error'] = "kod_makanan $id_menu di fail txt telah ada di pangkalan data.TUKAR id_menu DALAM FAIL TXT";
                header("Location: list-menu.php");
                exit();
            } else {
                # arahan SQL untuk menyimpan data
                $sql_simpan =   "insert into makanan set
                                 kod_makanan    = '$id_menu',
                                 nama_makanan   = '$nama_menu',
                                 harga          = '$harga' 
                                ";

                # memasukkan data kedalam jadual pengguna 
                $laksana_arahan_simpan = mysqli_query($condb, $sql_simpan);
                $bil++;
            }
        }
        # menutup fail txt yang dibuka
        fclose($fail_data);

        if (mysqli_num_rows($pilih) == 1) {
            $_SESSION['error'] = "kod_makanan $id_menu di fail txt telah ada di pangkalan data. TUKAR id_menu DALAM FAIL TXT";
            header("Location: list-menu.php");
            exit();
        } else {
            $_SESSION['success'] = "Import fail Data Selesai. Sebanyak $bil data telah disimpan. KEMASKINI MENU DAN UPLOAD GAMBAR";
            header("Location: list-menu.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "hanya fail berformat txt sahaja dibenarkan";
        header("Location: list-menu.php");
        exit();
    }
}

?>

<html lang="en">

</html>

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
                                <button id="DaftarMenuButton" class="bg-blue-800 text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Daftar Menu
                                </button>
                                <button id="uploadButton" class="bg-blue-800 text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Upload Menu
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-blue-200 text-blue-800">
                                    <th width='20%' class="px-[47px] py-2">Kod Menu</th>
                                    <th width='30%' class="px-[47px] py-2">Gambar</th>
                                    <th width='30%' class="px-[47px] py-2">Nama Menu</th>
                                    <th width='20%' class="px-[47px] py-2">Harga (RM)</th>
                                    <th width='20%' class="px-[47px] py-2">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($result) > 0) { ?>
                                    <?php while ($m = mysqli_fetch_assoc($result)) { ?>
                                        <tr class='bg-white border-b hover:bg-blue-50'>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['kod_makanan']); ?></td>
                                            <td class='px-8 py-4 flex justify-center items-center'>
                                                <img src='../menu-images/<?php echo htmlspecialchars($m['gambar']); ?>' alt='Gambar menu <?php echo htmlspecialchars($m['nama_makanan']); ?>' width='60%' class="rounded-md" >
                                            </td>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['nama_makanan']); ?></td>
                                            <td class='px-4 py-2 text-center'>RM <?php echo number_format($m['harga'], 2); ?> </td>
                                            <td class='px-4 py-2 text-center'>
                                                <div class="flex flex-col items-center space-y-4">
                                                    <button onclick="updateMenu('<?= $m['kod_makanan'] ?>')" class="bg-blue-800 text-white py-2 px-4 rounded flex items-center justify-center">
                                                        <i class="fas fa-edit mr-1"></i> Kemaskini
                                                    </button>
                                                    <button
                                                        data-id="<?php echo urlencode($m['kod_makanan']); ?>"
                                                        class="delete-btn bg-red-800 text-white py-2 px-9 rounded flex items-center justify-center">
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
        <footer class="bg-blue-800 text-white p-4 text-center w-full">
            &copy; 2024 Kedai KafeLip. All rights reserved.
        </footer>
    </div>

    <!-- menu -->
    <div id="uploadmenu" class="menu">
        <div class="menu-content">
            <span onclick="window.location.href = window.location.href;" class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Upload Menu</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="file" class="block text-gray-700">Pilih fail txt:</label>
                    <div class="dropzone" id="uploadDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Seret fail txt ke sini atau klik untuk memilih</p>
                        <input type="file" id="file" name='data' accept=".txt" class="hidden">
                    </div>
                    <!-- Tambah elemen baru untuk paparan nama fail -->
                    <div id="fileDisplay" class="hidden p-3 bg-gray-100 rounded flex justify-between items-center">
                        <span id="fileName" class="text-gray-700"></span>
                        <button type="button" id="removeFile" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name='upload' class="bg-blue-800 text-white p-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Menu -->
    <div id="DaftarMenu" class="DaftarMenu">
        <div class="DaftarMenu-content">
            <span onclick="window.location.href = window.location.href;" class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Pendaftaran Menu Baru</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Sila Lengkapkan Maklumat di bawah</label>
                    ID Menu:<input type="text" name='kod_makanan' id="nama" class="w-full border p-2 mb-3" required>
                    Nama Menu: <input type="text" name='nama_makanan' id="nama" class="w-full border p-2 mb-3" required>
                    Harga <input type='number' name='harga' step='0.01' class="w-full border p-2 mb-3" required>

                    <!-- Container untuk preview gambar -->
                    <div class="flex justify-center mb-4 relative" id="daftarPreviewContainer" style="display: none;">
                        <img id="preview" style="max-width: 300px;">
                        <button type="button" id="closeDaftarPreview" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="dropzone" id="daftarDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Seret gambar ke sini atau klik untuk memilih</p>
                        <input type="file" id="gambarDaftar" name="gambar" class="hidden" accept="image/*" required>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name='DaftarMenu' class="bg-blue-800 text-white p-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kemaskini Menu -->
    <div id="KemaskiniMenu" class="KemaskiniMenu">
        <div class="KemaskiniMenu-content">
            <span onclick="window.location.href = window.location.href;" class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Kemaskini Menu Baru</h2>
            <form action="../function/update-menu.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Sila Lengkapkan Maklumat di bawah</label>
                    <input type="hidden" name="id_menu" id="id_menu">
                    Nama Menu: <input id="nama_makanan" type="text" name='nama_menu' class="w-full border p-2 mb-3" required>
                    Harga <input id="harga_makanan" type='number' name='harga' step='0.01' class="w-full border p-2 mb-3" required>
                    <div class="flex justify-center mb-4 relative" id="previewContainer" style="display: none;">
                        <img id="preview_kemas" style="max-width: 300px;">
                        <button type="button" id="closePreview" class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="dropzone" id="kemaskiniDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Seret gambar ke sini atau klik untuk memilih</p>
                        <input type="file" id="gambar" name="gambar" class="hidden" accept="image/*">
                    </div>

                </div>
                <div class="flex justify-center">
                    <button type="submit" name='DaftarMenu' class="bg-blue-800 text-white p-2 rounded">Submit</button>
                </div>
            </form>
        </div>
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

        function previewGambar(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function() {
                var imgElement = document.getElementById('preview');
                imgElement.src = reader.result;
                imgElement.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]); // Baca fail sebagai URL
        }

        function previewGambarKemas(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function() {
                var imgElement = document.getElementById('preview_kemas');
                imgElement.src = reader.result;
                imgElement.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]); // Baca fail sebagai URL
        }



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


        // menu functionality
        const menu = document.getElementById("uploadmenu");
        const Daftarmenu = document.getElementById("DaftarMenu");
        const Kemaskinimenu = document.getElementById("KemaskiniMenu");
        const btnDaftarmenu = document.getElementById("DaftarMenuButton");
        const btnUpdateButton = document.getElementById("KemaskiniMenuButton");
        const btn = document.getElementById("uploadButton");

        function updateMenu(kod_menu) {
            fetch(`../api/get-menu.php?kod_menu=${kod_menu}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id_menu').value = kod_menu;
                    document.getElementById('nama_makanan').value = data.nama_makanan;
                    document.getElementById('harga_makanan').value = data.harga;
                    Kemaskinimenu.style.display = "block";
                });

        }

        btn.onclick = function() {
            menu.style.display = "block";

        }

        btnDaftarmenu.onclick = function() {
            Daftarmenu.style.display = "block";
        }


        window.onclick = function(event) {
            if (event.target == Kemaskinimenu) {
                window.location.href = window.location.href;
                Kemaskinimenu.style.display = "none";
            }
            if (event.target == Daftarmenu) {
                window.location.href = window.location.href;
                Daftarmenu.style.display = "none";
            }
            if (event.target == menu) {
                window.location.href = window.location.href;
                menu.style.display = "none";
            }

        }

        const notifsuccess = new Audio('../lib/audio/notif.mp3'); // Tukar path ke fail audio anda
            const notiferror = new Audio('../lib/audio/error.mp3'); // Tukar path ke fail audio anda
            const notifinfo = new Audio('../lib/audio/info.mp3'); // Tukar path ke fail audio anda
            const notifwarning = new Audio('../lib/audio/warning.mp3'); // Tukar path ke fail audio anda


        document.addEventListener('DOMContentLoaded', function() {
            // Untuk popup success
            <?php if (isset($_SESSION['success'])): ?>
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo $_SESSION['success']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    notifsuccess.play();
                    window.location.href = window.location.href;
                });
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            // Untuk popup error
            <?php if (isset($_SESSION['error'])): ?>
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo $_SESSION['error']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    notiferror.play();
                    window.location.href = window.location.href;
                });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>



            // Untuk delete button
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                   notifwarning.play();
                    Swal.fire({
                        title: 'Anda pasti?',
                        text: "Anda tidak boleh memulihkan data ini selepas dipadam!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, padam!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `../function/del-menu.php?id_menu=${id}`;
                        }
                    });
                });
            });

            // Untuk validation errors
            const showValidationError = (message) => {
                notiferror.play();
                Swal.fire({
                    icon: 'error',
                    title: 'Ralat',
                    text: message,
                    showConfirmButton: true
                });
            };

            // Form validation dengan SweetAlert
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    // Contoh validasi untuk fail
                    const fileInput = this.querySelector('input[type="file"]');
                    if (fileInput && fileInput.files.length > 0) {
                        const file = fileInput.files[0];
                        const acceptedTypes = fileInput.accept.split(',');

                        if (fileInput.accept.includes('.txt')) {
                            if (!file.name.endsWith('.txt')) {
                                e.preventDefault();
                                showValidationError('Sila pilih fail .txt sahaja');
                                return;
                            }
                        } else if (fileInput.accept.includes('image')) {
                            if (!file.type.startsWith('image/')) {
                                e.preventDefault();
                                showValidationError('Sila pilih fail gambar sahaja');
                                return;
                            }
                        }
                    }
                });
            });
        });

        // Untuk error handling pada dropzone
        function handleDropzoneError(message) {
            notiferror.play();
            Swal.fire({
                icon: 'error',
                title: 'Ralat',
                text: message,
                showConfirmButton: true
            });
        }

        // Update dropzone error handling
        function setupDropzone(dropzoneId, inputId, previewId = null, previewContainerId = null, closePreviewId = null, acceptType = null) {
            const dropzone = document.getElementById(dropzoneId);
            const input = document.getElementById(inputId);
            const previewContainer = previewContainerId ? document.getElementById(previewContainerId) : null;
            const closePreview = closePreviewId ? document.getElementById(closePreviewId) : null;

            function showPreview(file) {
                if (acceptType === 'image/*' && file.type.startsWith('image/')) {
                    // Logik untuk preview gambar
                    const reader = new FileReader();
                    reader.onload = function() {
                        const preview = document.getElementById(previewId);
                        if (previewContainer) {
                            previewContainer.style.display = 'flex';
                        }
                        preview.src = reader.result;
                        dropzone.style.display = 'none';
                    }
                    reader.readAsDataURL(file);
                } else if (acceptType === '.txt' && file.name.endsWith('.txt')) {
                    // Logik untuk fail txt
                    dropzone.style.display = 'none';
                    if (previewContainer) {
                        previewContainer.style.display = 'flex';
                        const fileName = document.getElementById('fileName');
                        if (fileName) {
                            fileName.textContent = file.name;
                        }
                    }
                }
            }

            // Handle file input change
            input.addEventListener('change', (e) => {
                if (e.target.files && e.target.files[0]) {
                    const file = e.target.files[0];
                    if ((acceptType === '.txt' && file.name.endsWith('.txt')) ||
                        (acceptType === 'image/*' && file.type.startsWith('image/'))) {
                        showPreview(file);
                    } else {
                        handleDropzoneError('Sila pilih fail yang betul: ' + (acceptType === '.txt' ? '.txt sahaja' : 'gambar sahaja'));
                        input.value = '';
                    }
                }
            });

            // Handle drag and drop
            dropzone.addEventListener('click', () => input.click());

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.add('dragover');
                });
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, () => {
                    dropzone.classList.remove('dragover');
                });
            });

            dropzone.addEventListener('drop', (e) => {
                const file = e.dataTransfer.files[0];
                if ((acceptType === '.txt' && file.name.endsWith('.txt')) ||
                    (acceptType === 'image/*' && file.type.startsWith('image/'))) {
                    input.files = e.dataTransfer.files;
                    showPreview(file);
                } else {
                    handleDropzoneError('Sila pilih fail yang betul: ' + (acceptType === '.txt' ? '.txt sahaja' : 'gambar sahaja'));
                }
            });

            // Handle close preview button
            if (closePreview) {
                closePreview.addEventListener('click', () => {
                    previewContainer.style.display = 'none';
                    dropzone.style.display = 'block';
                    input.value = ''; // Reset input file
                });
            }
        }

        // Setup semua dropzone apabila dokumen siap
        document.addEventListener('DOMContentLoaded', () => {
            setupDropzone('uploadDropzone', 'file', null, 'fileDisplay', 'removeFile', '.txt');
            setupDropzone('daftarDropzone', 'gambarDaftar', 'preview', 'daftarPreviewContainer', 'closeDaftarPreview', 'image/*');
            setupDropzone('kemaskiniDropzone', 'gambar', 'preview_kemas', 'previewContainer', 'closePreview', 'image/*');
        });
    </script>

</body>

</html>