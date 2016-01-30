<?php
switch($_GET[act]){
  // Tampil Berita
  default:
    echo "<h2>Produk</h2>
          <input type=button value='Tambah Berita' onclick=location.href='?module=berita&act=tambahberita'>
          <table>
          <tr><th>no</th><th>judul</th><th>tgl. posting</th><th>aksi</th></tr>";

    $p      = new Paging;
    $batas  = 10;
    $posisi = $p->cariPosisi($batas);

    if ($_SESSION[leveluser]=='admin'){
      $tampil = mysql_query("SELECT * FROM berita ORDER BY id_berita DESC limit $posisi,$batas");
    }
    else{
      $tampil=mysql_query("SELECT * FROM berita 
                           WHERE id_user='$_SESSION[namauser]'       
                           ORDER BY id_berita DESC");
    }
  
    $no = $posisi+1;
    while($r=mysql_fetch_array($tampil)){
      $tgl_posting=tgl_indo($r[tanggal]);
      echo "<tr><td>$no</td>
                <td>$r[judul]</td>
                <td>$tgl_posting</td>
		            <td><a href=?module=berita&act=editberita&id=$r[id_berita]>Edit</a> | 
		                <a href=./aksi.php?module=berita&act=hapus&id=$r[id_berita]>Hapus</a></td>
		        </tr>";
      $no++;
    }
    echo "</table>";
  
    if ($_SESSION[leveluser]=='admin'){
      $jmldata = mysql_num_rows(mysql_query("SELECT * FROM berita"));
    }
    else{
      $jmldata = mysql_num_rows(mysql_query("SELECT * FROM berita WHERE id_user='$_SESSION[namauser]'"));
    }  
    $jmlhalaman  = $p->jumlahHalaman($jmldata, $batas);
    $linkHalaman = $p->navHalaman($_GET[halaman], $jmlhalaman);

    echo "<div id=paging>$linkHalaman</div><br>";
    break;
  
  case "tambahberita":
    echo "<h2>Tambah Berita</h2>
          <form method=POST action='./aksi.php?module=berita&act=input' enctype='multipart/form-data'>
          <table>
          <tr><td>Judul</td>     <td> : <input type=text name='judul' size=60></td></tr>
          <tr><td>Kategori</td>  <td> : 
          <select name='kategori'>
            <option value=0 selected>- Pilih Kategori -</option>";
            $tampil=mysql_query("SELECT * FROM kategori ORDER BY nama_kategori");
            while($r=mysql_fetch_array($tampil)){
              echo "<option value=$r[id_kategori]>$r[nama_kategori]</option>";
            }
    echo "</select></td></tr>
          <tr><td>Isi Berita</td><td> : <textarea name='isi_berita' cols=80 rows=18></textarea></td></tr>
          <tr><td>Gambar</td>    <td> : <input type=file name='fupload' size=40></td></tr>
          <tr><td colspan=2><input type=submit value=Simpan>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
     break;
    
  case "editberita":
    $edit = mysql_query("SELECT * FROM berita WHERE id_berita='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Berita</h2>
          <form method=POST enctype='multipart/form-data' action=./aksi.php?module=berita&act=update>
          <input type=hidden name=id value=$r[id_berita]>
          <table>
          <tr><td>Judul</td>     <td> : <input type=text name='judul' size=40 value='$r[judul]'></td></tr>
          <tr><td>Kategori</td>  <td> : <select name='kategori'>";
 
    $tampil=mysql_query("SELECT * FROM kategori ORDER BY nama_kategori");
    while($w=mysql_fetch_array($tampil)){
      if ($r[id_kategori]==$w[id_kategori]){
        echo "<option value=$w[id_kategori] selected>$w[nama_kategori]</option>";
      }
      else{
        echo "<option value=$w[id_kategori]>$w[nama_kategori]</option>";
      }
    }
    echo "</select></td></tr>
          <tr><td>Isi Berita</td><td> : <textarea name='isi_berita' cols=60 rows=15>$r[isi_berita]</textarea></td></tr>
          <tr><td>Gambar</td><td> : <img src='foto_berita/$r[gambar]'></td></tr>
         <tr><td>Ganti Gbr</td>    <td> : <input type=file name='fupload' size=30> *)</td></tr>
         <tr><td colspan=2>*) Apabila gambar tidak diubah, dikosongkan saja.</td></tr>
         <tr><td colspan=2><input type=submit value=Update>
                           <input type=button value=Batal onclick=self.history.back()></td></tr>
         </table></form>";
    break;  
}
?>
