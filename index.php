<?php
	include "assets/install/config.php";
	include "assets/php/functions.php";
	if(empty($_SESSION['usuario'])) {
		echo '<script>location.href="https://'.$_SERVER['HTTP_HOST'].'/login";</script>';
		exit();
	}
	if(isset($_GET['deslogar'])) {
		session_destroy();
		echo '<script>location.href="https://'.$_SERVER['HTTP_HOST'].'/login";</script>';
		exit();
	}
	if(isset($_GET['notificacao'])) {
		$notificacao = $pdo->query("UPDATE aa_notificacao SET visto='true' WHERE usuario='".$aa_data['usuario']."'");
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<base href="<?php echo $config_base; ?>">
	<title><?php echo $_CONFIG['titulo_primario']; ?> - Content Manager 1.0</title>
	<link rel="shortcut icon" type="image/png" href="<?php echo $_CONFIG['icon']; ?>"/>
	<link rel="stylesheet" type="text/css" href="assets/css/default.css?<?php echo time(); ?>">
	<script src="assets/js/jquery.js?123"></script>
	<script src="assets/js/jquery.min.js?123"></script>
	<script src="//cdn.ckeditor.com/4.16.0/full/ckeditor.js"></script>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
	<link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
	<link rel="stylesheet" href="dist/css/adminlte.min.css">
	<link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
	<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
	<script>
	$(function() {
		$('#nav .menu > li > a').click(function(){
			var submenu = $(this).next('ul.submenu');
			$('.submenu').not(submenu).slideUp('slow');
			$(submenu).slideToggle('slow');
		});
		$('#notificacao #botao').click(function(){
			$.ajax({
				type: 'GET',
				url: 'index.php?notificacao',
				success:function(){
					$('#notificacao #botao .new').remove();
				}
			});
			var box = $(this).next('#box');
			$(box).slideToggle('slow');
		});
	});
	lgd=true;
	</script>
	<style> #right #conteudo .button{ background-image: url('assets/img/<?php echo $tema_color ?>'); } </style>
</head>
<body style="background: url('assets/img/<?php echo $tema_bg; ?>')">
	<div id="left" style="background: url('assets/img/<?php echo $tema_color; ?>')">
		<div id="avatar" style="background: #EEE url('assets/uplouds/<?php echo $aa_data['avatar']; ?>') no-repeat center;">
			<div class="avatar" style="background: url('http://www.habbo.com.br/habbo-imaging/avatarimage?user=<?php echo $aa_data['usuario'] ?>&head_direction=3&action=crr=667&gesture=sml') -8px -17px"></div>
		</div>
		<?php
			$aa_cargo = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_cargos c WHERE r.cargo_id=c.cargo_id AND r.user_id='".$aa_data['id']."' ORDER BY c.ordem ASC");
		?>
		<div id="data">
			<span style="font-size: 17px; font-weight: bold"><?php echo $aa_data['usuario']; ?></span><br>
			<span style="font-size: 11px"><?php while($ver_cargo = $aa_cargo->fetch(PDO::FETCH_ASSOC)){ echo $ver_cargo['cargo'].'<br>'; } ?></span></div>
		<div id="nav">
			<ul class="menu">
			<style>
				#left #nav .menu > li > a{ 
					background: <?php echo $tema_bg_menu; ?>; 
					color: <?php echo $tema_fonte_menu; ?>;
				} 
				#left #nav .menu .submenu > li > a{
					background: <?php echo $tema_bg_menu; ?>;
					color: <?php echo $tema_fonte_menu; ?>;
				}
			</style>
			<li><a href="inicio">Inicio</a></li>
			<?php
				$sql_menu = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_canais c, aa_permissao p WHERE r.user_id = '".$aa_data['id']."' AND r.cargo_id = p.cargo_id AND p.canal_id = c.canal_id AND c.status = 'true' AND c.pai = '0' GROUP BY p.canal_id ORDER BY c.ordem");
				while ($menu = $sql_menu->fetch(PDO::FETCH_ASSOC)) {
			?>
			<li><a><?php echo $menu['canal']; ?></a>
				<ul class="submenu">
				<?php
					$sql_submenu = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_canais c, aa_permissao p WHERE r.user_id = '".$aa_data['id']."' AND r.cargo_id = p.cargo_id AND p.canal_id = c.canal_id AND c.status = 'true' AND c.pai = '".$menu['canal_id']."' GROUP BY p.canal_id ORDER BY c.canal, c.ordem");
					if($sql_submenu->rowCount() == 0){
						echo '<li><a>Sem Acesso!</a></li>';	
					}else{
						while ($submenu = $sql_submenu->fetch(PDO::FETCH_ASSOC)) {
				?>
				<li><a href="pagina/<?php echo $submenu['diretorio']; ?>"><?php echo $submenu['canal']; ?></a></li>
				<?php
						}
					}
				?>
				</ul>
			</li>
			<?php
				}
			?>
			<li><a href="deslogar">Sair</a></li>
			</ul>
		</div>
	</div>
	<div id="right">
		<div id="notificacao">
			<?php
				$visto_num = $pdo->query("SELECT * FROM aa_notificacao WHERE visto='false' AND usuario='".$aa_data['usuario']."'")->rowCount();
			?>
			<div id="botao"><?php if($visto_num > 0){?> <div class="new"><?php echo $visto_num ?></div> <?php } ?>Notificações</div>
			<div id="box">
				<div class="arrow"></div>
				<div id="over">
					<?php
						$notificacao_conexao = $pdo->query("SELECT * FROM aa_notificacao WHERE usuario='".$aa_data['usuario']."' ORDER BY id DESC");
						while ($ver_notificacao = $notificacao_conexao->fetch(PDO::FETCH_ASSOC)) {
						?>
							<div class="notificacao"><?php echo $ver_notificacao['texto'] ?></div>
						<?php
						}
					?>
				</div>
			</div>
		</div>
		<div id="conteudo">
			<?php
				include "assets/modulos/conteudo.php";
			?>
		</div>
	</div>
	<script>CKEDITOR.replace('ckeditor')</script>
	<!-- jQuery -->
	<script src="assets/adminlte/plugins/jquery/jquery.min.js"></script>
	<!-- jQuery UI 1.11.4 -->
	<script src="assets/adminlte/plugins/jquery-ui/jquery-ui.min.js"></script>
	<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
	<script>
	  $.widget.bridge('uibutton', $.ui.button)
	</script>
	<!-- Bootstrap 4 -->
	<script src="assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- ChartJS -->
	<script src="assets/adminlte/plugins/chart.js/Chart.min.js"></script>
	<!-- Sparkline -->
	<script src="assets/adminlte/plugins/sparklines/sparkline.js"></script>
	<!-- JQVMap -->
	<script src="assets/adminlte/plugins/jqvmap/jquery.vmap.min.js"></script>
	<script src="assets/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
	<!-- jQuery Knob Chart -->
	<script src="assets/adminlte/plugins/jquery-knob/jquery.knob.min.js"></script>
	<!-- daterangepicker -->
	<script src="assets/adminlte/plugins/moment/moment.min.js"></script>
	<script src="assets/adminlte/plugins/daterangepicker/daterangepicker.js"></script>
	<!-- Tempusdominus Bootstrap 4 -->
	<script src="assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<!-- Summernote -->
	<script src="assets/adminlte/plugins/summernote/summernote-bs4.min.js"></script>
	<!-- overlayScrollbars -->
	<script src="assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
	<!-- AdminLTE App -->
	<script src="assets/adminlte/dist/js/adminlte.js"></script>
	<!-- AdminLTE for demo purposes -->
	<script src="assets/adminlte/dist/js/demo.js"></script>
	<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
	<script src="assets/adminlte/dist/js/pages/dashboard.js"></script>
</body>
</html>