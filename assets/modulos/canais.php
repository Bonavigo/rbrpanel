<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'criar'){
	if($_POST){
		$nome = $_POST['nome'];
		$diretorio = $_POST['diretorio'];
		$status = $_POST['status'];
		$pai = $_POST['pai'];
		if(empty($nome) || empty($diretorio)){
			echo Site::Alerta('Preencha todos os campos!',false);
		}else{
			$sql = $pdo->query("SELECT * FROM aa_canais ORDER BY ordem DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
			$ordem = $sql['ordem'] + 1;
			$inserir = $pdo->prepare("INSERT INTO aa_canais (canal, pai, diretorio, ordem, status) VALUES(:nome, :pai, :diretorio, :ordem, :status)");
			$inserir->bindParam(':nome',$nome);
			$inserir->bindParam(':pai',$pai);
			$inserir->bindParam(':diretorio',$diretorio);
			$inserir->bindParam(':ordem',$ordem);
			$inserir->bindParam(':status',$status);
			$inserir->execute();
			echo Site::Alerta('Criado com sucesso!','pagina/'.$url);
		}
	}
?>
<form method="post" autocomplete="off">
	Nome:<br>
	<input type="text" class="text" name="nome"><br>
	Diretório:<br>
	<input type="text" class="text" name="diretorio"><br>
	Principal / Sub-Menu:<br>
	<select name="pai" class="select">
		<option value="0">Principal</option>
<?php
	$diretorios_c = $pdo->query("SELECT * FROM aa_canais WHERE pai = '0' AND status = 'true' ORDER BY ordem ASC");
	while ($ver_d = $diretorios_c->fetch(PDO::FETCH_ASSOC)) {
?>
		<option value="<?php echo $ver_d['canal_id']; ?>"><?php echo $ver_d['canal']; ?></option>
<?php
	}
?>
	</select><br>
	Status:<br>
	<select name="status" class="select">
		<option value="true" selected>Ativo</option>
		<option value="false">Inativo</option>
	</select><br>
	<input type="submit" class="button" value="Criar" />
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
	$id = $_GET['id'];
	$ver_canal = $pdo->query("SELECT * FROM aa_canais WHERE canal_id ='".$id."' LIMIT 1")->fetch(PDO::FETCH_ASSOC);
	if($_POST){
		$nome = $_POST["nome"];
		$diretorio = $_POST["diretorio"];
		$status = $_POST["status"];
		$pai = $_POST["pai"];
		if(empty($nome) || empty($diretorio))	{
			echo Site::Alerta('Preencha todos os campos!',false);
		}else{
			$editar = $pdo->prepare("UPDATE aa_canais SET canal=:nome, diretorio=:diretorio, status=:status, pai=:pai WHERE canal_id=$id");
			$editar->bindParam(':nome',$nome);
			$editar->bindParam(':diretorio',$diretorio);
			$editar->bindParam(':status',$status);
			$editar->bindParam(':pai',$pai);
			$editar->execute();
			echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
		}
	}
?>
<form method="post" autocomplete="off">
	Nome:<br>
	<input type="text" class="text" name="nome" value="<?php echo $ver_canal['canal'];?>"><br>
	Diretório:<br>
	<input type="text" class="text" name="diretorio" value="<?php echo $ver_canal['diretorio'];?>"><br>
	Principal / Sub-Menu:<br>
	<select name="pai" class="select">
		<option <?php if($ver_canal['pai'] == '0'){ echo 'selected';} ?> value="0">Principal</option>
<?php
	$diretorios_c = $pdo->query("SELECT * FROM aa_canais WHERE pai = '0' AND status = 'true' ORDER BY ordem ASC");
	while ($ver_d = $diretorios_c->fetch(PDO::FETCH_ASSOC)) {
?>
		<option <?php if($ver_canal['pai'] == $ver_d['canal_id']){ echo 'selected';} ?> value="<?php echo $ver_d['canal_id']; ?>"><?php echo $ver_d['canal']; ?></option>
<?php
	}
?>
	</select><br>
	Status:<br>
	<select name="status" class="select">
		<option value="true" <?php if($ver_canal['status'] == 'true'){ echo 'selected';} ?>>Ativo</option>
		<option value="false" <?php if($ver_canal['status'] == 'false'){ echo 'selected';} ?>>Inativo</option>
	</select><br>
	<input type="submit" class="button" value="Editar" />
</form>
<?php
}else{
?>
<a href="pagina/<?php echo $url; ?>/criar"><input type="button" name="btn_form" class="button" value="Adicionar canal" /></a><br /><br />
<table width="100%" style="margin: 10px 0 0 0; float: left">
	<tr style="height: 40px;">
        <th><img src="assets/img/editar.png"></th>
        <th><b>Nome</b></th>
        <th><b>Diretório</b></th>
        <th><b>Canal Pai</b></th>
        <th><b>Status</b></th>
    </tr>
<?php
	$i = 1;
	$sql = $pdo->query("SELECT * FROM aa_canais ORDER BY pai, ordem ASC");
	while ($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
		$sql_pai = $pdo->query("SELECT * FROM aa_canais WHERE canal_id='".$ver['pai']."'")->fetch(PDO::FETCH_ASSOC);
?>
	 <tr style="height: 40px; <?php echo $css;?>">
    	<th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['canal_id']; ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['canal']; ?></th>
        <th><?php if($ver['diretorio'] == 'Null' or $ver['diretorio'] == ''){ echo "Null"; }else{ echo $ver['diretorio']; } ?></th>
        <th><?php if($ver['pai'] == '0'){ echo "Pai"; }else{ echo $sql_pai['canal']; } ?></th>
        <th><?php if($ver['status'] == 'true'){ echo "Ativo"; }else{ echo "Inativo"; } ?></th>
    </tr>
<?php
	$i++;
	}
?>
</table> 
<?php } ?>