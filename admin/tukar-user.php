<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();

# Memanggil fail header dan fail kawalan-admin.php
include('../function/header.php');
include('../function/admin-only.php');
include('../function/connection.php');

# Menyemak kewujudan data GET. Jika data GET empty, buka fail pengguna-senarai.php
if (empty($_GET)) {
    die("<script>window.location.href='list-user.php';</script>");
}

# Mendapatkan data daripada pangkalan data
$sql        =   "select* from pelanggan where notel = '" . $_GET['notel'] . "'";
$laksana    =   mysqli_query($condb, $sql);
$m          =   mysqli_fetch_array($laksana);
?>

<h3>Kemaskini Pengguna</h3>

<form action='../function/update-user.php?notel_lama=<?= $_GET['notel'] ?>' method='POST'>
    nama
    <input type='text' name='nama' value='<?= $m['nama'] ?>' required><br>

    notel
    <input type='text' name='notel' value='<?= $m['notel'] ?>' required><br>

    katalaluan
    <input type='text' name='katalaluan' value='<?= $m['password'] ?>' required><br>

    Tahap
    <select name='tahap'><br>
        <option value='<?= $m['tahap'] ?>'> <?= $m['tahap'] ?> </option>
        <?php

        # Proses memaparkan senarai tahap dalam bentuk drop down list
        $arahan_sql_tahap       =   "select tahap from pelanggan group by tahap order by tahap";
        $laksana_arahan_tahap   =   mysqli_query($condb, $arahan_sql_tahap);

        while ($n = mysqli_fetch_array($laksana_arahan_tahap)) {
            if ($n['tahap'] != $m['tahap']) {
                echo "<option value='" . $n['tahap'] . "'>
                " . $n['tahap'] . "
                </option>";
            }
        }
        ?>
    </select> <br>

    <input type='submit' value='Kemaskini'>

</form>
<?php include('../function/footer.php'); ?>