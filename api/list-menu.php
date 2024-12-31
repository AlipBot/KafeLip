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
                <p class="price">RM <?= $m['harga'] ?></p>
            </div>
            <div class="quantity-controls">
                <button class="quantity-btn minus" onclick="updateQuantity('<?= $m['kod_makanan'] ?>', 'decrease')">-</button>
                <span id="quantity-<?= $m['kod_makanan'] ?>" class="quantity-value">1</span>
                <button class="quantity-btn plus" onclick="updateQuantity('<?= $m['kod_makanan'] ?>', 'increase')">+</button>
            </div>
            <button class="add-to-cart" onclick="addToCartWithQuantity('<?= htmlspecialchars($m['kod_makanan']) ?>')">
                Tambah ke Troli
            </button>
        </div>
    <?php endwhile;
} else {
    echo "<p style='color: red;'>TIADA MAKANAN TERSEDIA SEKARANG</p>";
}
?>
