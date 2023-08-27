<?php
session_start();
error_reporting(0);
include('includes/config.php');
if($_SESSION===NULL){
	header('location:index.php');
	exit;
}

$error = null;
$titulo = null;
$id_alquiler = null;
$id_huesped = null;
if (isset($_GET['view'])){
	$titulo = "Ver Alquiler";
	$id_alquiler = $_GET['view'];
}else if (isset($_GET['edit'])){
	$titulo = "Editar Alquiler";
	$id_alquiler = $_GET['edit'];
}else if (isset($_GET['new'])){
	$titulo = "Alquilar Habitación";
	$id_huesped = $_GET['new'];
}

if(isset($_POST['submit'])){
	$id_habitacion = $_POST['id_habitacion'];
	if ($id_huesped){
		$sql="INSERT INTO alquileres VALUES (NULL, :v2, :v3, :v4, :v5, :v6, :v7, :v8, :v9, :v10, :v11, :v12)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v2', $id_huesped, PDO::PARAM_STR);
		$msg = "Habitación (".$id_habitacion.") Alquilada con Éxito";
	}else{
		$sql="UPDATE alquileres SET id_habitacion=(:v3), id_recepcionista=(:v4), id_pago=(:v5), fecha_alquiler=(:v6), dias=(:v7), costo=(:v8), personas=(:v9), motivo=(:v10), procedencia=(:v11), comentarios=(:v12) WHERE id=(:v1)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v1', $id_alquiler, PDO::PARAM_STR);
		$msg = "Alquiler (ID: ".$id_alquiler.") Actualizado con Éxito";
	}
	$id_pago = null;
	if ($_POST['estado_pago']==1){
		$sql2="INSERT INTO pagos VALUES (NULL, :v2, :v3)";
		$query2 = $dbh->prepare($sql2);
		$query2-> bindParam(':v2', $_POST['tipo_pago'], PDO::PARAM_STR);	
		$query2-> bindParam(':v3', $_POST['costo'], PDO::PARAM_STR);
		$query2->execute();
		$id_pago = $dbh->lastInsertId();
	}

	$query-> bindParam(':v3', $id_habitacion, PDO::PARAM_STR);
	$query-> bindParam(':v4', $_SESSION['ilogin'], PDO::PARAM_STR);
	$query-> bindParam(':v5', $id_pago, PDO::PARAM_STR);
	$query-> bindParam(':v6', $_POST['fecha_alquiler'], PDO::PARAM_STR);
	$query-> bindParam(':v7', $_POST['dias'], PDO::PARAM_STR);
	$query-> bindParam(':v8', $_POST['costo'], PDO::PARAM_STR);
	$query-> bindParam(':v9', $_POST['personas'], PDO::PARAM_STR);
	$query-> bindParam(':v10', $_POST['motivo'], PDO::PARAM_STR);
	$query-> bindParam(':v11', $_POST['procedencia'], PDO::PARAM_STR);
	$query-> bindParam(':v12', $_POST['comentarios'], PDO::PARAM_STR);

	try {
		$query->execute();
		if ($id_huesped){
			$id_alquiler = $dbh->lastInsertId();
			$sql2="UPDATE habitaciones SET id_alquiler=(:v2) WHERE id=(:v1)";
			$query2 = $dbh->prepare($sql2);
			$query2-> bindParam(':v1', $id_habitacion, PDO::PARAM_STR);
			$query2-> bindParam(':v2', $id_alquiler, PDO::PARAM_STR);
			$query2->execute();
		}
		header("Location: admin-guests.php?msg=".urlencode($msg));
		exit;
	} catch (PDOException $e) {
		$error = $e->getMessage();
	}
}

