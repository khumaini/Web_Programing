<?php
include "../config/koneksi.php";

if ($_SESSION[leveluser]=='admin'){
  $sql=mysql_query("select * from modul where aktif='Y' order by urutan");
}
else{
  $sql=mysql_query("select * from modul where status='user' and aktif='Y' order by urutan"); 
} 
while ($data=mysql_fetch_array($sql)){  
  echo "<li><a href='$data[link]'>&#187; $data[nama_modul]</a></li>";
}
?>
