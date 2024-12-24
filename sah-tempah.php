<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

include('function/connection.php');

if (!isset($_SESSION['orders'])) {
    die("<script>
        alert('Cart anda kosong');
        window.location.href='menu.php';
    </script>");
} else {
    # dapatkan bilangan setiap elemen
    $frekuensi = array_count_values($_SESSION['orders']);

    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($frekuensi, function ($count) {
        return $count >= 1;
    });


    $tarikh = date('Y-m-d H:i:s');
    # Mendapatkan data menu dan menyimpankannya dalam jadual tempahan
    foreach ($sama as $key => $bil) {

        $sqltempah  =   "insert into tempahan set
                        email              = '" . $_SESSION['email'] . "',
                        kod_makanan        =   '$key',
                        tarikh             =  '$tarikh',
                        jumlah_harga      =   '" . $_SESSION['jumlah_harga'] . "',
                        kuantiti        =   '$bil' ";
        $laktempah  =   mysqli_query($condb, $sqltempah);
    }

    # Memadam nilai pembolehubah session
    unset($_SESSION['orders']);
    unset($_SESSION['jumlah_harga']);
    echo "<script>alert('Tempahan Selesai'); 
window.location.href='resit.php?tarikh=$tarikh';
</script>";
}
