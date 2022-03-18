<?php
if(isset($_FILES["imagem"])){
	$foto = $_FILES["imagem"];
	if(empty($foto["name"])){
		echo Site::Alerta('Preencha todos campos!',false);
	}else{
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
				$criador = $aa_data['usuario'];
				$update = $pdo->prepare("INSERT INTO aa_uplouds(url, usuario) VALUES (:nome_imagem,:criador)");
				$update->bindParam(':nome_imagem',$nome_imagem);
				$update->bindParam(':criador',$criador);
				$update->execute();
				echo Site::Alerta('Enviado com sucesso!','pagina/'.$url);
			}
		}
	}
}
?>
<form method="post" enctype="multipart/form-data">
	<input type="file" class="text" name="imagem" /><br>
	<input type="submit" class="button" value="Enviar" />
</form>
<?php
	$quantidade = 20;
    $registros = $pdo->query("SELECT id FROM aa_uplouds")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
?>
<table width="100%" style="margin: 10px 0 0 0; float: left">
	<tr>
    	<th>Id</th>
        <th>Imagem</th>
        <th>Link</th>
        <th>Criador</th>
    </tr>
    <?php
	$i = 1;
    $sql = $pdo->query("SELECT * FROM aa_uplouds ORDER BY id DESC LIMIT $inicio, $quantidade");
	while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
	?>
    <tr style="height: 40px; <?php echo $css;?>">
	   	<th><?php echo $ver['id']; ?></th>
        <th><a href="assets/uplouds/<?php echo $ver['url']; ?>" rel="shadowbox"><img src="assets/uplouds/<?php echo $ver['url']; ?>" style="max-width:200px;max-height:200px"></a></th>
        <th><a href="assets/uplouds/<?php echo $ver['url']; ?>" target="_blank">Ver imagem</a></th>
        <th><?php echo $ver['usuario']; ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<div id="paginacao">
<?php
    for($i = 1; $i <= $totalPagina; $i++){
        if($i == $pagina){
            echo '<div class="pag" style="background: #999">'.$i.'</div>';
        }else{
            echo '<a href="pagina/'.$url.'/lista/'.$i.'"><div class="pag">'.$i.'</div></a>';
        }
    }
?>
</div>