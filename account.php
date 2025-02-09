<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("function/autoKeluar.php");  # fail function auto logout jika pengguna belum login
include("function/connection.php"); # Sambung Ke database
//――――――――――――――――――――――――――――――――――――――― ┏  Code Php ┓ ――――――――――――――――――――――――――――――――――――――― \\

# Mempaparkan bilangan senarai tempahan
if (isset($_SESSION['orders'])) {
  $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
  $bil = "";
}
//――――――――――――――――――――――――――――― ┏  POST DATA & KOD SQL & FUNCTION   ┓ ――――――――――――――――――――――――― \\

#    POST DATA KEMASKINI  NAMA
if (isset($_POST['KemaskiniNama'])) {
  $nama = $_POST['nama'];

  if ($nama == $_SESSION['nama']) {
    $_SESSION['error'] = "NAMA SAMA DENGAN NAMA SEMASA";
    header("Location: account.php");
    exit();
  }

  if (strlen($nama) < 3) {
    $_SESSION['error'] = "NAMA MESTI 3 AKSARA KE ATAS";
    header("Location: account.php");
    exit();
  }

  if (strlen($nama) > 50) {
    $_SESSION['error'] = "NAMA MESTI TIDAK BOLEH LEBIH 50 AKSARA";
    header("Location: account.php");
    exit();
  }

  #kod SQL untuk kemaskini nama baharu
  $sqlKemaskiniNama = "UPDATE pelanggan SET nama = '$nama' WHERE notel = '" . $_SESSION['notel'] . "'";

  if (mysqli_query($condb, $sqlKemaskiniNama)) {
    $_SESSION['nama'] = $nama; # Simpan data nama baharu di session
    $_SESSION['success'] = "Kemaskini Nama Berjaya";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Kemaskini Nama Gagal";
    header("Location: account.php");
    exit();
  }
}


#    POST  Data Kemaskini Email
if (isset($_POST['KemaskiniEmail'])) {
  $email = $_POST['email'];
  # semak email belum digunakan lagi
  $sql_semakemail = "select email from pelanggan where email = '$email' ";
  $check = mysqli_query($condb, $sql_semakemail);
  if (mysqli_num_rows($check) == 1) {
    $_SESSION['error'] = "EMAIL SUDAH DIGUNAKAN SILA GUNA EMAIL LAIN";
    header("Location: account.php");
    exit();
  }
  #Jika email sama dengan email sekarang
  if ($email == $_SESSION['email']) {
    $_SESSION['error'] = "EMAIL SAMA DENGAN EMAIL SEMASA";
    header("Location: account.php");
    exit();
  }
  # kod SQL untuk update email baharu
  $sqlKemaskiniEmail = "UPDATE pelanggan SET email = '" . $_POST['email'] . "' WHERE notel = '" . $_SESSION['notel'] . "'";
  if (mysqli_query($condb, $sqlKemaskiniEmail)) {
    $_SESSION['email'] = $email; # simpan data email baharu ke dalam session
    $_SESSION['success'] = "Kemaskini Email Berjaya";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Kemaskini Email Gagal";
    header("Location: account.php");
    exit();
  }
}


#   POST data kemaskini Nombor Telefon Baharu
if (isset($_POST['KemaskiniNotel'])) {
  $notel = $_POST['notel'];
  #   Semak nombor telefon telah digunakan belum
  $sql_semaknotel = "select notel from pelanggan where notel = '$notel' ";
  $check = mysqli_query($condb, $sql_semaknotel);
  if (mysqli_num_rows($check) == 1) {
    $_SESSION['error'] = "NOMBOR TELEFON SUDAH DIGUNAKAN SILA GUNA NOMBOR TELEFON LAIN";
    header("Location: account.php");
    exit();
  }
  #    semak nombor telefon tidak sama dengan fail session
  if ($notel == $_SESSION['notel']) {
    $_SESSION['error'] = "NOMBOR TELEFON SAMA DENGAN NOMBOR TELEFON SEMASA";
    header("Location: account.php");
    exit();
  }

  if (strlen($notel) < 10) {
    $_SESSION['error'] = "NOMBOR TELEFON MESTI 10 KE ATAS";
    header("Location: account.php");
    exit();
  }

  if (strlen($notel) > 15) {
    $_SESSION['error'] = "NOMBOR TELEFON MESTI TIDAK BOLEH LEBIH 14";
    header("Location: account.php");
    exit();
  }
  #   Kod SQL kemaskini Nombor telefon baharu
  $sqlKemaskiniNotel = "UPDATE pelanggan SET notel = '" . $_POST['notel'] . "' WHERE notel = '" . $_SESSION['notel'] . "'";
  if (mysqli_query($condb, $sqlKemaskiniNotel)) {
    $_SESSION['notel'] = $notel; # Simpan data nombor telefon baharu di session
    $_SESSION['success'] = "Nombor Telefon Berjaya DiKemaskini";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Nombor Telefon Gagal DiKemaskini";
    header("Location: account.php");
    exit();
  }
}


