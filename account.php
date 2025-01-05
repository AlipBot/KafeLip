<?php
include("function/autoKeluar.php");
// --- Koneksi Database ---
include("function/connection.php"); // Pastikan path file koneksi benar

if (isset($_SESSION['orders'])) {
  $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
  $bil = "";
}

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

  $sqlKemaskiniNama = "UPDATE pelanggan SET nama = '$nama' WHERE notel = '" . $_SESSION['notel'] . "'";
  if (mysqli_query($condb, $sqlKemaskiniNama)) {
    $_SESSION['nama'] = $nama;
    $_SESSION['success'] = "Nama Berjaya Di Kemaskini";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Kemaskini Gagal";
    header("Location: account.php");
    exit();
  }
}

if (isset($_POST['KemaskiniEmail'])) {
  $email = $_POST['email'];

  $sql_semakemail = "select email from pelanggan where email = '$email' ";
  $check = mysqli_query($condb, $sql_semakemail);
  if (mysqli_num_rows($check) == 1) {
    $_SESSION['error'] = "EMAIL SUDAH DIGUNAKAN SILA GUNA EMAIL LAIN";
    header("Location: account.php");
    exit();
  }

  if ($email == $_SESSION['email']) {
    $_SESSION['error'] = "EMAIL SAMA DENGAN EMAIL SEMASA";
    header("Location: account.php");
    exit();
  }

  $sqlKemaskiniEmail = "UPDATE pelanggan SET email = '" . $_POST['email'] . "' WHERE notel = '" . $_SESSION['notel'] . "'";
  if (mysqli_query($condb, $sqlKemaskiniEmail)) {
    $_SESSION['email'] = $email;
    $_SESSION['success'] = "Kemaskini Berjaya";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Kemaskini Gagal";
    header("Location: account.php");
    exit();
  }
}

if (isset($_POST['KemaskiniNotel'])) {
  $notel = $_POST['notel'];

  $sql_semaknotel = "select notel from pelanggan where notel = '$notel' ";
  $check = mysqli_query($condb, $sql_semaknotel);
  if (mysqli_num_rows($check) == 1) {
    $_SESSION['error'] = "NOMBOR TELEFON SUDAH DIGUNAKAN SILA GUNA NOMBOR TELEFON LAIN";
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

  $sqlKemaskiniNotel = "UPDATE pelanggan SET notel = '" . $_POST['notel'] . "' WHERE notel = '" . $_SESSION['notel'] . "'";
  if (mysqli_query($condb, $sqlKemaskiniNotel)) {
    $_SESSION['notel'] = $notel;
    $_SESSION['success'] = "Nombor Telefon Berjaya Di Kemaskini";
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Kemaskini Gagal";
    header("Location: account.php");
    exit();
  }
}

if (isset($_POST['KemaskiniPassword'])) {
  $pass = $_POST['password'];
  $pass2 = $_POST['password2'];
  if ($pass != $pass2) {
    $_SESSION['error'] = "Kata Laluan Tidak Sama";
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

  $sqlKemaskiniPassword = "UPDATE pelanggan SET password = '" . $_POST['password'] . "' WHERE notel = '" . $_SESSION['notel'] . "'";
  mysqli_query($condb, $sqlKemaskiniPassword);
  $_SESSION['success'] = "Kemaskini Berjaya";
  header("Location: account.php");
  exit();
}

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
    $_SESSION['error'] = "EMAIL TIDAK SEPADAN";
    header("Location: account.php");
    exit();
  }

  // Laksanakan penghapusan
  $sqlHapusAkaun = "DELETE FROM pelanggan WHERE notel = ? AND email = ?";
  $stmt = mysqli_prepare($condb, $sqlHapusAkaun);
  mysqli_stmt_bind_param($stmt, "ss", $notel, $email);

  if (mysqli_stmt_execute($stmt)) {
    header("Location: account.php");
    exit();
  } else {
    $_SESSION['error'] = "Gagal memadam akaun";
    header("Location: account.php");
    exit();
  }
}

$sql        =   "select* from pelanggan where notel = '" . $_SESSION['notel'] . "' AND email = '" . $_SESSION['email'] . "' LIMIT 1";
$laksana    =   mysqli_query($condb, $sql);
$m          =   mysqli_fetch_array($laksana);


