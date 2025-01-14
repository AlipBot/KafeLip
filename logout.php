<?php
# mula dan Padam data session
session_start();
session_unset();
session_destroy();

// padam data cookie yang di simpan data session di dalamnya
if (ini_get("session.use_cookies")) {
    setcookie(session_name(), '', time() - 3600, '/');
}
# redirect ke halaman login
header("Location: login.php?status=logout");
exit();
