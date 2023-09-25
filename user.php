<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(!isset($_SESSION['id']) || ($_SESSION['id']!==0 /*&& $_GET['edit']!==NULL*/ && $_GET['edit']!=$_SESSION['id'])){
	header('location:index.php');
	exit;
}

$msg; $error;
$id = $_GET['edit'];
$titulo = "Añadir Recepcionista";

if(isset($_POST['submit'])){
	$file = $_FILES['imagen']['name'];
	$file_loc = $_FILES['imagen']['tmp_name'];
	$folder="images/";
	$new_file_name = strtolower($file);
	$final_file=str_replace(' ','-',$new_file_name);
	$image = $_POST['imagen'];
	if (move_uploaded_file($file_loc, $folder.$final_file)){
		$image = $final_file;
	}
	
	if ($id===NULL){
		$sql="INSERT INTO recepcionistas VALUES (:v1, :v2, :v3, :v4, :v5, :v6, :v7)";
		$query = $dbh->prepare($sql);
	}else{
		$sql="UPDATE recepcionistas SET nombres=(:v1), apellidos=(:v2), dni=(:v3), correo=(:v4), telefono=(:v5), clave=(:v6), imagen=(:v7) WHERE id=(:v0)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v0', $id, PDO::PARAM_STR);
	}

	$query-> bindParam(':v1', $_POST['nombres'], PDO::PARAM_STR);
	$query-> bindParam(':v2', $_POST['apellidos'], PDO::PARAM_STR);
	$query-> bindParam(':v3', $_POST['dni'], PDO::PARAM_STR);
	$query-> bindParam(':v4', $_POST['correo'], PDO::PARAM_STR);
	$query-> bindParam(':v5', $_POST['telefono'], PDO::PARAM_STR);
	$query-> bindParam(':v6', $_POST['clave'], PDO::PARAM_STR);
	$query-> bindParam(':v7', $image, PDO::PARAM_STR);

	try {
		$query->execute();
		$msg = "Información Actualizada con Éxito";
	} catch (PDOException $e) {
		$error = $e->getMessage();
	}
}

if(isset($_GET['edit'])){
	$titulo = "Editar Recepcionista";
	$sql = "SELECT * FROM recepcionistas where id = (:v0);";
	$query = $dbh -> prepare($sql);
	$query-> bindParam(':v0', $_GET['edit'], PDO::PARAM_STR);
	$query->execute();
	$result=$query->fetch(PDO::FETCH_OBJ);
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
	<meta name="theme-color" content="#3e454c">
	<title><?php echo htmlentities($titulo);?></title>

	<!-- Font awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<!-- Sandstone Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Bootstrap Datatables -->
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<!-- Bootstrap social button library -->
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<!-- Bootstrap select -->
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<!-- Bootstrap file input -->
	<link rel="stylesheet" href="css/fileinput.min.css">
	<!-- Awesome Bootstrap checkbox -->
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<!-- Admin Stye -->
	<link rel="stylesheet" href="css/style.css">

	<script type= "text/javascript" src="../vendor/countries.js"></script>
	<style>
		.errorWrap {
			padding: 10px;
			margin: 0 0 20px 0;
			background: #dd3d36;
			color:#fff;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
		}
		.succWrap{
			padding: 10px;
			margin: 0 0 20px 0;
			background: #5cb85c;
			color:#fff;
			-webkit-box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
			box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);
		}
	</style>
</head>

<body>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<?php if ($_SESSION['id']===0) {?>
							<h3 class="page-title"><?php echo htmlentities($titulo); $id==NULL?"":$result->nombres." ".$result->apellidos ?></h3>
						<?php }?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Información del recepcionista</div>
									<?php if($error){?>
										<div class="errorWrap"><strong>ERROR: </strong><?php echo htmlentities($error); ?> </div>
									<?php }else if($msg){?>
										<div class="succWrap"><strong>ÉXITO: </strong><?php echo htmlentities($msg); ?> </div>
									<?php }?>
									<div class="panel-body">
										<form method="post" class="form-horizontal" enctype="multipart/form-data">
											<div class="form-group">
												<div class="col-sm-4"></div>
												<div class="col-sm-4 text-center">
													<img src="images/<?php echo htmlentities($result->imagen);?>" style="width:200px; border-radius:50%; margin:10px;">
													<input type="file" name="imagen" class="form-control">
													<input type="hidden" name="imagen" class="form-control" value="<?php echo htmlentities($result->imagen);?>">
												</div>
												<div class="col-sm-4"></div>
											</div><div class="form-group">
												<label class="col-sm-2 control-label">Nombre(s)<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="nombres" class="form-control" required value="<?php echo htmlentities($result->nombres);?>">
												</div>
												<label class="col-sm-2 control-label">Correo</span></label>
												<div class="col-sm-4">
													<input type="email" name="correo" class="form-control" value="<?php echo htmlentities($result->correo);?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Apellido(s)<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="apellidos" class="form-control" required value="<?php echo htmlentities($result->apellidos);?>">
												</div>
												<label class="col-sm-2 control-label">Teléfono<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="telefono" class="form-control" required value="<?php echo htmlentities($result->telefono);?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">DNI<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="dni" class="form-control" required value="<?php echo htmlentities($result->dni);?>">
												</div>
												<label class="col-sm-2 control-label">Clave<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="clave" class="form-control" required value="<?php echo htmlentities($result->clave);?>">
												</div>
											</div>
											
											<div class="form-group">
												<div class="col-sm-8 col-sm-offset-2">
													<button class="btn btn-primary" name="submit" type="submit">Aceptar</button>
												</div>
											</div>
										</form>
									</div>
								</div>
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
	<script type="text/javascript">
		$(document).ready(function () {          
		setTimeout(function() {
			$('.succWrap').slideUp("slow");
		}, 3000);
		});
	</script>
</body>
</html>