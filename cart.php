<?php
//―――――――――――――――――――――――――――――――――― ┏  Panggil Fail Function ┓ ―――――――――――――――――――――――――――――――― \\

include("function/autoKeluar.php");
include('function/connection.php');

//―――――――――――――――――――――――――――――――――― ┏  Kod Php ┓ ―――――――――――――――――――――――――――――――― \\

$jumlah_harga = 0; # setkan nila awal kosong

# Memamparkan Bilanggan Senarai Tempahan
if (isset($_SESSION['orders'])) {
    $bil = "<span style='color:red';'>[" . count($_SESSION['orders']) . "]</span>";
} else {
    $bil = "";
}

# menyemak jika tatasusunan order kosong
if (!isset($_SESSION['orders']) or count($_SESSION['orders']) == 0) {
    #jika tiada data tempahan di session pergi ke page menu semula dan memulang toast
    $_SESSION['info'] = "Cart Anda Kosong";
    header("Location: menu.php");
    exit();
} else {
    # jika ada data tempahan muat fail html cart
    # dapatkan bilangan setiap elemen 
    $bilangan = array_count_values($_SESSION['orders']);
    # Filter elemen yang muncul lebih dari satu kali
    $sama = array_filter($bilangan, function ($count) {
        return $count >= 1;
    });

?>
    <!-- Kod HTML & CSS + TAILWIND & JAVASCRIPT  -->

    <html lang="ms">

    <head>
        <title>CART</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="apple-touch-icon" sizes="180x180" href="lib/icon/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="lib/icon/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="lib/icon/favicon-16x16.png">
        <link rel="manifest" href="lib/icon/site.webmanifest">
        <link rel="stylesheet" href="lib/css/all.css">
        <link rel="stylesheet" href="lib/css/sharp-solid.css">
        <link rel="stylesheet" href="lib/css/sharp-regular.css">
        <link rel="stylesheet" href="lib/css/sharp-light.css">
        <link rel="stylesheet" href="lib/css/duotone.css" />
        <link rel="stylesheet" href="lib/css/brands.css" />
        <link href="lib/css/css2.css" rel="stylesheet" />
        <script src="lib/js/tailwind.js"></script>
        <link rel="stylesheet" href="lib/css/sweetalert2.min.css">
        <script src="lib/js/sweetalert2@11.js"></script>
        <style>
            .content {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
                width: 100%;
            }

            .container {
                max-width: 1200px;
                /* Menghadkan lebar maksimum jadual */
                width: 100%;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            thead th {
                text-align: center;
            }

            .overflow-x-auto {
                overflow-x: auto;
                /* Untuk responsif jika jadual terlalu besar */
            }

            .table-auto {
                width: 100%;
                /* Pastikan jadual memenuhi ruang */
                max-width: 900px;
                /* Hadkan lebar maksimum jadual */
                margin: 0 auto;
                /* Pusatkan jadual */
            }


            .container-wrapper {
                display: flex;
                flex-direction: column;
                min-height: 20vh;
            }

            .custom-font {
                font-family: 'Roboto', sans-serif;
            }

            #scrollToTopBtn:hover {
                background-color: #68B0AB;
                color: #fff;
                transition: background-color 0.3s ease, color 0.3s ease;
            }


            @media (max-width: 768px) {

                .nav a span,
                .goMenu a span {
                    display: none;
                }
            }

            /* Custom scrollbar styles */
            ::-webkit-scrollbar {
                width: 12px;
            }

            ::-webkit-scrollbar-track {
                background: #FAF3DD;
            }

            ::-webkit-scrollbar-thumb {
                background-color: #000;
                border-radius: 6px;
                border: 3px solid #FAF3DD;
            }

            ::-webkit-scrollbar-thumb:hover {
                background-color: #333;
            }

            /* Scroll to top button */
            #scrollToTopBtn {
                display: none;
                position: fixed;
                bottom: 60px;
                right: 20px;
                z-index: 100;
                background-color: #4A7C59;
                color: white;
                border: none;
                border-radius: 50%;
                padding: 10px 15px;
                cursor: pointer;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            }

            /* Hover effect for header navigation links */
            .nav a::after,
            .goMenu a::after {
                content: '';
                display: block;
                width: 0;
                height: 2px;
                background: #4A7C59;
                transition: width 0.3s;
            }

            .nav a:hover::after,
            .goMenu a:hover::after {
                width: 100%;
            }

            .add-to-cart:hover,
            #scrollToTopBtn:hover {
                background-color: #68B0AB;
                color: #fff;
                transition: background-color 0.3s ease, color 0.3s ease;
            }

            .quantity-controls {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            .quantity-btn {
                background-color: #4A7C59;
                color: white;
                border: none;
                width: 30px;
                height: 30px;
                border-radius: 50%;
                cursor: pointer;
                font-size: 18px;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: background-color 0.3s ease;
            }

            .quantity-btn:hover {
                background-color: #68B0AB;
            }

            .quantity-value {
                font-size: 18px;
                font-weight: bold;
                min-width: 30px;
                text-align: center;
            }
        </style>
    </head>

    <body class="bg-[#FAF3DD] text-gray-800">
        <!-- Header -->
        <div class="w-full bg-[#FAF3DD]">
            <div class="container mx-auto flex justify-between items-center py-6 px-4">
                <div class="logo text-2xl font-bold flex items-center mr-4">
                    <i class="fas fa-coffee text-[#4A7C59] mr-2"></i>
                    <span class="text-black">Kafe</span>
                    <span class="text-black">lip</span>
                </div>
                <div class="nav flex gap-6 -ml-10 mr-20">
                    <a class="text-black font-bold active:text-[#4A7C59]" href="menu.php">
                        <i class="fas fa-utensils text-[#4A7C59] mr-1"></i>
                        <span>MENU</span>
                    </a>
                    <a class="text-black font-bold active:text-[#4A7C59]" href="cart.php">
                        <i class="fas fa-shopping-cart text-[#4A7C59] mr-1"></i>
                        <span>CART <?= $bil ?></span>
                    </a>
                    <a class="text-black font-bold active:text-[#4A7C59]" href="sejarah-tempah.php">
                        <i class="fas fa-history text-[#4A7C59] mr-1"></i>
                        <span>SEJARAH TEMPAHAN</span>
                    </a>
                </div>
                <div class="relative">
                    <button id="menuButton" class="p-2 hover:bg-gray-100 rounded-full">
                        <i class="fas fa-bars text-[#4A7C59] text-xl"></i>
                    </button>
                    <div id="dropdownMenu"
                        class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-2 z-50">
                        <?php if ($_SESSION['tahap'] == "ADMIN"): ?>
                            <a href="admin/panel.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fa fa-list-alt mr-2 text-[#4A7C59]"></i>Panel Admin
                            </a>
                        <?php endif; ?>
                        <a href="profil.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2 text-[#4A7C59]"></i>Profil
                        </a>
                        <a href="account.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-cog mr-2 text-[#4A7C59]"></i>Akaun
                        </a>
                        <hr class="my-1">
                        <a href="logout.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-sign-out-alt mr-2 text-[#4A7C59]"></i>Log Keluar
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Body -->
        <div class="content">
            <div class="container mx-auto text-center py-8 px-4  rounded-lg">
                <h2 class="text-4xl font-bold mb-6 relative inline-block text-center w-full text-black">
                    SENARAI TEMPAHAN
                    <span class="absolute bottom-0 left-1/2 transform -translate-x-1/2 w-1/2 h-1 bg-[#4A7C59]">
                    </span>
                </h2>
                <div class="overflow-x-auto">
                    <table
                        class="border-2 border-[#4A7C59] table-auto  min-w-ful border-separate shadow-lg  rounded-lg bg-[#4A7C59]">
                        <thead>
                            <tr class="text-white">
                                <th width="20%" class="bg-[#4A7C59] px-4 py-2">Menu</th>
                                <th width="10%" class="bg-[#4A7C59] px-4 py-2">Gambar</th>
                                <th width="15%" class="bg-[#4A7C59] px-4 py-2">Kuantiti</th>
                                <th width="15%" class="bg-[#4A7C59] px-4 py-2">Harga seunit (RM)</th>
                                <th width="15%" class="bg-[#4A7C59] px-4 py-2">Harga (RM)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($sama as $key => $bil) {
                                $sql = "select* from makanan where kod_makanan = '$key'";
                                $lak = mysqli_query($condb, $sql);
                                $m = mysqli_fetch_array($lak);
                            ?>
                                <tr class="bg-[#FAF3DD] hover:bg-white">
                                    <td class="shadow-lg px-4 py-2 font-semibold custom-font"><?= $m['nama_makanan'] ?></td>
                                    <td class='px-8 py-4 flex justify-center items-center'>
                                        <div class="relative group">
                                            <div class="w-32 h-32 overflow-hidden">
                                                <img src='menu-images/<?php echo htmlspecialchars($m['gambar']); ?>'
                                                    alt='Gambar menu <?php echo htmlspecialchars($m['nama_makanan']); ?>'
                                                    class="w-full h-full object-cover rounded-md cursor-pointer transition-opacity group-hover:opacity-50">
                                            </div>
                                            <div
                                                class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                                <i onclick="showImagePopup('menu-images/<?php echo htmlspecialchars($m['gambar']); ?>', '<?php echo htmlspecialchars($m['nama_makanan']); ?>')"
                                                    class="fas fa-eye text-3xl text-white bg-black bg-opacity-50 p-3 rounded-full"></i>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-2 h-full">
                                        <div class="flex items-center justify-center h-full min-h-[160px]">
                                            <div class="quantity-controls">
                                                <button class="quantity-btn minus bg-[#CA0000D9] hover:bg-[#d33]"
                                                    onclick="updateCartQuantity('<?= $m['kod_makanan'] ?>', 'decrease', <?= $m['harga'] ?>)"><i class="fas fa-minus"></i></button>
                                                <span id="quantity-<?= $m['kod_makanan'] ?>"
                                                    class="quantity-value"><?= $bil ?></span>
                                                <button class="quantity-btn plus"
                                                    onclick="updateCartQuantity('<?= $m['kod_makanan'] ?>', 'increase', <?= $m['harga'] ?>)"><i class="fas fa-plus"></i></button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="shadow-lg text-center px-4 py-2 font-semibold custom-font"><?= $m['harga'] ?>
                                    </td>
                                    <td class="shadow-lg text-center px-4 py-2 font-semibold custom-font">
                                        <span id="total-<?= $m['kod_makanan'] ?>">
                                            <?php
                                            $harga = $bil * $m['harga'];
                                            $jumlah_bayaran = $jumlah_bayaran + $harga;
                                            echo number_format($harga, 2);
                                            ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php } ?>
                            <tr class="bg-[#FAF3DD] hover:bg-white">
                                <td class="shadow-lg px-4 py-2 text-right font-semibold custom-font" colspan="4">Jumlah
                                    Bayaran (RM)</td>
                                <td class="shadow-lg text-center px-4 py-2 font-semibold custom-font">
                                    <span id="grand-total"><?php echo number_format($jumlah_bayaran, 2) ?></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-5">
                    <button class="buang-btn bg-[#CA0000D9] text-white px-4 py-2 rounded hover:bg-[#d33]">
                        Kosongkan Senarai Tempahan
                    </button>
                    <button class="Sahkan-btn bg-[#4A7C59] text-white px-4 py-2 rounded hover:bg-[#68B0AB]">
                        Sahkan Tempahan
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="w-full bg-[#FAF3DD] text-black py-6 px-10">
            <div class="container mx-auto flex flex-col lg:flex-row justify-between items-center">
                <div class="mb-4 lg:mb-0">
                    © 2025 KAFELIP. Semua hak terpelihara.
                </div>
                <div class="flex gap-6">
                    <a class="text-[#4A7C59]" href="#">
                        <i class="fab fa-facebook-f">
                        </i>
                    </a>
                    <a class="text-[#4A7C59]" href="#">
                        <i class="fab fa-twitter">
                        </i>
                    </a>
                    <a class="text-[#4A7C59]" href="https://www.instagram.com/alipje29/#">
                        <i class="fab fa-instagram">
                        </i>
                    </a>
                </div>
            </div>
        </footer>

        <!-- Butang scroll keatas -->
        <button id="scrollToTopBtn" onclick="scrollToTop()">
            <i class="fas fa-arrow-up"></i>
        </button>

        <!-- Image Popup Modal -->
        <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden items-center justify-center z-50">
            <div class="relative max-w-2xl mx-auto p-4">
                <button onclick="closeImagePopup()"
                    class="absolute top-0 right-0 -mt-[14.5px] -mr-[14px] text-white text-3xl font-bold hover:text-gray-300">&times;</button>
                <div class="max-h-[70vh] max-w-[600px]">
                    <img id="popupImage" src="" alt="" class="w-full h-[70%] rounded-md object-contain">
                </div>
                <p id="imageCaption" class="text-white text-center mt-4 text-lg"></p>
            </div>
        </div>

        <script>
            // function butang scroll keatas dan auto ajdust kedudukan footer
            function adjustFooter() {
                const content = document.querySelector('.content');
                const footer = document.querySelector('footer');
                const windowHeight = window.innerHeight;
                const contentHeight = content.offsetHeight;
                const footerHeight = footer.offsetHeight;

                // Kira margin top footer supaya sentiasa berada di bawah
                const marginTop = windowHeight - (contentHeight + footerHeight);

                if (marginTop > 0) {
                    footer.style.marginTop = marginTop + 'px';
                } else {
                    footer.style.marginTop = '0px';
                }
            }

            // Panggil fungsi setiap kali halaman dimuat
            window.onload = adjustFooter;

            // Panggil fungsi semasa resize window
            window.onresize = adjustFooter;

            // Show or hide the scroll to top button
            window.onscroll = function() {
                var scrollToTopBtn = document.getElementById("scrollToTopBtn");
                if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
                    scrollToTopBtn.style.display = "block";
                } else {
                    scrollToTopBtn.style.display = "none";
                }
            };

            // Scroll to top function
            function scrollToTop() {
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }
        </script>
        <script>
            // function toast dan popup
            const notifsuccess = new Audio('lib/audio/notif.mp3'); // Path fail audio success
            const notiferror = new Audio('lib/audio/error.mp3'); // Path fail audio ralat
            const notifinfo = new Audio('lib/audio/info.mp3'); //  Path fail audio info
            const notifwarning = new Audio('lib/audio/warning.mp3'); // Path fail audio amaran


            const Toast = Swal.mixin({
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });

            document.addEventListener('DOMContentLoaded', function() {
                <?php if (isset($_SESSION['success'])): ?>
                    Toast.fire({
                        icon: "success",
                        title: "<?= $_SESSION['success'] ?>"
                    });
                    notifsuccess.play(); // Main bunyi bila toast muncul
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['info'])): ?>
                    Toast.fire({
                        icon: "info",
                        title: "<?= $_SESSION['info'] ?>"
                    });
                    notifinfo.play();
                    <?php unset($_SESSION['info']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['warning'])): ?>
                    Toast.fire({
                        icon: "warning",
                        title: "<?= $_SESSION['warning'] ?>"
                    });
                    notifwarning.play();
                    <?php unset($_SESSION['warning']); ?>
                <?php endif; ?>

                // Untuk popup error
                <?php if (isset($_SESSION['error'])): ?>
                    Toast.fire({
                        icon: "error",
                        title: "<?= $_SESSION['error'] ?>"
                    });
                    notiferror.play();
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
            })

            document.querySelectorAll('.Sahkan-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    notifinfo.play();

                    // Kumpul semua data kuantiti terkini
                    let updatedQuantities = {};
                    document.querySelectorAll('[id^="quantity-"]').forEach(element => {
                        const menuId = element.id.replace('quantity-', '');
                        updatedQuantities[menuId] = parseInt(element.textContent);
                    });

                    Swal.fire({
                        title: 'Anda pasti?',
                        text: "Selepas tempah Berjaya, anda dapat batalkan tempahan anda selama 60 saat sahaja di sejarah tempahan",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Hantar data kuantiti terkini ke server
                            fetch('function/update-cart.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                    },
                                    body: JSON.stringify(updatedQuantities)
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        window.location.href = 'function/sah-tempah.php';
                                    } else {
                                        notiferror.play();
                                        Toast.fire({
                                            icon: "error",
                                            title: "Ralat semasa mengemas kini cart"
                                        });
                                    }
                                });
                        }
                    });
                });
            });

            document.querySelectorAll('.buang-btn').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    notifwarning.play();

                    Swal.fire({
                        title: 'Anda pasti?',
                        text: "Mahu Kosongkan Cart Anda",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = 'function/kosongkan-cart.php';
                        }
                    });
                });
            });
        </script>
        <script>
            //function drawer menu di header
            const menuButton = document.getElementById('menuButton');
            const dropdownMenu = document.getElementById('dropdownMenu');

            menuButton.addEventListener('click', () => {
                dropdownMenu.classList.toggle('hidden');
            });

            // Tutup dropdown bila klik di luar
            document.addEventListener('click', (event) => {
                if (!menuButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                    dropdownMenu.classList.add('hidden');
                }
            });
        </script>
        <script>
            function showImagePopup(imageSrc, caption) {
                const modal = document.getElementById('imageModal');
                const popupImage = document.getElementById('popupImage');
                const imageCaption = document.getElementById('imageCaption');

                popupImage.src = imageSrc;
                imageCaption.textContent = caption;
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Tutup modal bila klik di luar gambar
                modal.onclick = function(e) {
                    if (e.target === modal) {
                        closeImagePopup();
                    }
                }
            }

            function closeImagePopup() {
                const modal = document.getElementById('imageModal');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            // Tutup modal dengan kekunci ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeImagePopup();
                }
            });
        </script>

        <script>
            // function butang + dan  -  untuk kuantii makanan
            function updateCartQuantity(menuId, action, hargaSeunit) {
                const quantityElement = document.getElementById(`quantity-${menuId}`);
                let quantity = parseInt(quantityElement.textContent);

                if (action === 'increase') {
                    quantity++;
                } else if (action === 'decrease' && quantity > 1) {
                    quantity--;
                } else if (action === 'decrease' && quantity === 1) {
                    notifwarning.play();
                    // Tanya pengguna jika mahu buang item
                    Swal.fire({
                        title: 'Buang item ini?',
                        text: "Item ini akan dibuang dari cart",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            removeItem(menuId);
                            return;
                        }
                    });
                    return;
                }

                // Kemas kini paparan
                quantityElement.textContent = quantity;
                updateItemTotal(menuId, quantity, hargaSeunit);
                updateGrandTotal();

                // Hantar perubahan ke server menggunakan AJAX
                fetch('function/update-quantity.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            menuId: menuId,
                            quantity: quantity
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (!data.success) {
                            notiferror.play();
                            Toast.fire({
                                icon: "error",
                                title: "Ralat semasa mengemas kini kuantiti"
                            });
                        }
                    });
            }

            function removeItem(menuId) {
                fetch('function/del-item.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            menuId: menuId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            if (data.isEmpty) {
                                // Jika cart kosong, pergi ke menu.php
                                window.location.href = 'menu.php';
                            } else {
                                // Jika masih ada item lain, reload halaman cart
                                window.location.href = 'cart.php';
                            }
                        } else {
                            notiferror.play();
                            Toast.fire({
                                icon: "error",
                                title: "Ralat semasa membuang item"
                            });
                        }
                    });
            }

            function updateItemTotal(menuId, quantity, hargaSeunit) {
                const totalElement = document.getElementById(`total-${menuId}`);
                const total = quantity * hargaSeunit;
                totalElement.textContent = total.toFixed(2);
            }

            function updateGrandTotal() {
                let grandTotal = 0;
                // Dapatkan semua elemen total harga
                document.querySelectorAll('[id^="total-"]').forEach(element => {
                    grandTotal += parseFloat(element.textContent);
                });

                // Kemas kini jumlah keseluruhan
                document.getElementById('grand-total').textContent = grandTotal.toFixed(2);

            }
        </script>
    </body>

    </html>

<?php } ?>