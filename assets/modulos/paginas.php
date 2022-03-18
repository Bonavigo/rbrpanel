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
	$categoria_id = $_GET['cargo_id'];
	if(isset($_GET['pag_id'])){
		$pagina_id = $_GET['pag_id'];
		$item = $pdo->query("SELECT * FROM paginas WHERE id='".$pagina_id."'")->fetch(PDO::FETCH_ASSOC);
		if($_POST){
			$titulo = $_POST['titulo'];
			$conteudo = $_POST['conteudo'];
			$categoria = $_POST['categoria'];
			$status = $_POST['status'];
			if(empty($titulo) || empty($conteudo)){
				echo Site::Alerta('Preencha todos campos!',false);
			}else{
				$inserir = $pdo->prepare("UPDATE paginas SET titulo=:titulo, conteudo=:conteudo, categoria=:categoria, status=:status WHERE id='".$pagina_id."'");
				$inserir->bindParam(':titulo',$titulo);
				$inserir->bindParam(':conteudo',$conteudo);
				$inserir->bindParam(':categoria',$categoria);
				$inserir->bindParam(':status',$status);
				$inserir->execute();
				echo Site::Alerta('Editado com sucesso!','pagina/'.$url.'/adicionar/'.$categoria_id);
			}
		}
		?>
		<form method="post" style="width: 100%" autocomplete="off">
			Titulo:<br>
			<input type="text" class="text" name="titulo" value="<?php echo $item['titulo']; ?>"><br><br><br>
			Categoria:<br>
			<select name="categoria" class="select">
		<?php
			$select_cat = $pdo->query("SELECT * FROM paginas_cat ORDER BY id ASC");
			while ($ver_cat = $select_cat->fetch(PDO::FETCH_ASSOC)) {
			?>
				<option value="<?php echo $ver_cat['id']; ?>" <?php if($ver_cat['id'] == $categoria_id){ echo 'selected'; } ?>><?php echo $ver_cat['categoria']; ?></option>
			<?php
			}
		?>
			</select><br><br><br>
			Status:<br>
			<select name="status" class="select">
				<option value="true" <?php if($item['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
				<option value="false" <?php if($item['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
			</select><br><br><br>
			Conteúdo:<br>
			<textarea id="ckeditor" name="conteudo"><?php echo $item['conteudo']; ?></textarea>
			<input type="submit" class="button" value="Criar">
		</form>
<?php
	}else{
		if($_POST){
			$titulo = $_POST['titulo'];
			$conteudo = $_POST['conteudo'];
			if(empty($titulo) || empty($conteudo)){
				echo Site::Alerta('Preencha todos campos!',false);
			}else{
				$inserir = $pdo->prepare("INSERT INTO paginas(titulo, conteudo, categoria, status) VALUES(:titulo, :conteudo, :categoria, 'true')");
				$inserir->bindParam(':titulo',$titulo);
				$inserir->bindParam(':conteudo',$conteudo);
				$inserir->bindParam(':categoria',$categoria_id);
				$inserir->execute();
				echo Site::Alerta('Criado com sucesso!','pagina/'.$url.'/adicionar/'.$categoria_id);
			}
		}
		$ver_cat = $pdo->query("SELECT * FROM paginas_cat WHERE id = '".$categoria_id."'")->fetch(PDO::FETCH_ASSOC);
		$paginas = $pdo->query("SELECT * FROM paginas_cat ORDER BY id ASC");
	?>
	<b>Categoria</b>: <?php echo $ver_cat['categoria']?><br><br>
	<form method="post" style="width: 100%" autocomplete="off">
		Titulo:<br>
		<input type="text" class="text" name="titulo"><br><br><br>
		Conteúdo:<br>
		<textarea id="ckeditor" name="conteudo"></textarea>
		<input type="submit" class="button" value="Criar">
	</form>
	<table width="100%" style="margin: 10px 0 0 0; float: left">
		<tr style="height: 40px;">
	        <th><img src="assets/img/x.png"></th>
	        <th><img src="assets/img/editar.png"></th>
	        <th><b>Titulo</b></th>
	        <th><b>Status</b></th>
	    </tr>
	<?php
		$i = 1;
		$sql = $pdo->query("SELECT * FROM paginas WHERE categoria='".$categoria_id."'");
		while ($ver = $sql->fetch(PDO::FETCH_ASSOC)){
			$css = $i%2==0 ? '' : 'background: #EEE;';
	?>
		 <tr style="height: 40px; <?php echo $css;?>">
	    	<th><a style="cursor:pointer" onclick="apagar.sim(<?php echo $ver['id'] ?>)"><img src="assets/img/x.png"></a></th>
	    	<th><a href="pagina/<?php echo $url; ?>/adicionar/<?php echo $categoria_id; ?>/editar/<?php echo $ver['id'] ?>"><img src="assets/img/editar.png"></a></th>
	        <th><?php echo $ver['titulo']; ?></th>
	        <th><?php if($ver['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; } ?></th>
	    </tr>
	<?php
		$i++;
		}
	?>
	</table> 
<?php
	}
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
	$id = $_GET['id'];
	$deletar = $pdo->query("DELETE FROM paginas WHERE id='$id'");
	echo 1;
}else{
?>
<script>
function seleciona_categoria(categoria_id)
{
	this.location = 'pagina/<?php echo $url; ?>/adicionar/'+categoria_id;
}
</script>
<?php
	$ver_paginas = $pdo->query("SELECT * FROM paginas_cat ORDER BY id ASC");
?>
<form>
	<select name="categoria_id" onchange="seleciona_categoria(this.value)" class="select">
    	<option value=""> -- </option>
		<?php 
		while($ver = $ver_paginas->fetch(PDO::FETCH_ASSOC)){?>
    	<option value="<?php echo $ver['id']; ?>"><?php echo $ver['categoria']; ?></option>
		<?php
		}
		?>
	</select>
</form>
<?php
}
?>