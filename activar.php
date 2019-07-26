<?php
require('funcs/conexion.php');
require('funcs/funcs.php');

if(isset($_GET["id"]) AND isset($_GET['val'])){
	$idUsuario = $_GET['id'];
	$token = $_GET['val'];
	
	$mensaje = validaIdToken($idUsuario, $token);
}
?>
<html>
	<head>
		<title>Registro</title>
		
		<link rel="stylesheet" href="css/bootstrap.min.css" >
		<link rel="stylesheet" href="css/bootstrap-theme.min.css" >
		<script src="js/bootstrap.min.js" ></script>
		<script src='https://www.google.com/recaptcha/api.js'></script>
	</head>
	<body>
		<div class="container">
		<div class="jumbotron">
			<h1><?php echo $mensaje ?></h1>
			<br>
			<br>
			<p><a class="btn btn-primary btn-lg" href="login.php" role="button">Iniciar sesi&oacute;n</a></p>
			</div>
		</div>
	</body>