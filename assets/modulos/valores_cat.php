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
if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
	$id = (int) $_GET['id'];
	$delete = $pdo->query("DELETE FROM valores_cat WHERE id='$id'");
    echo 1;
}else{
    if($_POST){
        $categoria = $_POST['categoria'];
        $foto = $_FILES["imagem"];
        if(empty($categoria) || empty($foto)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else if(@!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
            echo Site::Alerta('Isso não é uma imagem!',false);
        }else{
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
            $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
            $caminho_imagem = "assets/uplouds/".$nome_imagem;
            move_uploaded_file($foto["tmp_name"], $caminho_imagem);
    		$inserir = $pdo->prepare("INSERT INTO valores_cat(categoria, icone) VALUES(:categoria, :icone)");
            $inserir->bindParam(':categoria',$categoria);
            $inserir->bindParam(':icone',$nome_imagem);
    		$inserir->execute();
    		echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
	   }
    }
?>
<form method="POST" enctype="multipart/form-data" autocomplete="off">
	Categoria:<br>
	<input type="text" class="text" name="categoria"><br>
    Icone:<br>
    <input type="file" class="text" name="imagem"><br>
	<input type="submit" class="button" value="Adicionar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Categoria</th>
        <th>Ícone</th>
    </tr>
    <?php
	$i = 1;
    $sql = $pdo->query("SELECT * FROM valores_cat ORDER BY id DESC");
	while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['categoria']; ?></th>
        <th><img src="assets/uplouds/<?php echo $ver['icone']; ?>"></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php
}
?>