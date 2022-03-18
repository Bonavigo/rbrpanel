<?php
$item = $pdo->query("SELECT * FROM aa_usuarios WHERE usuario='".$aa_data['usuario']."'")->fetch(PDO::FETCH_ASSOC);
if($_POST){
	$turno = $_POST['turno'];
	$programa = $_POST['programa'];
	$skype = $_POST['skype'];
	$twitter = $_POST['twitter'];
	$facebook = $_POST['facebook'];
	if(empty($_POST['senha'])){
		$senha = $item['senha'];
	}else{
		$senha = substr(md5($_POST['senha']), 4);
	}
	if(empty($_POST['pin'])){
		$pin = $item['pin'];
	}else{
		$pin = $_POST['pin'];
	}
	$foto = $_FILES["imagem"];
	if(!empty($foto["name"])){
		$tamanho = 1000000;
		if(@!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
			echo Site::Alerta('Isso não é uma imagem!',false);
		}else if($foto["size"] > $tamanho) {
			echo Site::Alerta('A imagem deve ter no máximo '.$tamanho.' bytes!',false);
		}else{
			preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
			$nome_imagem = md5(uniqid(time())) . "." . $ext[1];
	    	$caminho_imagem = "assets/uplouds/".$nome_imagem;
			move_uploaded_file($foto["tmp_name"], $caminho_imagem);
			if($caminho_imagem){
				$update = $pdo->prepare("UPDATE aa_usuarios SET senha=:senha, pin=:pin, avatar=:nome_imagem, turno=:turno, programa=:programa, skype=:skype, twitter=:twitter, facebook=:facebook WHERE usuario='".$aa_data['usuario']."'");
				$update->bindParam(':senha',$senha);
				$update->bindParam(':pin',$pin);
				$update->bindParam(':nome_imagem',$nome_imagem);
				$update->bindParam(':turno',$turno);
				$update->bindParam(':programa',$programa);
				$update->bindParam(':skype',$skype);
				$update->bindParam(':twitter',$twitter);
				$update->bindParam(':facebook',$facebook);
				$update->execute();
				echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
			}
		}
	}else{
		$nome_imagem = $item['avatar'];
		$update = $pdo->prepare("UPDATE aa_usuarios SET senha=:senha, pin=:pin, avatar=:nome_imagem, turno=:turno, programa=:programa, skype=:skype, twitter=:twitter, facebook=:facebook WHERE usuario='".$aa_data['usuario']."'");
			$update->bindParam(':senha',$senha);
			$update->bindParam(':pin',$pin);
			$update->bindParam(':nome_imagem',$nome_imagem);
			$update->bindParam(':turno',$turno);
			$update->bindParam(':programa',$programa);
			$update->bindParam(':skype',$skype);
			$update->bindParam(':twitter',$twitter);
			$update->bindParam(':facebook',$facebook);
			$update->execute();
			echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
	}
}
?>
<form method="post" enctype="multipart/form-data" autocomplete="off">
	Usuario:<br>
	<input type="text" class="text" readonly value="<?php echo $aa_data['usuario'];?>"><br>
	Senha (Deixe em branco caso não queira mudar):<br>
	<input type="password" class="text" name="senha"><br>
	PIN (Deixe em branco caso não queira mudar):<br>
	<input type="password" class="text" name="pin"><br>
	Foto:<br>
	<input type="file" class="text" name="imagem"><br>
	Turno:<br>
	<select name="turno" class="select">
	<option value="manha" <?php if($item['turno'] == 'manha'){ echo 'selected'; }?>>Manhã</option>
		<option value="tarde" <?php if($item['turno'] == 'tarde'){ echo 'selected'; }?>>Tarde</option>
		<option value="noite" <?php if($item['turno'] == 'noite'){ echo 'selected'; }?>>Noite</option>
	</select><br>
	Programa (Ex: Tocando as Melhores / Deixe em branco):<br>
	<input type="text" class="text" name="programa" value="<?php echo $item['programa']; ?>"><br>
	Skype (Ex: NomeDeUsuario / Deixe em branco):<br>
	<input type="text" class="text" name="skype" value="<?php echo $item['skype']; ?>"><br>
    Twitter (Ex: @NomeDeUsuario / Deixe em branco):<br>
	<input type="text" class="text" name="twitter" value="<?php echo $item['twitter']; ?>"><br>
	Facebook (Ex: NomeDeUsuario / Deixe em branco):<br>
	<input type="text" class="text" name="facebook" value="<?php echo $item['facebook']; ?>"><br>
	<input type="submit" class="button" value="Editar">
</form>