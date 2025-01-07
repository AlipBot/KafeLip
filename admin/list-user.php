<?php
include('../function/autoKeluarAdmin.php');


#memanggil fail 
include('../function/connection.php');


$sql = "SELECT * FROM pelanggan";

// Logik untuk carian nama
if (isset($_GET['nama']) && !empty($_GET['nama'])) {
    $nama = $_GET['nama'];
    $sql .= " WHERE nama LIKE '%$nama%'";
}

// Logik untuk filter tahap
if (isset($_GET['tapis_tahap']) && !empty($_GET['tapis_tahap'])) {
    $tapis_tahap = $_GET['tapis_tahap'];

    // Jika carian nama sudah ditetapkan, tambahkan AND, jika tidak WHERE
    if (strpos($sql, 'WHERE') !== false) {
        $sql .= " AND tahap = '$tapis_tahap'";
    } else {
        $sql .= " WHERE tahap = '$tapis_tahap'";
    }
}

# Mendapatkan data pengguna dari pangkalan data 
$laksana = mysqli_query($condb, $sql);




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
            if (mysqli_num_rows($pilih) == 1) {
                $_SESSION['error'] = "notel $notel di fail txt telah ada di pangkalan data. TUKAR NOTEL DALAM FAIL TXT";
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



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pengguna KafeLip</title>

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
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-roboto bg-gray-100">


    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-[#588157] text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-[#3a5a40] text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Senarai Pelanggan Dan Pekerja KafeLip</div>
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
                        <span>Senarai Pelanggan Dan Pekerja</span>
                    </div>
                    <div class="text-center text-gray-600 mb-4">
                        <span class="font-bold text-lg">Tarikh: </span>
                        <span id="currentDate" class="font-bold text-lg"></span>
                        <br>
                        <span class="font-bold text-lg">Masa: </span>
                        <span id="currentTime" class="font-bold text-lg"></span>
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
                                <button type="submit" class="bg-[#588157] hover:bg-[#68B0AB] text-white p-2 rounded flex items-center">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                <button type="button" onclick="window.location.href='list-user.php';"
                                    class="bg-red-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-times mr-1"></i> Padam
                                </button>
                            </form>
                            <div class="flex space-x-2">
                                <button id="uploadButton"
                                    class="bg-[#588157] text-white p-2 hover:bg-[#68B0AB] rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Muat Naik Pekerja
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-[#a3b18a] font-bold text-black">
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
                                                        class="bg-[#588157] text-white py-2 px-6 w-32 rounded hover:bg-[#68B0AB] flex items-center justify-center">
                                                        <i class="fas fa-user mr-1"></i> Profil
                                                    </button>
                                                    <button onclick="updateUser('<?= $m['notel'] ?>')"
                                                        class="bg-[#588157] text-white py-2 px-6 w-32 rounded hover:bg-[#68B0AB] flex items-center justify-center">
                                                        <i class="fas fa-edit mr-1"></i> Kemaskini
                                                    </button>
                                                    <button data-id="<?php echo urlencode($m['notel']); ?>"
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
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-[#588157] text-white p-4 text-center bottom-0 w-full">
            &copy; 2024 Kedai KafeLip. All rights reserved.
        </footer>
    </div>

    <!-- pekerja -->
    <div id="uploadPekerja" class="pekerja">
        <div class="pekerja-content">
            <span class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Muat Naik Pekerja</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="file" class="block text-gray-700">Pilih fail txt :</label>
                    <div class="dropzone" id="uploadDropzone">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Seret fail txt ke sini atau klik untuk memilih</p>
                        <input type="file" id="file" name='data_pengguna' accept=".txt" class="hidden">
                    </div>
                    <div id="fileDisplay" class="hidden p-3 bg-gray-100 rounded flex justify-between items-center">
                        <span id="fileName" class="text-gray-700"></span>
                        <button type="button" id="removeFile" class="text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="flex justify-center">
                    <button type="submit" name='upload' class="bg-[#588157] text-white p-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Popup Modal -->
    <div id="editModal" class="KemaskiniPengguna">
        <div class="kemaskiniPengguna-content">
            <span onclick="kemaskiniPengguna.style.display = 'none' " class="close">&times;</span>
            <h3 class="text-lg font-bold">Kemaskini Pengguna</h3>
            <form id="updateForm" action="../function/update-user.php" method="POST">
                <input type="hidden" name="notel_lama" id="notel_lama">
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

                <div class="flex justify-end">
                    <button type="submit" name="KemaskiniDataPengguna"
                        class="bg-blue-500 text-white p-2">Kemaskini</button>
                </div>
            </form>
        </div>
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
        const kemaskiniPengguna = document.getElementById("editModal");

        function updateUser(notel) {

            fetch(`../api/get-user.php?notel=${notel}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('nama').value = data.nama;
                    document.getElementById('email').value = data.email;
                    document.getElementById('notel').value = data.notel;
                    document.getElementById('katalaluan').value = data.password;
                    document.getElementById('tahap').value = data.tahap;
                    document.getElementById('notel_lama').value = notel;
                    kemaskiniPengguna.style.display = "block";
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

        // pekerja functionality
        const pekerja = document.getElementById("uploadPekerja");
        const btn = document.getElementById("uploadButton");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            pekerja.style.display = "block";
        }

        span.onclick = function() {
            window.location.href = window.location.href;
            pekerja.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == pekerja) {
                window.location.href = window.location.href;
                pekerja.style.display = "none";
            }
        }

        const notifsuccess = new Audio('../lib/audio/notif.mp3'); // Tukar path ke fail audio anda
        const notiferror = new Audio('../lib/audio/error.mp3'); // Tukar path ke fail audio anda
        const notifinfo = new Audio('../lib/audio/info.mp3'); // Tukar path ke fail audio anda
        const notifwarning = new Audio('../lib/audio/warning.mp3'); // Tukar path ke fail audio anda


        document.addEventListener('DOMContentLoaded', function() {
            // Untuk popup success
            <?php if (isset($_SESSION['success'])): ?>
                notifsuccess.play();
                Swal.fire({
                    icon: 'success',
                    title: '<?php echo $_SESSION['success']; ?>',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {});
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
                }).then(() => {});
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
        });

        function SemakProfil(email) {
            let popupWindow = window.open(`semak-profil.php?email=${email}`, 'Profil',
                'width=800,height=600,resizable=yes,scrollbars=yes');
        }
    </script>

</body>

</html>