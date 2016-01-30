<?php
include "../config/koneksi.php";
include "../config/library.php";
include "../config/fungsi_indotgl.php";
include "../config/fungsi_combobox.php";
include "../config/class_paging.php";

// Bagian Home
if ($_GET[module]=='home'){
  echo "<h2>Selamat Datang</h2>
          <p>Hai <b>$_SESSION[namauser]</b>, silahkan klik menu pilihan yang berada 
          di sebelah kiri untuk mengelola content website. </p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p>&nbsp;</p>
          <p align=right>Login Hari ini: ";
  echo tgl_indo(date("Y m d")); 
  echo " | "; 
  echo date("H:i:s");
  echo "</p>";
}

// Bagian Profil Lembaga
elseif ($_GET[module]=='profil'){
  $sql  = mysql_query("SELECT * FROM modul WHERE id_modul='11'");
  $r    = mysql_fetch_array($sql);

  echo "<h2>Profil Lembaga</h2>
        <form method=POST enctype='multipart/form-data' action=aksi.php?module=profil&act=update>
        <input type=hidden name=id value=$r[id_modul]>
        <table>
        <tr><td><img src=foto_berita/$r[gambar]></td></tr>
        <tr><td>Ganti Foto : <input type=file size=30 name=fupload></td></tr>
        <tr><td><textarea name=isi cols=94 rows=30>$r[static_content]</textarea></td></tr>
        <tr><td><input type=submit value=Update></td></tr>
        </form></table>";
}

// Bagian User
elseif ($_GET[module]=='user'){
  include "modul/mod_user.php";
}

// Bagian Modul
elseif ($_GET[module]=='modul'){
  include "modul/mod_modul.php";
}

// Bagian Agenda
elseif ($_GET[module]=='agenda'){
  include "modul/mod_agenda.php";
}

// Bagian Berita
elseif ($_GET[module]=='berita'){
  include "modul/mod_berita.php";
}

// Bagian Pengumuman
elseif ($_GET[module]=='pengumuman'){
  include "modul/mod_pengumuman.php";
}


// Bagian Banner
elseif ($_GET[module]=='banner'){
  include "modul/mod_banner.php";
}

// Bagian Hubungi Kami
elseif ($_GET[module]=='hubungi'){
  include "modul/mod_hubungi.php";
}

// Apabila modul tidak ditemukan
else{
  echo "<p><b>MODUL BELUM ADA</b></p>";
}
?>
