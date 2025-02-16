<?php
//―――――――――――――――――――――――――――――――――― ┏  Setkan session ┓ ―――――――――――――――――――――――――――――――― \\
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("function\connection.php"); # sambung ke dalam database
//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\
#  semak jika session tahap wujud redirect ke menu.php
if (!empty($_SESSION['tahap'])) { ?>
    <script>
        window.location.href = 'menu.php';
    </script>
<?php }
# POST DaftarMasuk
if (isset($_POST['DaftarMasuk'])) {

    $nama = $_POST["nama"];
    $notel = $_POST["notel"];
    $email = $_POST["email"];
    $password = $_POST["pass"];
    $password2 = $_POST["pass2"];

    if ($password != $password) {
        $_SESSION['error'] = "KATA LAUAN TIDAK SAMA";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) < 8) {
        $_SESSION['error'] = "KATA LAUAN MESTI 8 AKSARA KE ATAS";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) > 12) {
        $_SESSION['error'] = "KATA LAUAN MESTI TIDAK BOLEH LEBIH 12 AKSARA";
        header("Location: signup.php");
        exit();
    }

    // Pengesahan format nombor telefon Malaysia
    if (!preg_match("/^(01)[0-46-9][0-9]{7,8}$/", $notel)) {
        $_SESSION['error'] = "SILA MASUKKAN NOMBOR TELEFON MALAYSIA YANG SAH";
        header("Location: signup.php");
        exit();
    }

    if (strlen($notel) < 10) {
        $_SESSION['error'] = "NOMBOR TELEFON MESTILAH 10 DIGIT KE ATAS";
        header("Location: signup.php");
        exit();
    }

    if (strlen($notel) > 11) {
        $_SESSION['error'] = "NOMBOR TELEFON TIDAK BOLEH MELEBIHI 11 DIGIT";
        header("Location: signup.php");
        exit();
    }

    #Jika email lebih daripada 50
    if (strlen($email) > 50) {
        $_SESSION['error'] = "EMAIL tidak boleh lebih daripada 50 aksara";
        header("Location: account.php");
        exit();
    }

    $sql_semakemail = "select email from pelanggan where email = '$email' ";
    $check = mysqli_query($condb, $sql_semakemail);
    if (mysqli_num_rows($check) == 1) {
        $_SESSION['error'] = "EMAIL SUDAH DIGUNAKAN SILA GUNA EMAIL LAIN";
        header("Location: signup.php");
        exit();
    }

    $sql_semaknotel = "select notel from pelanggan where notel = '$notel' ";
    $check = mysqli_query($condb, $sql_semaknotel);
    if (mysqli_num_rows($check) == 1) {
        $_SESSION['error'] = "NOMBOR TELEFON SUDAH DIGUNAKAN SILA GUNA NOMBOR TELEFON LAIN";
        header("Location: signup.php");
        exit();
    }

    $sql_simpan = "insert into pelanggan (email, nama, notel, password, tahap)
                   values
                   ('$email', '$nama', '$notel','$password' ,'PELANGGAN')";

    $simpan = mysqli_query($condb, $sql_simpan);
    if ($simpan) {
        $_SESSION['success'] = "Berjaya Daftar Sila Log Masuk.";
        header("Location: login.php");
        exit();
    } else {
        $_SESSION['error'] = "Gagal Daftar Sila Cuba Lagi.";
        header("Location: signup.php");
        exit();
    }
}
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Daftar Akaun</title>
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
</head>

<body class="bg-[#FAF3DD]  font-roboto">
    <!-- Content -->
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-[#A1CCA5] p-8 rounded-lg shadow-lg my-10 w-full max-w-md">
            <div class="flex justify-center mb-4">
                <img alt="Logo KafeLip" class="w-24 h-24" height="100" src="lib/icon/logo.png" width="100" />
            </div>
            <h2 class="text-2xl font-bold text-center mb-6">
                Daftar Akaun Baharu
            </h2>
            <form id="signupForm" action='' method='POST'>
                <div class="mb-4">
                    <label class="block text-gray-700" for="name">
                        Nama
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" placeholder="Masukkan Nama Anda" type="text" name='nama' required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="phone">
                        Nombor Telefon
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        id="phone"
                        placeholder="Contoh: 0123456789"
                        type="tel"
                        name='notel'
                        pattern="^(\+?6?01)[0-46-9]-*[0-9]{7,8}$"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                        maxlength="11"
                        required />
                    <p class="text-red-500 text-sm mt-1 hidden" id="phoneError">
                        Sila masukkan nombor telefon Malaysia yang sah (Contoh: 0123456789)
                    </p>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">
                        Email
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" placeholder="Masukkan Email Anda" type="email" name='email' minlength="4" maxlength="50" required />
                    <p class="text-red-500 text-sm mt-1 hidden" id="emailError">
                        Please enter a valid email address.
                    </p>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700" for="password">
                        Kata Laluan
                    </label>
                    <div class="relative">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" id="password" placeholder="Masukkan Kata Laluan Anda" type="password" name='pass' required />
                        <button class="absolute inset-y-0 right-0 px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="togglePassword" type="button">
                            <i class="fas fa-eye">
                            </i>
                        </button>
                    </div>
                    <p class="text-red-500 text-sm mt-1 hidden" id="passwordError">
                        Kata laluan mestilah 8-12 aksara panjang, mengandungi sekurang-kurangnya satu huruf besar, satu huruf kecil dan satu nombor.
                    </p>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700" for="confirm-password">
                        Sahkan Kata Laluan
                    </label>
                    <div class="relative">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" id="confirm-password" placeholder="Sahkan Kata Laluan Anda" type="password" name="pass2" required />
                        <button class="absolute inset-y-0 right-0 px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="toggleConfirmPassword" type="button">
                            <i class="fas fa-eye">
                            </i>
                        </button>
                    </div>
                    <p class="text-red-500 text-sm mt-1 hidden" id="confirmPasswordError">
                        Kata laluan tidak sepadan.
                    </p>
                </div>
                <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200" type="submit" name="DaftarMasuk" value="Singup">
                    Daftar
                </button>
            </form>
            <p class="text-center text-gray-700 mt-4">
                Sudah mempunyai akaun?
                <a class="text-blue-500 hover:underline" href="login.php">
                    Log Masuk
                </a>
            </p>
        </div>
    </div>

    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.getElementById('toggleConfirmPassword').addEventListener('click', function() {
            const confirmPasswordField = document.getElementById('confirm-password');
            const type = confirmPasswordField.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

        document.getElementById('email').addEventListener('input', function() {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const emailError = document.getElementById('emailError');
            if (!emailPattern.test(this.value)) {
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

        document.getElementById('confirm-password').addEventListener('input', function() {
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

        // Tambah event listener untuk nombor telefon
        document.getElementById('phone').addEventListener('input', function() {
            const phonePattern = /^(01)[0-46-9][0-9]{7,8}$/;
            const phoneError = document.getElementById('phoneError');
            const phone = this.value;

            if (!phonePattern.test(phone)) {
                this.classList.add('border-red-500');
                phoneError.classList.remove('hidden');
            } else {
                this.classList.remove('border-red-500');
                phoneError.classList.add('hidden');
            }
        });
    </script>
    <script>
        // function toast dan popup
        const notifsuccess = new Audio('lib/audio/notif.mp3'); // Path fail audio success
        const notiferror = new Audio('lib/audio/error.mp3'); // Path fail audio ralat
        const notifinfo = new Audio('lib/audio/info.mp3'); //  Path fail audio info
        const notifwarning = new Audio('lib/audio/warning.mp3'); // Path fail audio amaran


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
</body>

</html>