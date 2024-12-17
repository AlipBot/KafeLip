<?php
# Memulakan fungsi session dan memanggil fail header.php
session_start();

include('function/connection.php');

if(!isset($_SESSION['orders'])){
    die("<script>
        alert('Cart anda kosong');
        window.location.href='menu.php';
    </script>");
} else {
    # dapatkan bilangan setiap elemen
    $frekuensi = array_count_values($_SESSION['orders']);

    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($frekuensi, function($count) {
        return $count >= 1;
    });



    # Mendapatkan data menu dan menyimpankannya dalam jadual tempahan
    foreach($sama as $key => $bil) { 
      

        $sqltempah  =   "insert into tempahan set
                        email              = '".$_SESSION['email']."',
                        kod_makanan        =   '$key',
                        jumlah_harga      =   '".$_SESSION['jumlah_harga']."',
                        kuantiti        =   '$bil' ";
        $laktempah  =   mysqli_query($condb,$sqltempah);

    }

# Memadam nilai pembolehubah session
unset($_SESSION['orders']);
unset($_SESSION['jumlah_harga']);
$timestamp = date('Y-m-d H:i:s'); // Format timestamp semasa
echo "<script>alert('Tempahan Selesai'); 
window.location.href='tempah-resit.php?timestamp=$timestamp';
</script>";


} 

?>