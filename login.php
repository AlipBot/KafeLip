<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

// Periksa login terlebih dahulu
if (isset($_POST['LogMasuk'])) {
    include("function/connection.php");
    $email = $_POST['email'];
    $password = $_POST['pass'];

    $cari = "select * from pelanggan
             where email = '$email'
             and password = '$password' limit 1";

    $cek = mysqli_query($condb, $cari);

    if (mysqli_num_rows($cek) == 1) {
        $m = mysqli_fetch_array($cek);
        $_SESSION['nama'] = $m['nama'];
        $_SESSION['notel'] = $m['notel'];
        $_SESSION['email'] = $m['email'];
        $_SESSION['tahap'] = $m['tahap'];

        if ($m['tahap'] == "ADMIN") {
            $_SESSION['success'] = "Hai Boss  <br>" . $_SESSION['nama'];
            header("Location: admin/panel.php");
            exit();
        } else {
            $_SESSION['success'] = "Log Masuk Berjaya";
            header("Location: menu.php");
            exit();
        }
    } else {
        $_SESSION['error'] = "Tolong Semak Semula Email Dan Password";
        header("Location: login.php");
        exit();
    }
}

// Periksa jika sudah log masuk
if (!empty($_SESSION['tahap'])) {
    header("Location: menu.php");
    exit();
}

// Tambah kod ini sebelum HTML output bermula
if (isset($_GET['status']) && $_GET['status'] === 'logout') {
    $_SESSION['success'] = "Berjaya Log Keluar";
}

// HTML output bermula selepas semua logic PHP
?>

<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>
        Login Page
    </title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;display=swap" rel="stylesheet" />
</head>

<body class="bg-[#FAF3DD] font-roboto">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-[#A1CCA5] p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-center mb-4">
                <img alt="Company logo with a detailed description of the logo design" class="w-24 h-24" height="100" src="https://storage.googleapis.com/a1aa/image/LQPFEfzjLQ3FSiVaoQjIIsMkqbnxqfbQMfo1vKLZeMkeuhEgC.jpg" width="100" />
            </div>
            <h2 class="text-2xl font-bold text-center mb-6">
                Login to Your Account
            </h2>
        <form action="" method='POST'>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">
                        Email
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" placeholder="Enter your email" type="email" name='email' required/>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700" for="password">
                        Password
                    </label>
                    <div class="relative">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" id="password" placeholder="Enter your password" type="password"  name='pass' required />
                        <button class="absolute inset-y-0 right-0 px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="togglePassword" type="button">
                            <i class="fas fa-eye-slash">
                            </i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center">
                        <input class="mr-2" id="remember" type="checkbox" />
                        <label class="text-gray-700" for="remember">
                            Remember me
                        </label>
                    </div>
                    <a class="text-blue-500 hover:underline" href="#">
                        Forgot password?
                    </a>
                </div>
                <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200" type="submit" id="submit" name="LogMasuk" value="Login">
                    Login
                </button>
            </form>
            <p class="text-center text-gray-700 mt-4">
                Don't have an account?
                <a class="text-blue-500 hover:underline" href="signup.php">
                    Sign up
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
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['info'])): ?>
                Toast.fire({
                    icon: "info",
                    title: "<?= $_SESSION['info'] ?>"
                });
                <?php unset($_SESSION['info']); ?>
            <?php endif; ?>

            // Untuk popup error
            <?php if (isset($_SESSION['error'])): ?>
                Toast.fire({
                    icon: "error",
                    title: "<?= $_SESSION['error'] ?>"
                });
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
        })
    </script>
</body>

</html>