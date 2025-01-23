<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\
include("autoKeluar.php");
include('connection.php');
//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\
#  semak ada session orders 
if (!isset($_SESSION['orders'])) {
    $_SESSION['info'] = "Cart Anda Kosong";
    header("Location: ../menu.php");
    exit();
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
        $sqlharga = "SELECT * FROM makanan WHERE makanan.kod_makanan = '$key' ORDER BY makanan.kod_makanan";
        $ceharga = mysqli_fetch_assoc(mysqli_query($condb, $sqlharga));
        $jum = $ceharga['harga'] * $bil;

        $sqltempah  =   "insert into tempahan set
                        email              = '" . $_SESSION['email'] . "',
                        kod_makanan        =   '$key',
                        tarikh             =  '$tarikh',
                        jumlah_harga      =   '" . $jum . "',
                        kuantiti        =   '$bil' ";
        $laktempah  =   mysqli_query($condb, $sqltempah);
    }

    if ($laktempah) {
        $_SESSION['success'] = "Tempahan Berjaya, Sila Cetak Resit Anda";
    } else {
        #jika gagal papar punca error
        $_SESSION['error'] = "Tempahan Gagal: " . mysqli_error($condb);
    }
    # Memadam nilai pembolehubah session
    unset($_SESSION['orders']);
    header("Location: ../resit.php?tarikh=$tarikh");
    exit();
}
