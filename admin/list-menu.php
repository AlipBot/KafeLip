<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include('../function/autoKeluarAdmin.php'); # fail function auto logout jika pengguna belum login dan bukan admin
include('../function/connection.php');  # Sambung Ke database
//―――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\

# umpukan nilai pemboleh ubah
$tambahan = ""; # Query untuk pencarian makanan
$searchTerm = "";
$rekodSehalaman = 10; # Tetapkan bilangan rekod per halaman


# filter data atau sorting mengikut menaik atau menurun
$sort_order = isset($_GET['sort']) ? $_GET['sort'] : '';
if ($sort_order == 'asc') {
    $order_by = " ORDER BY kod_makanan ASC";
} elseif ($sort_order == 'desc') {
    $order_by = " ORDER BY kod_makanan DESC";
} else {
    $order_by = " ORDER BY makanan.nama_makanan";
}

# Dapatkan halaman semasa
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;

# Kira offset untuk query (formula kira untuk data pertama di halaman data ke berapa perlu bermula)
$offset = ($halaman - 1) * $rekodSehalaman;

# query memapaparkan semua list menu
$sql = "SELECT * FROM makanan";

# Semak pangilang get nama_makanan untuk fungsi pencarian menu
if (isset($_GET['nama_makanan']) && !empty($_GET['nama_makanan'])) {
    $nama_makanan = $_GET['nama_makanan'];
    $sql .= " WHERE nama_makanan LIKE '%$nama_makanan%'"; # tambah di query sql papar semua makanan
}

# Tambah order by jika ada
if (isset($_GET['sort']) && $_GET['sort'] == 'desc') {
    $sql .= " ORDER BY kod_makanan DESC";
} elseif (isset($_GET['sort']) && $_GET['sort'] == 'asc') {
    $sql .= " ORDER BY kod_makanan ASC";
} else {
    $sql .= " ORDER BY nama_makanan"; #jika tiada pangilan get makanan set campur
}

# Query untuk kira jumlah rekod
$sql_total = $sql; # umpukkan nama varible baru sql kepada sql_total
$result_total = mysqli_query($condb, $sql_total);
$jumlahRekod = mysqli_num_rows($result_total);

# Kira jumlah halaman 
$jumlahHalaman = ceil($jumlahRekod / $rekodSehalaman);

# Tambah LIMIT dan OFFSET pada query utama 
# limit untuk berapa data je keluar dalam satu halaman dan  offset untuk data ke berapa yang perlu dimula di halaman tersebut
$sql .= " LIMIT $rekodSehalaman OFFSET $offset";
$laksana = mysqli_query($condb, $sql);

// Function untuk generate kod_makanan baru di borang daftar menu
$sql_latest = "SELECT MAX(CAST(SUBSTRING(kod_makanan, 3) AS UNSIGNED)) as max_id FROM makanan";
$result_latest = mysqli_query($condb, $sql_latest);
$row = mysqli_fetch_assoc($result_latest);
$next_id = 'R-' . str_pad(($row['max_id'] + 1), 3, '0', STR_PAD_LEFT);

//――――――――――――――――――――――――――――――――――――――― ┏  KAWALAN POST  ┓ ――――――――――――――――――――――――――――――――――――――― \\
#jika post daftarmenu wujud atau button daftar menu ditekan
if (isset($_POST['DaftarMenu'])) {

    # Mengambil data daripada borang (form)
    $kod_makanan = $_POST['kod_makanan'];
    $nama_makanan = $_POST['nama_makanan'];
    $harga = $_POST['harga'];

    # Mengambil data gambar
    $file_extension = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
    # Buang jarak dan tukar kepada huruf kecil
    #buat nama file gamabr berdasarkan nama makanan
    $timestamp = date('Ymd_His'); // Format: YYYYMMDD_HHMMSS
    $nama_fail_baru = strtolower(str_replace(' ', '', $nama_makanan)) . '_' . $timestamp . '.' . $file_extension;
    $lokasi = $_FILES['gambar']['tmp_name'];

    # Data validation : had atas
    if (!is_numeric($harga) || $harga < 0) {
        $_SESSION['error'] = "Ralat: Sila masukkan harga yang sah";
        header("Location: list-menu.php");
        exit();
    }
    # Data validation : had atas harga
    if ($harga > 9999.99) {
        $_SESSION['error'] = "Ralat: Harga maksimum adalah RM9999.99";
        header("Location: ../admin/list-menu.php");
        exit();
    }

    # Data validation : had atas
    if (strlen($nama_makanan) > 30 or strlen($nama_makanan) < 3) {
        $_SESSION['error'] = "Maksimum pajang nama 30 sahaja dan minimum 3 aksara";
        header("Location: ../admin/list-menu.php");
        exit();
    }

    # Semak id_menu dah wujud atau belum
    $sql_semak = "select kod_makanan from makanan where kod_makanan = '$kod_makanan' ";
    $laksana_semak = mysqli_query($condb, $sql_semak);
    if (mysqli_num_rows($laksana_semak) == 1) {
        $_SESSION['error'] = "ID Menu telah digunakan. Sila guna kod makanan yang lain";
        header("Location: list-menu.php");
        exit();
    }

    # Semak nama_makanan dah wujud atau belum
    $sql_semak = "select nama_makanan from makanan where nama_makanan = '$nama_makanan' ";
    $laksana_semak = mysqli_query($condb, $sql_semak);
    if (mysqli_num_rows($laksana_semak) == 1) {
        $_SESSION['error'] = "Nama Menu telah digunakan. Sila guna nama makanan yang lain";
        header("Location: list-menu.php");
        exit();
    }

    # proses menyimpan data
    $sql_simpan = "insert into makanan set
                    kod_makanan     = '$kod_makanan',
                    nama_makanan   = '$nama_makanan',
                    harga       = '$harga',
                    gambar      = '$nama_fail_baru'
                ";
    $laksanamenu = mysqli_query($condb, $sql_simpan);


    # Pengujian proses menyimpan data 
    if ($laksanamenu) {
        // Copy files gambar ke folder menu-images
        if (!copy($lokasi, "../menu-images/" . $nama_fail_baru)) {
            #jika gagal
            $_SESSION['error'] = "Gagal memuat naik gambar";
            header("Location: ../admin/list-menu.php");
            exit();
        }

        $_SESSION['success'] = "Pendaftaran Berjaya";
        header("Location: list-menu.php");
        exit();
    } else {
        $_SESSION['error'] = "Pendaftaran Gagal: " . mysqli_error($condb);
        header("Location: list-menu.php");
        exit();
    }
}


