<?php
$lifetime = 60 * 60 * 24 * 30;  // 30 days
session_set_cookie_params($lifetime);
session_start();
if (!empty($_SESSION['tahap'])){ ?>
    <script>window.location.href='menu.php';</script>
<?php } ?>

<!DOCTYPE html>

<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  
  

    <link rel="apple-touch-icon" type="image/png" href="https://cpwebassets.codepen.io/assets/favicon/apple-touch-icon-5ae1a0698dcc2402e9712f7d01ed509a57814f994c660df9f7a952f3060705ee.png">

    <meta name="apple-mobile-web-app-title" content="CodePen">

    <link rel="shortcut icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/favicon-aec34940fbc1a6e787974dcd360f2c6b63348d4b1f4e06c77743096d55480f33.ico">

    <link rel="mask-icon" type="image/x-icon" href="https://cpwebassets.codepen.io/assets/favicon/logo-pin-b4b4269c16397ad2f0f7a01bcdf513a1994f4c94b8af2f191c09eb0d601762b1.svg" color="#111">



  
  

  <title>DAFTAR MASUK</title>

    <link rel="canonical" href="https://codepen.io/wahidullah_karimi/pen/QwLLNrM">
  
  
  
  
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

*{
    margin: -8px;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

body{
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #8f8f8f;
}

.box {
    position: relative;
    width: 390px;
    height: 500px;
    background: #1c1c1c;
    border-radius: 8px;
    overflow: hidden;
    padding: 10px;
    
}

.box::before{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #45f3ff, #45f3ff, #45f3ff);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
}

.box::after{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #45f3ff, #45f3ff, #45f3ff);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -3s;
}

.borderLine::before{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #ff2770, #ff2770, #ff2770);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -1.5s;
}

.borderLine::after
{
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 380px;
    height: 420px;
    background: linear-gradient(0deg, transparent, transparent, #ff2770, #ff2770, #ff2770);
    z-index: 1;
    transform-origin: bottom right;
    animation: animate 6s linear infinite;
    animation-delay: -4.5s;
}


@keyframes animate {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
    
}

.box form {
    position: absolute;
    inset: 13px;
    background: #307351;
    padding: 23px 40px;
    border-radius: 13px;
    z-index: 2;
    display: flex;
    flex-direction: column;
}

.box form h2{
    color: #fff;
    font-weight: 500;
    text-align: center;
    letter-spacing: 0.1em;
}

.box form .inputBox{
    position: relative;
    width: 300px;
    margin-top: 35px;
}

.box form .inputBox input{
    position: relative;
    width: 100%;
    padding: 20px 10px 10px;
    background: transparent;
    outline: none;
    border: none;
    box-shadow: none;
    color: #23242a;
    font-size: 1em;
    letter-spacing: 0.05em;
    transition: 0.5s;
    z-index: 10;
}

.box form .inputBox span{
    position: absolute;
    left: 0;
    padding: 20px 0px 10px;
    pointer-events: none;
    color: #fff;
    font-size: 1em;
    letter-spacing: 0.05em;
    transition: 0.5s;
}

.box form .inputBox input:valid ~ span,
.box form .inputBox input:focus ~ span {
    color: #fff;
    font-size: 0.75em;
    transform: translateY((-34px));
}

.box form .inputBox i{
    position: absolute;
    left: 0;
    bottom: 0;
    width: 100%;
    height: 2px;
    background: #fff;
    border-radius: 4px;
    overflow: hidden;
    transition: 0.5s;
    pointer-events: none;
}

.box form .inputBox input:valid ~ i,
.box form .inputBox input:focus ~ i {
    height: 44px;
}

.box form .links{
    display: flex;
    justify-content: space-between;
}

.box form .links a{
    margin-top: 40px;
    margin-bottom: 20px;

    font-size: 0.75em;
    color: #8f8f8f;
    text-decoration: none;
}

.box form .links a:hover,
.box form .links a:nth-child(2){
    color: #fff;
}

#submit{
    border: none;
    outline: none;
    padding: 10px 25px;
    cursor: pointer;
    font-size: 0.9em;
    border-radius: 4px;
    font-weight: 600;
    width: 100px;
    margin-top: 10px;
}

#submit:active{
    opacity: 0.8;
}
</style>

  <script>
  window.console = window.console || function(t) {};
