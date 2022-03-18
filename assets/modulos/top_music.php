<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
	$id = $_GET['id'];
	$top_ver = $pdo->query("SELECT * FROM top_music WHERE id='$id'")->fetch(PDO::FETCH_ASSOC);
	if($_POST){
		$titulo = $_POST['titulo'];
		$banda = $_POST['banda'];
		$url_s = $_POST['url'];
		$foto = $_FILES['imagem'];
		if(empty($titulo) || empty($banda)){
			echo Site::Alerta('Preencha todos campos!',false);
		}else if(!empty($foto["name"])){
			$tamanho = 1000000;
			if(@!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
				echo Site::Alerta('Isso não é uma imagem!',false);
			}else if($foto["size"] > $tamanho) {
				echo Site::Alerta('A imagem deve ter no máximo '.$tamanho.' bytes!',false);
			}else {
				preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
				$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
		    	$caminho_imagem = "assets/uplouds/".$nome_imagem;
				move_uploaded_file($foto["tmp_name"], $caminho_imagem);
				if($caminho_imagem){
					$inserir = $pdo->prepare("UPDATE top_music SET titulo=:titulo, banda=:banda, imagem=:imagem, url=:url WHERE id='$id'");
					$inserir->bindParam(':titulo',$titulo);
					$inserir->bindParam(':banda',$banda);
					$inserir->bindParam(':imagem',$nome_imagem);
					$inserir->bindParam(':url',$url_s);
					$inserir->execute();
					echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
				}
			}
		}else{
			$nome_imagem = $top_ver['imagem'];
			$inserir = $pdo->prepare("UPDATE top_music SET titulo=:titulo, banda=:banda, imagem=:imagem, url=:url WHERE id='$id'");
			$inserir->bindParam(':titulo',$titulo);
			$inserir->bindParam(':banda',$banda);
			$inserir->bindParam(':imagem',$nome_imagem);
			$inserir->bindParam(':url',$url_s);
			$inserir->execute();
			echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
		}
	}
?>
<form method="post" enctype="multipart/form-data" autocomplete="off">
     Titulo da Música:<br>
     <input type="text" class="text" name="titulo" value="<?php echo $top_ver['titulo']; ?>"><br>
     Banda:<br>
     <input type="text" class="text" name="banda" value="<?php echo $top_ver['banda']; ?>"><br>
     Capa:<br>
     <div style="width: 430px; height: 200px; float: left; background: url('assets/uplouds/<?php echo $top_ver['imagem']; ?>') center no-repeat"></div><br>
     <input type="file" class="text" name="imagem"><br>
     Url (ou deixa em branco):<br>
     <input type="text" class="text" name="url" value="<?php echo $top_ver['url']; ?>"><br>
    <input type="submit" class="button" value="Editar">
</form>
<?php
}else{
?>
<table width="100%" style="margin: 10px 0 0 0; float: left">
	<tr style="height: 40px;">
        <th><img src="assets/img/editar.png"></th>
        <th><b>Titulo da Música</b></th>
        <th><b>Banda</b></th>
        <th><b>Imagem</b></th>
        <th><b>URL</b></th>
    </tr>
<?php
	$i = 1;
	$sql = $pdo->query("SELECT * FROM top_music ORDER BY id ASC");
	while ($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
?>
	 <tr style="min-height: 40px; <?php echo $css;?>">
    	<th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id']; ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['titulo']; ?></th>
        <th><?php echo $ver['banda']; ?></th>
        <th><img src="assets/uplouds/<?php echo $ver['imagem']; ?>"></th>
        <th><a href="<?php echo $ver['url']; ?>" target="_blank">Clique aqui</a></th>
    </tr>
<?php
	$i++;
	}
?>
</table> 
<?php
}
?>