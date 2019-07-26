<?php
	
	$mysqli=new mysqli("mysql.hostinger.es","u271561281_simon","c15SFDwJGetI","u271561281_simon"); //servidor, usuario de base de datos, contraseña del usuario, nombre de base de datos
	
	if(mysqli_connect_errno()){
		echo 'Conexion Fallida : ', mysqli_connect_error();
		exit();
	}
	
?>