<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();
if (!empty($_SESSION['tahap'])) { ?>
    <script>
        window.location.href = 'menu.php';
    </script>
<?php }

if (isset($_POST['DaftarMasuk'])) {

    include("function\connection.php");

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

    if (strlen($password) < 7) {
        $_SESSION['error'] = "KATA LAUAN MESTI 8 AKSARA KE ATAS";
        header("Location: signup.php");
        exit();
    }

    if (strlen($password) > 13) {
        $_SESSION['error'] = "KATA LAUAN MESTI TIDAK BOLEH LEBIH 12 AKSARA";
        header("Location: signup.php");
        exit();
    }

    if (strlen($notel) < 10) {
        $_SESSION['error'] = "NOMBOR TELEFON MESTI 10 KE ATAS";
        header("Location: signup.php");
        exit();
    }

    if (strlen($notel) > 15) {
        $_SESSION['error'] = "NOMBOR TELEFON MESTI TIDAK BOLEH LEBIH 14";
        header("Location: signup.php");
        exit();
    }

    $sql_semak = "select email from pelanggan where email = '$email' ";
    $check = mysqli_query($condb, $sql_semak);
    if (mysqli_num_rows($check) == 1) {
        $_SESSION['error'] = "EMAIL SUDAH DIGUNAKAN SILA GUNA EMAIL LAIN";
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
    <title>
        Signup Page
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
</head>

<body class="bg-[#FAF3DD]  font-roboto">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-[#A1CCA5] p-8 rounded-lg shadow-lg my-10 w-full max-w-md">
            <div class="flex justify-center mb-4">
                <img alt="Company logo with a detailed description of the logo design" class="w-24 h-24" height="100" src="https://storage.googleapis.com/a1aa/image/LQPFEfzjLQ3FSiVaoQjIIsMkqbnxqfbQMfo1vKLZeMkeuhEgC.jpg" width="100" />
            </div>
            <h2 class="text-2xl font-bold text-center mb-6">
                Create Your Account
            </h2>
            <form id="signupForm" action='' method='POST'>
                <div class="mb-4">
                    <label class="block text-gray-700" for="name">
                        Name
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="name" placeholder="Enter your name" type="text" name='nama' required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="phone">
                        Phone Number
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="phone" placeholder="Enter your phone number" type="tel" name='notel' required />
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">
                        Email
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" placeholder="Enter your email" type="email" name='email' required />
                    <p class="text-red-500 text-sm mt-1 hidden" id="emailError">
                        Please enter a valid email address.
                    </p>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" id="password" placeholder="Enter your password" type="password" name='pass' required />
                        <button class="absolute inset-y-0 right-0 px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="togglePassword" type="button">
                            <i class="fas fa-eye-slash">
                            </i>
                        </button>
                    </div>
                    <p class="text-red-500 text-sm mt-1 hidden" id="passwordError">
                        Password must be 8-12 characters long, contain at least one uppercase letter, one lowercase letter, and one number.
                    </p>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700" for="confirm-password">
                        Confirm Password
                    </label>
                    <div class="relative">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" id="confirm-password" placeholder="Confirm your password" type="password" name="pass2" required />
                        <button class="absolute inset-y-0 right-0 px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="toggleConfirmPassword" type="button">
                            <i class="fas fa-eye-slash">
                            </i>
                        </button>
                    </div>
                    <p class="text-red-500 text-sm mt-1 hidden" id="confirmPasswordError">
                        Passwords do not match.
                    </p>
                </div>
                <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200" type="submit" name="DaftarMasuk" value="Singup">
                    Sign Up
                </button>
            </form>
            <p class="text-center text-gray-700 mt-4">
                Already have an account?
                <a class="text-blue-500 hover:underline" href="login.php">
                    Login
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
</body>

</html>