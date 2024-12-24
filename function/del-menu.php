<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

# memanggil fail kawalan-admin.php
include('admin-only.php');

# menyemak kewujudan data GET id_menu
if(!empty($_GET))
{
    # memanggil fail connection
    include('connection.php');

    # arahan SQL untuk memadam data pengguna berdasarkan id_menu yang dihantar
    $arahan     =   "delete from makanan where kod_makanan='".$_GET['id_menu']."'";

    # melaksanakan arahan SQL padam data dan menguji proses padam data
    if(mysqli_query($condb,$arahan))
    {
        # jika data berjaya dipadam
        echo "<script>alert('Padam data Berjaya');
        window.location.href='../admin/list-menu.php';</script>";
    }
    else
    {
        # jika data gagal dipadam
        echo "<script>alert('Padam data gagal');
        window.location.href='../admin/list-menu.php';</script>";
    }
}
else
{
    # jika data GET tidak wujud (empty)
    die("<script>alert('Ralat! akses secara terus');
    window.location.href='../admin/list-menu.php';</script>");
}
?>