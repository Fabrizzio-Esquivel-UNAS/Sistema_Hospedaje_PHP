<?php
session_start();
include('includes/config.php');
if(isset($_POST['login'])){
	$userid=$_POST['userid'];
	$password=$_POST['password'];
	$sql ="SELECT * FROM recepcionistas WHERE id=:v1 and clave=:v2";
	$query= $dbh -> prepare($sql);
	$query-> bindParam(':v1', $userid, PDO::PARAM_STR);
	$query-> bindParam(':v2', $password, PDO::PARAM_STR);
	$query-> execute();
	$result=$query->fetch(PDO::FETCH_OBJ);
	if($query->rowCount()!==0){
		$_SESSION['ilogin']=$result->id;
		$_SESSION['nlogin']=$result->nombres;
		$_SESSION['img_login']=$result->imagen;
		$_SESSION['alogin']=false;
		echo "<script type='text/javascript'> document.location = 'admin-guests.php'; </script>";
	}else{
		echo "<script>alert('Datos no válidos');</script>";
	}
}

?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">

</head>

<body>
	<div class="login-page bk-img">
		<div class="form-content">
			<div class="container">
				<div class="row">
					<div class="col-md-6 col-md-offset-3">
						<h1 class="text-center text-bold mt-4x">Iniciar sesión</h1>
						<div class="well row pt-2x pb-3x bk-light">
							<div class="col-md-8 col-md-offset-2">
								<form method="post">
									<label for="" class="text-uppercase text-sm">Identificación</label>
									<input type="tel" placeholder="Id de usuario" name="userid" class="form-control mb" required>

									<label for="" class="text-uppercase text-sm">Contraseña</label>
									<input type="password" placeholder="Clave de usuario" name="password" class="form-control mb" required>
									<button class="btn btn-primary btn-block" name="login" type="submit">INGRESAR</button>
								</form>
								<br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>

</body>

</html>