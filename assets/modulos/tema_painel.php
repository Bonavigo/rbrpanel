<?php
$ver_tema = $pdo->query("SELECT * FROM aa_ktema")->fetch(PDO::FETCH_ASSOC);
if($_POST){
	$tema = $_POST['tema'];
	if($tema != '1' && $tema != '2' && $tema != '3' && $tema != '4' && $tema != '5' && $tema != '6'){
		echo Site::Alerta('Erro interno!',false);
	}else{
		$update = $pdo->prepare("UPDATE aa_ktema SET tema=:tema");
		$update->bindParam(':tema',$tema);
		$update->execute();
		echo Site::Alerta('Alterado com sucesso!','pagina/'.$url);
	}
}
?>
<form method="post" style="width: calc(100% + 5px)">
	<div class="tema" style="background-image: url('assets/img/tema_cinza.png')"><input type="radio" name="tema" value="1" <?php if($ver_tema['tema'] == '1'){ echo 'checked'; } ?>></div>
	<div class="tema" style="background-image: url('assets/img/tema_azul.png')"><input type="radio" name="tema" value="2" <?php if($ver_tema['tema'] == '2'){ echo 'checked'; } ?>></div>
	<!--<div class="tema" style="background-image: url('assets/img/tema_laranja.png')"><input type="radio" name="tema" value="3" <?php if($ver_tema['tema'] == '3'){ echo 'checked'; } ?>></div>-->
	<div class="tema" style="background-image: url('assets/img/tema_br.png')"><input type="radio" name="tema" value="4" <?php if($ver_tema['tema'] == '4'){ echo 'checked'; } ?>></div>
	<input type="submit" class="button" value="Alterar">
</form>