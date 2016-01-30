<table width="100%" cellspacing=5>
<?php
include "config/koneksi.php";
include "config/fungsi_indotgl.php";
include "config/library.php";
include "config/class_paging.php";

// Bagian Home
if ($_GET[module]=='home'){
  echo "<tr><td align=center><img src=images/welcome.jpg><br><br></td></tr>";
  // Tampilkan 3 berita terbaru
  echo "<tr><td class=judul_head>&#187; Berita Terkini</td></tr>";
 	$terkini= mysql_query("SELECT * FROM berita,user 
                          WHERE user.id_user=berita.id_user 
                          ORDER BY id_berita DESC LIMIT 3");		 
	while($t=mysql_fetch_array($terkini)){
		$tgl = tgl_indo($t[tanggal]);
		echo "<tr><td class=isi_kecil>$t[hari], $tgl</td></tr>";
		echo "<tr><td class=judul><a href=?module=detailberita&id=$t[id_berita]>$t[judul]</a></td></tr>";
		echo "<tr><td class=isi_kecil>Ditulis Oleh : $t[nama_lengkap]</td></tr>";
    echo "<tr><td class=isi>";
 		if ($t[gambar]!=''){
			echo "<img src='admin/foto_berita/$t[gambar]' width=150 height=120 hspace=10 border=0 align=left>";
		}

    // Tampilkan hanya sebagian isi berita 
    $isi_berita = nl2br($t[isi_berita]);
    $isi = substr($isi_berita,0,410); // ambil sebanyak 410 karakter
    $isi = substr($isi_berita,0,strrpos($isi," ")); // spasi antar kalimat

    echo "$isi ... <a href=?module=detailberita&id=$t[id_berita]>Selengkapnya</a>
          <br><br><hr color=white></td></tr>";
	}

  // Tampilkan 5 berita sebelumnya
  echo "<tr><td><img src=images/berita_sebelumnya.jpg></td></tr>";
  $sebelum=mysql_query("SELECT * FROM berita 
                        ORDER BY id_berita DESC LIMIT 3,5");		 
	while($s=mysql_fetch_array($sebelum)){
	   echo "<tr><td class=isi>&bull; &nbsp; &nbsp; 
          <a href=?module=detailberita&id=$s[id_berita]>$s[judul]</a></td></tr>";
	}
  echo "<tr><td align=right><a href=?module=berita>
        <img src=images/arsip_berita.jpg border=0></a></td></tr>";

  // Tampilkan 3 agenda dan 3 pengumuman terbaru
  echo "<tr><td>
        <table width=100%>
        <tr>
          <td><img src=images/agenda.jpg></td>
          <td><img src=images/pengumuman.jpg></td>
        </tr>
        <tr valign=top>
          <td>";
        
 	$agenda=mysql_query("SELECT * FROM agenda 
                      ORDER BY id_agenda DESC LIMIT 3");		 
	while($a=mysql_fetch_array($agenda)){
    $mulai  =tgl_indo($a[tgl_mulai]);
    $selesai=tgl_indo($a[tgl_selesai]);
	 	echo "<div class=isi_kecil>$mulai - $selesai </div>
          <div class=isi><a href=?module=detailagenda&id=$a[id_agenda]>$a[tema]</a></div>
          <hr color=white>";
			}
  
  echo "</td>
        <td>";
 	
  $umum=mysql_query("SELECT * FROM pengumuman 
                    ORDER BY id_pengumuman DESC LIMIT 3");		 
	while($u=mysql_fetch_array($umum)){
    $tgl=tgl_indo($u[tanggal]);
	 	echo "<div class=isi_kecil>$tgl </div>
          <div class=isi><a href=?module=detailpengumuman&id=$u[id_pengumuman]>$u[judul]</a></div>
         <hr color=white>";
			}

  echo "</td></tr></table>
        </td></tr>";

}


// Detail Berita
elseif ($_GET[module]=='detailberita'){
	$detail=mysql_query("SELECT * FROM berita,user 
                      WHERE user.id_user=berita.id_user 
                      AND id_berita='$_GET[id]'");
	$d   = mysql_fetch_array($detail);
	$tgl = tgl_indo($d[tanggal]);
	echo "<tr><td class=isi_kecil>$d[hari], $tgl</td></tr>";
	echo "<tr><td class=judul>$d[judul]</td></tr>";
	echo "<tr><td class=isi_kecil>Ditulis Oleh : $d[nama_lengkap]</td></tr>";
  echo "<tr><td class=isi>";
 	if ($d[gambar]!=''){
		echo "<img src='admin/foto_berita/$d[gambar]' hspace=10 border=0 align=left>";
	}
 	$isi_berita=nl2br($d[isi_berita]);
	echo "$isi_berita</td></tr>";	 		  
	echo "<tr><td class=kembali><br>
        [ <a href=javascript:history.go(-1)>Kembali</a> ]</td></tr>";	 		  

  // Apabila berita dibuka, maka tambahkan counternya
  mysql_query("UPDATE berita SET counter=$d[counter]+1 
              WHERE id_berita='$_GET[id]'");
}


// Bagian Berita
elseif ($_GET[module]=='berita'){
   echo "<tr><td class=judul_head>&#187; Berita</td></tr>";
      
  $p      = new Paging;
  $batas  = 8;
  $posisi = $p->cariPosisi($batas);

 	$sql   = "SELECT * FROM berita,user 
           WHERE user.id_user=berita.id_user 
           ORDER BY id_berita DESC LIMIT $posisi,$batas";		 
	$hasil = mysql_query($sql);
	
  while($r=mysql_fetch_array($hasil)){
	 $tgl = tgl_indo($r[tanggal]);
	 echo "<tr><td class=isi_kecil>$r[hari], $tgl</td></tr>";
	 echo "<tr><td class=judul><a href=$_SERVER[PHP_SELF]?module=detailberita&id=$r[id_berita]>$r[judul]</a></td></tr>";
	 echo "<tr><td class=isi>";

   // Tampilkan hanya sebagian isi berita 
   $isi_berita = nl2br($r[isi_berita]);
   $isi = substr($isi_berita,0,380); // ambil sebanyak 410 karakter
   $isi = substr($isi_berita,0,strrpos($isi," ")); // spasi antar kalimat

   echo "$isi ... <a href=?module=detailberita&id=$r[id_berita]>Selengkapnya</a>
         <br><br><hr color=white></td></tr>";
	}

  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM berita"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

  echo "<tr><td class=kembali>$linkHalaman</td></tr>";
}


// Bagian Agenda
elseif ($_GET[module]=='agenda'){
   echo "<tr><td class=judul_head>&#187; Agenda</td></tr>";
      
  $p      = new Paging;
  $batas  = 10;
  $posisi = $p->cariPosisi($batas);

 	$sql   = "SELECT * FROM agenda,user  
           WHERE user.id_user=agenda.id_user 
           ORDER BY id_agenda DESC LIMIT $posisi,$batas";		 
	$hasil = mysql_query($sql);
	
  while($r=mysql_fetch_array($hasil)){
	 $tgl_mulai   = tgl_indo($r[tgl_mulai]);
	 $tgl_selesai = tgl_indo($r[tgl_selesai]);
   $isi_agenda=nl2br($r[isi_agenda]);
	
	 echo "<tr><td class=isi_kecil>$tgl_mulai s/d $tgl_selesai</td></tr>";
	 echo "<tr><td class=judul>$r[tema]</td></tr>";
	 echo "<tr><td class=isi><b>Topik</b>    : $isi_agenda</td></tr>";
 	 echo "<tr><td class=isi><b>Tempat</b>   : $r[tempat]</td></tr>";
 	 echo "<tr><td class=isi><b>Pengirim</b> : $r[nama_lengkap]<hr color=white></td></tr>";
	}

  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM agenda"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

  echo "<tr><td class=kembali>$linkHalaman</td></tr>";
}


// Detail Agenda
elseif ($_GET[module]=='detailagenda'){
	$detail=mysql_query("SELECT * FROM agenda,user 
                      WHERE user.id_user=agenda.id_user 
                      AND id_agenda='$_GET[id]'");
	$d   = mysql_fetch_array($detail);
  $tgl_mulai   = tgl_indo($d[tgl_mulai]);
  $tgl_selesai = tgl_indo($d[tgl_selesai]);
  $isi_agenda=nl2br($d[isi_agenda]);
	
  echo "<tr><td class=isi_kecil>$tgl_mulai s/d $tgl_selesai</td></tr>";
  echo "<tr><td class=judul>$d[tema]</td></tr>";
	echo "<tr><td class=isi><b>Topik</b>    : $isi_agenda</td></tr>";
 	echo "<tr><td class=isi><b>Tempat</b>   : $d[tempat]</td></tr>";
 	echo "<tr><td class=isi><b>Pengirim</b> : $d[nama_lengkap]<hr color=white></td></tr>";

	echo "<tr><td class=kembali><br>
        [ <a href=javascript:history.go(-1)>Kembali</a> ]</td></tr>";	 		  
}


// Bagian Pengumuman
elseif ($_GET[module]=='pengumuman'){
   echo "<tr><td class=judul_head>&#187; Pengumuman</td></tr>";
      
  $p      = new Paging;
  $batas  = 10;
  $posisi = $p->cariPosisi($batas);

 	$sql   = "SELECT * FROM pengumuman,user  
           WHERE user.id_user=pengumuman.id_user 
           ORDER BY id_pengumuman DESC LIMIT $posisi,$batas";		 
	$hasil = mysql_query($sql);
	
  while($r=mysql_fetch_array($hasil)){
	 $tgl         = tgl_indo($r[tanggal]);
   $isi         = nl2br($r[isi]);
	
	 echo "<tr><td class=isi_kecil>$tgl</td></tr>";
	 echo "<tr><td class=judul>$r[judul]</td></tr>";
	 echo "<tr><td class=isi>$isi</td></tr>";
 	 echo "<tr><td class=isi><b>Pengirim</b> : $r[nama_lengkap]<hr color=white></td></tr>";
	}

  $jmldata     = mysql_num_rows(mysql_query("SELECT * FROM pengumuman"));
  $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
  $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

  echo "<tr><td class=kembali>$linkHalaman</td></tr>";
}


// Detail Pengumuman
elseif ($_GET[module]=='detailpengumuman'){
	$detail=mysql_query("SELECT * FROM pengumuman,user 
                      WHERE user.id_user=pengumuman.id_user 
                      AND id_pengumuman='$_GET[id]'");
	$d   = mysql_fetch_array($detail);
  $tgl         = tgl_indo($d[tanggal]);
  $isi         = nl2br($d[isi]);
	
  echo "<tr><td class=isi_kecil>$tgl</td></tr>";
  echo "<tr><td class=judul>$d[judul]</td></tr>";
	echo "<tr><td class=isi>$isi</td></tr>";
 	echo "<tr><td class=isi><b>Pengirim</b> : $d[nama_lengkap]<hr color=white></td></tr>";

	echo "<tr><td class=kembali><br>
        [ <a href=javascript:history.go(-1)>Kembali</a> ]</td></tr>";	 		  
}


// Bagian Hubungi Kami
elseif ($_GET[module]=='hubungi'){
  echo "<tr><td class=judul_head>&#187; Hubungi Kami</td></tr>";

  echo "<tr><td class=isi>Silahkan hubungi kami secara online:</td></tr>";  

  echo "<form method=POST action='?module=kirimemail'>  
        <tr><td class=isi>Nama   : <input type=text name=nama size=35></td></tr>
        <tr><td class=isi>E-mail : <input type=text name=email size=35></td></tr>
        <tr><td class=isi>Subjek: <input type=text name=subjek size=50></td></tr>
        <tr><td class=isi>Pesan  : <br><textarea name=pesan rows=13 cols=70></textarea></td></tr>
        <tr><td><input type=submit value=Kirim></td></tr>
        </form>";

	echo "<tr><td class=kembali><br>
        [ <a href=javascript:history.go(-1)>Kembali</a> ]</td></tr>";	 		  
}


// Bagian Kirim Email
elseif ($_GET[module]=='kirimemail'){
  mysql_query("INSERT INTO hubungi(nama,
                                   email,
                                   subjek,
                                   pesan,
                                   tanggal) 
                        VALUES('$_POST[nama]',
                               '$_POST[email]',
                               '$_POST[subjek]',
                               '$_POST[pesan]',
                               '$tgl_sekarang')");

  echo "<tr><td class=judul_head>&#187; Status Email</td></tr>
        <tr><td class=isi>Email telah sukses terkirim dan segera akan kami balas</td></tr>
        <tr><td class=kembali><br>
        [ <a href=index.php>Kembali</a> ]</td></tr>";	 		  
}


// Bagian Profil
elseif ($_GET[module]=='profil'){
   echo "<tr><td class=judul_head>&#187; Profil Lembaga</td></tr>";

	$profil = mysql_query("SELECT * FROM modul WHERE id_modul='11'");
	$r      = mysql_fetch_array($profil);

  echo "<tr><td class=isi>";
  if ($r[gambar]!=''){
		echo "<img src='admin/foto_berita/$r[gambar]' hspace=10 border=0 align=left>";
	}
	$isi_profil=nl2br($r[static_content]);
  echo "$isi_profil</td></tr>";  

	echo "<tr><td class=kembali><br>
        [ <a href=javascript:history.go(-1)>Kembali</a> ]</td></tr>";	 		  
}


// Bagian Hasil Pencarian
elseif ($_GET[module]=='hasilcari'){
   echo "<tr><td class=judul_head>&#187; Hasil Pencarian</td></tr>";

  // Hanya mencari berita, apabila diperlukan bisa ditambahkan utk mencari agenda, pengumuman, dll
	$cari   = mysql_query("SELECT * FROM berita WHERE isi_berita LIKE '%$_POST[kata]%' OR judul LIKE '%$_POST[kata]%'");
	$jumlah = mysql_num_rows($cari);

  if ($jumlah > 0){
    echo "<tr><td class=isi>
          <br>Ditemukan <b>$jumlah</b> berita dengan kata kunci <b>$_POST[kata]</b> : <ul>"; 
    
    while($r=mysql_fetch_array($cari)){
      echo "<li><a href=?module=detailberita&id=$r[id_berita]>$r[judul]</a></li>";
    }      
    echo "</ul></td></tr>";
  }
  else{
    echo "<tr><td class=judul>
          Tidak ditemukan berita dengan kata <b>$_POST[kata]</b></td></tr>";
  }

	echo "<tr><td class=kembali><br>
        [ <a href=javascript:history.go(-1)>Kembali</a> ]</td></tr>";	 		  
}


?>
</table>
