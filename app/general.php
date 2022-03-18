<?php
	session_start();
	date_default_timezone_set("America/Sao_Paulo");
	$_CONFIG = array(
		'host' => "localhost",
		'user' => "exbrhb80_radio",
		'pass' => "!~)GH}6eoqJv",
		'banco' => "exbrhb80_radio",
		'base' => 'https://'.$_SERVER['HTTP_HOST'].'/rbrpanel/',
		'base_href' => '/rbrpanel/'
	);
	$conn = new PDO("mysql:host=".$_CONFIG['host'].";dbname=".$_CONFIG['banco'].";charset=UTF8;","".$_CONFIG['user']."","".$_CONFIG['pass']."");
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$ver_tema = $conn->query("SELECT * FROM aa_ktema")->fetch(PDO::FETCH_ASSOC);
	//Temas definidos usando o id que está na tabela. É temporário, pretendo colocar no Banco tudo.
	$_THEME = array(
		'titulo_primario' => 'RBR Manager 2.0',
		'icon' => 'assets/img/rbr_icon.ico',
		'logo_grupo_grande' => 'assets/img/radio_big.png',
		'logo_grupo_pequena' => 'assets/img/radio_small.gif',
		'logo_login' => 'assets/img/logo_rbr.png',
		'nome' => 'Rádio BR',
		'tema_color' => 'teste',
		'tema_bg' => 'teste',
		'tema_lbg' => 'teste',
		'tema_fonte' => 'teste',
		'tema_bg_menu' => 'teste',
		'tema_fonte_menu' => 'teste'
	);
	if (!empty($_SESSION['usuario'])) {
		$data_user = $conn->query("SELECT * FROM aa_usuarios WHERE usuario = '".$_SESSION['usuario']."' AND status='true' AND banido='false'");
		$data_user = $data_user->fetch(PDO::FETCH_ASSOC);
		$_USER[] = $data_user;
		unset($data_user);
	}
?>