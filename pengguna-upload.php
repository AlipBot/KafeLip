<?php
# memulakan fungsi session
session_start();

# memanggil fail header, kawalan-admin 
include('function/header.php');
include('function/kawalan-admin.php');
?>

<!-- Tajuk Laman -->
<h3>Muat Naik Data Pekerja (*.txt)</h3>

<!-- Borang untuk memuat naik fail -->
<form action='' method='POST' enctype='multipart/form-data'>

    <h3><b>Sila Pilih Fail txt yang ingin diupload</b></h3>
    <input type='file' name='data_pengguna'>
    <button type='submit' name='upload'>Muat Naik</button>

</form>
<?php include('function/footer.php'); ?>

<!-- Bahagian Memproses Data yang dimuat naik -->
<?PHP
# data validation : menyemak kewujudan data dari borang
if (isset($_POST['upload'])) {
    # memanggil fail connection
    include('function/connection.php');

    # mengambil nama sementara fail
    $namafailsementara = $_FILES["data_pengguna"]["tmp_name"];

    # mengambil nama fail
    $namafail = $_FILES['data_pengguna']['name'];

    # mengambil jenis fail
    $jenisfail = pathinfo($namafail, PATHINFO_EXTENSION);

    # menguji jenis fail dan sail fail 
    if ($_FILES["data_pengguna"]["size"] > 0 and $jenisfail == "txt") {
        # membuka fail yang diambil
        $fail_data_pengguna = fopen($namafailsementara, "r");

        $bil = 0;
        # mendapatkan data dari fail baris demi baris
        while (!feof($fail_data_pengguna)) {
            # mengambil data sebaris sahaja bg setiap pusingan 
            $ambilbarisdata = fgets($fail_data_pengguna);

            # memecahkan baris data mengikut tanda pipe
            $pecahkanbaris = explode("|", $ambilbarisdata);

            # selepas pecahan tadi akan diumpukan kepada 5
            list($email, $notel, $nama, $katalaluan) = $pecahkanbaris;

            $pilih = mysqli_query($condb, "select* from pelanggan where notel='" . $notel . "'");
            if (mysqli_num_rows($pilih) == 1) {
                echo "<script>
        alert ('notel $notel di fail txt telah ada di pangkalan data. 
               TUKAR NOTEL DALAM FAIL TXT');
            </script> ";
            } else {

                # arahan SQL untuk menyimpan data
                $arahan_sql_simpan = "insert into pelanggan 
        (email, notel, nama, password, tahap) values
        ('$email','$notel','$nama','$katalaluan','ADMIN')";

                # memasukkan data kedalam jadual pengguna 
                $laksana_arahan_simpan = mysqli_query($condb, $arahan_sql_simpan);
                $bil++;
            }
        }
        # menutup fail txt yang dibuka
        fclose($fail_data_pengguna);

        echo "<script>
    alert('import fail Data Selesai. Sebanyak $bil data telah disimpan');
    window.location.href='pengguna-senarai.php'; 
</script>";
    } else {
        # jika fail yang dimuat naik kosong atau tersalah format.
        echo "<script>alert('hanya fail berformat txt sahaja dibenarkan');</script>";
    }
}

?>