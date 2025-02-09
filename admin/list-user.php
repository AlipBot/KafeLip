<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include('../function/autoKeluarAdmin.php'); # fail function auto logout jika pengguna belum login dan bukan admin
include('../function/connection.php');  # Sambung Ke database
//――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\

$rekodSehalaman = 10; # Tetapkan bilangan rekod per halaman

// Dapatkan halaman semasa
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 1;

// Kira offset untuk query
$offset = ($halaman - 1) * $rekodSehalaman;

// Ubahsuai query untuk pagination
$sql = "SELECT * FROM pelanggan";

// Logik untuk carian nama
if (isset($_GET['nama']) && !empty($_GET['nama'])) {
    $nama = $_GET['nama'];
    $sql .= " WHERE nama LIKE '%$nama%'";
}

// Logik untuk filter tahap
if (isset($_GET['tapis_tahap']) && !empty($_GET['tapis_tahap'])) {
    $tapis_tahap = $_GET['tapis_tahap'];
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND tahap = '$tapis_tahap'";
    } else {
        $sql .= " WHERE tahap = '$tapis_tahap'";
    }
}

// Query untuk kira jumlah rekod
$sql_total = $sql;
$result_total = mysqli_query($condb, $sql_total);
$jumlahRekod = mysqli_num_rows($result_total);

// Kira jumlah halaman
$jumlahHalaman = ceil($jumlahRekod / $rekodSehalaman);

// Tambah LIMIT dan OFFSET pada query utama
$sql .= " LIMIT $rekodSehalaman OFFSET $offset";
$laksana = mysqli_query($condb, $sql);

//――――――――――――――――――――――――――――――――――――――― ┏  KAWALAN POST ┓ ――――――――――――――――――――――――――――――――――――――― \\

