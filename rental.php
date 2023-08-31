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
		$sql="INSERT INTO alquileres VALUES (NULL, :v2, :v3, :v4, :v5, :v6, :v7, :v8, :v9, :v10, :v11, :v12, :v13)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v2', $id_huesped, PDO::PARAM_STR);
		$msg = "Habitación (".$id_habitacion.") Alquilada con Éxito";
	}else{
		$sql=
		"UPDATE alquileres 
		SET id_habitacion=(:v3), id_recepcionista=(:v4), id_pago=(:v5), fecha_alquiler=(:v6), check_in=(:v7), 
		check_out=(:v8), costo=(:v9), personas=(:v10), motivo=(:v11), procedencia=(:v12), comentarios=(:v13) 
		WHERE id=(:v1)";
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
	if ($_POST['tipo_alquiler']==NULL){
		$_POST['check_in'] = NULL;
	}

	$query-> bindParam(':v3', $id_habitacion, PDO::PARAM_STR);
	$query-> bindParam(':v4', $_SESSION['ilogin'], PDO::PARAM_STR);
	$query-> bindParam(':v5', $id_pago, PDO::PARAM_STR);
	$query-> bindParam(':v6', $_POST['fecha_alquiler'], PDO::PARAM_STR);
	$query-> bindParam(':v7', $_POST['check_in'], PDO::PARAM_STR);
	$query-> bindParam(':v8', $_POST['check_out'], PDO::PARAM_STR);
	$query-> bindParam(':v9', $_POST['costo'], PDO::PARAM_STR);
	$query-> bindParam(':v10', $_POST['personas'], PDO::PARAM_STR);
	$query-> bindParam(':v11', $_POST['motivo'], PDO::PARAM_STR);
	$query-> bindParam(':v12', $_POST['procedencia'], PDO::PARAM_STR);
	$query-> bindParam(':v13', $_POST['comentarios'], PDO::PARAM_STR);

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
		/* Style the checkbox input */
		.left-aligned-checkbox {
			width: auto;
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
												<label class="col-sm-2 control-label">Fecha</label>
												<div class="col-sm-4">
													<input type="date" name="fecha_alquiler" id="fecha_alquiler" class="form-control" required value="<?php 
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
												<label for="pago" class="col-sm-2 control-label">Tipo</label>
												<div class="col-sm-4">
													<select name="tipo_alquiler" id="tipo_alquiler" class="form-control" required>
														<option hidden></option>
														<option value='0' selected>INMEDIATO</option>
														<option value='1' <?php if (isset($result->check_in)) echo 'selected';?>>RESERVA</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Check-in</label>
												<div class="col-sm-4">
													<input type="date" name="check_in" id="check_in" class="form-control" required value="<?php 
														if (isset($result->check_in)){
															echo htmlentities($result->check_in);
														}else{
															echo date('Y-m-d');
														}
														?>">
												</div>
												<label class="col-sm-2 control-label">Check-out</label>
												<div class="col-sm-4">
													<input type="date" name="check_out" class="form-control" required value="<?php 
														if (isset($result->check_out)){
															echo htmlentities($result->check_out);
														}else{
															echo date('Y-m-d');
														}
														?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Costo<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="costo" class="form-control" required value="<?php echo htmlentities($result->costo);?>">
												</div>
												<label class="col-sm-2 control-label">Personas</label>
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
												<label for="pago" class="col-sm-2 control-label">Estado del pago</label>
												<div class="col-sm-4">
													<select name="estado_pago" id="estado_pago" class="form-control" required>
														<option hidden></option>
														<option value='0' selected>PENDIENTE</option>
														<option value='1' <?php if (isset($result->id_pago)) echo 'selected';?>>REALIZADO</option>
													</select>
												</div>
												<label for="pago" class="col-sm-2 control-label">Metodo de pago</label>
												<div class="col-sm-4">
													<select name="tipo_pago" id="tipo_pago" class="form-control" required>
														<option value='' hidden></option>
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
        const selectInput1 = document.getElementById("tipo_alquiler");
        const selectInput2 = document.getElementById("estado_pago");
        const selectInput3 = document.getElementById("fecha_alquiler");
		const input1 = document.getElementById("check_in");
		const input2 = document.getElementById("tipo_pago");
		function check(){
			if (selectInput1.value === "0"){
				input1.disabled = true;
				input1.value = selectInput3.value
			}else{
				input1.disabled = false;
			}
			if (selectInput2.value === "0"){
				input2.disabled = true;
				input2.value = ""
			}else{
				input2.disabled = false;
				input2.value = "EFECTIVO"
			}
		}
        selectInput1.addEventListener("change", check);
		selectInput2.addEventListener("change", check);
		selectInput3.addEventListener("change", check);
		check();
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