# semak post upload jika butang upload menu form
if (isset($_POST['upload'])) {

    # mengambil nama sementara fail
    $namafailsementara = $_FILES['data']['tmp_name'];
    $namafail = $_FILES['data']['name'];
    $jenisfail = pathinfo($namafail, PATHINFO_EXTENSION);

    # menguji jenis fail dan sail fail 
    if ($_FILES["data"]["size"] > 0 and $jenisfail == "txt") {
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
            $id_menu = trim($data[0]);
            $nama_menu = trim($data[1]);
            $harga = trim($data[2]);
            # Data validation : had atas
            if (!is_numeric($harga) || $harga < 0) {
                $_SESSION['error'] = "Ralat: Sila masukkan harga yang sah";
                header("Location: list-menu.php");
                exit();
            }
            # Data validation : had atas harga
            if ($harga > 9999.99) {
                $_SESSION['error'] = "Ralat: Harga maksimum adalah RM9999.99";
                header("Location: ../admin/list-menu.php");
                exit();
            }

            # Data validation : had atas
            if (strlen($nama_menu) > 30 or strlen($nama_menu) < 3) {
                $_SESSION['error'] = "Maksimum pajang nama 30 sahaja dan minimum 3 aksara";
                header("Location: ../admin/list-menu.php");
                exit();
            }

            # semak jika id menu telah ada dalam  pangkalan data
            $pilih = mysqli_query($condb, "select* from makanan where kod_makanan='" . $id_menu . "'");
            if (mysqli_num_rows($pilih) == 1) {
                $_SESSION['error'] = "ID menu $id_menu telah digunakan sila tukar lain";
                header("Location: list-menu.php");
                exit();
            } else {
                # arahan SQL untuk menyimpan data
                $sql_simpan = "insert into makanan set
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
            $_SESSION['error'] = "ID menu $id_menu telah digunakan sila tukar lain";
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
<html lang="ms">

</html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Makanan</title>
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
    <link rel="stylesheet" href="../lib/css/css2.css" />
    <script src="../lib/js/tailwind.js"></script>
    <link rel="stylesheet" href="../lib/css/sweetalert2.min.css">
    <script src="../lib/js/sweetalert2@11.js"></script>
    <link rel="stylesheet" href="../lib/css/cropper.min.css">
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

        .dropzone:hover {
            border-color: #428D41;
            background: #f0f9f0;
        }

        .dropzone.dragover {
            background: #e1f5fe;
            border-color: #428D41;
        }

        /* Tambah style untuk keadaan normal selepas drop */
        .dropzone:not(.dragover) {
            border-color: #ccc;
            background: #f9f9f9;
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

        .crop-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
        }

        .crop-container {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            max-width: 90%;
            max-height: 90%;
        }

        .crop-preview {
            max-width: 100%;
            max-height: 70vh;
        }

        .crop-buttons {
            margin-top: 1rem;
            text-align: center;
        }

        .error-input {
            border-width: 2px !important;
            border-color: red !important;
            background-color: #fff2f2 !important;
        }

        .error-message {
            color: red;
            font-size: 0.8rem;
            margin-top: -0.5rem;
            margin-bottom: 0.5rem;
        }

        /* Tambah style untuk input */
        input[type="text"],
        input[type="number"],
        input[type="file"] {
            border-width: 2px;
        }

        /* Tambah style untuk input search */
        input[type="text"][name="nama_makanan"] {
            border-width: 2px;
        }

        /* Style untuk input focus */
        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="file"]:focus,
        input[type="email"]:focus,
        input[type="tel"]:focus,
        input[type="password"]:focus,
        select:focus {
            border-width: 1px;
            outline: none;
            border-color: #3b82f6; /* Warna biru */
            box-shadow: 0 0 0 1px #3b82f6; /* Tambah shadow untuk efek lebih jelas */
        }

        /* Pastikan style focus tidak terganggu dengan style error */
        .error-input:focus {
            border-color: red !important;
            box-shadow: 0 0 0 1px red !important;
        }
    </style>

</head>

<body class="font-roboto bg-gray-100">

    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-[#428D41] text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-[#3a5a40] text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Senarai Makanan KafeLip</div>
            <div class="w-12"></div>
        </header>

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
                        <span>Senarai Makanan</span>
                    </div>
                    <div class="text-center text-gray-600 mb-4">
                        <div class="flex items-center justify-between space-x-5">
                            <form action="list-menu.php" method="GET" class="py-5 flex items-center space-x-2 w-full">
                                <input type="text" name="nama_makanan" placeholder="Carian Menu"
                                    value="<?php echo htmlspecialchars($_GET['nama_makanan'] ?? ''); ?>"
                                    class="border rounded-2xl p-2 w-2/5">
                                <button type="submit"
                                    class="bg-[#428D41] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                <button type="button" onclick="window.location.href='list-menu.php';"
                                    class="bg-red-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </button>
                            </form>
                            <div class="flex space-x-2">
                                <button id="DaftarMenuButton"
                                    class="bg-[#428D41] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Daftar Menu
                                </button>
                                <button id="uploadButton"
                                    class="bg-[#428D41] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Muat Naik Menu
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-[#A4D153] font-bold text-black">
                                    <th width='20%' class="px-[47px] py-2">
                                        <div class="flex items-center justify-between">
                                            <span>Kod Menu</span>
                                            <div class="flex ml-2">
                                                <?php
                                                $current_sort = isset($_GET['sort']) ? $_GET['sort'] : 'default';
                                                $next_sort = ($current_sort == 'desc') ? 'asc' : 'desc';
                                                $icon_class = ($current_sort == 'desc') ? 'fa-regular fa-arrow-up-1-9' : 'fa-regular fa-arrow-down-9-1';
                                                $title_text = ($current_sort == 'desc') ? 'Susun Menaik' : 'Susun Menurun';
                                                ?>
                                                <a href="?sort=<?= $next_sort ?><?= !empty($_GET['nama_makanan']) ? '&nama_makanan=' . $_GET['nama_makanan'] : '' ?>"
                                                    class="bg-[#3a5a40] text-white px-3 py-2 rounded hover:bg-[#68B0AB] transition-colors"
                                                    title="<?= $title_text ?>">
                                                    <i class="<?= $icon_class ?>"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </th>
                                    <th width='30%' class="px-[47px] py-2">Gambar</th>
                                    <th width='30%' class="px-[47px] py-2">Nama Menu</th>
                                    <th width='20%' class="px-[47px] py-2">Harga (RM)</th>
                                    <th width='20%' class="px-[47px] py-2">Tindakan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($laksana) > 0) { ?>
                                    <?php while ($m = mysqli_fetch_assoc($laksana)) { ?>
                                        <tr class='bg-white border-b hover:bg-blue-50'>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['kod_makanan']); ?>
                                            </td>
                                            <td class='px-8 py-4 flex justify-center items-center'>
                                                <div class="relative group">
                                                    <div class="w-32 h-32 overflow-hidden">
                                                        <img src='../menu-images/<?php echo htmlspecialchars($m['gambar']); ?>'
                                                            alt='Gambar menu <?php echo htmlspecialchars($m['nama_makanan']); ?>'
                                                            class="w-full h-full object-cover rounded-md cursor-pointer transition-opacity group-hover:opacity-50">
                                                    </div>
                                                    <div
                                                        class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                        <i onclick="showImagePopup('../menu-images/<?php echo htmlspecialchars($m['gambar']); ?>', '<?php echo htmlspecialchars($m['nama_makanan']); ?>')"
                                                            class="fas fa-eye text-3xl text-white bg-black bg-opacity-50 p-3 rounded-full"></i>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class='px-4 py-2 text-center'>
                                                <?php echo htmlspecialchars($m['nama_makanan']); ?>
                                            </td>
                                            <td class='px-4 py-2 text-center'>RM <?php echo number_format($m['harga'], 2); ?>
                                            </td>
                                            <td class='px-4 py-2 text-center'>
                                                <div class="flex flex-col items-center space-y-4">
                                                    <button onclick="updateMenu('<?= $m['kod_makanan'] ?>')"
                                                        class="bg-[#428D41] hover:bg-[#68B0AB] text-white py-2 px-4 rounded flex items-center justify-center">
                                                        <i class="fas fa-edit mr-1"></i> Kemaskini
                                                    </button>
                                                    <button data-id="<?php echo urlencode($m['kod_makanan']); ?>"
                                                        data-nama_makanan="<?php echo htmlspecialchars($m['nama_makanan']); ?>"
                                                        class="delete-btn bg-red-800 text-white py-2 px-7 rounded flex items-center justify-center">
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
                    <div class="pagination-container flex justify-center items-center space-x-2 mt-4">
                        <?php if ($jumlahHalaman > 1): ?>
                            <!-- First Page -->
                            <?php if ($halaman > 1): ?>
                                <a href="?halaman=1<?= isset($_GET['nama_makanan']) ? '&nama_makanan=' . $_GET['nama_makanan'] : '' ?><?= isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '' ?>"
                                    class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Previous Page -->
                            <?php if ($halaman > 1): ?>
                                <a href="?halaman=<?= $halaman - 1 ?><?= isset($_GET['nama_makanan']) ? '&nama_makanan=' . $_GET['nama_makanan'] : '' ?><?= isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '' ?>"
                                    class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $halaman - 2);
                            $end = min($jumlahHalaman, $halaman + 2);

                            for ($i = $start; $i <= $end; $i++): ?>
                                <a href="?halaman=<?= $i ?><?= isset($_GET['nama_makanan']) ? '&nama_makanan=' . $_GET['nama_makanan'] : '' ?><?= isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '' ?>"
                                    class="px-3 py-1 <?= $i == $halaman ? 'bg-[#68B0AB] text-white' : 'bg-[#428D41] text-white hover:bg-[#68B0AB]' ?> rounded">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next Page -->
                            <?php if ($halaman < $jumlahHalaman): ?>
                                <a href="?halaman=<?= $halaman + 1 ?><?= isset($_GET['nama_makanan']) ? '&nama_makanan=' . $_GET['nama_makanan'] : '' ?><?= isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '' ?>"
                                    class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Last Page -->
                            <?php if ($halaman < $jumlahHalaman): ?>
                                <a href="?halaman=<?= $jumlahHalaman ?><?= isset($_GET['nama_makanan']) ? '&nama_makanan=' . $_GET['nama_makanan'] : '' ?><?= isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '' ?>"
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

        <!-- Footer -->
        <footer class="bg-[#428D41] text-white p-4 text-center w-full">
            &copy; 2025 KAFELIP. Semua hak terpelihara.
        </footer>
    </div>

    <!-- menu -->
    <div id="uploadmenu" class="menu">
        <div class="menu-content">
            <span onclick="uploadmenu.style.display = 'none';" class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Muat Naik Menu</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700">Pilih fail txt :</label>
                    <div class="dropzone" id="uploadDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Seret fail txt ke sini atau klik untuk memilih</p>
                        <input type="file" id="file" name='data' accept=".txt" class="hidden">
                    </div>
                    <!-- Tambah elemen baru untuk paparan nama fail -->
                    <div id="fileDisplay" class="hidden p-3 bg-gray-100 rounded md:flex justify-between items-center">
                        <span id="fileName" class="text-gray-700"></span>
                        <button type="button" id="removeFile" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name='upload' id="uploadMenuBtn"
                        class="bg-gray-400 text-white p-2 rounded cursor-not-allowed" disabled>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Daftar Menu -->
    <div id="DaftarMenu" class="DaftarMenu">
        <div class="DaftarMenu-content">
            <span onclick="DaftarMenu.style.display = 'none';" class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Pendaftaran Menu Baru</h2>
            <form action="" method="POST" enctype="multipart/form-data" id="daftarMenuForm">
                <div class="mb-4">
                    <label class="block text-gray-700">Sila Lengkapkan Maklumat di bawah</label>
                    ID Menu :
                    <input type="text" name='kod_makanan' id="kod_makanan" class="w-full border p-2 mb-3"
                        value="<?php echo $next_id; ?>" maxlength="5" required>
                    <span id="kod_makanan_error" class="error-message hidden">ID Menu telah digunakan. Sila guna kod
                        menu yang lain</span><br>
                    Nama Menu : <input type="text" name='nama_makanan' id="nama_makanan_daftar"
                        class="w-full border p-2 mb-3" required minlength="3" maxlength="30" pattern=".{3,30}"
                        title="Nama menu mesti antara 3 hingga 30 aksara"
                        oninput="this.value = this.value.slice(0, 30)">
                    Harga (RM) : <input type='number' name='harga' id="harga_daftar" step='0.01'
                        class="w-full border p-2 mb-3" required min="0" max="9999.99"
                        oninput="if(this.value > 9999.99) this.value = 9999.99;">
                    <label class="block text-black">Sila Pilih Gambar Menu : </label>

                    <!-- Container untuk preview gambar -->
                    <div class="flex justify-center mb-4 relative" id="daftarPreviewContainer" style="display: none;">
                        <img id="preview" style="max-width: 300px;">
                        <button type="button" id="closeDaftarPreview"
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
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
                    <button type="submit" name='DaftarMenu' id="daftarMenuBtn"
                        class="bg-gray-400 text-white p-2 rounded cursor-not-allowed" disabled>Daftar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Kemaskini Menu -->
    <div id="KemaskiniMenu" class="KemaskiniMenu">
        <div class="KemaskiniMenu-content">
            <span onclick="Kemaskinimenu.style.display = 'none';" class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Kemaskini Menu Baru</h2>
            <form action="../function/update-menu.php" method="POST" enctype="multipart/form-data">
                <!-- Input tersembunyi untuk parameter URL -->
                <input type="hidden" name="current_page" value="<?php echo isset($_GET['halaman']) ? $_GET['halaman'] : '1'; ?>">
                <input type="hidden" name="search_query" value="<?php echo isset($_GET['nama_makanan']) ? htmlspecialchars($_GET['nama_makanan']) : ''; ?>">
                <input type="hidden" name="sort" value="<?php echo isset($_GET['sort']) ? htmlspecialchars($_GET['sort']) : ''; ?>">
                
                <div class="mb-4">
                    <label class="block text-gray-700">Sila Lengkapkan Maklumat di bawah</label>
                    <input type="hidden" name="id_menu" id="id_menu">
                    <input type="hidden" id="original_nama_makanan" name="original_nama_makanan">
                    <input type="hidden" id="original_harga_makanan" name="original_harga_makanan">
                    Nama Menu : <input id="nama_makanan" type="text" name='nama_menu' class="w-full border p-2 mb-3"
                        minlength="3" maxlength="30" pattern=".{3,30}" title="Nama menu mesti antara 3 hingga 30 aksara"
                        oninput="this.value = this.value.slice(0, 30)">
                    Harga (RM) : <input id="harga_makanan" type='number' name='harga' step='0.01'
                        class="w-full border p-2 mb-3" min="0" max="9999.99"
                        oninput="if(this.value > 9999.99) this.value = 9999.99;" required>
                    <label class="block text-gray-700">Sila Pilih Gambar Menu (jika ingin diubah) : </label>
                    <div class="flex justify-center mb-4 relative" id="previewContainer" style="display: none;">
                        <img id="preview_kemas" style="max-width: 300px;">
                        <button type="button" id="closePreview"
                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center">
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
                    <button type="submit" name='DaftarMenu' id="kemaskiniMenuBtn"
                        class="bg-gray-400 text-white p-2 rounded cursor-not-allowed" disabled>
                        Kemaskini
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Image Popup Modal -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
        <div class="relative max-w-2xl mx-auto p-4">
            <button onclick="closeImagePopup()"
                class="absolute top-0 right-0 -mt-[14.5px] -mr-[14px] text-white text-3xl font-bold hover:text-gray-300">&times;</button>
            <div class="max-h-[70vh] max-w-[600px]">
                <img id="popupImage" src="" alt="" class="w-full h-[70%] rounded-md object-contain">
            </div>
            <p id="imageCaption" class="text-white text-center mt-4 text-lg"></p>
        </div>
    </div>

    <!-- Popup crop image  -->
    <div id="cropModal" class="crop-modal">
        <div class="crop-container">
            <img id="cropImage" class="crop-preview">
            <div class="crop-buttons">
                <button type="button" id="cropDone" class="bg-[#428D41] text-white p-2 rounded mr-2">
                    <i class="fas fa-check mr-1"></i> Selesai
                </button>
                <button type="button" id="cropCancel" class="bg-red-500 text-white p-2 rounded">
                    <i class="fas fa-times mr-1"></i> Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Butang scroll ke atas -->
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up"></i>
    </button>

    <!-- script Cropper.js  -->
    <script src="../lib/js/cropper.min.js"></script>

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
        // script drawer
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

            reader.onload = function () {
                var imgElement = document.getElementById('preview');
                imgElement.src = reader.result;
                imgElement.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]); // Baca fail sebagai URL
        }

        function previewGambarKemas(event) {
            var input = event.target;
            var reader = new FileReader();

            reader.onload = function () {
                var imgElement = document.getElementById('preview_kemas');
                imgElement.src = reader.result;
                imgElement.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]); // Baca fail sebagai URL
        }

        // menu functionality
        const menu = document.getElementById("uploadmenu");
        const Daftarmenu = document.getElementById("DaftarMenu");
        const Kemaskinimenu = document.getElementById("KemaskiniMenu");
        const btnDaftarmenu = document.getElementById("DaftarMenuButton");
        const btn = document.getElementById("uploadButton");


        btn.onclick = function () {
            menu.style.display = "block";
        }

        btnDaftarmenu.onclick = function () {
            Daftarmenu.style.display = "block";
        }


        window.onclick = function (event) {
            if (event.target == Kemaskinimenu) {
                Kemaskinimenu.style.display = "none";
            }
            if (event.target == Daftarmenu) {
                Daftarmenu.style.display = "none";
            }
            if (event.target == menu) {
                menu.style.display = "none";
            }

        }

        const notifsuccess = new Audio('../lib/audio/notif.mp3'); // Path fail audio success
        const notiferror = new Audio('../lib/audio/error.mp3'); // Path fail audio ralat
        const notifinfo = new Audio('../lib/audio/info.mp3'); //  Path fail audio info
        const notifwarning = new Audio('../lib/audio/warning.mp3'); // Path fail audio amaran

        document.addEventListener('DOMContentLoaded', function () {
            // Untuk popup success
            <?php if (isset($_SESSION['success'])): ?>
                notifsuccess.play();
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo $_SESSION['success']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = window.location.href;
                });
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            // Untuk popup error
            <?php if (isset($_SESSION['error'])): ?>
                notiferror.play();
                Swal.fire({
                    icon: 'error',
                    title: '<?php echo $_SESSION['error']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = window.location.href;
                });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            // Untuk delete button
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const nama_makanan = this.dataset.nama_makanan;
                    notifwarning.play();
                    
                    Swal.fire({
                        title: 'Anda pasti?',
                        text: `Anda akan memadam ${nama_makanan} dan tidak dapat memulihkannya!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, padam!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Dapatkan parameter URL semasa
                            const currentPage = new URLSearchParams(window.location.search).get('halaman') || '1';
                            const searchQuery = new URLSearchParams(window.location.search).get('nama_makanan') || '';
                            const sort = new URLSearchParams(window.location.search).get('sort') || '';
                            
                            // Bina URL dengan parameter
                            let deleteUrl = `../function/del-menu.php?id_menu=${id}`;
                            if (currentPage) deleteUrl += `&current_page=${currentPage}`;
                            if (searchQuery) deleteUrl += `&search_query=${encodeURIComponent(searchQuery)}`;
                            if (sort) deleteUrl += `&sort=${sort}`;
                            
                            window.location.href = deleteUrl;
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
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Faham',

                });
            };

            // Form validation dengan SweetAlert
            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', function (e) {
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
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Faham',
            });
        }

        // Fungsi untuk upload gambar
        function setupImageDropzone(dropzoneId, inputId, previewId = null, previewContainerId = null, closePreviewId = null) {
            const dropzone = document.getElementById(dropzoneId);
            const input = document.getElementById(inputId);
            const preview = previewId ? document.getElementById(previewId) : null;
            const previewContainer = previewContainerId ? document.getElementById(previewContainerId) : null;
            const closePreview = closePreviewId ? document.getElementById(closePreviewId) : null;
            const submitBtn = document.getElementById(inputId === 'gambarDaftar' ? 'daftarMenuBtn' : 'kemaskiniMenuBtn');

            function resetDropzone() {
                if (previewContainer) {
                    previewContainer.style.display = 'none';
                }
                dropzone.style.display = 'block';
                input.value = '';

                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }

            function handleImage(file) {
                if (!file.type.startsWith('image/')) {
                    notiferror.play();
                    Swal.fire({
                        icon: 'error',
                        title: 'Ralat',
                        text: 'Sila pilih fail gambar sahaja',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Faham'
                    });
                    resetDropzone();
                    return;
                }

                // Nyahaktifkan butang submit semasa proses crop
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');

                originalFile = file;
                const reader = new FileReader();
                reader.onload = function (e) {
                    const cropModal = document.getElementById('cropModal');
                    const cropImage = document.getElementById('cropImage');

                    cropModal.dataset.previewId = previewId;
                    cropModal.dataset.previewContainerId = previewContainerId;
                    cropModal.dataset.dropzoneId = dropzoneId;
                    cropModal.dataset.inputId = inputId;

                    cropModal.style.display = 'block';
                    cropImage.src = e.target.result;

                    if (cropper) {
                        cropper.destroy();
                    }

                    cropper = new Cropper(cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                        background: false
                    });
                }
                reader.readAsDataURL(file);
            }

            // Event listeners untuk image dropzone
            dropzone.addEventListener('click', () => input.click());

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            dropzone.addEventListener('dragover', (e) => {
                preventDefaults(e);
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', (e) => {
                preventDefaults(e);
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (e) => {
                preventDefaults(e);
                dropzone.classList.remove('dragover'); // Pastikan class dragover dibuang selepas drop
                const file = e.dataTransfer.files[0];
                if (file) {
                    handleImage(file);
                }
            });

            // Tambah event listener untuk mouse keluar dari dropzone
            dropzone.addEventListener('mouseleave', () => {
                dropzone.classList.remove('dragover');
            });

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                handleImage(file);
            });

            if (closePreview) {
                closePreview.addEventListener('click', resetDropzone);
            }
        }

        // Fungsi untuk upload fail txt
        function setupMenuUploadDropzone() {
            const dropzone = document.getElementById('uploadDropzone');
            const input = document.getElementById('file');
            const fileDisplay = document.getElementById('fileDisplay');
            const fileName = document.getElementById('fileName');
            const removeFile = document.getElementById('removeFile');
            const submitBtn = document.getElementById('uploadMenuBtn');

            function resetDropzone() {
                input.value = '';
                fileDisplay.classList.add('hidden');
                dropzone.style.display = 'block';
                fileName.textContent = '';
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }

            function handleFile(file) {
                if (file) {
                    if (!file.name.endsWith('.txt')) {
                        notiferror.play();
                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat',
                            text: 'Sila pilih fail .txt sahaja',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Faham'
                        });
                        resetDropzone();
                        return;
                    }

                    fileName.textContent = file.name;
                    fileDisplay.classList.remove('hidden');
                    dropzone.style.display = 'none';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    submitBtn.classList.add('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                }
            }

            // Event listeners untuk txt dropzone
            dropzone.addEventListener('click', () => input.click());

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, preventDefaults, false);
            });

            dropzone.addEventListener('dragover', (e) => {
                preventDefaults(e);
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', (e) => {
                preventDefaults(e);
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (e) => {
                preventDefaults(e);
                dropzone.classList.remove('dragover'); // Pastikan class dragover dibuang selepas drop
                const file = e.dataTransfer.files[0];
                if (file) {
                    const dataTransfer = new DataTransfer();
                    dataTransfer.items.add(file);
                    input.files = dataTransfer.files;
                    handleFile(file);
                }
            });

            // Tambah event listener untuk mouse keluar dari dropzone
            dropzone.addEventListener('mouseleave', () => {
                dropzone.classList.remove('dragover');
            });

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                handleFile(file);
            });

            removeFile.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                resetDropzone();
            });
        }

        // Helper function untuk kedua-dua dropzone
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        // Initialize dropzones
        document.addEventListener('DOMContentLoaded', () => {
            // Setup untuk upload gambar
            setupImageDropzone('daftarDropzone', 'gambarDaftar', 'preview', 'daftarPreviewContainer', 'closeDaftarPreview');
            setupImageDropzone('kemaskiniDropzone', 'gambar', 'preview_kemas', 'previewContainer', 'closePreview');

            // Setup untuk upload menu
            setupMenuUploadDropzone();
        });
    </script>

    <script>
        function showImagePopup(imageSrc, caption) {
            const modal = document.getElementById('imageModal');
            const popupImage = document.getElementById('popupImage');
            const imageCaption = document.getElementById('imageCaption');

            popupImage.src = imageSrc;
            imageCaption.textContent = caption;
            modal.classList.remove('hidden');
            modal.classList.add('flex');

            // Tutup modal bila klik di luar gambar
            modal.onclick = function (e) {
                if (e.target === modal) {
                    closeImagePopup();
                }
            }
        }

        function closeImagePopup() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Tutup modal dengan kekunci ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeImagePopup();
            }
        });
    </script>

    <script>
        let cropper = null;
        let originalFile = null;

        // Add crop modal handlers
        document.getElementById('cropDone').addEventListener('click', function () {
            if (!cropper) return;

            const cropModal = document.getElementById('cropModal');
            const inputId = cropModal.dataset.inputId;

            // Tukar format ke JPEG untuk konsistensi
            cropper.getCroppedCanvas().toBlob((blob) => {
                const preview = document.getElementById(cropModal.dataset.previewId);
                const previewContainer = document.getElementById(cropModal.dataset.previewContainerId);
                const dropzone = document.getElementById(cropModal.dataset.dropzoneId);
                const input = document.getElementById(cropModal.dataset.inputId);

                // Pastikan nama fail menggunakan extension .jpg
                const fileName = originalFile.name.replace(/\.[^/.]+$/, "") + ".jpg";

                // Create new file from blob
                const croppedFile = new File([blob], fileName, {
                    type: 'image/jpeg',
                    lastModified: new Date().getTime()
                });

                // Update file input dengan File API
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(croppedFile);
                input.files = dataTransfer.files;

                // Show preview
                preview.src = URL.createObjectURL(blob);
                previewContainer.style.display = 'flex';
                dropzone.style.display = 'none';

                // Close modal
                cropModal.style.display = 'none';
                cropper.destroy();
                cropper = null;

                // Tambah: Panggil checkFormCompletion selepas crop selesai
                if (inputId === 'gambarDaftar') {
                    setTimeout(checkFormCompletion, 100); // Slight delay to ensure file is properly set
                } else if (inputId === 'gambar') {
                    setTimeout(checkUpdateFormCompletion, 100);
                }
            }, 'image/jpeg', 0.8); // Specify JPEG format and quality
        });


        document.getElementById('cropCancel').addEventListener('click', function () {
            const cropModal = document.getElementById('cropModal');
            const inputId = cropModal.dataset.inputId;

            cropModal.style.display = 'none';
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
            document.getElementById(inputId).value = '';
        });
    </script>

    <script>
        function checkFormCompletion() {
            const kodMakanan = document.getElementById('kod_makanan').value;
            const namaMakanan = document.getElementById('nama_makanan_daftar').value;
            const harga = document.getElementById('harga_daftar').value;
            const gambar = document.getElementById('gambarDaftar').files[0];
            const daftarBtn = document.getElementById('daftarMenuBtn');

            // Periksa jika semua field telah diisi
            if (kodMakanan && namaMakanan && harga && harga > 0 && gambar) {
                // Aktifkan butang dan tukar style
                daftarBtn.disabled = false;
                daftarBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                daftarBtn.classList.add('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
            } else {
                // Nyahaktifkan butang dan tukar style
                daftarBtn.disabled = true;
                daftarBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                daftarBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }

        // Tambah event listeners untuk semua input
        document.getElementById('kod_makanan').addEventListener('input', checkFormCompletion);
        document.getElementById('nama_makanan_daftar').addEventListener('input', checkFormCompletion);
        document.getElementById('harga_daftar').addEventListener('input', checkFormCompletion);
        document.getElementById('gambarDaftar').addEventListener('change', checkFormCompletion);

        // Tambah event listener untuk reset form
        document.getElementById('closeDaftarPreview').addEventListener('click', function () {
            document.getElementById('gambarDaftar').value = '';
            checkFormCompletion();
        });

    </script>

    <script>
        // Fungsi untuk memeriksa borang upload menu
        function checkUploadFormCompletion() {
            const fileInput = document.getElementById('file');
            const uploadBtn = document.getElementById('uploadMenuBtn');

            if (fileInput && fileInput.files.length > 0) {
                uploadBtn.disabled = false;
                uploadBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                uploadBtn.classList.add('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
            } else {
                uploadBtn.disabled = true;
                uploadBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                uploadBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }

        // Add event listeners for upload menu form
        document.getElementById('file').addEventListener('change', checkUploadFormCompletion);
    </script>

    <script>
        // Fungsi untuk memeriksa borang kemaskini menu
        function checkUpdateFormCompletion() {
            const namaInput = document.getElementById('nama_makanan');
            const hargaInput = document.getElementById('harga_makanan');
            const gambarInput = document.getElementById('gambar');
            const kemaskiniBtn = document.getElementById('kemaskiniMenuBtn');

            const originalNama = document.getElementById('original_nama_makanan').value;
            const originalHarga = document.getElementById('original_harga_makanan').value;

            const hasNameChanged = namaInput.value !== originalNama;
            const hasPriceChanged = hargaInput.value !== originalHarga;
            const hasImageChanged = gambarInput.files[0];

            if ((hasNameChanged || hasPriceChanged || hasImageChanged) &&
                namaInput.value.trim() !== '' &&
                hargaInput.value.trim() !== '' &&
                parseFloat(hargaInput.value) > 0) {

                kemaskiniBtn.disabled = false;
                kemaskiniBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                kemaskiniBtn.classList.add('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
            } else {
                kemaskiniBtn.disabled = true;
                kemaskiniBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                kemaskiniBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            }
        }

        document.getElementById('closePreview').addEventListener('click', function () {
            document.getElementById('gambar').value = '';
            checkUpdateFormCompletion()
        });

        // Modify updateMenu function to set original values
        function updateMenu(kod_menu) {
            fetch(`../api/get-menu.php?kod_menu=${kod_menu}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('id_menu').value = kod_menu;
                    document.getElementById('nama_makanan').value = data.nama_makanan;
                    document.getElementById('harga_makanan').value = data.harga;

                    // Set original values
                    document.getElementById('original_nama_makanan').value = data.nama_makanan;
                    document.getElementById('original_harga_makanan').value = data.harga;

                    Kemaskinimenu.style.display = "block";
                    checkUpdateFormCompletion(); // Check initial state
                });
        }

        document.getElementById('gambar').addEventListener('change', checkUpdateFormCompletion);

        // Add event listeners for update menu form
        document.getElementById('nama_makanan').addEventListener('input', checkUpdateFormCompletion);
        document.getElementById('harga_makanan').addEventListener('input', checkUpdateFormCompletion);
        document.getElementById('gambar').addEventListener('change', checkUpdateFormCompletion);
    </script>

    <script>
        document.getElementById('kod_makanan').addEventListener('input', function () {
            const kodMakanan = this.value;
            const errorSpan = document.getElementById('kod_makanan_error');

            // Reset style
            this.classList.remove('error-input');
            errorSpan.classList.add('hidden');

            // Check kod_makanan in database
            fetch(`../api/check-kod-makanan.php?kod_makanan=${kodMakanan}`)
                .then(response => response.json())
                .then(data => {
                    if (data.wujud) {
                        this.classList.add('error-input');
                        errorSpan.classList.remove('hidden');
                    }
                    checkFormCompletion(); // Recheck form completion
                });
        });
    </script>

</body>

</html>