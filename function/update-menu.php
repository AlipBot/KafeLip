<?php
include('autoKeluarAdmin.php');
include('connection.php');

# Menyemak kewujudan data POST
if(!empty($_POST)){
    # Mengambil data daripada borang (form)
    $id_menu        =   $_POST['id_menu'];
    $nama_menu      =   $_POST['nama_menu'];
    $harga          =   $_POST['harga'];
    $tambahan       =   '';

    # Data validation : had atas
    if(!is_numeric($harga) and $harga > 0){
        $_SESSION['error'] = "Ralat: Sila masukkan harga yang sah";
        header("Location: ../admin/list-menu.php");
        exit();
    }

    # Dapatkan data gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        # Mengambil data gambar
        $file_extension    =   pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
        # Buang jarak dan tukar kepada huruf kecil
        $nama_fail_baru    =   strtolower(str_replace(' ', '', $nama_menu)) . '.' . $file_extension;
        $lokasi             =   $_FILES['gambar']['tmp_name'];
        $tambahan = $tambahan."gambar = '".$nama_fail_baru ."',";
        
        // Get the filename from the database
        $sql = "SELECT gambar FROM makanan WHERE kod_makanan = '$id_menu'";
        $result = mysqli_query($condb, $sql);
    
        if ($row = mysqli_fetch_assoc($result)) {
            $filename = $row['gambar'];
            $filepath = "../menu-images/" . $filename;
    
            // Check if the file exists and delete it
            if (file_exists($filepath)) {
                unlink($filepath);  // Delete the file
            }
            copy($lokasi,"../menu-images/". $nama_fail_baru);
        }
    } 
    
    # proses kemaskini data
    $sql_kemaskini  =   "update makanan set
                        $tambahan
                        nama_makanan   = '$nama_menu',
                        harga       = '$harga'                
                        where
                        kod_makanan     =  '$id_menu' ";
    $laksana        =   mysqli_query($condb,$sql_kemaskini);

    # Pengujian proses menyimpan data 
    if($laksana){
        #jika berjaya
        $_SESSION['success'] = "Kemaskini Berjaya";
        header("Location: ../admin/list-menu.php");
        exit();
    }else{
        #jika gagal papar punca error
        $_SESSION['error'] = "Kemaskini Gagal: " . mysqli_error($condb);
        header("Location: ../admin/list-menu.php");
        exit();
    }
} 
?>