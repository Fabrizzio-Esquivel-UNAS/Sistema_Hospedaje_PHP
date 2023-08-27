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
$id_habitacion = null;
if (isset($_GET['view'])){
	$titulo = "Ver Habitación";
	$id_habitacion = $_GET['view'];
}else if (isset($_GET['edit'])){
	$titulo = "Editar Habitación";
	$id_habitacion = $_GET['edit'];
}else{
	$titulo = "Añadir Habitación";
}

if(isset($_POST['submit'])){
	if ($id_habitacion===null){
		$sql="INSERT INTO habitaciones VALUES (:v1, NULL, :v3, :v4, :v5, DEFAULT)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v1', $_POST['id_habitacion'], PDO::PARAM_STR);
		$msg = "Habitación (".$dbh->lastInsertId().") Añadida con Éxito";
	}else{
		$sql="UPDATE habitaciones SET tipo=(:v3), precio=(:v4), `desc`=(:v5) WHERE id=(:v1)";
		$query = $dbh->prepare($sql);
		$query-> bindParam(':v1', $id_habitacion, PDO::PARAM_STR);
		$msg = "Habitación (".$id_habitacion.") Actualizada con Éxito";
	}
	$query-> bindParam(':v3', $_POST['tipo'], PDO::PARAM_STR);
	$query-> bindParam(':v4', $_POST['precio'], PDO::PARAM_STR);
	$query-> bindParam(':v5', $_POST['desc'], PDO::PARAM_STR);

	try {
		$query->execute();
		header("Location: admin-rooms.php?msg=".urlencode($msg));
		exit;
	} catch (PDOException $e) {
		$error = $e->getMessage();
	}
}

if($id_habitacion){
	$sql = "SELECT * FROM habitaciones WHERE id = (:v1);";
	$query = $dbh -> prepare($sql);
	$query-> bindParam(':v1', $id_habitacion, PDO::PARAM_STR);
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
									<div class="panel-heading">Información de la Habitación</div>
									<?php if($error){?>
										<div class="errorWrap"><strong>ERROR: </strong><?php echo htmlentities($error); ?> </div>
									<?php }?>
									<div class="panel-body">
										<form id="room" method="post" class="form-horizontal" enctype="multipart/form-data">
											<div class="form-group">
											<label class="col-sm-2 control-label">Número<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="id_habitacion" id="id_habitacion" class="form-control" required value="<?php echo htmlentities($result->id);?>">
												</div>
												<label class="col-sm-2 control-label">Tipo<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<select name="tipo" class="form-control" required>
														<option hidden></option>
														<option value='SIMPLE' <?php if ($result->tipo==='SIMPLE') echo 'selected';?>>SIMPLE</option>
														<option value='DOBLE' <?php if ($result->tipo==='DOBLE') echo 'selected';?>>DOBLE</option>
														<option value='FAMILIAR' <?php if ($result->tipo==='FAMILIAR') echo 'selected';?>>FAMILIAR</option>
														<option value='MATRIMONIAL' <?php if ($result->tipo==='MATRIMONIAL') echo 'selected';?>>MATRIMONIAL</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-sm-2 control-label">Precio<span style="color:red">*</span></label>
												<div class="col-sm-4">
													<input type="text" name="precio" class="form-control" required value="<?php echo htmlentities($result->precio);?>">
												</div>
												<label class="col-sm-2 control-label">Descripción</label>
												<div class="col-sm-4">
													<textarea name="desc" class="form-control" rows="5"><?php echo htmlentities($result->desc);?></textarea>
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
			var form = document.getElementById("room"); 
			for (var i = 0; i < form.elements.length; i++) {
				form.elements[i].disabled = true;
			}
		</script>
	<?php }?>
	<?php if ($_SESSION['alogin']){?>
		<script>
			document.getElementById("id_habitacion").disabled = true;
		</script>
	<?php }?>	
</body>
</html>