<?php
switch($_GET[act]){
  // Tampil Hubungi Kami
  default:
    echo "<h2>Hubungi Kami</h2>
          <table>
          <tr><th>no</th><th>nama</th><th>email</th><th>subjek</th><th>tanggal</th><th>aksi</th></tr>";
    $no=1;
    $tampil=mysql_query("SELECT * FROM hubungi ORDER BY id_hubungi desc");  
    while ($r=mysql_fetch_array($tampil)){
      $tgl=tgl_indo($r[tanggal]);
      echo "<tr><td>$no</td>
                <td>$r[nama]</td>
                <td><a href=?module=hubungi&act=balasemail&id=$r[id_hubungi]>$r[email]</a></td>
                <td>$r[subjek]</td>
                <td>$tgl</a></td>
                <td><a href=./aksi.php?module=hubungi&act=hapus&id=$r[id_hubungi]>Hapus</a>
                </td></tr>";
      $no++;
    }
    echo "</table>";
    break;
  
  case "balasemail":
    $tampil = mysql_query("SELECT * FROM hubungi WHERE id_hubungi='$_GET[id]'");
    $r      = mysql_fetch_array($tampil);

    echo "<h2>Reply Email</h2>
          <form method=POST action='?module=hubungi&act=kirimemail'>
          <table>
          <tr><td>Kepada</td><td> : <input type=text name='email' size=30 value='$r[email]'></td></tr>
          <tr><td>Subjek</td><td> : <input type=text name='subjek' size=50 value='Re: $r[subjek]'></td></tr>
          <tr><td>Pesan</td><td>  : <textarea name='pesan' rows=13 cols=70>
          
          
              
  -------------------------------------------------------------------------------------------
  $r[pesan]</textarea></td></tr>
          <tr><td colspan=2><input type=submit value=Kirim>
                            <input type=button value=Batal onclick=self.history.back()></td></tr>
          </table></form>";
     break;
    
  case "kirimemail":
    mail($_POST[email],$_POST[subjek],$_POST[pesan],"From: redaksi@bukulokomedia.com");
    echo "<h2>Status Email</h2>
          <p>Email telah sukses terkirim ke tujuan</p>
          <p>[ <a href=javascript:history.go(-2)>Kembali</a> ]</p>";	 		  
    break;  
}
?>
