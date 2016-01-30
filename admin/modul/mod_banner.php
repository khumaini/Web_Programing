<?php
switch($_GET[act]){
  // Tampil Banner
  default:
    echo "<h2>Banner</h2>
          <input type=button value='Tambah Banner' onclick=location.href='?module=banner&act=tambahbanner'>
          <table>
          <tr><th>no</th><th>judul</th><th>link</th><th>tgl. posting</th><th>aksi</th></tr>";
    $tampil=mysql_query("SELECT * FROM banner ORDER BY id_banner DESC");
    $no=1;
    while ($r=mysql_fetch_array($tampil)){
      $tgl=tgl_indo($r[tgl_posting]);
      echo "<tr><td>$no</td>
                <td>$r[judul]</td>
                <td><a href=$r[url]>$r[url]</a></td>
                <td>$tgl</td>
                <td><a href=?module=banner&act=editbanner&id=$r[id_banner]>Edit</a> | 
	                  <a href=./aksi.php?module=banner&act=hapus&id=$r[id_banner]>Hapus</a>
		        </tr>";
    $no++;
    }
    echo "</table>";
    break;
  
  case "tambahbanner":
    echo "<h2>Tambah Banner</h2>
          <form method=POST action='./aksi.php?module=banner&act=input' enctype='multipart/form-data'>
          <table>
          <tr><td>Judul</td><td>  : <input type=text name='judul' size=30></td></tr>
          <tr><td>Link</td><td>   : <input type=text name='link' size=50 value='http://'></td></tr>
          <tr><td>Gambar</td><td> : <input type=file name='fupload' size=40></td></tr>
          <tr><td colspan=2><input type=submit value=Simpan>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form><br><br><br>";
     break;
    
  case "editbanner":
    $edit = mysql_query("SELECT * FROM banner WHERE id_banner='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Banner</h2>
          <form method=POST enctype='multipart/form-data' action=./aksi.php?module=banner&act=update>
          <input type=hidden name=id value=$r[id_banner]>
          <table>
          <tr><td>Judul</td><td>     : <input type=text name='judul' size=30 value='$r[judul]'></td></tr>
          <tr><td>Link</td><td>      : <input type=text name='link' size=50 value='$r[url]'></td></tr>
          <tr><td>Gambar</td><td>    : <img src='foto_berita/$r[gambar]'></td></tr>
          <tr><td>Ganti Gbr</td><td> : <input type=file name='fupload' size=30> *)</td></tr>
          <tr><td colspan=2>*) Apabila gambar tidak diubah, dikosongkan saja.</td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;  
}
?>
