<?php
if(isset($_POST['submit'])){
	$resetar = $pdo->query("TRUNCATE TABLE aa_presenca_marcadas");
	echo Site::Alerta('Resetado com sucesso!','pagina/'.$url);
}else{
?>
	<form method="POST">
		<input type="submit" class="button" name="submit" value="Resetar Presenças"/>
	</form>
<?php
}
?>