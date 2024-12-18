<?php 
# memulakan fungsi session
session_start();

#memanggil fail 
include('function/header.php');
include('function/connection.php');
include('function/kawalan-admin.php');
?>
<!-- Header bagi jadual untuk memaparkan senarai pengguna -->
<h3 align='center'>Senarai pengguna</h3>

<table align='center' width='100%' border='1' id='saiz'> 
    <tr bgcolor='cyan'>
        <td colspan='1'>
            <form action='' method='POST' style="margin:0; padding:0;">
                <input type='text'      name='nama' placeholder='Carian Nama pengguna'>
                <input type='submit'    value='Cari'>
            </form>
        </td>
        <td colspan='4' align='right'>
            | <a href='pengguna-upload.php'>Muat Naik Data Pekerja</a> |
            <?php include('butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor='yellow'> 
        <td width='35%'>Nama</td> 
        <td width='15%'>notel</td> 
        <td width='10%'>Katalaluan</td> 
        <td width='10%'>tahap</td> 
        <td width='20%'>Tindakan</td>
    </tr> 

<?php 

# syarat tambahan yang akan dimasukkan dalam arahan(query) senarai pengguna
$tambahan="";
if(!empty($_POST['nama']))
{
    $tambahan= " where pelanggan.nama like '%".$_POST['nama']."%'";
}

# Mendapatkan data pengguna dari pangkalan data 
$arahan_papar="select* from pelanggan $tambahan "; 
$laksana = mysqli_query($condb,$arahan_papar); 

# Mengambil data yang ditemui 
    while($m = mysqli_fetch_array($laksana)) 
    { 
    
        # memaparkan senarai nama dalam jadual 
        echo"<tr> 
        <td>".$m['nama']."</td> 
        <td>".$m['notel']."</td> 
        <td>".$m['password']."</td>
        <td>".$m['tahap']."</td>  ";
# memaparkan navigasi untuk kemaskini dan hapus data pengguna
echo"<td>
|<a href='pengguna-kemaskini-borang.php?notel=".$m['notel']."' >Kemaskini</a>

|<a href='pengguna-padam-proses.php?notel=".$m['notel']."' 
onClick=\"return confirm('Anda pasti anda ingin memadam data ini.')\">Hapus</a>|

</td></tr>"; 

}

?> 
</table>
<?php include ('footer.php'); ?>