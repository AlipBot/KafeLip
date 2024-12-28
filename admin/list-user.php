<?php
include('../function/autoKeluarAdmin.php');


#memanggil fail 
include('../function/connection.php');

$tambahan = "";
if (!empty($_GET['nama'])) {
    $tambahan = " where pelanggan.nama like '%" . $_GET['nama'] . "%'";
}

# Mendapatkan data pengguna dari pangkalan data 
$arahan_papar = "select* from pelanggan $tambahan ";
$laksana = mysqli_query($condb, $arahan_papar);


$popup_message = "";
$popup_visible = false;

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
                $popup_message = "notel $notel di fail txt telah ada di pangkalan data. TUKAR NOTEL DALAM FAIL TXT";
                $popup_visible = true;
            } else {
                $arahan_sql_simpan = "insert into pelanggan (email, notel, nama, password, tahap) values ('$email','$notel','$nama','$katalaluan','ADMIN')";
                mysqli_query($condb, $arahan_sql_simpan);
                $bil++;
            }
        }
        fclose($fail_data_pengguna);

        if (mysqli_num_rows($pilih) == 1) {
            $popup_message = "notel $notel di fail txt telah ada di pangkalan data. TUKAR NOTEL DALAM FAIL TXT";
            $popup_visible = true;
        } else {
            $popup_message = "Import fail Data Selesai. Sebanyak $bil data telah disimpan";
            $popup_visible = true;
        }


    } else {
        $popup_message = "Hanya fail berformat txt sahaja dibenarkan";
        $popup_visible = true;
    }
}
?>



<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senarai Pengguna KafeLip</title>
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

        .password {
            display: inline;
            /* Show by default */
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
            background-color: rgba(0,0,0,0.4);
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
    </style>
</head>

<body class="font-roboto bg-gray-100">

<?php if ($popup_visible) : ?>
        <div id="notif" class="notif" style="display:block;">
            <div class="notif-content">
                <span class="close">&times;</span>
                <p><?php echo htmlspecialchars($popup_message); ?></p>
            </div>
        </div>
    <?php endif; ?>


    <div class="flex h-screen flex-col">
        <!-- Header -->
        <header class="bg-blue-800 text-white p-4 flex justify-between items-center fixed w-full z-10">
            <button id="drawerToggle" class="bg-blue-700 text-white p-2 rounded">
                <i class="fas fa-bars"></i> Menu
            </button>
            <div class="text-[150%] font-bold mx-auto">Senarai Pelanggan Dan Pekerja KafeLip</div>
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
                                <input type="text" name="nama" placeholder="Carian Nama pengguna" value="<?= $_GET['nama'] ?>" class="border rounded p-2 w-2/5">
                                <button type="submit" class="bg-blue-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-search mr-1"></i> Cari
                                </button>
                                <button type="button" onclick="window.location.href='list-user.php';" class="bg-red-800 text-white p-2 rounded flex items-center">
                                    <i class="fas fa-times mr-1"></i> Padam
                                </button>
                            </form>
                            <div class="flex space-x-2">
                                <button id="uploadButton" class="bg-blue-800 text-white p-2 rounded flex items-center whitespace-nowrap">
                                    <i class="fas fa-plus mr-1"></i> Upload Pekerja
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="table-container">
                        <table class="w-full table-auto rounded-lg overflow-hidden">
                            <thead>
                                <tr class="bg-blue-200 text-blue-800">
                                    <td width='30%' class=" px-[70px] py-2">Nama</td>
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
                                                <span class="password" id="password-<?php echo $m['notel']; ?>" style="display: none;"><?php echo htmlspecialchars($m['password']); ?></span>
                                                <span class="hidden-password" id="hidden-password-<?php echo $m['notel']; ?>">********</span>
                                                <i class="fas fa-eye cursor-pointer" onclick="togglePasswordVisibility('<?php echo $m['notel']; ?>')" id="eye-icon-<?php echo $m['notel']; ?>"></i>
                                            </td>
                                            <td class='px-4 py-2 text-center'><?php echo htmlspecialchars($m['tahap']); ?></td>

                                            <td class='px-4 py-2 text-center'>
                                                <div class="flex flex-col items-center space-y-4">
                                                    <button onclick="location.href='tukar-user.php?notel=<?php echo urlencode($m['notel']); ?>'" class="bg-blue-800 text-white py-2 px-4 rounded flex items-center justify-center">
                                                        <i class="fas fa-edit mr-1"></i> Kemaskini
                                                    </button>
                                                    <button onclick="if(confirm('Anda pasti anda ingin memadam pengguna <?= $m['nama'] ?>  ini?')) location.href='../function/del-user.php?notel=<?php echo urlencode($m['notel']); ?>'" class="bg-red-800 text-white py-2 px-9 rounded flex items-center justify-center">
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
        <footer class="bg-blue-800 text-white p-4 text-center bottom-0 w-full">
            &copy; 2024 Kedai KafeLip. All rights reserved.
        </footer>
    </div>

    <!-- pekerja -->
    <div id="uploadPekerja" class="pekerja">
        <div class="pekerja-content">
            <span class="close">&times;</span>
            <h2 class="text-2xl font-bold mb-4">Upload Pekerja</h2>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="file" class="block text-gray-700">Pilih fail txt:</label>
                    <input type="file" id="file" name='data_pengguna' accept=".txt" class="border rounded p-2 w-full">
                </div>
                <div class="flex justify-center">
                    <button type="submit" name='upload' class="bg-blue-800 text-white p-2 rounded">Submit</button>
                </div>
            </form>
        </div>
    </div>


    <script>
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
        const notif = document.getElementById("notif");

        btn.onclick = function() {
            pekerja.style.display = "block";
        }

        span.onclick = function() {
            window.location.href = window.location.href;
            notif.style.display = "none";
            pekerja.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == pekerja) {
                window.location.href = window.location.href;
                pekerja.style.display = "none";
            }
            if (event.target == notif) {
                window.location.href = window.location.href;
                notif.style.display = "none";
            }
        }



    </script>

</body>

</html>