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
if(isset($_GET['tipo']) && $_GET['tipo'] == 'criar'){
	if($_POST){
		$titulo = $_POST['titulo'];
		$descricao = $_POST['subtitulo'];
		$url_s = $_POST['url'];
		$popup = $_POST['guia'];
		$foto = $_FILES['imagem'];
		if(empty($titulo) || empty($descricao) || empty($foto["name"])){
			echo Site::Alerta('Preencha todos campos!',false);
		}else if(!empty($foto["name"])){
			$tamanho = 1000000;
			if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
				echo Site::Alerta('Isso não é uma imagem!',false);
				$error[1] = "Isso não é uma imagem.";
			}

			if($foto["size"] > $tamanho) {
				echo Site::Alerta('A imagem deve ter no máximo '.$tamanho.' bytes!',false);
				$error[2] = "A imagem deve ter no máximo ".$tamanho." bytes";
			}
			if(count($error) == 0) {
				preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
				$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
		    	$caminho_imagem = "assets/uplouds/".$nome_imagem;
				move_uploaded_file($foto["tmp_name"], $caminho_imagem);
				if($caminho_imagem){
					$inserir = $pdo->prepare("INSERT INTO slide (imagem, titulo, descricao, url, nova_guia) VALUES(:imagem, :titulo, :descricao, :url, :nova_guia)");
					$inserir->bindParam(':imagem',$nome_imagem);
					$inserir->bindParam(':titulo',$titulo);
					$inserir->bindParam(':descricao',$descricao);
					$inserir->bindParam(':url',$url_s);
					$inserir->bindParam(':nova_guia',$popup);
					$inserir->execute();
					echo Site::Alerta('Criado com sucesso!','pagina/'.$url);
				}
			}
		}
	}
?>
<form method="post" enctype="multipart/form-data" autocomplete="off">
     Titulo:<br>
     <input type="text" class="text" name="titulo"><br>
	 Descrição:<br>
     <input type="text" class="text" name="subtitulo"><br>
     Imagem: (430x200)<br>
     <input type="file" class="text" name="imagem"><br>
     Url (ou deixa em branco):<br>
     <input type="text" class="text" name="url"><br>
     Nova guia?<br>
     <select name="guia" class="select">
     	<option value="true">Sim</option>
     	<option value="false">Nao</option>
     </select><br>
    <input type="submit" class="button" value="Criar">
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
	$id = $_GET['id'];
	$slide_ver = $pdo->query("SELECT * FROM slide WHERE id='$id'")->fetch(PDO::FETCH_ASSOC);
	if($_POST){
		$titulo = $_POST['titulo'];
		$descricao = $_POST['subtitulo'];
		$url_s = $_POST['url'];
		$popup = $_POST['guia'];
		$foto = $_FILES['imagem'];
		if(empty($titulo) || empty($descricao)){
			echo Site::Alerta('Preencha todos campos!',false);
		}else if(!empty($foto["name"])){
			$tamanho = 1000000;
			if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
				echo Site::Alerta('Isso não é uma imagem!',false);
				$error[1] = "Isso não é uma imagem.";
			}

			if($foto["size"] > $tamanho) {
				echo Site::Alerta('A imagem deve ter no máximo '.$tamanho.' bytes!',false);
				$error[2] = "A imagem deve ter no máximo ".$tamanho." bytes";
			}
			if(count($error) == 0) {
				preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
				$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
		    	$caminho_imagem = "assets/uplouds/".$nome_imagem;
				move_uploaded_file($foto["tmp_name"], $caminho_imagem);
				if($caminho_imagem){
					$inserir = $pdo->prepare("UPDATE slide SET imagem=:imagem, titulo=:titulo, descricao=:descricao, url=:url, nova_guia=:nova_guia WHERE id='$id'");
					$inserir->bindParam(':imagem',$nome_imagem);
					$inserir->bindParam(':titulo',$titulo);
					$inserir->bindParam(':descricao',$descricao);
					$inserir->bindParam(':url',$url_s);
					$inserir->bindParam(':nova_guia',$popup);
					$inserir->execute();
					echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
				}
			}
		}else{
			$nome_imagem = $slide_ver['imagem'];
			$inserir = $pdo->prepare("UPDATE slide SET imagem=:imagem, titulo=:titulo, descricao=:descricao, url=:url, nova_guia=:nova_guia WHERE id='$id'");
			$inserir->bindParam(':imagem',$nome_imagem);
			$inserir->bindParam(':titulo',$titulo);
			$inserir->bindParam(':descricao',$descricao);
			$inserir->bindParam(':url',$url_s);
			$inserir->bindParam(':nova_guia',$popup);
			$inserir->execute();
			echo Site::Alerta('Editado com sucesso!','index.php?diretorio='.$url);
		}
	}
?>
<form method="post" enctype="multipart/form-data" autocomplete="off">
     Titulo:<br>
     <input type="text" class="text" name="titulo" value="<?php echo $slide_ver['titulo']; ?>"><br>
	 Descrição:<br>
     <input type="text" class="text" name="subtitulo" value="<?php echo $slide_ver['descricao']; ?>"><br>
     Imagem: (430x200)<br>
     <div style="width: 430px; height: 200px; float: left; background: url('assets/uplouds/<?php echo $slide_ver['imagem']; ?>') center no-repeat"></div><br>
     <input type="file" class="text" name="imagem"><br>
     Url (ou deixa em branco):<br>
     <input type="text" class="text" name="url" value="<?php echo $slide_ver['url']; ?>"><br>
     Nova guia?<br>
     <select name="guia" class="select">
     	<option value="true" <?php if($slide_ver['nova_guia'] == 'true'){ echo 'selected'; } ?>>Sim</option>
     	<option value="false" <?php if($slide_ver['nova_guia'] == 'false'){ echo 'selected'; } ?>>Nao</option>
     </select><br>
    <input type="submit" class="button" value="Editar">
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
	$id = (int) $_GET['id'];
	$delete = $pdo->query("DELETE FROM slide WHERE id='$id'");
    echo 1;
}else{
?>
<a href="pagina/<?php echo $url; ?>/criar"><input type="button" name="btn_form" class="button" value="Criar Slide" /></a><br /><br />
<table width="100%" style="margin: 10px 0 0 0; float: left">
	<tr style="height: 40px;">
        <th><img src="assets/img/editar.png"></th>
        <th><img src="assets/img/x.png"></th>
        <th><b>Titulo</b></th>
        <th><b>Descrição</b></th>
        <th><b>Imagem</b></th>
        <th><b>URL</b></th>
        <th><b>Nova Guia</b></th>
    </tr>
<?php
	$i = 1;
	$sql = $pdo->query("SELECT * FROM slide ORDER BY id ASC");
	while ($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
?>
	 <tr style="min-height: 40px; <?php echo $css;?>">
    	<th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id']; ?>"><img src="assets/img/editar.png"></a></th>
    	<th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['titulo']; ?></th>
        <th><?php echo $ver['descricao']; ?></th>
        <th><img src="assets/uplouds/<?php echo $ver['imagem']; ?>"></th>
        <th><a href="<?php echo $ver['url']; ?>" target="_blank">Clique aqui</a></th>
        <th><?php if($ver['nova_guia'] == 'true'){ echo "Sim"; }else{ echo "Não"; } ?></th>
    </tr>
<?php
	$i++;
	}
?>
</table> 
<?php
}
?>