#  POST Data Kemaskini Kata Laluan
if (isset($_POST['KemaskiniPassword'])) {
  $pass = $_POST['password'];
  $pass2 = $_POST['password2'];

  # semak kata laluan sama ke tak
  if ($pass != $pass2) {
    $_SESSION['error'] = "Kata Laluan Tidak Sepadan";
    header("Location: account.php");
    exit();
  }

  if (strlen($pass) < 7) {
    $_SESSION['error'] = "KATA LAUAN MESTI 8 AKSARA KE ATAS";
    header("Location: account.php");
    exit();
  }

  if (strlen($pass) > 13) {
    $_SESSION['error'] = "KATA LAUAN MESTI TIDAK BOLEH LEBIH 12 AKSARA";
    header("Location: account.php");
    exit();
  }
  #Kod SQL kemaskini Kata Laluan Baharu
  $sqlKemaskiniPassword = "UPDATE pelanggan SET password = '" . $_POST['password'] . "' WHERE notel = '" . $_SESSION['notel'] . "'";
  if (mysqli_query($condb, $sqlKemaskiniPassword)) {
    $_SESSION['success'] = "Kata Laluan Baharu Berjaya Dikemaskini";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Kata Laluan Gagal DiKemaskini";
    header("Location: account.php");
    exit();
  }
}


#  Function Padam Akaun
if (isset($_POST['HapusAkaun'])) {
  $email = $_POST['email'];
  $notel = $_SESSION['notel'];

  // Semak email dahulu
  $sql_semakemail = "SELECT email FROM pelanggan WHERE email = ? AND notel = ?";
  $stmt = mysqli_prepare($condb, $sql_semakemail);
  mysqli_stmt_bind_param($stmt, "ss", $email, $notel);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  if (mysqli_num_rows($result) == 0) {
    $_SESSION['error'] = "EMAIL TIDAK WUJUD";
    header("Location: account.php");
    exit();
  }

  // Laksanakan penghapusan
  $sqlHapusAkaun = "DELETE FROM pelanggan WHERE notel = ? AND email = ?";
  $stmt = mysqli_prepare($condb, $sqlHapusAkaun);
  mysqli_stmt_bind_param($stmt, "ss", $notel, $email);

  if (mysqli_stmt_execute($stmt)) {
    # Refresh page pastu function auto keluar berkerja untuk auto logout akaun untuk buang semua data session
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Gagal Memadam Akaun";
    header("Location: account.php");
    exit();
  }
}


# Dapatkan maklumat pelanggan untuk isi kat value di borang kemaskini masing2
$sql        =   "select* from pelanggan where notel = '" . $_SESSION['notel'] . "' AND email = '" . $_SESSION['email'] . "' LIMIT 1";
$laksana    =   mysqli_query($condb, $sql);
$m          =   mysqli_fetch_array($laksana);


?>

<!-- Kod HTML & CSS + TAILWIND & JAVASCRIPT  -->

