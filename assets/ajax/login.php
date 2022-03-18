<?php

	include "../install/config.php";

	$usuario = $_POST['usuario'];

	$senha = substr(md5($_POST['senha']), 4);

	$pin = $_POST['pin'];



	$login = $pdo->prepare('SELECT * FROM aa_usuarios WHERE usuario=:usuario AND pin=:pin AND senha=:senha AND status="true"');

	$login->bindParam(':usuario', $usuario);

	$login->bindParam(':pin', $pin);

	$login->bindParam(':senha', $senha);

	$login->execute();

	$logar = $login->fetch(PDO::FETCH_ASSOC);

		

	if($login->rowCount() > 0){

		$ip_banned = $pdo->query("SELECT * FROM aa_ip_ban WHERE ip='$ip'")->rowCount();
		$lista_negra = $pdo->query("SELECT * FROM aa_lista_negra WHERE usuario='$usuario'")->rowCount();

		if($ip_banned > 0){

			echo 'baned_ip';

		}else if($logar['banido'] == "true"){
			echo 'baned_user';
		}else if($lista_negra > 0){
			echo 'negra';
		}else{

			$log = $pdo->query("UPDATE aa_usuarios SET ultimo_time='$time', ultimo_ip='$ip', online='true', online_time='".time()."' WHERE usuario='$usuario'");

			$_SESSION['logado'] = true;

			$_SESSION['usuario'] = $usuario;

			echo 'true';

		}

	}else{

		echo 'false';

	}

	

?>