</script>

  
 
  <style>      
      /* Injected by five-server */
      /*[data-highlight="true"] {
        border: 1px rgb(90,170,255) solid !important;
        background-color: rgba(155,215,255,0.5);
        animation: fadeOutHighlight 1s forwards 0.5s;
      }
      img[data-highlight="true"] {
        filter: sepia(100%) hue-rotate(180deg) saturate(200%);
        animation: fadeOutHighlightIMG 0.5s forwards 0.5s;
      }*/
      @keyframes fadeOutHighlight {
        from {background-color: rgba(155,215,255,0.5);}
        to {background-color: rgba(155,215,255,0);}
      }      
      @keyframes fadeOutHighlightIMG {
        0% {filter: sepia(100%) hue-rotate(180deg) saturate(200%);}
        33% {filter: sepia(66%) hue-rotate(180deg) saturate(100%);}
        50% {filter: sepia(50%) hue-rotate(90deg) saturate(50%);}
        66% {filter: sepia(33%) hue-rotate(0deg) saturate(100%);}
        100% {filter: sepia(0%) hue-rotate(0deg) saturate(100%);}
      }
      @keyframes fiveserverInfoPopup {
        0%   {top:-40px;}
        15%  {top:4px;}
        85%  {top:4px;}
        100% {top:-40px;}
      }
      /*smaller*/
      @media (max-width: 640px) {
        #fiveserver-info-wrapper {
          max-width: 98%;
        }
        #fiveserver-info {
          border-radius: 0px;
        }      
      }

      </style></head>

<body translate="no">
  <div class="box">
        <span class="borderLine"></span>

        <form action='' method = 'POST'>
            <h2>DAFTAR MASUK</h2>
            <div class="inputBox">
                <input type="text" name='nama' required>
                <span> Nama</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="text" name='notel' required>
                <span> Nombor Telefon</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="text" name='email' required>
                <span> Email</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" name='pass'required>
                <span>Kata Laluan</span>
                <i></i>
            </div>
            <div class="inputBox">
                <input type="password" required="">
                <span>Sahkan Kata Laluan</span>
                <i></i>
            </div>
            <div class="links">
                <a href="login.php">LOG MASUK?</a>
            </div>
            <input type="submit" id="submit" value="Simpan">
        </form>
    </div>
  

</body></html>

<?php
if(!empty($_POST)){

    include("function\connection.php");

    $nama = $_POST["nama"];
    $notel = $_POST["notel"];
    $email = $_POST["email"];
    $password = $_POST["pass"];

    if(strlen($password) < 7){
        die("<script>alert('KATA LAUAN MESTI 8 AKSARA KE ATAS');
        window.location.href='signup.php';</script>");    

    }

    if(strlen($password) > 13){
        die("<script>alert('KATA LAUAN MESTI TIDAK BOLEH LEBIH 12 AKSARA');
        window.location.href='signup.php';</script>");    
    }

    if(strlen($notel) < 10){
        die("<script>alert('NOMBOR TELEFON MESTI 10 KE ATAS');
        window.location.href='signup.php';</script>");    

    }

    if(strlen($notel) > 15){
        die("<script>alert('NOMBOR TELEFON MESTI TIDAK BOLEH LEBIH 14');
        window.location.href='signup.php';</script>");    

    }

    $sql_semak = "select email from pelanggan where email = '$email' ";
    $check =mysqli_query($condb, $sql_semak);
    if(mysqli_num_rows($check)== 1) {
        die("<script>alert('EMAIL SUDAH DIGUNAKAN SILA GUNA EMAIL LAIN');
        window.location.href='signup.php';</script>");   

    }

    $sql_simpan = "insert into pelanggan (email, nama, notel, password, tahap)
                   values
                   ('$email', '$nama', '$notel','$password' ,'PELANGGAN')";

    $simpan = mysqli_query($condb, $sql_simpan);
    if($simpan){
        echo("<script>alert('PENDAFTARAN BERJAYA sila login');
        window.location.href='login.php';</script>");  
    } else {
        echo "<script>alert('PENDAFTARAN BERJAYA sila login');</script>";
        echo $sql_simpan.mysqli_error($condb);
    }
    
    }
?>