<?php
require('funcs/conexion.php');
require('funcs/funcs.php');

$errors= array();

if(!empty($_POST))
{
	
$nombre = $mysqli->real_escape_string($_POST['nombre']);
$usuario = $mysqli->real_escape_string($_POST['usuario']);
$password = $mysqli->real_escape_string($_POST['password']);
$con_password = $mysqli->real_escape_string($_POST['con_password']);
$email = $mysqli->real_escape_string($_POST['email']);
$captcha = $mysqli->real_escape_string($_POST['g-recaptcha-response']);
	
	
	$activo= 0;
	$tipo_usuario = 2;
	$secret = '';
	
	if(!$captcha){
		$errors[] = "Por favor verifica el captcha";
	}
	if(isNull($nombre,$usuario,$password,$con_password,$email))
	{
		$errors[] = "Debe llenar todos los campos";
	}
    if(!isEmail($email))
	{
		$errors[] = "Direecion de correo invalida";
	}
	if(!validaPassword($password, $con_password))
	{
		$errors[] = "Las contraseñas no coinciden";
	}
	if(usuarioExiste($usuario))
	{
		$errors[] = "El nombre de usuario $usuario ya existe";
	}
	if(emailExiste($email))
	{
		$errors[] = "El correo electronico $email ya existe";
	}
	if(count($errors) == 0)
	{
		$response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$captcha");
		
		$arr = json_decode($response, TRUE);
		
		if($arr['success'])
		{
		$pass_hash = hashPassword($password);
		$token = generateToken();
			
		$registro = registraUsuario($usuario, $pass_hash, $nombre, $email, $activo, $token, $tipo_usuario);
		if($registro > 0)
		{
			$url = 'http://'.$_SERVER["SERVER_NAME"].'/activar.php?id='.$registro.'&val='.$token;
			
			$asunto = 'Verificacion de cuenta';
			$cuerpo = "Hola $nombre: <br><br><br>Para continuar con tu registro, Ve al siguiente link para confirmar que esta cuenta es tuya <a href='$url'>Activar Cuenta</a>";
			if(enviarEmail($email, $nombre, $asunto, $cuerpo)){
				echo "Para terminar su proceso de registro le hemos enviado a su correo electronico: $email un enlace para continuar con su registro";
				echo "<br><a href='login.php'>Iniciar sesion</a>";
				exit;
			}else{
				$errors[] = "Error al enviar email";
			}
		}else{
			$errors[] = "Error al registrar";
		}
		}else{
			$errors[] = "Error al comprobar captcha";
		}
	}
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
			<div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="panel-title">Reg&iacute;strate</div>
						<div style="float:right; font-size: 85%; position: relative; top:-10px;"><a id="signinlink" href="index.php">Iniciar Sesi&oacute;n</a></div>
					</div>  
					
					<div class="panel-body" >
						
						<form id="signupform" class="form-horizontal" role="form" action="<?php $_SERVER['PHP_SELF'] ?>" method="POST" autocomplete="off">
							
							<div id="signupalert" style="display:none" class="alert alert-danger">
								<p>Error:</p>
								<span></span>
							</div>
							
							<div class="form-group">
								<label for="nombre" class="col-md-3 control-label">Nombre</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="nombre" placeholder="Nombre IC" value="<?php if(isset($nombre)) echo $nombre; ?>" required >
								</div>
							</div>
							
							<div class="form-group">
								<label for="usuario" class="col-md-3 control-label">Usuario</label>
								<div class="col-md-9">
									<input type="text" class="form-control" name="usuario" placeholder="Usuario" value="<?php if(isset($usuario)) echo $usuario; ?>" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="password" class="col-md-3 control-label">Password</label>
								<div class="col-md-9">
									<input type="password" class="form-control" name="password" placeholder="Password" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="con_password" class="col-md-3 control-label">Confirmar Password</label>
								<div class="col-md-9">
									<input type="password" class="form-control" name="con_password" placeholder="Confirmar Password" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="email" class="col-md-3 control-label">Email</label>
								<div class="col-md-9">
									<input type="email" class="form-control" name="email" placeholder="Email" value="<?php if(isset($email)) echo $email; ?>" required>
								</div>
							</div>
							
							<div class="form-group">
								<label for="captcha" class="col-md-3 control-label"></label>
								<div class="g-recaptcha col-md-9" data-sitekey="6Lc44IwUAAAAAHBFnqK5p_sW3y-yAY6tDqMvFYT8"></div>
							</div>
							
							<div class="form-group">                                      
								<div class="col-md-offset-3 col-md-9">
									<button id="btn-signup" type="submit" class="btn btn-primary"><i class="icon-hand-right"></i>Registrar</button>
								</div>
							</div>
						</form>
						<?php 
						echo resultBlock($errors);
						?>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>													