<html lang="ms">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title> Urus Akaun </title>
  <link rel="apple-touch-icon" sizes="180x180" href="lib/icon/apple-touch-icon.png">
  <link rel="icon" type="image/png" sizes="32x32" href="lib/icon/favicon-32x32.png">
  <link rel="icon" type="image/png" sizes="16x16" href="lib/icon/favicon-16x16.png">
  <link rel="manifest" href="lib/icon/site.webmanifest">
  <link rel="stylesheet" href="lib/css/all.css">
  <link rel="stylesheet" href="lib/css/sharp-solid.css">
  <link rel="stylesheet" href="lib/css/sharp-regular.css">
  <link rel="stylesheet" href="lib/css/sharp-light.css">
  <link rel="stylesheet" href="lib/css/duotone.css" />
  <link rel="stylesheet" href="lib/css/brands.css" />
  <link href="lib/css/css2.css" rel="stylesheet" />
  <script src="lib/js/tailwind.js"></script>
  <link rel="stylesheet" href="lib/css/sweetalert2.min.css">
  <script src="lib/js/sweetalert2@11.js"></script>
  <style>
    @media (max-width: 768px) {

      .nav a span,
      .goMenu a span {
        display: none;
      }

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

    /* Hover effect for header navigation links */
    .nav a::after,
    .goMenu a::after {
      content: '';
      display: block;
      width: 0;
      height: 2px;
      background: #4A7C59;
      transition: width 0.3s;
    }

    .nav a:hover::after,
    .goMenu a:hover::after {
      width: 100%;
    }

    .add-to-cart:hover,
    #scrollToTopBtn:hover {
      background-color: #68B0AB;
      color: #fff;
      transition: background-color 0.3s ease, color 0.3s ease;
    }
  </style>
</head>

