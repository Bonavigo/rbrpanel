<?php
    // Primeiro Login KPanel 1.0
    // Usuário:  Cartwon
    // Senha:  8660
    // Pin:  0
    
    // Configuração do Banco de Dados
	$config_host = "localhost";	// Hospedagem
	$config_user = "exbrhb80_radio";	// Usuario
	$config_pass = "!~)GH}6eoqJv";	// Senha
	$config_sql = "exbrhb80_radio";	// SQL
	$config_base = "/"; // Base do Painel com / no final
	session_start();
	date_default_timezone_set("America/Sao_Paulo");
	$pdo = new PDO("mysql:host=".$config_host.";dbname=".$config_sql.";charset=UTF8;","".$config_user."","".$config_pass."");
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
	$time = time();
	$ip = $_SERVER['REMOTE_ADDR'];
	if(isset($_SESSION['logado'])){
		$aa_data_c = $pdo->query("SELECT * FROM aa_usuarios WHERE usuario = '".$_SESSION['usuario']."' AND status='true' AND banido='false'");
		if($aa_data_c->rowCount() == 0){ header("Location:deslogar"); }
		$aa_data = $aa_data_c->fetch(PDO::FETCH_ASSOC);
		$online_time = time() - 1*30;
		$online = $pdo->query("UPDATE aa_usuarios SET online='true', online_time='".time()."' WHERE usuario='".$aa_data['usuario']."'");
		$online_remove = $pdo->query("UPDATE aa_usuarios SET online='false' WHERE online_time < '$online_time'");
		$logado = true;
	}else{
		$logado = false;
	}
	$ver_tema = $pdo->query("SELECT * FROM aa_ktema")->fetch(PDO::FETCH_ASSOC);
	if($ver_tema['tema'] == '1'){
		$tema_color = "tema_color_cinza.png";
		$tema_bg = "tema_bg_eee.png";
		$tema_lbg = "tema_lbg_esp.png";
		$tema_fonte = "#333";
		$tema_bg_menu = "rgba(0,0,0,0.2)";
		$tema_fonte_menu = "#FFF";
		$_CONFIG = array(
			'titulo_primario' => 'KPanel Content Manager 1.0',
			'icon' => 'assets/img/favicon.png',
			'logo' => 'logo.png'
		);
	}else if($ver_tema['tema'] == "2"){
		$tema_color = "tema_color_azul.png";
		$tema_bg = "tema_bg_eee.png";
		$tema_lbg = "tema_lbg_azul.jpg";
		$tema_fonte = "#FFF";
		$tema_bg_menu = "rgba(0,0,0,0.2)";
		$tema_fonte_menu = "#FFF";
		$_CONFIG = array(
			'titulo_primario' => 'KPanel Content Manager 1.0',
			'icon' => 'assets/img/favicon.png',
			'logo' => 'logo.png'
		);
	}else if($ver_tema['tema'] == "3"){
		$tema_color = "tema_color_laranja.png";
		$tema_bg = "tema_bg_eee.png";
		$tema_lbg = "tema_lbg_laranja.jpg";
		$tema_fonte = "#000";
		$tema_bg_menu = "rgba(255,255,255,0.2)";
		$tema_fonte_menu = "#FFF";
		$_CONFIG = array(
			'titulo_primario' => 'KPanel Content Manager 1.0',
			'icon' => 'assets/img/favicon.png',
			'logo' => 'logo.png'
		);
	} else if($ver_tema['tema'] == "4"){
		$tema_color = "tema_color_azul.png";
		$tema_bg = "tema_bg_eee.png";
		$tema_lbg = "bg_pattern_green_exbr.png";
		$tema_fonte = "#FFF";
		$tema_bg_menu = "rgba(0,0,0,0.2)";
		$tema_fonte_menu = "#FFF";
		$_CONFIG = array(
			'titulo_primario' => 'RBRPanel',
			'icon' => 'assets/img/rbr_icon.ico',
			'logo' => 'logo_rbr.png'
		);
	}
?>