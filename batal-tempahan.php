<?php
include('function/connection.php');
session_start();

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
        
        echo "<script>
                alert('Tempahan berjaya dibatalkan');
                window.location.href='sejarah-tempah.php';
              </script>";
    } else {
        echo "<script>
                alert('Tempahan tidak boleh dibatalkan selepas 60 saat');
                window.location.href='sejarah-tempah.php';
              </script>";
    }
} else {
    header("Location: sejarah-tempah.php");
}
?> 