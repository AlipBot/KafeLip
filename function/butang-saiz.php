<script>

function ubahsaiz(gandaan) {

  var saiz = document.getElementById("saiz");
  var saiz_semasa = saiz.style.fontSize || 1;

   if(gandaan==2) {

    saiz.style.fontSize = "1em";

} else {

saiz.style.fontSize = (parseFloat(saiz_semasa) + (gandaan*0.2)) + "em";

}

} </script>



<input name='rb' type='button' value='reset' onclick="ubahsaiz(2)"/>

<input name='r' type='button' value='&nbsp; + &nbsp;' onclick="ubahsaiz(1)"/>

<input names='rk' type='button' value='&nbsp; - &nbsp;' onclick="ubahsaiz(-1)"/>

<button onclick="window.print()">Cetak</button>