<?php
	if(isset($_GET['diretorio'])){
		$url = $_GET['diretorio'];
	}
	if(isset($_GET['diretorio'])){
		$canal_name_select = $pdo->query("SELECT * FROM aa_canais WHERE diretorio = '$url'");
		if($canal_name_select->rowCount() > 0){
			$canal_name = $canal_name_select->fetch(PDO::FETCH_ASSOC);
			$canal = "Página Inicial - ".$canal_name['canal']."";
		}else{
			$canal = "Página Inicial - Erro 404";
		}
	}else{
		$canal = 'Página Inicial';
	}
?>
	<div id="diretorio" style="background: url('assets/img/<?php echo $tema_color; ?>"><?php echo $canal; ?></div>
<?php
	if(isset($url)){
		$sql_p = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_canais c, aa_permissao p WHERE r.user_id = '".$aa_data['id']."' AND r.cargo_id = p.cargo_id AND p.canal_id = c.canal_id AND c.status = 'true' AND c.diretorio = '$url' AND p.canal_id = c.canal_id GROUP BY p.canal_id");
		if(file_exists('assets/modulos/'.$url.'.php')){
			if($sql_p->rowCount() > 0){
				$row = $sql_p->fetch(PDO::FETCH_ASSOC);
				include "assets/modulos/".$row['diretorio'].".php"; 
			}else{
				include "assets/modulos/erro.php";
			}
		}else{
			include "assets/modulos/erro.php";
		}
	}else{
		include "assets/modulos/inicio.php";
	}

	if(isset($aa_data['id']) || isset($aa_data['usuario'])){
		if(isset($url)){
			$logs_painel = $pdo->query("INSERT INTO aa_logs_painel(ip, time, usuario, canal) VALUES ('".$ip."','".$time."','".$aa_data['usuario']."','index.php?diretorio=".$url."')");
		}else{
			$logs_painel = $pdo->query("INSERT INTO aa_logs_painel(ip, time, usuario, canal) VALUES ('".$ip."','".$time."','".$aa_data['usuario']."','index.php')");
		}
	}else{
		echo '';
	}
?>