<?php
include('../function/autoKeluarAdmin.php');
include('../function/connection.php');
$jumlah_harga = 0;

# Dapatkan email dan tarikh daripada URL
$email = $_GET['email'];
$tarikh = $_GET['tarikh'];

# Mendapatkan data tempahan
$sql_pilih = "SELECT t.*, m.nama_makanan, m.harga, p.nama, p.notel 
              FROM tempahan t
              JOIN makanan m ON t.kod_makanan = m.kod_makanan
              JOIN pelanggan p ON t.email = p.email
              WHERE t.email = '$email' AND t.tarikh = '$tarikh'";

$laksana = mysqli_query($condb, $sql_pilih);
$maklumat = mysqli_fetch_array($laksana);
mysqli_data_seek($laksana, 0);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Semak Resit</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body class="bg-[#FAF3DD] p-4">
    <h2 class="text-2xl mt-9 font-bold mb-4 text-center text-black">
        <i class="fas fa-receipt text-[#4A7C59] mr-1"></i> Resit
    </h2>
    <div class="print-area max-w-[700px] mx-auto bg-white shadow-md rounded-lg p-6">

        <div class="text-left mb-4 flex justify-between">
            <div>
                <p class="text-lg font-semibold mb-2">
                    <i class="fas fa-user-circle text-[#4A7C59] mr-1"></i> Maklumat Pelanggan
                </p>
                <p class="text-sm mb-1">
                    <i class="fas fa-user text-[#4A7C59] mr-1"></i> Nama: <?= $maklumat['nama'] ?>
                </p>
                <p class="text-sm mb-1">
                    <i class="fas fa-phone text-[#4A7C59] mr-1"></i> No. Telefon: <?= $maklumat['notel'] ?>
                </p>
                <p class="text-sm">
                    <i class="fas fa-envelope text-[#4A7C59] mr-1"></i> Email: <?= $email ?>
                </p>
            </div>
            <div class="text-right">
                <p class="text-lg font-semibold mb-2">
                    <i class="fas fa-store text-[#4A7C59] mr-1"></i> KafeLip
                </p>
                <p class="text-sm mb-1">
                    <i class="fas fa-calendar-alt text-[#4A7C59] mr-1"></i> Tarikh:
                    <?= date_format(date_create($tarikh), "d/m/Y") ?>
                </p>
                <p class="text-sm mb-1">
                    <i class="fas fa-clock text-[#4A7C59] mr-1"></i> Masa:
                    <?= date_format(date_create($tarikh), "g:i:s A") ?>
                </p>
                <p class="text-sm"><i class="fas fa-calendar-week text-[#4A7C59] mr-1"></i> Hari:
                    <?php
                    $hari = date('l', strtotime($tarikh));
                    $hari_melayu = [
                        'Sunday' => 'Ahad',
                        'Monday' => 'Isnin',
                        'Tuesday' => 'Selasa',
                        'Wednesday' => 'Rabu',
                        'Thursday' => 'Khamis',
                        'Friday' => 'Jumaat',
                        'Saturday' => 'Sabtu'
                    ];
                    echo $hari_melayu[$hari];
                    ?>
                </p>
            </div>
        </div>

        <table class="min-w-full bg-white border-2 border-gray-300 rounded-lg">
            <thead>
                <tr>
                    <th class="p-2 border border-gray-300">Menu</th>
                    <th class="p-2 border border-gray-300">Kuantiti</th>
                    <th class="p-2 border border-gray-300">Harga Seunit (RM)</th>
                    <th class="p-2 border border-gray-300">Jumlah (RM)</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($m = mysqli_fetch_array($laksana)) { ?>
                    <tr>
                        <td class="p-2 border border-gray-300"><?= $m['nama_makanan'] ?></td>
                        <td class="p-2 text-center border border-gray-300"><?= $m['kuantiti'] ?></td>
                        <td class="p-2 text-center border border-gray-300"><?= number_format($m['harga'], 2) ?></td>
                        <td class="p-2 text-center border border-gray-300">
                            <?php
                            $harga = $m['kuantiti'] * $m['harga'];
                            $jumlah_harga += $harga;
                            echo number_format($harga, 2);
                            ?>
                        </td>
                    </tr>
                <?php } ?>
                <tr class="font-semibold">
                    <td colspan="3" class="p-2 text-right border border-gray-300">Jumlah Bayaran (RM)</td>
                    <td class="p-2 text-center border border-gray-300"><?= number_format($jumlah_harga, 2) ?></td>
                </tr>
            </tbody>
        </table>

        <div class="text-center mt-4">
            <button onclick="window.print()"
                class="bg-[#4A7C59] text-white py-2 px-4 rounded-lg hover:bg-[#68B0AB] transition duration-300">
                <i class="fas fa-print mr-2"></i> Cetak
            </button>
        </div>
    </div>
</body>

</html>