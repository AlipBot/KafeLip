<?php
# Set masa session biar tahan lama dan bila tutup browser dat session masih ada
$lifetime = 60 * 60 * 24 * 30; # Setkan 30 hari atau 1 bulan
session_set_cookie_params($lifetime);
session_start();

//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\


include("function/connection.php"); # sambung ke database

//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\

# function semak data yang dimasukan
function SemakLogin($input)
{
    // semak input email ke nombor telefon
    if (filter_var($input, FILTER_VALIDATE_EMAIL)) {
        return 'email';
    }
    // jika nombor telefon (digit dan nombor 10-15 )
    elseif (preg_match('/^[0-9]{10,15}$/', $input)) {
        return 'notel';
    } else {
        return false; //  Jika data bukan email dan nombor telefon 
    }
}

// POST DATA LOG MASUK
if (isset($_POST['LogMasuk'])) {
    $emailORnotel = $_POST['emailOrNotel'];
    $SemakinputLogin = SemakLogin($emailORnotel);  # tentukan jenis data dimasukan notel or email
    $loginSql = "";

    if ($SemakinputLogin) {
        if ($SemakinputLogin == "email") {
            $loginSql = "email = '" . $emailORnotel . "'";
        } elseif ($SemakinputLogin == "notel") {
            $loginSql = "notel = '" . $emailORnotel . "'";
        }
    } else {
        $_SESSION['error'] = "Sila masukkan email atau no. tel sahaja";
        header("Location: login.php");
        exit();
    }

    $password = $_POST['pass'];

    $cari = "select * from pelanggan
             where " . $loginSql . "
             and password = '$password' limit 1";

    $cek = mysqli_query($condb, $cari);

    if (mysqli_num_rows($cek) == 1) {
        $m = mysqli_fetch_array($cek);
        # simpan data data pengguna ke session
        $_SESSION['nama'] = $m['nama'];
        $_SESSION['notel'] = $m['notel'];
        $_SESSION['email'] = $m['email'];
        $_SESSION['tahap'] = $m['tahap'];

        if ($m['tahap'] == "ADMIN") {
            #jika akaun login ialah admin redirect ke page panel
            $_SESSION['success'] = "Hai Boss  <br>" . $_SESSION['nama'];
            header("Location: admin/panel.php");
            exit();
        } else {
            # jika bukan ke menu
            $_SESSION['success'] = "Log Masuk Berjaya";
            header("Location: menu.php");
            exit();
        }
    } else {
        # jika email atau notel tidak wujud
        $_SESSION['error'] = "Tolong Semak Semula Email Dan Password";
        header("Location: login.php");
        exit();
    }
}

// jika data session tahap telah wujud atau pengguna sudah login maka redirect ke page menu tidak perlu login semula
if (!empty($_SESSION['tahap'])) {
    header("Location: menu.php");
    exit();
}

//  jika pengguna logout dan terdapat parameter status logout keluar toast
if (isset($_GET['status']) && $_GET['status'] === 'logout') {
    $_SESSION['success'] = "Berjaya Log Keluar";
    header("Location: login.php");
    exit();
}

?>

<html lang="ms">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Log Masuk</title>
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

<body class="bg-[#FAF3DD] font-roboto">
    <!-- Kandungan -->
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="bg-[#A1CCA5] p-8 rounded-lg shadow-lg w-full max-w-md">
            <div class="flex justify-center mb-4">
                <img alt="Logo KafeLip" class="w-24 h-24" height="100" src="lib/icon/logo.png" width="100" />
            </div>
            <h2 class="text-2xl font-bold text-center mb-6">
                Log Masuk
            </h2>
            <form action="" method='POST'>
                <div class="mb-4">
                    <label class="block text-gray-700" for="email">
                        Email Atau Nombor Telefon
                    </label>
                    <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" id="email" placeholder="Masukkan email atau Nombor Telefon Anda" type="text" name='emailOrNotel' required />
                </div>
                <div class="mb-4 relative">
                    <label class="block text-gray-700" for="password">
                        Kata Laluan
                    </label>
                    <div class="relative">
                        <input class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 pr-10" id="password" placeholder="Masukkan Kata Laluan Anda" type="password" name='pass' required />
                        <button class="absolute inset-y-0 right-0 px-3 py-2 text-gray-600 hover:text-gray-800 focus:outline-none" id="togglePassword" type="button">
                            <i class="fas fa-eye-slash">
                            </i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between mb-4">
                    <a class="text-blue-500 hover:underline" href="#">
                        Lupa Kata Laluan?
                    </a>
                </div>
                <button class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition duration-200" type="submit" id="submit" name="LogMasuk" value="Login">
                    Log Masuk
                </button>
            </form>
            <p class="text-center text-gray-700 mt-4">
                Tidak mempunyai akun?
                <a class="text-blue-500 hover:underline" href="signup.php">
                    Daftar akaun baharu
                </a>
            </p>
        </div>
    </div>

    
    <script>
        // function sorokkan dan tunjukkan kata laluan
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordField.setAttribute('type', type);
            this.querySelector('i').classList.toggle('fa-eye');
            this.querySelector('i').classList.toggle('fa-eye-slash');
        });

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