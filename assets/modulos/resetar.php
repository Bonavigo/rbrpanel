<?php
if(isset($_POST['submit'])){
	$resetar = $pdo->query("UPDATE aa_horarios SET user_id='0'");
	echo Site::Alerta('Resetado com sucesso!','pagina/'.$url);
}else{
?>
	<form method="POST">
		<input type="submit" class="button" name="submit" value="Resetar Horários"/>
	</form>
<?php
}
?>