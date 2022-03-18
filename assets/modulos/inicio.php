<?php
	$usuarios_registrados = $pdo->query("SELECT * FROM usuarios")->rowCount();
	$usuarios_online = $pdo->query("SELECT * FROM usuarios WHERE online='true'")->rowCount();
	$noticias_criadas = $pdo->query("SELECT * FROM noticias WHERE status='true' AND evento='false'")->rowCount();
	$noticias_comentarios = $pdo->query("SELECT * FROM noticias n, noticias_comentarios c WHERE c.noticia_id = n.id AND n.status='true' AND n.evento = 'false'")->rowCount();
	$topicos_criados = $pdo->query("SELECT * FROM topicos WHERE status='true'")->rowCount();
	$topicos_comentarios = $pdo->query("SELECT * FROM topicos_comentarios")->rowCount();
	$artes_criadas = $pdo->query("SELECT * FROM artes WHERE status='true' AND tirinha='false'")->rowCount();
	$artes_comentarios = $pdo->query("SELECT * FROM artes a, artes_comentarios c WHERE c.arte_id = a.id AND a.status='true' AND a.tirinha = 'false'")->rowCount();
	$membros_registrados = $pdo->query("SELECT * FROM aa_usuarios WHERE status='true'")->rowCount();
	$membros_online = $pdo->query("SELECT * FROM aa_usuarios WHERE online='true'")->rowCount();
	if(isset($_GET['tipo']) && $_GET['tipo'] == 'aviso_lido'){
		$id = $_GET['id'];
		$vi = $pdo->query("SELECT * FROM aa_avisos_visto WHERE aviso_id='".$id."' AND usuario='".$aa_data['usuario']."'")->rowCount();
		if($vi == 0){
			$inserir = $pdo->query("INSERT INTO aa_avisos_visto(aviso_id, usuario, time) VALUES('".$id."', '".$aa_data['usuario']."', '".time()."')");
			echo 1;
		}
	}
?>
<script>
	$(document).ready(function() {
		$('#visto .vesto').click(function(){
			var vistos = $(this).next().next('#vistos');
			$(vistos).slideToggle('slow');
		});
	});
	aviso = {
		lido:function(id){
			$.ajax({
                type:'GET',
                url:'index.php?tipo=aviso_lido&id='+id,
                data:{'id':id},
                success:function(){
                    var visto = $('[onclick="aviso.lido(\''+id+'\')"]').next('#vistos');
                    $('[onclick="aviso.lido(\''+id+'\')"]').hide().removeAttr('onclick');
                    $(visto).append('<div class="visto"><?php echo $aa_data['usuario'] ?></div>');
                }
            });
		}
	}
</script>
<div id="infos">
	<div id="info" style="background: #97DE7E">
		<div class="icon" style="background: #EEE url('assets/img/icons.png?') 0 0 no-repeat;"></div>
		<div class="info">
			<span style="font-size: 14px"><b><?php echo $usuarios_registrados ?></b> usuários registrados</span><br>
			<span style="font-size: 12px"><b><?php echo $usuarios_online ?></b> usuários online</span>
		</div>
	</div>
	<div id="info" style="background: #77A4E6">
		<div class="icon" style="background: #EEE url('assets/img/icons.png?') -40px 0 no-repeat;"></div>
		<div class="info">
			<span style="font-size: 14px"><b><?php echo $noticias_criadas ?></b> notícias criadas</span><br>
			<span style="font-size: 12px"><b><?php echo $noticias_comentarios ?></b> comentários em notícias</span>
		</div>
	</div>
	<div id="info" style="background: #ECE271">
		<div class="icon" style="background: #EEE url('assets/img/icons.png?') -80px 0 no-repeat;"></div>
		<div class="info">
			<span style="font-size: 14px"><b><?php echo $topicos_criados ?></b> tópicos criados</span><br>
			<span style="font-size: 12px"><b><?php echo $topicos_comentarios ?></b> comentários em tópicos</span>
		</div>
	</div>
	<div id="info" style="background: #8B94D1">
		<div class="icon" style="background: #EEE url('assets/img/icons.png?') -120px 0 no-repeat;"></div>
		<div class="info">
			<span style="font-size: 14px"><b><?php echo $artes_criadas ?></b> artes criadas</span><br>
			<span style="font-size: 12px"><b><?php echo $artes_comentarios ?></b> comentários em artes</span>
		</div>
	</div>
	<div id="info" style="background: #E97474">
		<div class="icon" style="background: #EEE url('assets/img/icons.png?') -160px 0 no-repeat;"></div>
		<div class="info">
			<span style="font-size: 14px"><b><?php echo $membros_registrados ?></b> membros na equipe</span><br>
			<span style="font-size: 12px"><b><?php echo $membros_online ?></b> membros online</span>
		</div>
	</div>
</div>
<style> #right #avisos #aviso #topo #visto #vistos .visto, #right #avisos #aviso #topo #visto .vesto, #right #avisos #aviso #topo #visto .marcar{ background: url('assets/img/<?php echo $tema_color; ?>'); } </style>
<div id="avisos">
	<div id="title">Avisos</div>
<?php
	$avisos = $pdo->query("SELECT * FROM aa_avisos ORDER BY id DESC");
	while ($aviso = $avisos->fetch(PDO::FETCH_ASSOC)){
?>
	<div id="aviso">
		<div id="topo">
			<?php
			$vi = $pdo->query("SELECT * FROM aa_avisos_visto WHERE aviso_id='".$aviso['id']."' AND usuario='".$aa_data['usuario']."'")->rowCount();
			if($vi == 0){
				$marcar = '<div class="marcar" onclick="aviso.lido(\''.$aviso['id'].'\')">Marcar como lido</div>';
			}else{
				$marcar = '<div class="marcar" style="opacity: 0">Marcar como lido</div>';
			}
			?>
			<div class="titulo"><?php echo $aviso['titulo'] ?></div>
			<div id="visto">
				<div class="vesto">Vistos</div>
				<?php echo $marcar ?>
				<div id="vistos">
				<?php
				$vistos = $pdo->query("SELECT * FROM aa_avisos_visto WHERE aviso_id='".$aviso['id']."'");
					while($visto = $vistos->fetch(PDO::FETCH_ASSOC)){?>
						<div class="visto"><?php echo $visto['usuario'] ?></div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="texxto">
			<?php echo $aviso['texto'] ?>
			<span style="width: 100%; font-weight: bold; margin: 10px 0 0 0; float: left">Postado por <?php echo $aviso['autor']?> - <?php echo date('d/m/Y - H:i',@$aviso['time']) ?></span>
		</div>
	</div>
<?php
	}
?>
</div>