<?php
session_start();
include "../config/koneksi.php";
include "../config/library.php";

$module=$_GET[module];
$act=$_GET[act];


// Menghapus data
if (isset($module) AND $act=='hapus'){
  mysql_query("DELETE FROM ".$module." WHERE id_".$module."='$_GET[id]'");
  header('location:media.php?module='.$module);
}


// Input user
elseif ($module=='user' AND $act=='input'){
  $pass=md5($_POST[password]);
  mysql_query("INSERT INTO user(id_user,
                                password,
                                nama_lengkap,
                                email) 
	                       VALUES('$_POST[id_user]',
                                '$pass',
                                '$_POST[nama_lengkap]',
                                '$_POST[email]')");
  header('location:media.php?module='.$module);
}

// Update user
elseif ($module=='user' AND $act=='update'){
  // Apabila password tidak diubah
  if (empty($_POST[password])) {
    mysql_query("UPDATE user SET id_user      = '$_POST[id_user]',
                                 nama_lengkap = '$_POST[nama_lengkap]',
                                 email        = '$_POST[email]'  
                           WHERE id_user      = '$_POST[id]'");
  }
  // Apabila password diubah
  else{
    $pass=md5($_POST[password]);
    mysql_query("UPDATE user SET id_user      = '$_POST[id_user]',
                                 password     = '$pass',
                                 nama_lengkap = '$_POST[nama_lengkap]',
                                 email        = '$_POST[email]'  
                           WHERE id_user      = '$_POST[id]'");
  }
  header('location:media.php?module='.$module);
}


// Input modul
elseif ($module=='modul' AND $act=='input'){
  mysql_query("INSERT INTO modul(nama_modul,
                                 link,
                                 publish,
                                 aktif,
                                 status,
                                 urutan) 
	                       VALUES('$_POST[nama_modul]',
                                '$_POST[link]',
                                '$_POST[publish]',
                                '$_POST[aktif]',
                                '$_POST[status]',
                                '$_POST[urutan]')");
  header('location:media.php?module='.$module);
}

// Update modul
elseif ($module=='modul' AND $act=='update'){
  mysql_query("UPDATE modul SET nama_modul = '$_POST[nama_modul]',
                                link       = '$_POST[link]',
                                publish    = '$_POST[publish]',
                                aktif      = '$_POST[aktif]',
                                status     = '$_POST[status]',
                                urutan     = '$_POST[urutan]'  
                          WHERE id_modul   = '$_POST[id]'");
  header('location:media.php?module='.$module);
}


// Input agenda
elseif ($module=='agenda' AND $act=='input'){
  $mulai=sprintf("%02d%02d%02d",$_POST[thn_mulai],$_POST[bln_mulai],$_POST[tgl_mulai]);
  $selesai=sprintf("%02d%02d%02d",$_POST[thn_selesai],$_POST[bln_selesai],$_POST[tgl_selesai]);
  
  mysql_query("INSERT INTO agenda(tema,
                                  isi_agenda,
                                  tempat,
                                  tgl_mulai,
                                  tgl_selesai,
                                  tgl_posting,
                                  id_user) 
					                VALUES('$_POST[tema]',
                                 '$_POST[isi_agenda]',
                                 '$_POST[tempat]',
                                 '$mulai',
                                 '$selesai',
                                 '$tgl_sekarang',
                                 '$_SESSION[namauser]')");
  header('location:media.php?module='.$module);
}

// Update agenda
elseif ($module=='agenda' AND $act=='update'){
  $mulai=sprintf("%02d%02d%02d",$_POST[thn_mulai],$_POST[bln_mulai],$_POST[tgl_mulai]);
  $selesai=sprintf("%02d%02d%02d",$_POST[thn_selesai],$_POST[bln_selesai],$_POST[tgl_selesai]);

  mysql_query("UPDATE agenda SET tema        = '$_POST[tema]',
                                 isi_agenda  = '$_POST[isi_agenda]',
                                 tgl_mulai   = '$mulai',
                                 tgl_selesai = '$selesai',
                                 tempat      = '$_POST[tempat]'  
                           WHERE id_agenda   = '$_POST[id]'");
  header('location:media.php?module='.$module);
}


// Input pengumuman
elseif ($module=='pengumuman' AND $act=='input'){
  $tanggal=sprintf("%02d%02d%02d",$_POST[thn],$_POST[bln],$_POST[tgl]);
  
  mysql_query("INSERT INTO pengumuman(judul,
                                      isi,
                                      tanggal,
                                      tgl_posting,
                                      id_user) 
					                   VALUES('$_POST[judul]',
                                    '$_POST[isi_pengumuman]',
                                    '$tanggal',
                                    '$tgl_sekarang',
                                    '$_SESSION[namauser]')");
  header('location:media.php?module='.$module);
}

// Update pengumuman
elseif ($module=='pengumuman' AND $act=='update'){
  $tanggal=sprintf("%02d%02d%02d",$_POST[thn],$_POST[bln],$_POST[tgl]);

  mysql_query("UPDATE pengumuman SET judul         = '$_POST[judul]',
                                     isi           = '$_POST[isi_pengumuman]',
                                     tanggal       = '$tanggal'
                               WHERE id_pengumuman = '$_POST[id]'");
  header('location:media.php?module='.$module);
}


// Input berita
elseif ($module=='berita' AND $act=='input'){
  $lokasi_file    = $_FILES['fupload']['tmp_name'];
  $tipe_file      = $_FILES['fupload']['type'];
  $nama_file      = $_FILES['fupload']['name'];
  $acak           = rand(000000,999999);
  $nama_file_unik = $acak.$nama_file; 

  // Apabila ada gambar yang diupload
  if (!empty($lokasi_file)){
    // Apabila tipe gambar bukan jpeg akan tampil peringatan
    if ($tipe_file != "image/jpeg" AND $tipe_file != "image/pjpeg"){
      echo "Gagal menyimpan data !!! <br>
            Tipe file <b>$nama_file</b> : $tipe_file <br>
            Tipe file yang diperbolehkan adalah : <b>JPG/JPEG</b>.<br>";
      	echo "<a href=javascript:history.go(-1)>Ulangi Lagi</a>";	 		  
    }
    else{
      move_uploaded_file($lokasi_file,"foto_berita/$nama_file_unik");
      mysql_query("INSERT INTO berita(judul,
                                      id_kategori,
                                      isi_berita,
                                      id_user,
                                      jam,
                                      tanggal,
                                      hari,
                                      gambar) 
                              VALUES('$_POST[judul]',
                                     '$_POST[kategori]',
                                     '$_POST[isi_berita]',
                                     '$_SESSION[namauser]',
                                     '$jam_sekarang',
                                     '$tgl_sekarang',
                                     '$hari_ini',
                                     '$nama_file_unik')");
     header('location:media.php?module='.$module);
   }
   }
   else{
     mysql_query("INSERT INTO berita(judul,
                                     id_kategori,
                                     isi_berita,
                                     id_user,
                                     jam,
                                     tanggal,
                                     hari) 
                             VALUES('$_POST[judul]',
                                    '$_POST[kategori]',
                                    '$_POST[isi_berita]',
                                    '$_SESSION[namauser]',
                                    '$jam_sekarang',
                                    '$tgl_sekarang',
                                    '$hari_ini')");
    header('location:media.php?module='.$module);
  }
}

// Update berita
elseif ($module=='berita' AND $act=='update'){
  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];

  // Apabila gambar tidak diganti
  if (empty($lokasi_file)){
    mysql_query("UPDATE berita SET judul       = '$_POST[judul]',
                                   id_kategori = '$_POST[kategori]',
                                   isi_berita  = '$_POST[isi_berita]'  
                             WHERE id_berita   = '$_POST[id]'");
  }
  else{
    move_uploaded_file($lokasi_file,"foto_berita/$nama_file");
    mysql_query("UPDATE berita SET judul       = '$_POST[judul]',
                                   id_kategori = '$_POST[kategori]',
                                   isi_berita  = '$_POST[isi_berita]',
                                   gambar      = '$nama_file'   
                             WHERE id_berita   = '$_POST[id]'");
  }
  header('location:media.php?module='.$module);
}


// Input banner
elseif ($module=='banner' AND $act=='input'){
  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];

  // Apabila ada gambar yang diupload
  if (!empty($lokasi_file)){
    move_uploaded_file($lokasi_file,"foto_berita/$nama_file");
    mysql_query("INSERT INTO banner(judul,
                                    url,
                                    tgl_posting,
                                    gambar) 
                            VALUES('$_POST[judul]',
                                   '$_POST[link]',
                                   '$tgl_sekarang',
                                   '$nama_file')");
  }
  else{
    mysql_query("INSERT INTO banner(judul,
                                    tgl_posting,
                                    url) 
                            VALUES('$_POST[judul]',
                                   '$tgl_sekarang',
                                   '$_POST[link]')");
  }
  header('location:media.php?module='.$module);
}

// Update banner
elseif ($module=='banner' AND $act=='update'){
  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];

  // Apabila gambar tidak diganti
  if (empty($lokasi_file)){
    mysql_query("UPDATE banner SET judul     = '$_POST[judul]',
                                   url       = '$_POST[link]'
                             WHERE id_banner = '$_POST[id]'");
  }
  else{
    move_uploaded_file($lokasi_file,"foto_berita/$nama_file");
    mysql_query("UPDATE banner SET judul     = '$_POST[judul]',
                                   url       = '$_POST[link]',
                                   gambar    = '$nama_file'   
                             WHERE id_banner = '$_POST[id]'");
  }
  header('location:media.php?module='.$module);
}

// Update profil
elseif ($module=='profil' AND $act=='update'){
  $lokasi_file = $_FILES['fupload']['tmp_name'];
  $nama_file   = $_FILES['fupload']['name'];

  // Apabila gambar tidak diganti
  if (empty($lokasi_file)){
    mysql_query("UPDATE modul SET static_content = '$_POST[isi]'
                            WHERE id_modul       = '$_POST[id]'");
  }
  else{
    move_uploaded_file($lokasi_file,"foto_berita/$nama_file");
    mysql_query("UPDATE modul SET static_content = '$_POST[isi]',
                                  gambar         = '$nama_file'    
                            WHERE id_modul       = '$_POST[id]'");
  }
  header('location:media.php?module='.$module);
}
?>
