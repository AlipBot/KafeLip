<?php
// Sambungan ke database
include('../function/connection.php');

// Query untuk mendapatkan semua data dari tabel 'makanan'
$sql = "SELECT * FROM makanan";
$result = mysqli_query($condb, $sql);

// Periksa apakah query berhasil dijalankan
if (!$result) {
    die("Query gagal: " . mysqli_error($condb));
}

// Tampilkan menu-item
if (mysqli_num_rows($result) > 0) {
    while ($m = mysqli_fetch_assoc($result)): ?>
        <div class="menu-item">
            <img src="menu-images/<?= htmlspecialchars($m['gambar']) ?>" />
            <div>
                <h2><?= htmlspecialchars($m['nama_makanan']) ?></h2>
                <p class="price">RM <?= htmlspecialchars($m['harga']) ?></p>
            </div>
            <button class="add-to-cart" onclick="location.href='function/add-cart.php?page=menu&id_menu=<?= htmlspecialchars($m['kod_makanan']) ?>';">
                Add to Cart
            </button>
        </div>
    <?php endwhile;
} else {
    echo "<p style='color: red;'>TIADA MAKANAN TERSEDIA SEKARANG</p>";
}
?>
