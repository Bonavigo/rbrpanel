<?php
$dados = $pdo->query("SELECT * FROM aa_dados_radio")->fetch(PDO::FETCH_ASSOC);
if($_POST){
	$ip_radio = $_POST['ip'];
	$porta = $_POST['porta'];
	$senha_radio = $_POST['senha_radio'];
	if(empty($senha_radio)){
		$senha_radio = $dados['senha_radio'];
	}else{
		$senha_radio = $_POST['senha_radio'];
	}
	$senha_kick = $_POST['senha_kick'];
	if(empty($senha_kick)){
		$senha_kick = $dados['senha_kick'];
	}else{
		$senha_kick = $_POST['senha_kick'];
	}
	if(empty($ip_radio) || empty($porta)){
		echo Site::Alerta('Preencha todos campos!',false);
	}else{
		$update = $pdo->prepare("UPDATE aa_dados_radio SET ip=:ip, porta=:porta, senha_radio=:senha_radio, senha_kick=:senha_kick");
		$update->bindParam(':ip',$ip_radio);
		$update->bindParam(':porta',$porta);
		$update->bindParam(':senha_radio',$senha_radio);
		$update->bindParam(':senha_kick',$senha_kick);
		$update->execute();
		echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
	}
}
?>

<form method="POST" autocomplete="off">
	IP:<br>
	<input type="text" class="text" name="ip" value="<?php echo $dados['ip'] ?>"><br>
	Porta:<br>
	<input type="text" class="text" name="porta" value="<?php echo $dados['porta'] ?>"><br>
	Senha da Rádio (Deixe em branco caso não queira mudar):<br>
	<input type="password" class="text" name="senha_radio"><br>
	Senha do Kick (Deixe em branco caso não queira mudar):<br>
	<input type="password" class="text" name="senha_kick"><br>
	<input type="submit" class="button" value="Editar">
</form>