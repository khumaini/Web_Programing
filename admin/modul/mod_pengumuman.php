<?php
switch($_GET[act]){
  // Tampil Pengumuman
  default:
    echo "<h2>Pengumuman</h2>
          <input type=button value='Tambah Pengumuman' onclick=location.href='?module=pengumuman&act=tambahpengumuman'>
          <table>
          <tr><th>no</th><th>judul</th><th>tanggal</th><th>aksi</th></tr>";
    if ($_SESSION[leveluser]=='admin'){
      $tampil=mysql_query("SELECT * FROM pengumuman ORDER BY id_pengumuman DESC");
    }
    else{
      $tampil=mysql_query("SELECT * FROM pengumuman 
                         WHERE id_user='$_SESSION[namauser]'       
                         ORDER BY id_pengumuman DESC");
    }
    $no=1;
    while ($r=mysql_fetch_array($tampil)){
      $tanggal=tgl_indo($r[tanggal]);
      echo "<tr><td>$no</td>
                <td>$r[judul]</td>
                <td>$tanggal</td>
                <td><a href=?module=pengumuman&act=editpengumuman&id=$r[id_pengumuman]>Edit</a> | 
	                  <a href=./aksi.php?module=pengumuman&act=hapus&id=$r[id_pengumuman]>Hapus</a>
		        </tr>";
    $no++;
    }
    echo "</table>";
    break;
  
  case "tambahpengumuman":
    echo "<h2>Tambah Pengumuman</h2>
          <form method=POST action='./aksi.php?module=pengumuman&act=input'>
          <table>
          <tr><td>Judul</td>         <td> : <input type=text name='judul' size=60></td></tr>
          <tr><td>Isi Pengumuman</td><td> : <textarea name='isi_pengumuman' cols=80 rows=10></textarea></td></tr>
          <tr><td>Tanggal</td><td> : ";        
          combotgl(1,31,'tgl',Tgl);
          combobln(1,12,'bln',Bulan);
          combotgl($thn_sekarang-2,$thn_sekarang+2,'thn',Tahun);  
    echo "</td></tr>
          <tr><td colspan=2><input type=submit value=Simpan>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;
    
  case "editpengumuman":
    $edit = mysql_query("SELECT * FROM pengumuman WHERE id_pengumuman='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Pengumuman</h2>
          <form method=POST action=./aksi.php?module=pengumuman&act=update>
          <input type=hidden name=id value=$r[id_pengumuman]>
          <table>
          <tr><td>Judul</td>         <td> : <input type=text name='judul' size=60 value='$r[judul]'></td></tr>
          <tr><td>Isi Pengumuman</td><td> : <textarea name='isi_pengumuman' cols=80 rows=10>$r[isi]</textarea></td></tr>
          <tr><td>Tanggal</td><td> : ";    
          $get_tgl=substr("$r[tanggal]",8,2);
          combotgl2(1,31,'tgl',$get_tgl);
          $get_bln=substr("$r[tanggal]",5,2);
          combobln2(1,12,'bln',$get_bln);
          $get_thn=substr("$r[tanggal]",0,4);
          $thn_skrg=date("Y");
          combotgl2($thn_sekarang-2,$thn_sekarang+2,'thn',$get_thn);
    echo "</td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;  
}
?>
