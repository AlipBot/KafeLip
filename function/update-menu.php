<?php
# Memulakan fungsi session
session_start();
include('connection.php');
include('kawalan-admin.php');

# Menyemak kewujudan data POST
if(!empty($_POST)){

    # Mengambil data daripada borang (form)
    $id_menu        =   $_GET['id_menu'];
    $nama_menu      =   $_POST['nama_menu'];
    $harga          =   $_POST['harga'];
    $tambahan       =   '';

    # Dapatkan data gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $nama_fail          =   $_FILES['gambar']['name'];
        $lokasi             =   $_FILES['gambar']['tmp_name'];
        $tambahan = $tambahan."gambar = '".$nama_fail ."',";
        move_uploaded_file($lokasi,"../menu-images".$nama_fail);
    } 

    # Data validation : had atas
    if(!is_numeric($harga) and $harga > 0){
        die("<script>
                alert('Ralat Harga');
                location.href='../admin/info-menu.php';
            </script>" );
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
        echo "  <script>
                alert('Kemaskini Berjaya');
                location.href='../admin/list-menu.php';
                </script>";  
    }else{
        #jika gagal papar punca error
        echo "<p style='color:red;'>Kemaskini Gagal</p>";
        echo $sql_kemaskini.mysqli_error($condb);
    }
} ?>