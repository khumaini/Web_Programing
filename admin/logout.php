<?php
  session_start();
  session_destroy();
  echo "<center>Anda telah sukses keluar sistem <b>[LOGOUT]<b>";

// Apabila setelah logout langsung menuju halaman utama website, aktifkan baris di bawah ini:
  echo "Anda berhasil logout. silahkan menuju <a href='http://localhost/malang_network/admin/index.php'>Halaman Utama</a>"
//  header('location:http://www.alamatwebsite.com');
?>
