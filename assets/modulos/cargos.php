<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'criar'){
	if($_POST){
		$nome = $_POST["nome"];
		$ordem = $_POST["ordem"];
		$status = $_POST["status"];
		if(empty($nome) || empty($ordem)){
			echo Site::Alerta('Preencha todos os campos!',false);
		}else{
			$inserir = $pdo->prepare("INSERT INTO aa_cargos (cargo, ordem, status) VALUES(:nome, :ordem, :status)");
			echo Site::Alerta('Criado com sucesso!','pagina/'.$url);
			$inserir->bindParam(':nome', $nome);
			$inserir->bindParam(':ordem', $ordem);
			$inserir->bindParam(':status', $status);
			$inserir->execute();
		}
	}
?>

<form method="post" autocomplete="off">
	Nome:<br>
	<input type="text" class="text" name="nome" value="<?php echo $ver_cargo['cargo'];?>"><br>
	Ordem:<br>
	<input type="text" class="text" name="ordem" value="<?php echo $ver_cargo['ordem'];?>"><br>
	Status:<br>
	<select name="status" class="select">
		<option value="true" <?php if($ver_cargo['status'] == 'true'){ echo 'selected';} ?>>Ativo</option>
		<option value="false" <?php if($ver_cargo['status'] == 'false'){ echo 'selected';} ?>>Inativo</option>
	</select><br>
	<input type="submit" class="button" value="Editar" />
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
	$id = $_GET['id'];
	$ver_cargo = $pdo->query("SELECT * FROM aa_cargos WHERE cargo_id ='".$id."' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
	if($_POST){
		$nome = $_POST["nome"];
		$ordem = $_POST["ordem"];
		$status = $_POST["status"];
		if(empty($nome) || empty($ordem))	{
			echo Site::Alerta('Preencha todos os campos!',false);
		}else{
			$editar = $pdo->prepare("UPDATE aa_cargos SET cargo=:nome, ordem=:ordem, status=:status WHERE cargo_id=$id");
			$editar->bindParam(':nome', $nome);
			$editar->bindParam(':ordem', $ordem);
			$editar->bindParam(':status', $status);
			$editar->execute();
			echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
		}
	}
?>
<form method="post" autocomplete="off">
	Nome:<br>
	<input type="text" class="text" name="nome" value="<?php echo $ver_cargo['cargo']; ?>"><br>
	Ordem:<br>
	<input type="text" class="text" name="ordem" value="<?php echo $ver_cargo['ordem']; ?>"><br>
	Status:<br>
	<select name="status" class="select">
		<option value="true" <?php if($ver_cargo['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
		<option value="false" <?php if($ver_cargo['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
	</select><br>
	<input type="submit" class="button" value="Editar" />
</form>
<?php
}else{
?>
<a href="pagina/<?php echo $url; ?>/criar"><input type="button" name="btn_form" class="button" value="Adicionar Cargo" /></a><br /><br />
<table width="100%" style="margin: 10px 0 0 0; float: left">
	<tr style="height: 40px;">
        <th><img src="assets/img/editar.png"></th>
        <th><b>Cargo</b></th>
        <th><b>Ordem</b></th>
        <th><b>Status</b></th>
    </tr>
<?php
	$i = 1;
	$sql = $pdo->query("SELECT * FROM aa_cargos ORDER BY ordem ASC");
	while ($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
?>
	 <tr style="height: 40px; <?php echo $css;?>">
    	<th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['cargo_id']; ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['cargo']; ?></th>
        <th><?php echo $ver['ordem']; ?></th>
        <th><?php if($ver['status'] == 'true'){ echo "Ativo"; }else{ echo "Inativo"; } ?></th>
    </tr>
<?php
	$i++;
	}
?>
</table> 
<?php } ?>