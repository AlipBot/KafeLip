<?php
# Memulakan fungsi session
session_start();

# Memanggil fail header.php, connection.php, dan kawalan-admin.php
include('function/header.php');
include('function/connection.php');
include('function/kawalan-admin.php');

# Syarat tambahan yang akan dimasukkan dalam arahan (query) senarai pengguna
$tambahan = "";
$searchTerm = "";

if (!empty($_POST['nama_menu'])) {
    $searchTerm = $_POST['nama_menu'];
    $tambahan = " WHERE makanan.nama_makanan LIKE ?";
}

# Arahan query untuk mencari senarai nama pengguna
$arahan_papar = "SELECT * FROM makanan" . $tambahan . " ORDER BY makanan.nama_makanan";

# Menggunakan prepared statement untuk elak SQL injection
$stmt = mysqli_prepare($condb, $arahan_papar);

if (!empty($searchTerm)) {
    $searchTerm = "%" . $searchTerm . "%";
    mysqli_stmt_bind_param($stmt, "s", $searchTerm);
}

mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!-- Header bagi jadual untuk memaparkan senarai pengguna -->
<h3 align='center'>Senarai Menu</h3>

<table align='center' width='100%' border='1' id='saiz'>
    <tr bgcolor='cyan'>
        <td colspan='2'>
            <form action='' method='POST' style="margin:0; padding:0;">
                <input type='text' name='nama_menu' placeholder='Carian Menu' value="<?php echo htmlspecialchars($_POST['nama_menu'] ?? ''); ?>">
                <input type='submit' value='Cari'>
            </form>
        </td>
        <td colspan='4' align='right'>
            | <a href='menu-daftar.php'>Daftar Menu Baru</a> |
            | <a href='menu-upload.php'>Upload Menu Baru</a> |<br>
            <?php include('function/butang-saiz.php'); ?>
        </td>
    </tr>
    <tr bgcolor='yellow'>
        <td width='15%'></td>
        <td width='35%'>Menu</td>
        <td width='10%'>Harga (RM)</td>
        <td width='20%'>Tindakan</td>
    </tr>
    <?php
    # Mengambil data yang ditemui
    while ($m = mysqli_fetch_assoc($result)) {
        echo "<tr>
            <td><img src='lib/imagemenu/" . htmlspecialchars($m['gambar']) . "' width='100%'></td>
            <td>" . htmlspecialchars($m['nama_makanan']) . "</td>
            <td>" . htmlspecialchars($m['harga']) . "</td>
            <td>
                | <a href='menu-kemaskini-borang.php?id_menu=" . urlencode($m['kod_makanan']) . "'>Kemaskini</a> |
                | <a href='menu-padam-proses.php?id_menu=" . urlencode($m['kod_makanan']) . "' 
                   onClick=\"return confirm('Anda pasti anda ingin memadam data ini?')\">Hapus</a> |
            </td>
        </tr>";
    }
    ?>
</table>

<?php include('footer.php'); ?>