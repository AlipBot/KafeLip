<?php

if(!empty($_SESSION['tahap'])) {

   if($_SESSION['tahap'] != "ADMIN") {
    die("<script>alert('sila login');
    window.location.href='logout.php';</script>");
    }
} else {
    die("<script>alert('sila login');
    window.location.href='logout.php';</script>");  
}
?>