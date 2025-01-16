<?php
date_default_timezone_set("Asia/Kuala_Lumpur");

#HUBUNG DENGAN  DATABASE
$condb = mysqli_connect('localhost','root','admin123');

#PILIH DATABASE Nama databse
mysqli_select_db($condb, 'kafelip');

?>