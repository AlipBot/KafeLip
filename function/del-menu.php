<?php
include('autoKeluarAdmin.php'); # kawalan admin 

# menyemak kewujudan data GET id_menu
if(!empty($_GET)){
    # memanggil fail connection
    include('connection.php');

    # Dapatkan parameter URL
    $redirect_params = [];
    if (!empty($_GET['current_page'])) {
        $redirect_params[] = "halaman=" . $_GET['current_page'];
    }
    if (!empty($_GET['search_query'])) {
        $redirect_params[] = "nama_makanan=" . urlencode($_GET['search_query']);
    }
    if (!empty($_GET['sort'])) {
        $redirect_params[] = "sort=" . $_GET['sort'];
    }

    $redirect_url = "../admin/list-menu.php";
    if (!empty($redirect_params)) {
        $redirect_url .= "?" . implode("&", $redirect_params);
    }

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
        $_SESSION['success'] = "Berjaya padam data";
        header("Location: " . $redirect_url);
        exit();
    } else {
      $_SESSION['error'] = "Kemaskini Gagal: " . mysqli_error($condb);
      header("Location: " . $redirect_url);
      exit();
    }
} else{
    #jika gagal papar punca error
    $_SESSION['error'] = "Ralat! akses secara terus";
    header("Location: ../admin/list-menu.php");
    exit();
}
?>