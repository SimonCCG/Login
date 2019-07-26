<?php
session_start();
require('funcs/conexion.php');
require('funcs/funcs.php');

if(!isset($_SESSION["id_usuario"])){
	header("Location: index.php");
}

$idUsuario = $_SESSION['id_usuario'];
$sql = "SELECT id,nombre FROM usuarios WHERE id = '$idUsuario'";
$result = $mysqli->query($sql);

$row = $result->fetch_assoc();
?>
