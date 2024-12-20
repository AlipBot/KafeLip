<?php
# Memulakan fungsi session dan memanggil fail header.php
session_start();;
include('function/connection.php');
$jumlah_harga = 0;


# menyemak jika tatasusunan order kosong
if (!isset($_SESSION['orders']) or count($_SESSION['orders']) == 0) {
    die("<script>
    alert('Cart anda kosong');
    window.location.href='menu.php';
    </script>");
} else {

    # dapatkan bilangan setiap elemen 
    $bilangan = array_count_values($_SESSION['orders']);

    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($bilangan, function ($count) {
        return $count >= 1;
    });



?>
    <html>

    <head>
        <title>Sistem Tempahan Makanan Roti</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@500&display=swap" rel="stylesheet">
        <style>
            .custom-font {
                font-family: 'Roboto', sans-serif;
            }
        </style>
    </head>

    <body class="bg-[#A1CCA5] text-gray-800">

        <div class="w-full bg-[#8FB996] fade-in">
            <div class="container mx-auto flex justify-between items-center py-6 px-4">
                <div class="logo text-2xl font-bold flex items-center mr-4">
                    <i class="fas fa-coffee text-white mr-2"></i>
                    Kafe <span class="text-white">lip</span>
                </div>
                <div class="nav flex gap-6">
                    <a class="text-white font-medium active:text-[#415D43]" href="menu.php">
                        <i class="fas fa-home mr-1"></i>
                        <span>MENU</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="container mx-auto text-center mt-10 p-5 bg-[#8FB996] shadow-lg rounded-lg">
            <h1 class="text-3xl font-bold mb-5 text-white">SENARAI TEMPAHAN</h1>
            <div class="overflow-x-auto">
                <table class="table-auto w-full border-collapse border border-gray-300">
                    <thead>
                        <tr class="bg-gray-200">
                            <th class="border border-gray-300 px-4 py-2">Menu</th>
                            <th class="border border-gray-300 px-4 py-2">Kuantiti</th>
                            <th class="border border-gray-300 px-4 py-2">Harga seunit</th>
                            <th class="border border-gray-300 px-4 py-2">Harga</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        foreach ($sama as $key => $bil) {
                            $sql = "select* from makanan where kod_makanan = '$key'";
                            $lak = mysqli_query($condb, $sql);
                            $m = mysqli_fetch_array($lak);
                        ?>

                            <tr class="bg-cyan-100 hover:bg-cyan-200">
                                <td class="border border-gray-300 px-4 py-2 font-semibold custom-font"><?= $m['nama_makanan'] ?></td>
                                <td class="border border-gray-300 px-4 py-2 flex justify-center items-center space-x-2">
                                    <button onclick="location.href='function/add-cart.php?page=cart&id_menu=<?= $m['kod_makanan'] ?>';" class="bg-[#48bd4e] text-white px-2.5 py-1 rounded ">+</button>
                                    <span><?= $bil ?></span>
                                    <button onclick="location.href='function/del-cart.php?id_menu=<?= $m['kod_makanan'] ?>';" class="bg-[#CA0000D9] text-white px-3 py-1 rounded ">-</button>
                                </td>
                                <td class="border border-gray-300 px-4 py-2 font-semibold custom-font">RM <?= $m['harga'] ?></td>
                                <td class="border border-gray-300 px-4 py-2 font-semibold custom-font">RM
                                    <?php
                                    $harga = $bil * $m['harga'];
                                    $jumlah_harga = $jumlah_harga + $harga;
                                    echo number_format($harga, 2);
                                    $_SESSION['jumlah_harga'] = $jumlah_harga;
                                    ?>
                                </td>

                            </tr>
                        <?php } ?>
                        <tr class="bg-cyan-100 hover:bg-cyan-200">
                            <td class="border border-gray-300 px-4 py-2 font-semibold custom-font" colspan="3">Jumlah Bayaran (RM)</td>
                            <td class="border border-gray-300 px-4 py-2 font-semibold custom-font">RM <?php echo number_format($jumlah_harga, 2) ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-5">
                
                <button onclick="location.href='tempah-sah.php';" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Sahkan Tempahan
                </button>
            </div>
        </div>
    </body>

    </html>

<?php } ?>