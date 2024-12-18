<?php
# memulakan fungsi session
session_start();

# memanggil fail kawalan-admin.php
include('function/kawalan-admin.php');

# menyemak kewujudan data POST
if(!empty($_POST))
{
    # memanggil fail connection.php
    include('function/connection.php');

    # pengesahan data (validation) notel pengguna
    if(strlen($_POST['notel']) < 10 or strlen($_POST['notel']) > 15)
    {
        die("<script>alert('Ralat notel');
        window.history.back();</script>");
    }

    # arahan SQL (query) untuk kemaskini maklumat pelanggan
    $arahan         =   "update pelanggan set
    nama            =   '".$_POST['nama']."' ,
    notel           =   '".$_POST['notel']."' ,
    password      =   '".$_POST['katalaluan']."' ,
    tahap           =   '".$_POST['tahap']."'
    where       
    notel           =   '".$_GET['notel_lama']."' ";

    # melaksana dan menyemak proses kemaskini
    if(mysqli_query($condb,$arahan))
    { 
        # kemaskini berjaya
        echo "<script>alert('Kemaskini Berjaya');
        window.location.href='pengguna-senarai.php';</script>";
        
    }
    else
    {
        # kemaskini gagal
	    # die(mysqli_error($condb); echo $arahan;
        echo "<script>alert('kemaskini Gagal');
        window.history.back();</script>";
    }
}
else
{
    # jika data GET tidak wujud. kembali ke fail pengguna-senarai.php
    die("<script>alert('sila lengkapkan data');
    window.location.href='pengguna-senarai.php';</script>");
}
?>