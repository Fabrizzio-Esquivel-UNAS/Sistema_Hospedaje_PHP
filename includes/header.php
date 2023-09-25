<div class="brand clearfix">
	<h4 class="pull-left text-white text-uppercase" style="margin:20px 0px 0px 20px"><i class="fa fa-user"></i>&nbsp; <?php echo htmlentities($_SESSION['name']);?></h4>
	<span class="menu-btn"><i class="fa fa-bars"></i></span>
	<ul class="ts-profile-nav">
		
		<li class="ts-account">
			<a href="#"><img src="images/<?php echo htmlentities($_SESSION['img']);?>" class="ts-avatar hidden-side" alt=""> Cuenta <i class="fa fa-angle-down hidden-side"></i></a>
			<ul>
			<li><a href="user.php?edit=<?php echo htmlentities($_SESSION['id']);?>">Editar perfil</a></li>
			<li><a href="logout.php">Cerrar sesión</a></li>
			</ul>
		</li>
	</ul>
</div>