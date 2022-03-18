<?php
$dados = $pdo->query("SELECT * FROM aa_quarto_dj")->fetch(PDO::FETCH_ASSOC);
if($_POST){
	$url_q = $_POST['url'];
	$update = $pdo->prepare("UPDATE aa_quarto_dj SET url=:url");
	$update->bindParam(':url',$url_q);
	$update->execute();
	echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
}
?>
<form method="POST" autocomplete="off">
	Link:<br>
	<input type="text" class="text" name="url" value="<?php echo $dados['url'] ?>"><br>
	<input type="submit" class="button" value="Editar">
</form>