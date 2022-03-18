<script>
var apagar = {
	sim:function(id){
		if(confirm('Tem certeza que deseja apagar ?')){
			$.ajax({
				type:'GET',
				url:'pagina/<?php echo $url; ?>/apagar/'+id,
				data:{'id':id},
				success:function(html){
					alert('Apagado com sucesso!');
					location.reload();
				}
			});
		}
	}
}
</script>
<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'adicionar'){
	$cargo_id = $_GET['cargo_id'];
	if($_POST){
		$permissao = $_POST['permissao'];
		if(empty($permissao)){
			echo Site::Alerta('Selecione um canal!',false);
		}else{
			$inserir = $pdo->prepare("INSERT INTO aa_permissao (canal_id, cargo_id) VALUES(:permissao, :cargo_id)");
			$inserir->bindParam(':permissao',$permissao);
			$inserir->bindParam(':cargo_id',$cargo_id);
			$inserir->execute();
			echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
		}
	}
	$ver_cargo = $pdo->query("SELECT * FROM aa_cargos WHERE cargo_id = '$cargo_id'")->fetch(PDO::FETCH_ASSOC);
	$canais = $pdo->query("SELECT * FROM aa_canais WHERE status='true' ORDER BY pai");
?>
	<?php echo $ver_cargo['cargo']?>:<br>
<form method="post">
	<select name="permissao" class="select">
		<option value=""> -- </option>
		<?php 
		while($ver = $canais->fetch(PDO::FETCH_ASSOC)){
			$existe = $pdo->query("SELECT * FROM aa_permissao WHERE canal_id = '".$ver['canal_id']."' AND cargo_id='$cargo_id'")->rowCount();
			if($existe == 0){
		?>
    		<option value="<?php echo $ver['canal_id']; ?>"><?php echo $ver['canal']; ?></option>
		<?php
			}
		}
		?>
	</select>
	<input type="submit" class="button" value="Adicionar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float: left">
	<tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th><b>Cargo</b></th>
        <th><b>Permissão</b></th>
    </tr>
<?php
	$i = 1;
	$sql = $pdo->query("SELECT * FROM aa_permissao p, aa_cargos g, aa_canais c WHERE p.cargo_id = '".$cargo_id."' AND p.cargo_id = g.cargo_id AND p.canal_id = c.canal_id GROUP BY p.canal_id ORDER BY g.ordem ASC");
	while ($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
?>
	 <tr style="height: 40px; <?php echo $css;?>">
    	<th><a style="cursor:pointer" onclick="apagar.sim(<?php echo $ver['per_id'] ?>)"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['cargo']; ?></th>
        <th><?php echo $ver['canal'] ?></th>
    </tr>
<?php
	$i++;
	}
?>
</table> 
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
	$id = $_GET['id'];
	$deletar = $pdo->query("DELETE FROM aa_permissao WHERE per_id='$id'");
	echo 1;
}else{
?>
<script>
function seleciona_cargo(cargo_id)
{
	this.location = 'pagina/<?php echo $url; ?>/adicionar/'+cargo_id;
}
</script>
<?php
	$ver_cargos = $pdo->query("SELECT * FROM aa_cargos WHERE status='true'");
?>
<form>
	<select name="cargo_id" onchange="seleciona_cargo(this.value)" class="select">
    	<option value=""> -- </option>
		<?php 
		while($ver = $ver_cargos->fetch(PDO::FETCH_ASSOC)){?>
    	<option value="<?php echo $ver['cargo_id']; ?>"><?php echo $ver['cargo']; ?></option>
		<?php
		}
		?>
	</select>
</form>
<?php
}
?>