<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(!isset($_SESSION['id'])){	
	header('location:index.php');
	exit;
}

$error;
$titulo = "Añadir Huesped";
$id = $_GET['edit'];

if(isset($_POST['submit'])){
	if($id===NULL){
		$sql= "INSERT INTO huespedes VALUES (NULL, :v1, :v2, :v3, :v4, :v5, :v6)";
		$query = $dbh->prepare($sql);
	}else{
		$sql= "UPDATE huespedes SET nombres=(:v1), apellidos=(:v2), doc_tipo=(:v3), doc_num=(:v4), sexo=(:v5), fecha_registro=(:v6) WHERE id=:v0";		
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v0', $id, PDO::PARAM_STR);
	}
	$query-> bindParam(':v1', $_POST['nombres'], PDO::PARAM_STR);
	$query-> bindParam(':v2', $_POST['apellidos'], PDO::PARAM_STR);
	$query-> bindParam(':v3', $_POST['doc_tipo'], PDO::PARAM_STR);
	$query-> bindParam(':v4', $_POST['doc_num'], PDO::PARAM_STR);
	$query-> bindParam(':v5', $_POST['sexo'], PDO::PARAM_STR);
	$query-> bindParam(':v6', $_POST['fecha_registro'], PDO::PARAM_STR);
	try {
		$query-> execute();
		$msg = ($id===NULL? "Huesped Añadido con Éxito" : "Huesped (ID: ".$id.") Éditado con Éxito");
		header("Location: admin-guests.php?msg=".urlencode($msg));
		exit;
	} catch (PDOException $e) {
		$error = $e->getMessage();
	}
}
if(isset($id)){
	$sql = "SELECT * FROM huespedes WHERE id=:v1";
	$query = $dbh -> prepare($sql);
	$query->bindParam(':v1',$id,PDO::PARAM_INT);
	$query->execute();
	$result=$query->fetch(PDO::FETCH_OBJ);	
	$titulo="Editar Huesped";
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
	<title><?php echo $titulo;?></title>

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
						<h3 class="page-title"><?php echo $titulo; if (isset($result)) echo ": ".htmlentities(($result->nombres))." ".htmlentities(($result->apellidos));?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Información del huesped</div>
									<?php if($error){?>
										<div class="errorWrap"><strong>ERROR: </strong><?php echo htmlentities($error); ?> </div>
									<?php }?>
									<div class="panel-body"><form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform" id="imgform">
										<div class="form-group">
											<label class="col-sm-2 control-label">Nombre(s)<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="text" name="nombres" class="form-control" required value="<?php echo htmlentities($result->nombres);?>">
											</div>
											<label class="col-sm-2 control-label">Tipo de documento<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<select name="doc_tipo" class="form-control" required>
													<option value="DNI" selected>DNI</option>
													<option value="CE" <?php if ($result->doc_tipo==='CE') echo 'selected';?>>Carnet de Extranjería</option>
													<option value="P" <?php if ($result->doc_tipo==='P') echo 'selected';?>>Pasaporte</option>
												</select>
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-2 control-label">Apellidos(s)<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="text" name="apellidos" class="form-control" required value="<?php echo htmlentities($result->apellidos);?>">
											</div>
											<label class="col-sm-2 control-label">Número de documento<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="tel" name="doc_num" class="form-control" required value="<?php echo htmlentities($result->doc_num);?>">
											</div>
										</div>

										<div class="form-group">
											<label class="col-sm-2 control-label">Sexo<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<select name="sexo" class="form-control" required>
													<option hidden></option>
													<option value="M" <?php if ($result->sexo==='M') echo 'selected';?>>MASCULINO</option>
													<option value="F" <?php if ($result->sexo==='F') echo 'selected';?>>FEMENIMO</option>
												</select>
											</div>
											<label class="col-sm-2 control-label">Fecha de registro<span style="color:red">*</span></label>
											<div class="col-sm-4">
												<input type="date" name="fecha_registro" class="form-control" required value="<?php 
													if ($result->fecha_registro === null){
														echo date('Y-m-d');
													}else{
														echo htmlentities($result->fecha_registro);
													}?>">
											</div>
										</div>

										<div class="form-group">
											<div class="col-sm-8 col-sm-offset-2">
												<button class="btn btn-primary" name="submit" type="submit" value="<?php echo htmlentities($id);?>">Aceptar</button>
											</div>
										</div>
									</form></div>
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