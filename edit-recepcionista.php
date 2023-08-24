<?php
session_start();
error_reporting(0);
include('includes/config.php');
if(strlen($_SESSION['alogin'])==0){	
	header('location:index.php');
	return;
}
if(isset($_GET['edit'])){
		$editid=$_GET['edit'];
}

$msg="";
if(isset($_POST['submit']))
  {	 
	$v1=$_POST['nombres'];
	$v2=$_POST['apellidos'];
	$v3=$_POST['clave'];
	$v4=$_POST['telefono'];
	$v5=$_POST['correo'];
	$v6=$_POST['dni'];
	$idedit=$_POST['idedit'];
	$sql= "UPDATE recepcionistas SET nombres=(:v1), apellidos=(:v2), clave=(:v3), telefono=(:v4), correo=(:v5), dni=(:v6) WHERE id=:idedit";
	$query = $dbh->prepare($sql);
	$query-> bindParam(':v1', $v1, PDO::PARAM_STR);
	$query-> bindParam(':v2', $v2, PDO::PARAM_STR);
	$query-> bindParam(':v3', $v3, PDO::PARAM_STR);
	$query-> bindParam(':v4', $v4, PDO::PARAM_STR);
	$query-> bindParam(':v5', $v5, PDO::PARAM_STR);
	$query-> bindParam(':v6', $v6, PDO::PARAM_STR);
	$query-> bindParam(':idedit', $idedit, PDO::PARAM_STR);
	$query->execute();	
	$msg=" Información actualizada con éxito";
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
	
	<title>Editar Paquete</title>

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
	<?php
		$sql = "SELECT * FROM recepcionistas WHERE id=:editid";
		$query = $dbh -> prepare($sql);
		$query->bindParam(':editid',$editid,PDO::PARAM_INT);
		$query->execute();
		$result=$query->fetch(PDO::FETCH_OBJ);
		$cnt=1;	
	?>
	<?php include('includes/header.php');?>
	<div class="ts-main-content">
	<?php include('includes/leftbar.php');?>
		<div class="content-wrapper">
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-12">
						<h3 class="page-title">Editar Recepcionista : <?php echo htmlentities(($result->id));?></h3>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">Editar Información</div>
	<?php 
	if($error){?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } 
	else if($msg){?><div class="succWrap"><strong>ÉXITO</strong>:<?php echo htmlentities($msg); ?> </div><?php }
	?>

									<div class="panel-body">
<form method="post" class="form-horizontal" enctype="multipart/form-data" name="imgform">
<div class="form-group">
	<label class="col-sm-2 control-label">Nombre(s)<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="text" name="nombres" class="form-control" required value="<?php echo htmlentities($result->nombres);?>">
	</div>
	<label class="col-sm-2 control-label">Telefono<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="tel" name="telefono" class="form-control" pattern="[0-9]{9}" required value="<?php echo htmlentities($result->telefono);?>">
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">Apellidos(s)<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="text" name="apellidos" class="form-control" required value="<?php echo htmlentities($result->apellidos);?>">
	</div>
	<label class="col-sm-2 control-label">Correo<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="text" name="correo" class="form-control" required value="<?php echo htmlentities($result->correo);?>">
	</div>
</div>

<div class="form-group">
	<label class="col-sm-2 control-label">DNI<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="tel" name="dni" class="form-control" pattern="[0-9]{8}" required value="<?php echo htmlentities($result->dni);?>">
	</div>
	<label class="col-sm-2 control-label">Clave<span style="color:red">*</span></label>
		<div class="col-sm-4">
		<input type="text" name="clave" class="form-control" required value="<?php echo htmlentities($result->clave);?>">
	</div>
	<input type="hidden" name="idedit" value="<?php echo htmlentities($result->id);?>" >
</div>

<div class="form-group">
	<div class="col-sm-8 col-sm-offset-2">
		<button class="btn btn-primary" name="submit" type="submit">Guardar Cambios</button>
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