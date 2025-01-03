<?php
include('connection.php');
include('autoKeluar.php');


if (isset($_GET['tarikh'])) {
    $tarikh = $_GET['tarikh'];
    $email = $_SESSION['email'];
    
    // Semak jika tempahan masih dalam tempoh 60 saat
    $sql_check = "SELECT * FROM tempahan 
                  WHERE email = '$email' 
                  AND tarikh = '$tarikh'
                  AND TIMESTAMPDIFF(SECOND, tarikh, NOW()) <= 60";
    $result = mysqli_query($condb, $sql_check);
    
    if (mysqli_num_rows($result) > 0) {
        // Delete tempahan
        $sql_delete = "DELETE FROM tempahan 
                       WHERE email = '$email' 
                       AND tarikh = '$tarikh'";
        mysqli_query($condb, $sql_delete);
        
        $_SESSION['success'] = "Tempahan Berjaya Dibatalkan";
        header("Location: ../sejarah-tempah.php");
        exit();
    } else {
        $_SESSION['error'] = "Tempahan Tidak Boleh Dibatalkan Selepas 60 saat";
        header("Location: ../sejarah-tempah.php");
        exit();
    }
} else {
    header("Location: ../sejarah-tempah.php");
}
?> 