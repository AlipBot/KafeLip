<?php
include('autoKeluarAdmin.php'); # kawalan admin 
include('connection.php'); # sambung ke database

# Menyemak kewujudan data POST
if (!empty($_POST)) {
    # Mengambil data daripada borang (form)
    $id_menu = $_POST['id_menu'];
    $nama_menu = $_POST['nama_menu'];
    $harga = $_POST['harga'];
    $tambahan = '';

    # Dapatkan parameter URL dari form tersembunyi
    $redirect_params = [];
    if (!empty($_POST['current_page'])) {
        $redirect_params[] = "halaman=" . $_POST['current_page'];
    }
    if (!empty($_POST['search_query'])) {
        $redirect_params[] = "nama_makanan=" . urlencode($_POST['search_query']);
    }
    if (!empty($_POST['sort'])) {
        $redirect_params[] = "sort=" . $_POST['sort'];
    }

    $redirect_url = "../admin/list-menu.php";
    if (!empty($redirect_params)) {
        $redirect_url .= "?" . implode("&", $redirect_params);
    }

    # Data validation : had atas
    if (!is_numeric($harga) or $harga <= 0) {
        $_SESSION['error'] = "Ralat: Sila masukkan harga yang sah";
        header("Location: " . $redirect_url);
        exit();
    }

    # Data validation : had atas harga
    if ($harga > 9999.99) {
        $_SESSION['error'] = "Ralat: Harga maksimum adalah RM9999.99";
        header("Location: " . $redirect_url); 
        exit();
    }

    # Data validation : had atas
    if (strlen($nama_menu) > 30 or strlen($nama_menu) < 3) {
        $_SESSION['error'] = "Maksimum pajang nama 30 sahaja dan minimum 3 aksara";
        header("Location: " . $redirect_url);
        exit();
    }

    # Dapatkan data gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        # Mengambil data gambar
        $file_extension = 'jpg'; // Set extension to jpg since we're converting all images to JPEG
        # Buang jarak dan tukar kepada huruf kecil
        $nama_fail_baru = strtolower(str_replace(' ', '', $nama_menu)) . '.' . $file_extension;
        $lokasi = $_FILES['gambar']['tmp_name'];

        // Tambah gambar ke query update
        $tambahan = "gambar = '$nama_fail_baru',";

        // Get the filename from the database
        $sql = "SELECT gambar FROM makanan WHERE kod_makanan = '$id_menu'";
        $result = mysqli_query($condb, $sql);

        if ($row = mysqli_fetch_assoc($result)) {
            $filename = $row['gambar'];
            $filepath = "../menu-images/" . $filename;
            # auto delete files gambar lama dan tukar gambar baru
            // Check if the file exists and delete it
            if (file_exists($filepath)) {
                unlink($filepath);  // Delete the file
            }
        }

        // Copy file gambar ke folder gmenu-images jika gagal hantar toast error
        if (!copy($lokasi, "../menu-images/" . $nama_fail_baru)) {
            $_SESSION['error'] = "Gagal memuat naik gambar";
            header("Location: " . $redirect_url);
            exit();
        }
    }

    # Query proses kemaskini data
    $sql_kemaskini = "UPDATE makanan SET 
                      $tambahan
                      nama_makanan = '$nama_menu',
                      harga = '$harga'                
                      WHERE kod_makanan = '$id_menu'";

    $laksana = mysqli_query($condb, $sql_kemaskini);

    # Pengujian proses menyimpan data 
    if ($laksana) {
        $_SESSION['success'] = "Kemaskini Berjaya";
        header("Location: " . $redirect_url);
        exit();
    } else {
        $_SESSION['error'] = "Kemaskini Gagal: " . mysqli_error($condb);
        header("Location: " . $redirect_url);
        exit();
    }
}
?>