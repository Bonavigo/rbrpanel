<?php
	if($_POST){
		$codigo = rand(10000,99999);
		$codigo = 'RBR-'.$codigo;
		$inserir = $pdo->query("INSERT INTO aa_presenca(codigo, time) VALUES('".$codigo."', '".time()."')");
		$logs = $pdo->query("INSERT INTO aa_logs_presenca(usuario, codigo, time) VALUES('".$aa_data['usuario']."', '".$codigo."', '".time()."')");
		echo 'O código é: <b>'.$codigo.'</b> - Válido por 10 minutos';
	}
?>
<form method="post" style="width: 100%">
	<input type="hidden" name="gerar" value="sim">
	<input type="submit" class="button" value="Gerar Presença">
</form>