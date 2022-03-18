<?php
$dados = $pdo->query("SELECT * FROM aa_dados_radio")->fetch(PDO::FETCH_ASSOC);
if($_POST){
	$scfp = fsockopen($dados['ip'], $dados['porta'], $errno, $errstr, 10);
	if($scfp){
		fputs($scfp,"GET /admin.cgi?pass=".$dados['senha_kick']."&mode=kicksrc HTTP/1.0\r\nUser-Agent: SHOUTcast Song Status (Mozilla Compatible)\r\n\r\n");
		while(!feof($scfp)) {
			$page .= fgets($scfp, 1000);
		}
		fclose($scfp);
	}
	$logs = $pdo->query("INSERT INTO aa_logs_kick(usuario, ip, time) VALUES('".$aa_data['usuario']."', '".$ip."','".time()."')");
	echo Site::Alerta('Locutor kikado com sucesso!',false);
}
?>

<!--<form method="post" style="width: 100%">
<input type="hidden" name="kikar" value="sim">
<input type="submit" class="button" value="Kikar Dj">
</form>
<b>Quality</b>: High Quality<br>
<b>Format</b>: AccPlus: 64 kb/s, 44,1 kHz, Stereo<br>
<b>Ip</b>: <?php echo $dados['ip'] ?><br>
<b>Porta</b>: <?php echo $dados['porta'] ?><br>
<b>Senha da rádio</b>: <?php echo $dados['senha_radio'] ?><br>
<b>Station name</b>: <?php echo $aa_data['usuario'] ?><br>
<b>Genre</b>: <?php echo $aa_data['programa'] ?><br>
<b>Website URL</b>: --<br>-->
<b>Esta página está desativada.</b>