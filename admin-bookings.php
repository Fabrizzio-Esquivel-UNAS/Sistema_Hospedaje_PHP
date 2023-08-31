<?php
session_start();
error_reporting(0);
include('includes/config.php');
if($_SESSION===NULL){
	header('location:index.php');
	exit;
}

$msg; $error;

if(isset($_GET['del'])){
	$sql = "DELETE FROM alquileres WHERE id=:v1";
	$query = $dbh->prepare($sql);
	$query -> bindParam(':v1', $_GET['del'], PDO::PARAM_STR);
	$query -> execute();
	$msg = "Datos Eliminados Correctamente";
}else if(isset($_GET['msg'])){
	$msg = urldecode($_GET['msg']);
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
	
	<title>Reservaciones</title>

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
						<h2 class="page-title">Reservaciones</h2>
						<!-- Zero Configuration Table -->
						<div class="panel panel-default">
							<div class="panel-heading">Lista de Reservaciones</div>
							<div class="panel-body">
							<?php if($error){?>
								<div class="errorWrap" id="msgshow"> <?php echo htmlentities($error);?> </div>
							<?php } else if($msg){?>
								<div class="succWrap" id="msgshow"> <?php echo htmlentities($msg);?> </div>
							<?php }?>
								<table id="zctb" class="display table table-striped table-bordered table-hover" cellspacing="0" width="100%">
									<thead>
										<tr>
											<th>ID</th>
											<th>Fecha</th>
											<th>Huesped</th>
											<th>Habitación</th>
											<th>Check-in</th>
											<th>Check-out</th>
											<th>Acción</th>
										</tr>
									</thead>
									<tbody>
									<?php
									$sql = 
									"SELECT a.id, a.fecha_alquiler, hu.nombres, hu.apellidos, a.id_habitacion, a.check_in, a.check_out FROM alquileres a 
									INNER JOIN huespedes hu ON hu.id=a.id_huesped
									WHERE (a.check_in IS NOT NULL) AND (a.check_in>CURDATE())";
									$query = $dbh -> prepare($sql);
									$query->execute();
									$results=$query->fetchAll(PDO::FETCH_OBJ);
									if($query->rowCount() > 0){
										foreach($results as $result){?>
										<tr>
											<td><?php echo htmlentities($result->id);?></td>
                                            <td><?php echo htmlentities($result->fecha_alquiler);?></td>
                                            <td><?php echo htmlentities($result->nombres." ".$result->apellidos);?></td>
                                            <td><?php echo htmlentities($result->id_habitacion);?></td>
                                            <td><?php echo htmlentities($result->check_in);?></td>
                                            <td><?php echo htmlentities($result->check_out);?></td>
											<td>
											<?php if ($_SESSION['alogin']===true){?>
												<a href="rental.php?edit=<?php echo $result->id;?>" onclick="return confirm('¿Realmente desea Editar?');">&nbsp; <i class="fa fa-pencil fa-lg"></i></a>&nbsp;&nbsp;
												<a href="admin-bookings.php?del=<?php echo $result->id;?>" onclick="return confirm('¿Realmente desea Eliminar?');"><i class="fa fa-trash fa-lg" style="color:red"></i></a>&nbsp;&nbsp;
											<?php }else{?>
												<a href="rental.php?view=<?php echo $result->id;?>">&nbsp; <i class="fa fa-info-circle fa-lg"></i></a>&nbsp;&nbsp;											
											<?php }?>
											</td>
										</tr>
										<?php }
									} ?>
									</tbody>
								</table>
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