?>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <title>
    Account Settings
  </title>
  <script src="https://cdn.tailwindcss.com">
  </script>
  <!-- SweetAlert2 CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
  <script>
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
  <style>
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

  <div class="w-full bg-[#FAF3DD]">
    <div class="container mx-auto flex justify-between items-center py-6 px-4">
      <div class="logo text-2xl font-bold flex items-center mr-4">
        <i class="fas fa-coffee text-[#4A7C59] mr-2">
        </i>
        <span class="text-black">
          Kafe
        </span>
        <span class="text-black">
          lip
        </span>
      </div>
      <div class="nav flex gap-6 -ml-10 mr-20">
        <a class="text-black font-medium active:text-[#4A7C59]" href="index.php">
          <i class="fas fa-home text-[#4A7C59] mr-1"></i>
          <span>LAMAN UTAMA</span>
        </a>
        <a class="text-black font-medium active:text-[#4A7C59]" href="cart.php">
          <i class="fas fa-shopping-cart text-[#4A7C59] mr-1"></i>
          <span>CART <?= $bil ?></span>
        </a>
        <a class="text-black font-medium active:text-[#4A7C59]" href="sejarah-tempah.php">
          <i class="fas fa-history text-[#4A7C59] mr-1"></i>
          <span>Sejarah Tempahan</span>
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


  <main class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-center mb-4">
      Account
    </h1>
    <p class="text-center mb-8">
      Update your profile and set your account preferences.
    </p>
    <div class="flex flex-col md:flex-row justify-center">
      <section class="w-full md:w-3/4 space-y-8">

        <form id='KemaskiniNama' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
        <input type="hidden" name="KemaskiniNama" value="1">
          <h2 class="text-xl font-bold mb-4">
            Nama
          </h2>
          <p class="text-gray-600 mb-4">
            If you change your username all the existing links to your profile on other websites will become 404.
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

        <form id='KemaskiniEmail' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
        <input type="hidden" name="KemaskiniEmail" value="1">
          <h2 class="text-xl font-bold mb-4">
            Email
          </h2>
          <p class="text-gray-600 mb-4">
            Email address
          </p>
          <div class="flex items-center">
            <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="email" required type="email" name="email" value="<?= $m['email'] ?>" />

          </div>
          <p class="text-red-500 text-sm mt-1 hidden" id="emailError">
            Please enter a valid email address.
          </p>
          <div class="flex justify-end">
            <button class="ml-4 my-5 bg-blue-500 text-white px-4 py-2 rounded-lg" name="KemaskiniEmail" type="button" onclick="Kemaskini('KemaskiniEmail')">
              Kemaskini
            </button>
          </div>
        </form>

        <form id='KemaskiniNotel' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
        <input type="hidden" name="KemaskiniNotel" value="1">
          <h2 class="text-xl font-bold mb-4">
            Nombor Telefon
          </h2>
          <p class="text-gray-600 mb-4">
            Phone number
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

        <form id='KemaskiniPassword' method='POST' class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg		">
        <input type="hidden" name="KemaskiniPassword" value="1">
          <h2 class="text-xl font-bold mb-4">
            Change a Password
          </h2>
          <p class="text-gray-600 mb-4">
            Create a password to access with your email when Google and Apple are not available.
          </p>
          <div class="space-y-4">
            <div class="flex items-center relative">
              <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="password" placeholder="Min 8 characters" required type="password" name="password" />
              <i class="fas fa-eye ml-4 text-gray-500 absolute right-3 cursor-pointer" id="password-icon" onclick="togglePasswordVisibility('password')">
              </i>
            </div>
            <p class="text-red-500 text-sm mt-1 hidden" id="passwordError">
              Password must be 8-12 characters long, contain at least one uppercase letter, one lowercase letter, and one number.
            </p>
            <div class="flex items-center relative">
              <input class="border border-gray-300 rounded-lg p-2 flex-grow" id="repeat-password" placeholder="Repeat password" required type="password" name="password2" />
              <i class="fas fa-eye ml-4 text-gray-500 absolute right-3 cursor-pointer" id="repeat-password-icon" onclick="togglePasswordVisibility('repeat-password')">
              </i>
            </div>
            <p class="text-red-500 text-sm mt-1 hidden" id="confirmPasswordError">
              Passwords do not match.
            </p>
          </div>
          <div class="flex justify-end">
            <button class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-lg" name="KemaskiniPassword" type="button" onclick="Kemaskini('KemaskiniPassword')">
              Kemaskini
            </button>
          </div>
        </form>

        <form id="delete-email-form" action="" method="POST" class="bg-white shadow rounded-lg p-6 mx-auto max-w-lg">
          <input type="hidden" name="HapusAkaun" value="1">
          <h2 class="text-xl font-bold mb-4">
            Delete Account
          </h2>
          <p class="text-gray-600 mb-4">
            Enter your email to delete your account
          </p>
          <div class="flex items-center">
            <input id="deleteEmail" class="border border-gray-300 rounded-lg p-2 flex-grow" placeholder="Sila ketik <?= $m['email'] ?>" required type="email" name="email" />
          </div>
          <p class="text-red-500 text-sm mt-1 hidden" id="deleteEmailError">
            Please enter a valid email address.
          </p>
          <div class="flex justify-end">
            <button type="button" class="delete-email ml-4 my-5 bg-red-500 text-white px-4 py-2 rounded-lg" onclick="confirmDelete()">
              Delete
            </button>
          </div>
        </form>

      </section>
    </div>
  </main>

  <footer class="w-full bg-[#FAF3DD] text-black py-6 px-10">
    <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
      <div class="mb-4 lg:mb-0">
        © 2023 KAFELIP. All rights reserved.
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
        <a class="text-[#4A7C59]" href="#">
          <i class="fab fa-instagram">
          </i>
        </a>
      </div>
    </div>
  </footer>
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
    const notifsuccess = new Audio('lib/audio/notif.mp3'); // Tukar path ke fail audio anda
    const notiferror = new Audio('lib/audio/error.mp3'); // Tukar path ke fail audio anda
    const notifinfo = new Audio('lib/audio/info.mp3'); // Tukar path ke fail audio anda
    const notifwarning = new Audio('lib/audio/warning.mp3'); // Tukar path ke fail audio anda



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
  </script>
  <script>
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