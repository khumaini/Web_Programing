<?php
switch($_GET[act]){
  // Tampil Agenda
  default:
    echo "<h2>Agenda</h2>
          <input type=button value='Tambah Agenda' onclick=location.href='?module=agenda&act=tambahagenda'>
          <table>
          <tr><th>no</th><th>tema</th><th>tgl. mulai</th><th>tgl. selesai</th><th>aksi</th></tr>";
    if ($_SESSION[leveluser]=='admin'){
      $tampil=mysql_query("SELECT * FROM agenda ORDER BY id_agenda DESC");
    }
    else{
      $tampil=mysql_query("SELECT * FROM agenda 
                           WHERE id_user='$_SESSION[namauser]'       
                           ORDER BY id_agenda DESC");
    }
    $no=1;
    while ($r=mysql_fetch_array($tampil)){
      $tgl_mulai   = tgl_indo($r[tgl_mulai]);
      $tgl_selesai = tgl_indo($r[tgl_selesai]);
      echo "<tr><td>$no</td>
                <td>$r[tema]</td>
                <td>$tgl_mulai</td>
                <td>$tgl_selesai</td>
                <td><a href=?module=agenda&act=editagenda&id=$r[id_agenda]>Edit</a> | 
	                  <a href=./aksi.php?module=agenda&act=hapus&id=$r[id_agenda]>Hapus</a>
		        </tr>";
      $no++;
    }
    echo "</table>";
    break;
  
  case "tambahagenda":
    echo "<h2>Tambah Agenda</h2>
          <form method=POST action='./aksi.php?module=agenda&act=input'>
          <table>
          <tr><td>Tema</td>      <td> : <input type=text name='tema' size=60></td></tr>
          <tr><td>Isi Agenda</td><td> : <textarea name='isi_agenda' cols=80 rows=10></textarea></td></tr>
          <tr><td>Tempat</td>    <td> : <input type=text name='tempat' size=40></td></tr>

          <tr><td>Tgl Mulai</td><td> : ";        
          combotgl(1,31,'tgl_mulai',Tgl);
          combobln(1,12,'bln_mulai',Bulan);
          combotgl($thn_sekarang-2,$thn_sekarang+2,'thn_mulai',Tahun);
      
    echo "<tr><td>Tgl Selesai</td><td> : ";
          combotgl(1,31,'tgl_selesai',Tgl);
          combobln(1,12,'bln_selesai',Bulan);
          combotgl($thn_sekarang-2,$thn_sekarang+2,'thn_selesai',Tahun);

    echo "</td></tr>
          <tr><td colspan=2><input type=submit value=Simpan>
          <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table>
          </form>";
    break;
    
  case "editagenda":
    $edit = mysql_query("SELECT * FROM agenda WHERE id_agenda='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Agenda</h2>
          <form method=POST action=./aksi.php?module=agenda&act=update>
          <input type=hidden name=id value=$r[id_agenda]>
          <table>
          <tr><td>Tema</td>      <td> : <input type=text name='tema' size=60 value='$r[tema]'></td></tr>
          <tr><td>Isi Agenda</td><td> : <textarea name='isi_agenda' cols=80 rows=10>$r[isi_agenda]</textarea></td></tr>
          <tr><td>Tempat</td>    <td> : <input type=text name='tempat' size=40 value='$r[tempat]'></td></tr>

          <tr><td>Tgl Mulai</td><td> : ";    
          $get_tgl=substr("$r[tgl_mulai]",8,2);
          combotgl2(1,31,'tgl_mulai',$get_tgl);
          $get_bln=substr("$r[tgl_mulai]",5,2);
          combobln2(1,12,'bln_mulai',$get_bln);
          $get_thn=substr("$r[tgl_mulai]",0,4);
          $thn_skrg=date("Y");
          combotgl2($thn_sekarang-2,$thn_sekarang+2,'thn_mulai',$get_thn);

    echo "</td></tr>
          <tr><td>Tgl Selesai</td><td> : ";    
          $get_tgl2=substr("$r[tgl_selesai]",8,2);
          combotgl2(1,31,'tgl_selesai',$get_tgl2);
          $get_bln2=substr("$r[tgl_selesai]",5,2);
          combobln2(1,12,'bln_selesai',$get_bln2);
          $get_thn2=substr("$r[tgl_selesai]",0,4);
          combotgl2($thn_sekarang-2,$thn_sekarang+2,'thn_selesai',$get_thn2);

    echo "</td></tr>
          <tr><td colspan=2><input type=submit value=Update>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
    break;  
}
?>
