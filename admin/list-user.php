<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

#memanggil fail 
include('../function/header.php');
include('../function/connection.php');
include('../function/admin-only.php');
?>
<!-- Header bagi jadual untuk memaparkan senarai pengguna -->
<h3 align='center'>Senarai pengguna</h3>

<table align='center' width='100%' border='1' id='saiz'>
    <tr bgcolor='cyan'>
        <td colspan='1'>
            <form action='' method='POST' style="margin:0; padding:0;">
                <input type='text' name='nama' placeholder='Carian Nama pengguna'>
                <input type='submit' value='Cari'>
            </form>
        </td>
        <td colspan='5' align='right'>
            | <a href='upload-user.php'>Muat Naik Data Pekerja</a> |
            <?php include('../function/butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor='yellow'>
        <td width='35%'>Nama</td>
        <td width='15%'>Email</td>
        <td width='15%'>Nombor Telefon</td>
        <td width='10%'>Kata laluan</td>
        <td width='10%'>Tahap</td>
        <td width='20%'>Tindakan</td>
    </tr>

    <?php

    # syarat tambahan yang akan dimasukkan dalam arahan(query) senarai pengguna
    $tambahan = "";
    if (!empty($_POST['nama'])) {
        $tambahan = " where pelanggan.nama like '%" . $_POST['nama'] . "%'";
    }

    # Mendapatkan data pengguna dari pangkalan data 
    $arahan_papar = "select* from pelanggan $tambahan ";
    $laksana = mysqli_query($condb, $arahan_papar);

    # Mengambil data yang ditemui 
    while ($m = mysqli_fetch_array($laksana)) {

        # memaparkan senarai nama dalam jadual 
        echo "<tr> 
        <td>" . $m['nama'] . "</td> 
        <td>" . $m['email'] . "</td>
        <td>" . $m['notel'] . "</td> 
        <td>" . $m['password'] . "</td>
        <td>" . $m['tahap'] . "</td>  ";
        # memaparkan navigasi untuk kemaskini dan hapus data pengguna
        echo "<td>
|<a href='tukar-user.php?notel=" . $m['notel'] . "' >Kemaskini</a>

|<a href='../function/del-user.php?notel=" . $m['notel'] . "'onClick=\"return confirm('Anda pasti anda ingin memadam data " . $m['nama'] . " ini.')\">Hapus</a>|

</td></tr>";
    }

    ?>
</table>
<?php include('../function/footer.php'); ?>