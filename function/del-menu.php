<?php
include('autoKeluarAdmin.php');


# menyemak kewujudan data GET id_menu
if(!empty($_GET))
{
    # memanggil fail connection
    include('connection.php');

    # arahan SQL untuk memadam data pengguna berdasarkan id_menu yang dihantar
    $arahan     =   "delete from makanan where kod_makanan='".$_GET['id_menu']."'";

        // Get the filename from the database
        $sql = "SELECT gambar FROM makanan WHERE kod_makanan = '".$_GET['id_menu']."'";
        $result = mysqli_query($condb, $sql);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $filename = $row['gambar'];
            $filepath = "../menu-images/" . $filename;
    
            // Check if the file exists and delete it
            if (file_exists($filepath)) {
                unlink($filepath);  // Delete the file
            }
        }
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