# POST data upload pekerja form
if (isset($_POST['upload'])) {
    $namafailsementara = $_FILES["data_pengguna"]["tmp_name"];
    $namafail = $_FILES['data_pengguna']['name'];
    $jenisfail = pathinfo($namafail, PATHINFO_EXTENSION);

    if ($_FILES["data_pengguna"]["size"] > 0 and $jenisfail == "txt") {
        $fail_data_pengguna = fopen($namafailsementara, "r");
        $bil = 0;

        while (!feof($fail_data_pengguna)) {
            $ambilbarisdata = fgets($fail_data_pengguna);
            $pecahkanbaris = explode("|", $ambilbarisdata);
            list($email, $notel, $nama, $katalaluan) = $pecahkanbaris;

            $pilih = mysqli_query($condb, "select* from pelanggan where notel = '" . $notel . "'");
            $pilih2 = mysqli_query($condb, "select* from pelanggan where email = '" . $email . "'");

            if (mysqli_num_rows($pilih) == 1) {
                $_SESSION['error'] = "notel $notel di fail txt telah ada di pangkalan data. TUKAR NOTEL DALAM FAIL TXT";
                header("Location: list-user.php");
                exit();
            } elseif (mysqli_num_rows($pilih2) == 1) {
                $_SESSION['error'] = "email $email di fail txt telah ada di pangkalan data. TUKAR EMAIL DALAM FAIL TXT";
                header("Location: list-user.php");
                exit();
            } else {
                $arahan_sql_simpan = "insert into pelanggan (email, notel, nama, password, tahap) values ('$email','$notel','$nama','$katalaluan','ADMIN')";
                mysqli_query($condb, $arahan_sql_simpan);
                $bil++;
            }
        }
        fclose($fail_data_pengguna);

        if (mysqli_num_rows($pilih) == 1) {
            $_SESSION['error'] = "notel $notel di fail txt telah ada di pangkalan data. TUKAR NOTEL DALAM FAIL TXT";
            header("Location: list-user.php");
            exit();
        } elseif (mysqli_num_rows($pilih2) == 1) {
            $_SESSION['error'] = "email $email di fail txt telah ada di pangkalan data. TUKAR EMAIL DALAM FAIL TXT";
            header("Location: list-user.php");
            exit();
        } else {
            $_SESSION['success'] = "Import fail Data Selesai. Sebanyak $bil data telah disimpan";
            header("Location: list-user.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Hanya fail berformat txt sahaja dibenarkan";
        header("Location: list-user.php");
        exit();
    }
}
?>



<html lang="ms">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pengguna KafeLip</title>
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

        .password {
            display: inline;
            /* Show by default */
        }

        .KemaskiniPengguna,
        .pekerja {
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

        .kemaskiniPengguna-content,
        .pekerja-content {
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

        .dropzone i {
            font-size: 2em;
            color: #666;
            margin-bottom: 10px;
            display: block;
        }

        .dropzone p {
            margin: 0;
            color: #666;
        }

        .dropzone.dragover {
            background: #e1f5fe;
            border-color: #428D41;
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
    </style>
</head>

<body class="font-roboto bg-gray-100">

    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-[#428D41] text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-[#3a5a40] text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Senarai Pelanggan Dan Pekerja KafeLip</div>
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
            <div id="mainContent" class="flex-1 p-6 transition-all duration-300 content-expanded">
                <div class="senarai-menu bg-white p-6 rounded-lg shadow relative">
                    <div class="text-[30px] font-bold mb-4 flex justify-between items-center">
                        <span>Senarai Pelanggan Dan Pekerja</span>
                    </div>
                    <div class="text-center text-gray-600 mb-4">
                        <div class="flex items-center justify-between space-x-5">
                            <form action="list-user.php" method="GET" class="py-5 flex items-center space-x-2 w-full">
                                <input type="text" name="nama" placeholder="Carian Nama pengguna"
                                    value="<?= $_GET['nama'] ?>" class="border rounded p-2 w-2/5">
                                <select name="tapis_tahap" class="border p-2 rounded ">
                                    <option value="">Semua</option>
                                    <option value="ADMIN" <?php if (isset($_GET['tapis_tahap']) && $_GET['tapis_tahap'] == 'ADMIN')
                                        echo 'selected'; ?>>Admin</option>
                                    <option value="PELANGGAN" <?php if (isset($_GET['tapis_tahap']) && $_GET['tapis_tahap'] == 'PELANGGAN')
                                        echo 'selected'; ?>>Pelanggan</option>
                                </select>
                                <button type="submit"
                                    class="bg-[#428D41] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                <button type="button" onclick="window.location.href='list-user.php';"
                                    class="bg-red-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-redo mr-1"></i> Reset
                                </button>
                            </form>
                            <div class="flex space-x-2">
                                <button id="uploadButton"
                                    class="bg-[#428D41] text-white p-2 hover:bg-[#68B0AB] rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Muat Naik Pekerja
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-[#A4D153] font-bold text-black">
                                    <td width='30%' class="px-[70px] py-2">Nama</td>
                                    <td width='15%' class="text-center py-2">Email</td>
                                    <td width='15%' class="text-center  py-2">Nombor Telefon</td>
                                    <td width='20%' class="text-center  py-2">Kata laluan</td>
                                    <td width='15%' class="text-center  py-2">Tahap</td>
                                    <td width='10%' class="text-center  py-2">Tindakan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (mysqli_num_rows($laksana) > 0) { ?>
                                    <?php while ($m = mysqli_fetch_array($laksana)) { ?>
                                        <tr class='bg-white border-b hover:bg-blue-50'>
                                            <td class='px-4 py-2 '><?php echo htmlspecialchars($m['nama']); ?></td>
                                            <td class='px-4 py-2 '><?php echo htmlspecialchars($m['email']); ?></td>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['notel']); ?></td>
                                            <td class='px-4 py-2 text-center'>
                                                <span class="password" id="password-<?php echo $m['notel']; ?>"
                                                    style="display: none;"><?php echo htmlspecialchars($m['password']); ?></span>
                                                <span class="hidden-password"
                                                    id="hidden-password-<?php echo $m['notel']; ?>">********</span>
                                                <i class="fas fa-eye cursor-pointer"
                                                    onclick="togglePasswordVisibility('<?php echo $m['notel']; ?>')"
                                                    id="eye-icon-<?php echo $m['notel']; ?>"></i>
                                            </td>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['tahap']); ?></td>

                                            <td class='px-4 py-2 text-center'>
                                                <div class="flex flex-col items-center space-y-4">
                                                    <button onclick="SemakProfil('<?= $m['email'] ?>')"
                                                        class="bg-[#428D41] text-white py-2 px-6 w-32 rounded hover:bg-[#68B0AB] flex items-center justify-center">
                                                        <i class="fas fa-user mr-1"></i> Profil
                                                    </button>
                                                    <button onclick="updateUser('<?= $m['notel'] ?>', '<?= $m['email'] ?>')"
                                                        class="bg-[#428D41] text-white py-2 px-6 w-32 rounded hover:bg-[#68B0AB] flex items-center justify-center">
                                                        <i class="fas fa-edit mr-1"></i> Kemaskini
                                                    </button>
                                                    <button data-namauser="<?= $m['nama'] ?>"
                                                        data-id="<?php echo urlencode($m['notel']); ?>"
                                                        class="delete-btn bg-red-800 text-white py-2 px-6 w-32 rounded  flex items-center justify-center">
                                                        <i class="fas fa-trash mr-1"></i> Hapus
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                <?php } else { ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-6 text-gray-500">
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
                                <a href="?halaman=1<?= isset($_GET['nama']) ? '&nama=' . $_GET['nama'] : '' ?><?= isset($_GET['tapis_tahap']) ? '&tapis_tahap=' . $_GET['tapis_tahap'] : '' ?>"
                                    class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Previous Page -->
                            <?php if ($halaman > 1): ?>
                                <a href="?halaman=<?= $halaman - 1 ?><?= isset($_GET['nama']) ? '&nama=' . $_GET['nama'] : '' ?><?= isset($_GET['tapis_tahap']) ? '&tapis_tahap=' . $_GET['tapis_tahap'] : '' ?>"
                                    class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                    <i class="fas fa-angle-left"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Page Numbers -->
                            <?php
                            $start = max(1, $halaman - 2);
                            $end = min($jumlahHalaman, $halaman + 2);

                            for ($i = $start; $i <= $end; $i++): ?>
                                <a href="?halaman=<?= $i ?><?= isset($_GET['nama']) ? '&nama=' . $_GET['nama'] : '' ?><?= isset($_GET['tapis_tahap']) ? '&tapis_tahap=' . $_GET['tapis_tahap'] : '' ?>"
                                    class="px-3 py-1 <?= $i == $halaman ? 'bg-[#68B0AB] text-white' : 'bg-[#428D41] text-white hover:bg-[#68B0AB]' ?> rounded">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>

                            <!-- Next Page -->
                            <?php if ($halaman < $jumlahHalaman): ?>
                                <a href="?halaman=<?= $halaman + 1 ?><?= isset($_GET['nama']) ? '&nama=' . $_GET['nama'] : '' ?><?= isset($_GET['tapis_tahap']) ? '&tapis_tahap=' . $_GET['tapis_tahap'] : '' ?>"
                                    class="px-3 py-1 bg-[#428D41] text-white rounded hover:bg-[#68B0AB]">
                                    <i class="fas fa-angle-right"></i>
                                </a>
                            <?php endif; ?>

                            <!-- Last Page -->
                            <?php if ($halaman < $jumlahHalaman): ?>
                                <a href="?halaman=<?= $jumlahHalaman ?><?= isset($_GET['nama']) ? '&nama=' . $_GET['nama'] : '' ?><?= isset($_GET['tapis_tahap']) ? '&tapis_tahap=' . $_GET['tapis_tahap'] : '' ?>"
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
        <footer class="bg-[#428D41] text-white p-4 text-center bottom-0 w-full">
            &copy; 2025 KAFELIP. Semua hak terpelihara.
        </footer>
    </div>

    <!-- Borang pekerja -->
    <div id="uploadPekerja" class="pekerja">
        <div class="pekerja-content">
            <span class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Muat Naik Pekerja</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Pilih fail txt :</label>
                    <div class="dropzone" id="uploadDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Seret fail txt ke sini atau klik untuk memilih</p>
                        <input type="file" 
                               id="file" 
                               name="data_pengguna" 
                               accept=".txt"
                               class="hidden">
                    </div>
                    <div id="fileDisplay" class="hidden p-3 bg-gray-100 rounded flex justify-between items-center mt-2">
                        <span id="fileName" class="text-gray-700"></span>
                        <button type="button" id="removeFile" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" 
                            name="upload" 
                            id="uploadMenuBtn"
                            class="bg-gray-400 text-white p-2 rounded cursor-not-allowed" 
                            disabled>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!--  Borang Kemakini Pengguna -->
    <div id="kemaskiniPengguna" class="KemaskiniPengguna">
        <div class="kemaskiniPengguna-content">
            <span onclick="kemaskiniPengguna.style.display = 'none' " class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Kemaskini Pengguna</h2>
            <form id="updateForm" action="../function/update-user.php" method="POST">
                <input type="hidden" name="nama_lama" id="nama_lama">
                <input type="hidden" name="notel_lama" id="notel_lama">
                <input type="hidden" name="email_lama" id="email_lama">
                <input type="hidden" name="katalaluan_lama" id="katalaluan_lama">
                <input type="hidden" name="tahap_lama" id="tahap_lama">

                Nama :
                <input type="text" name="nama" id="nama" class="w-full border p-2 mb-3" required>
                Email :
                <input type="text" name="email" id="email" class="w-full border p-2 mb-3" required>
                Nombor Telefon :
                <input type="text" name="notel" id="notel" class="w-full border p-2 mb-3" required>
                Kata Laluan :
                <input type="text" name="katalaluan" id="katalaluan" class="w-full border p-2 mb-3" required>
                Tahap :
                <select name="tahap" id="tahap" class="w-full border p-2 mb-3">
                    <option value="ADMIN">ADMIN</option>
                    <option value="PELANGGAN">PELANGGAN</option>
                </select>

                <div class="flex mt-[20px] justify-center">
                    <button type="submit" name="KemaskiniDataPengguna" id="kemaskiniMenuBtn"
                        class="bg-[#428D41] text-white p-2 rounded">Kemaskini</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Butang scroll ke atas -->
    <button id="scrollToTopBtn" onclick="scrollToTop()">
        <i class="fas fa-arrow-up">
        </i>
    </button>
    <script>
        function setupDropzone() {
            const dropzone = document.getElementById('uploadDropzone');
            const input = document.getElementById('file');
            const fileDisplay = document.getElementById('fileDisplay');
            const fileName = document.getElementById('fileName');
            const removeFile = document.getElementById('removeFile');
            const submitBtn = document.getElementById('uploadMenuBtn');

            dropzone.addEventListener('click', () => input.click());

            dropzone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropzone.classList.add('dragover');
            });

            dropzone.addEventListener('dragleave', () => {
                dropzone.classList.remove('dragover');
            });

            dropzone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropzone.classList.remove('dragover');
                
                const file = e.dataTransfer.files[0];
                handleFile(file);
            });

            input.addEventListener('change', (e) => {
                const file = e.target.files[0];
                handleFile(file);
            });

            removeFile.addEventListener('click', () => {
                input.value = '';
                fileDisplay.classList.add('hidden');
                dropzone.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.remove('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                submitBtn.classList.add('bg-gray-400', 'cursor-not-allowed');
            });

            function handleFile(file) {
                if (file) {
                    if (!file.name.toLowerCase().endsWith('.txt')) {
                        notiferror.play();
                        Swal.fire({
                            icon: 'error',
                            title: 'Ralat',
                            text: 'Sila pilih fail .txt sahaja',
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Faham'
                        });
                        input.value = '';
                        return;
                    }

                    fileName.textContent = file.name;
                    fileDisplay.classList.remove('hidden');
                    dropzone.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('bg-gray-400', 'cursor-not-allowed');
                    submitBtn.classList.add('bg-[#428D41]', 'hover:bg-[#68B0AB]', 'cursor-pointer');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', setupDropzone);
    </script>

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
        const kemaskiniPengguna = document.getElementById("kemaskiniPengguna");

        function updateUser(notel, email) {
            fetch(`../api/get-user.php?notel=${notel}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('email').value = data.email;
                    document.getElementById('notel').value = data.notel;
                    document.getElementById('katalaluan').value = data.password;
                    document.getElementById('tahap').value = data.tahap;

                    document.getElementById('notel_lama').value = notel;
                    document.getElementById('email_lama').value = email;
                    document.getElementById('nama_lama').value = data.nama;;
                    document.getElementById('tahap_lama').value = data.tahap;
                    document.getElementById('katalaluan_lama').value = data.password;

                    kemaskiniPengguna.style.display = "block";
                    semakdataKemaskini()
                });
        }



        function togglePasswordVisibility(notel) {
            const passwordField = document.getElementById(`password-${notel}`);
            const hiddenPasswordField = document.getElementById(`hidden-password-${notel}`);
            const eyeIcon = document.getElementById(`eye-icon-${notel}`);

            if (passwordField.style.display === "none") {
                passwordField.style.display = "inline"; // Show actual password
                hiddenPasswordField.style.display = "none"; // Hide asterisks
                eyeIcon.classList.remove("fa-eye"); // Change icon to eye-slash
                eyeIcon.classList.add("fa-eye-slash");
            } else {
                passwordField.style.display = "none"; // Hide actual password
                hiddenPasswordField.style.display = "inline"; // Show asterisks
                eyeIcon.classList.remove("fa-eye-slash"); // Change icon to eye
                eyeIcon.classList.add("fa-eye");
            }
        }

        const drawerToggle = document.getElementById('drawerToggle');
        const drawer = document.getElementById('drawer');
        const mainContent = document.getElementById('mainContent');


        drawerToggle.addEventListener('click', () => {
            drawer.classList.toggle('drawer-open');
            drawer.classList.toggle('drawer-closed');
            mainContent.classList.toggle('content-expanded');
            mainContent.classList.toggle('content-collapsed');
        });


        // pekerja functionality
        const pekerja = document.getElementById("uploadPekerja");
        const btn = document.getElementById("uploadButton");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = function () {
            pekerja.style.display = "block";
        }

        span.onclick = function () {
            pekerja.style.display = "none";
        }

        window.onclick = function (event) {
            if (event.target == pekerja) {
                pekerja.style.display = "none";
            }
            if (event.target == kemaskiniPengguna) {
                kemaskiniPengguna.style.display = "none";
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
                }).then(() => { });
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
                }).then(() => { });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>



            // Untuk delete button
            document.querySelectorAll('.delete-btn').forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault();
                    const id = this.dataset.id;
                    const nama = this.dataset.namauser;
                    notifwarning.play();

                    Swal.fire({
                        title: 'Anda pasti?',
                        text: `Anda memadam ${nama} dan tidak boleh memulihkan data ini selepas dipadam!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, padam!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = `../function/del-user.php?notel=${id}`;
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

        function SemakProfil(email) {
            let popupWindow = window.open(`semak-profil.php?email=${email}`, 'Profil',
                'width=800,height=600,resizable=yes,scrollbars=yes');
        }
    </script>

</body>

</html>