<h1 align ='center' > SISTEM TEMPAHAN  MAKANAN ROTI</H1>
<hr> <nav align='center'>

<?php 
if(isset($_SESSION['orders'])){
    $bil = "<span style='color:red';'>[".count($_SESSION['orders'])."]</span>";
} else {
 $bil = "";
} ?>

<?php if (!empty($_SESSION['tahap'])){ ?>
     <?php if ($_SESSION['tahap'] == "ADMIN"){ ?>
       <a href= 'menu.php'>MENU</a>
     | <a href='tempah-cart.php'>Cart<?= $bil ?></a>
     | <a href='tempah-sejarah.php'>Sejarah Tempahan</a>
     | <a href='pengguna-senarai.php'>Senarai Pengguna</a> |
     | <a href='menu-senarai.php'>Senarai menu</a>
     | <a href='laporan.php'>Laporan Tempahan</a>
     | <a href='logout.php'>Logout</a>
     <?php } elseif ($_SESSION['tahap'] == "PELANGGAN"){ ?>
     | <a href='menu.php'>Menu</a>
     | <a href='tempah-cart.php'>Cart<?= $bil ?></a>
     | <a href='tempah-sejarah.php'>Sejarah Tempahan</a>
     | <a href='logout.php'>Logout</a>

<?php } ?>

<?php } else { ?>

<!-- menu Laman Utama dipaparkan sekiranya admin atau pembeli tidak login -->

| <a href='index.php'>Laman Utama</a>
| <a href='login.php'>Log Masuk</a>
| <a href='signup.php'>Daftar Sini</a>

<?php } ?>

</nav> <hr>

<article align='center'>