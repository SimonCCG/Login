<?php
	
	$mysqli=new mysqli("localhost:3306","ivao-pe-plesk","Frgp^e050TzgbgwT","tester"); 
	
	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}
	
?>