if($id_alquiler){
	$sql = 
	"SELECT * FROM alquileres a 
	LEFT JOIN huespedes hu ON a.id_huesped=hu.id 
	LEFT JOIN habitaciones ha ON a.id_habitacion=ha.id
	LEFT JOIN pagos p ON a.id_pago=p.id
	WHERE a.id = (:v0)";
	$query = $dbh -> prepare($sql);
	$query-> bindParam(':v0', $id_alquiler, PDO::PARAM_STR);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_OBJ);
}else if($id_huesped){
	$sql = "SELECT * FROM huespedes WHERE id = (:v0);";
	$query = $dbh -> prepare($sql);
	$query-> bindParam(':v0', $id_huesped, PDO::PARAM_STR);
	$query->execute();
	$result = $query->fetch(PDO::FETCH_OBJ);	
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
						<h3 class="page-title"><?php echo htmlentities($titulo);?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Información del Alquiler</div>
									<?php if($error){?>
										<div class="errorWrap"><strong>ERROR: </strong><?php echo htmlentities($error); ?> </div>
									<?php }?>
									<div class="panel-body">
										<form id="rental" method="post" class="form-horizontal" enctype="multipart/form-data">
											<div class="form-group">
												<label class="col-sm-2 control-label">Huesped</label>
												<div class="col-sm-4">
													<input type="text" class="form-control" disabled value="<?php
													if (isset($result)){
														echo htmlentities($result->nombres." ".$result->apellidos);
													}
													?>">
												</div>
												<label class="col-sm-2 control-label">Fecha<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="date" name="fecha_alquiler" class="form-control" required value="<?php 
														if (isset($result->fecha_alquiler)){
															echo htmlentities($result->fecha_alquiler);
														}else{
															echo date('Y-m-d');
														}
														?>">
												</div>
											</div>
											<div class="form-group">
											<label class="col-sm-2 control-label">Habitación<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="tel" name="id_habitacion" id="id_habitacion" class="form-control" required value="<?php echo htmlentities($result->id_habitacion);?>">
												</div>
												<label class="col-sm-2 control-label">Dias<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="number" name="dias" class="form-control" min="1" required value="<?php 
														if (isset($result->telefono)){
															echo htmlentities($result->telefono);
														}else{
															echo 1;
														}
													?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Costo<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="costo" class="form-control" required value="<?php echo htmlentities($result->costo);?>">
												</div>
												<label class="col-sm-2 control-label">Personas<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="number" name="personas" class="form-control" min="1" required value="<?php 
														if (isset($result->personas)){
															echo htmlentities($result->personas);
														}else{
															echo 1;
														}
													?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Motivo</label>
												<div class="col-sm-4">
													<input type="text" name="motivo" class="form-control" value="<?php echo htmlentities($result->motivo);?>">
												</div>
												<label class="col-sm-2 control-label">Procedencia</label>
												<div class="col-sm-4">
													<input type="text" name="procedencia" class="form-control" value="<?php echo htmlentities($result->procedencia);?>">
												</div>
											</div>
											<div class="form-group">
												<label for="pago" class="col-sm-2 control-label">Estado del pago<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<select name="estado_pago" class="form-control" required>
														<option hidden></option>
														<option value='0' selected>PENDIENTE</option>
														<option value='1' <?php if (isset($result->id_pago)) echo 'selected';?>>REALIZADO</option>
													</select>
												</div>
												<label for="pago" class="col-sm-2 control-label">Metodo de pago<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<select name="tipo_pago" class="form-control" required>
														<option hidden></option>
														<option value='EFECTIVO' <?php if ($result->tipo_pago==='EFECTIVO') echo 'selected';?>>EFECTIVO</option>
														<option value='YAPE' <?php if ($result->tipo_pago==='YAPE') echo 'selected';?>>YAPE</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Comentarios</label>
												<div class="col-sm-10">
													<textarea name="comentarios" class="form-control" rows="5"><?php echo htmlentities($result->motivo);?></textarea>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-8 col-sm-offset-2">
													<button class="btn btn-primary" name="submit" id="submit" type="submit">Aceptar</button>
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
	<?php if (isset($_GET['view'])){?>
		<script>
			document.getElementById("submit").style.display = "none";
			var form = document.getElementById("rental"); 
			for (var i = 0; i < form.elements.length; i++) {
				form.elements[i].disabled = true;
			}
		</script>
	<?php }?>
</body>
</html>