<body class="bg-[#FAF3DD]">
  <!-- Header -->
  <div class="w-full bg-[#FAF3DD]">
    <div class="container mx-auto flex justify-between items-center py-6 px-4">
      <div class="logo text-2xl font-bold flex items-center mr-4">
        <i class="fas fa-coffee text-[#4A7C59] mr-2">
        </i>
        <span class="text-black">Kafe </span>
        <span class="text-black">lip</span>
      </div>
      <div class="nav flex gap-6 -ml-10 mr-20">
        <a class="text-black font-bold active:text-[#4A7C59]" href="menu.php">
          <i class="fas fa-utensils text-[#4A7C59] mr-1"></i>
          <span>MENU</span>
        </a>
        <a class="text-black font-bold active:text-[#4A7C59]" href="cart.php">
          <i class="fas fa-shopping-cart text-[#4A7C59] mr-1"></i>
          <span>CART <?= $bil ?></span>
        </a>
        <a class="text-black font-bold active:text-[#4A7C59]" href="sejarah-tempah.php">
          <i class="fas fa-history text-[#4A7C59] mr-1"></i>
          <span>SEJARAH TEMPAHAN</span>
        </a>
      </div>
      <div class="relative">
        <button id="menuButton" class="p-2 hover:bg-gray-100 rounded-full">
          <i class="fas fa-bars text-[#4A7C59] text-xl"></i>
        </button>
        <div id="dropdownMenu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
          <?php if ($_SESSION['tahap'] == "ADMIN"): ?>
            <a href="admin/panel.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
              <i class="fa fa-list-alt mr-2 text-[#4A7C59]"></i>Panel Admin
            </a>
          <?php endif; ?>
          <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-user mr-2 text-[#4A7C59]"></i>Profil
          </a>
          <a href="account.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-cog mr-2 text-[#4A7C59]"></i>Akaun
          </a>
          <hr class="my-1">
          <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
            <i class="fas fa-sign-out-alt mr-2 text-[#4A7C59]"></i>Log Keluar
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Body -->
  <main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-4"> Akaun</h1>
    <p class="text-center mb-8">Kemaskini Profil Anda Dan Tetapkan Pilihan Akaun Anda. </p>
    <div class="flex flex-col md:flex-row justify-center">
      <section class="w-full md:w-3/4 space-y-8">
        <!-- Borang Kemaskin Nama -->
        <form id='KemaskiniNama' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
          <input type="hidden" name="KemaskiniNama" value="1">
          <h2 class="text-xl font-bold mb-4">
            Nama
          </h2>
          <p class="text-gray-600 mb-4">
            Kemaskini Nama Baharu
          </p>
          <div class="flex items-center">
            <input class="border border-gray-300 rounded-lg p-2 flex-grow" required type="text" name="nama" value="<?= $m['nama'] ?>" />
          </div>
          <div class="flex justify-end">
            <button class="ml-4 my-5 bg-blue-500 text-white px-4 py-2 rounded-lg " type="button" name="KemaskiniNama" onclick="Kemaskini('KemaskiniNama')">
              Kemaskini
            </button>
          </div>
        </form>
        <!-- Borang Kemaskini Email -->
        <form id='KemaskiniEmail' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
          <input type="hidden" name="KemaskiniEmail" value="1">
          <h2 class="text-xl font-bold mb-4">
            Email
          </h2>
          <p class="text-gray-600 mb-4">
            Kemaskini Email Baharu
          </p>
          <div class="flex items-center">
            <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="email" required type="email" name="email" value="<?= $m['email'] ?>" />
          </div>
          <p class="text-red-500 text-sm mt-1 hidden" id="emailError">
            Sila masukkan alamat email yang sah.
          </p>
          <div class="flex justify-end">
            <button class="ml-4 my-5 bg-blue-500 text-white px-4 py-2 rounded-lg" name="KemaskiniEmail" type="button" onclick="Kemaskini('KemaskiniEmail')">
              Kemaskini
            </button>
          </div>
        </form>
        <!-- Borang Kemaskini Nombor Telefon -->
        <form id='KemaskiniNotel' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
          <input type="hidden" name="KemaskiniNotel" value="1">
          <h2 class="text-xl font-bold mb-4">
            Nombor Telefon
          </h2>
          <p class="text-gray-600 mb-4">
            Kemaskini Nombor Telefon Baharu
          </p>
          <div class="flex items-center">
            <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="notel" required type="tel" name="notel" value="<?= $m['notel'] ?>" />
          </div>
          <p class="text-red-500 text-sm mt-1 hidden" id="notelError">
            Nombor telefon mesti 10-14 digit dan berbeza dari nombor sekarang.
          </p>
          <div class="flex justify-end">
            <button class="ml-4 my-5 bg-blue-500 text-white px-4 py-2 rounded-lg " name="KemaskiniNotel" type="button" onclick="Kemaskini('KemaskiniNotel')">
              Kemaskini
            </button>
          </div>
        </form>
        <!-- Borang Kemaskini Kata Laluan -->
        <form id='KemaskiniPassword' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg		">
          <input type="hidden" name="KemaskiniPassword" value="1">
          <h2 class="text-xl font-bold mb-4">
            Tukar Kata Laluan Baharu
          </h2>
          <p class="text-gray-600 mb-4">
            Sila Cipta Kata Laluan Yang Kuat
          </p>
          <div class="space-y-4">
            <div class="flex items-center relative">
              <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="password" placeholder="Minimum 8 Askara" required type="password" name="password" />
              <i class="fas fa-eye ml-4 text-gray-500 absolute right-3 cursor-pointer" id="password-icon" onclick="togglePasswordVisibility('password')">
              </i>
            </div>
            <p class="text-red-500 text-sm mt-1 hidden" id="passwordError">
              Kata laluan mestilah 8-12 aksara panjang, mengandungi sekurang-kurangnya satu huruf besar, satu huruf kecil dan satu nombor. </p>
            <div class="flex items-center relative">
              <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="repeat-password" placeholder="Ulang Kata Laluan Semula" required type="password" name="password2" />
              <i class="fas fa-eye ml-4 text-gray-500 absolute right-3 cursor-pointer" id="repeat-password-icon" onclick="togglePasswordVisibility('repeat-password')">
              </i>
            </div>
            <p class="text-red-500 text-sm mt-1 hidden" id="confirmPasswordError">
              Kata laluan tidak sepadan.
            </p>
          </div>
          <div class="flex justify-end">
            <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg" name="KemaskiniPassword" type="button" onclick="Kemaskini('KemaskiniPassword')">
              Kemaskini
            </button>
          </div>
        </form>
        <!-- Borang Padam akaun -->
        <form id="delete-email-form" action="" method="POST" class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
          <input type="hidden" name="HapusAkaun" value="1">
          <h2 class="text-xl font-bold mb-4">
            Padam Akaun Ini
          </h2>
          <p class="text-gray-600 mb-4">
            Masukkan Email Anda Untuk Memadam Akaun Anda
          </p>
          <div class="flex items-center">
            <input id="deleteEmail" class="border border-gray-300 rounded-lg p-2 flex-grow" placeholder="Sila ketik <?= $m['email'] ?>" required type="email" name="email" />
          </div>
          <p class="text-red-500 text-sm mt-1 hidden" id="deleteEmailError">
            Sila Masukkan Alamat Email Yang Sah.
          </p>
          <div class="flex justify-end">
            <button type="button" class="delete-email ml-4 my-5 bg-red-500 text-white px-4 py-2 rounded-lg" onclick="confirmDelete()">
              Padam
            </button>
          </div>
        </form>
      </section>
    </div>
  </main>
  <!-- Footer -->
  <footer class="w-full bg-[#FAF3DD] text-black py-6 px-10">
    <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
      <div class="mb-4 lg:mb-0">
        © 2025 KAFELIP. Semua hak terpelihara.
      </div>
      <div class="flex gap-6">
        <a class="text-[#4A7C59]" href="#">
          <i class="fab fa-facebook-f">
          </i>
        </a>
        <a class="text-[#4A7C59]" href="#">
          <i class="fab fa-twitter">
          </i>
        </a>
        <a class="text-[#4A7C59]" href="https://www.instagram.com/alipje29/#">
        <i class="fab fa-instagram">
          </i>
        </a>
      </div>
    </div>
  </footer>

  <!-- Butang scroll keatas -->
  <button id="scrollToTopBtn" onclick="scrollToTop()">
    <i class="fas fa-arrow-up"></i>
  </button>

  <!-------------SCRIPT & FUNCTION------------>

  <script>
    // Function untuk sembunyikan kata lalauan
    function togglePasswordVisibility(id) {
      const passwordInput = document.getElementById(id);
      const icon = document.getElementById(id + '-icon');
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    }
  </script>

  <script>
    // Tunjukan atau sorokkan butang scroll keatas
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
    //  Function Untuk memamparkan syarat untuk isi borang mengikut jenis data diperlukan 
    document.getElementById('email').addEventListener('input', function() {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const emailError = document.getElementById('emailError');
      if (!emailPattern.test(this.value) || this.value == "<?= $m['email'] ?>") {
        this.classList.add('border-red-500');
        emailError.classList.remove('hidden');
      } else {
        this.classList.remove('border-red-500');
        emailError.classList.add('hidden');
      }
    });

    document.getElementById('deleteEmail').addEventListener('input', function() {
      const emailUser = "<?= $m['email'] ?>";
      const emailError = document.getElementById('deleteEmailError');
      if (this.value != emailUser) {
        this.classList.add('border-red-500');
        emailError.classList.remove('hidden');
      } else {
        this.classList.remove('border-red-500');
        emailError.classList.add('hidden');
      }
    });

    document.getElementById('password').addEventListener('input', function() {
      const confirmPasswordField = document.getElementById('confirm-password');
      const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,12}$/;
      const passwordError = document.getElementById('passwordError');
      if (!passwordPattern.test(this.value)) {
        this.classList.add('border-red-500');
        passwordError.classList.remove('hidden');
      } else {
        this.classList.remove('border-red-500');
        passwordError.classList.add('hidden');
      }
      if (this.value !== confirmPasswordField.value) {
        confirmPasswordField.classList.add('border-red-500');
        document.getElementById('confirmPasswordError').classList.remove('hidden');
      } else {
        confirmPasswordField.classList.remove('border-red-500');
        document.getElementById('confirmPasswordError').classList.add('hidden');
      }
    });

    document.getElementById('repeat-password').addEventListener('input', function() {
      const passwordField = document.getElementById('password');
      const confirmPasswordError = document.getElementById('confirmPasswordError');
      if (this.value !== passwordField.value) {
        this.classList.add('border-red-500');
        confirmPasswordError.classList.remove('hidden');
        passwordField.classList.add('border-red-500');
      } else {
        this.classList.remove('border-red-500');
        confirmPasswordError.classList.add('hidden');
        passwordField.classList.remove('border-red-500');
      }
    });

    document.getElementById('notel').addEventListener('input', function() {
      const notelSekarang = "<?= $m['notel'] ?>";
      const notelError = document.getElementById('notelError');
      const notelValue = this.value.trim();

      if (notelValue === notelSekarang ||
        notelValue.length < 10 ||
        notelValue.length > 14 ||
        !/^\d+$/.test(notelValue)) {

        this.classList.add('border-red-500');
        notelError.classList.remove('hidden');
      } else {
        this.classList.remove('border-red-500');
        notelError.classList.add('hidden');
      }
    });

    function validateEmail(email) {
      const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      const currentEmail = "<?= $m['email'] ?>";
      return emailPattern.test(email) && email !== currentEmail;
    }

    function validatePassword(password) {
      const passwordPattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,12}$/;
      return passwordPattern.test(password);
    }

    function validateNotel(notel) {
      const currentNotel = "<?= $m['notel'] ?>";
      const notelValue = notel.trim();
      return notelValue !== currentNotel &&
        notelValue.length >= 10 &&
        notelValue.length <= 14 &&
        /^\d+$/.test(notelValue);
    }

    function validateNama(nama) {
      const currentNama = "<?= $m['nama'] ?>";
      return nama.length >= 3 &&
        nama.length <= 50 &&
        nama !== currentNama;
    }

    // Tambah event listeners untuk setiap form
    document.addEventListener('DOMContentLoaded', function() {
      // Validasi nama
      const namaInput = document.querySelector('input[name="nama"]');
      const namaSaveBtn = document.querySelector('button[name="KemaskiniNama"]');
      namaSaveBtn.disabled = true;
      namaSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');

      namaInput.addEventListener('input', function() {
        if (validateNama(this.value)) {
          namaSaveBtn.disabled = false;
          namaSaveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
          namaSaveBtn.disabled = true;
          namaSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
      });

      // Validasi email
      const emailInput = document.querySelector('input[name="email"]');
      const emailSaveBtn = document.querySelector('button[name="KemaskiniEmail"]');
      emailSaveBtn.disabled = true;
      emailSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');

      emailInput.addEventListener('input', function() {
        if (validateEmail(this.value)) {
          emailSaveBtn.disabled = false;
          emailSaveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
          emailSaveBtn.disabled = true;
          emailSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
      });

      // Validasi notel
      const notelInput = document.querySelector('input[name="notel"]');
      const notelSaveBtn = document.querySelector('button[name="KemaskiniNotel"]');
      notelSaveBtn.disabled = true;
      notelSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');

      notelInput.addEventListener('input', function() {
        if (validateNotel(this.value)) {
          notelSaveBtn.disabled = false;
          notelSaveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
          notelSaveBtn.disabled = true;
          notelSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
      });

      // Validasi password
      const passwordInput = document.querySelector('input[name="password"]');
      const password2Input = document.querySelector('input[name="password2"]');
      const passwordSaveBtn = document.querySelector('button[name="KemaskiniPassword"]');
      passwordSaveBtn.disabled = true;
      passwordSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');

      function checkPasswords() {
        const isValid = validatePassword(passwordInput.value) &&
          passwordInput.value === password2Input.value;

        if (isValid) {
          passwordSaveBtn.disabled = false;
          passwordSaveBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
          passwordSaveBtn.disabled = true;
          passwordSaveBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
      }

      passwordInput.addEventListener('input', checkPasswords);
      password2Input.addEventListener('input', checkPasswords);
    });
  </script>
  <script>
    const menuButton = document.getElementById('menuButton');
    const dropdownMenu = document.getElementById('dropdownMenu');

    menuButton.addEventListener('click', () => {
      dropdownMenu.classList.toggle('hidden');
    });

    // Tutup dropdown bila klik di luar
    document.addEventListener('click', (event) => {
      if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
        dropdownMenu.classList.add('hidden');
      }
    });
  </script>
  <script>
    const notifsuccess = new Audio('lib/audio/notif.mp3'); // Path fail audio success
    const notiferror = new Audio('lib/audio/error.mp3'); // Path fail audio ralat
    const notifinfo = new Audio('lib/audio/info.mp3'); //  Path fail audio info
    const notifwarning = new Audio('lib/audio/warning.mp3'); // Path fail audio amaran

    // setkan function toast 
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
  <script>
    // Function popup untuk menyakinkan pengguna

    // popup butang delete
    function confirmDelete() {
      const emailInput = document.getElementById('deleteEmail');
      const expectedEmail = "<?= $m['email'] ?>";
      if (emailInput.value !== expectedEmail) {
        notiferror.play();
        Toast.fire({
          icon: "error",
          title: "Email tidak sepadan"
        });
        return;
      }
      notifwarning.play();
      Swal.fire({
        title: 'Anda pasti mahu padam akaun?',
        text: "Tindakan ini tidak boleh dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Ya, padam akaun!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('delete-email-form').submit();
        }
      });
    }

    // Functio bila tekan button kemaskini
    function Kemaskini(id) {
      notifwarning.play();
      Swal.fire({
        title: 'Anda pasti mahu kemaskini data?',
        text: "Tindakan ini tidak boleh dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, kemaskini!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById(id).submit();
        }
      });
    }
  </script>
</body>

</html>