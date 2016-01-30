<?php
	$host = "localhost";
	$user = "root";
	$pass = "";
	$dbname = "PDE_Network";
	$conn = mysql_connect($host,$user,$pass);
if($conn) {
//select database
	$sele = mysql_select_db($dbname);
if(!$sele) {
	echo mysql_error();
	}
	}

	?>