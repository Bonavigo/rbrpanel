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
	$delete = $pdo->query("DELETE FROM valores WHERE id='$id'");
    echo 1;
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'criar'){
    if($_POST){
        $mobi = $_POST['mobi'];
        $categoria = $_POST['categoria'];
        $preco = $_POST['preco'];
        $estado = $_POST['estado'];
        $foto = $_FILES["imagem"];
        if(empty($mobi) || empty($categoria) || empty($preco) || empty($foto["name"])){
            echo Site::Alerta('Preencha todos campos!',false);
        }else if(@!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
            echo Site::Alerta('Isso não é uma imagem!',false);
        }else{
            preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
            $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
            $caminho_imagem = "assets/uplouds/".$nome_imagem;
            move_uploaded_file($foto["tmp_name"], $caminho_imagem);
            $inserir = $pdo->prepare("INSERT INTO valores(mobi, categoria, preco, estado, icone) VALUES(:mobi, :categoria, :preco, :estado, :icone)");
            $inserir->bindParam(':mobi',$mobi);
            $inserir->bindParam(':categoria',$categoria);
            $inserir->bindParam(':preco',$preco);
            $inserir->bindParam(':estado',$estado);
            $inserir->bindParam(':icone',$nome_imagem);
            $inserir->execute();
            echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
       }
    }
?>
<form method="POST" enctype="multipart/form-data" autocomplete="off">
    Mobi:<br>
    <input type="text" class="text" name="mobi"><br>
    Categoria:<br>
    <select name="categoria" class="select">
    <?php
        $select_cat = $pdo->query("SELECT * FROM valores_cat");
        while ($ver_cat = $select_cat->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <option value="<?php echo $ver_cat['id'] ?>"><?php echo $ver_cat['categoria'] ?></option>
    <?php
        }
    ?>   
    </select>
    Preço:<br>
    <input type="number" min="1" class="text" name="preco"><br>
    Estado:<br>
    <select name="estado" class="select">
        <option value="subiu">Subiu</option>
        <option value="manteve">Manteve</option>
        <option value="caiu">Caiu</option>
    </select><br>
    Icone:<br>
    <input type="file" class="text" name="imagem"><br>
    <input type="submit" class="button" value="Adicionar">
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $item = $pdo->query("SELECT * FROM valores WHERE id = '".$id."'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $mobi = $_POST['mobi'];
        $categoria = $_POST['categoria'];
        $preco = $_POST['preco'];
        $estado = $_POST['estado'];
        $foto = $_FILES["imagem"];
        if(empty($mobi) || empty($categoria) || empty($preco)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else if(!empty($foto["name"]) && @!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
            echo Site::Alerta('Isso não é uma imagem!',false);
        }else{
            if(empty($foto["name"])){
                $nome_imagem = $item['icone'];
            }else{
                preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
                $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                $caminho_imagem = "assets/uplouds/".$nome_imagem;
                move_uploaded_file($foto["tmp_name"], $caminho_imagem);
            }
            $inserir = $pdo->prepare("UPDATE valores SET mobi=:mobi, categoria=:categoria, preco=:preco, estado=:estado, icone=:icone, valorista='".$aa_data['usuario']."'");
            $inserir->bindParam(':mobi',$mobi);
            $inserir->bindParam(':categoria',$categoria);
            $inserir->bindParam(':preco',$preco);
            $inserir->bindParam(':estado',$estado);
            $inserir->bindParam(':icone',$nome_imagem);
            $inserir->execute();
            echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
       }
    }
?>
<form method="POST" enctype="multipart/form-data" autocomplete="off">
    Mobi:<br>
    <input type="text" class="text" name="mobi" value="<?php echo $item['mobi'] ?>"><br>
    Categoria:<br>
    <select name="categoria" class="select">
    <?php
        $select_cat = $pdo->query("SELECT * FROM valores_cat");
        while ($ver_cat = $select_cat->fetch(PDO::FETCH_ASSOC)) {
    ?>
        <option value="<?php echo $ver_cat['id'] ?>" <?php if($item['categoria'] == $ver_cat['id']){ echo 'selected'; } ?>><?php echo $ver_cat['categoria'] ?></option>
    <?php
        }
    ?>   
    </select>
    Preço:<br>
    <input type="number" min="1" class="text" name="preco" value="<?php echo $item['preco'] ?>"><br>
    Estado:<br>
    <select name="estado" class="select">
        <option value="subiu" <?php if($item['estado'] == 'subiu'){ echo 'selected'; } ?>>Subiu</option>
        <option value="manteve" <?php if($item['estado'] == 'manteve'){ echo 'selected'; } ?>>Manteve</option>
        <option value="caiu" <?php if($item['estado'] == 'caiu'){ echo 'selected'; } ?>>Caiu</option>
    </select><br>
    Icone:<br>
    <img src="assets/uplouds/<?php echo $item['icone'] ?>"><br>
    <input type="file" class="text" name="imagem"><br>
    <input type="submit" class="button" value="Adicionar">
</form>
<?php
}else{
?>
<a href="pagina/<?php echo $url;?>/criar"><input type="button" class="button" value="Adicionar Mobi"></a>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th><img src="assets/img/editar.png"></th>
        <th>Mobi</th>
        <th>Categoria</th>
        <th>Preço</th>
        <th>Estado</th>
        <th>Icone</th>
    </tr>
    <?php
	$i = 1;
    $sql = $pdo->query("SELECT * FROM valores ORDER BY id DESC");
	while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $categoria = $pdo->query("SELECT * FROM valores_cat WHERE id='".$ver['categoria']."'")->fetch(PDO::FETCH_ASSOC);
		$css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id'] ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['mobi']; ?></th>
        <th><?php echo $categoria['categoria']; ?></th>
        <th><?php echo $ver['preco']; ?></th>
        <th><?php if($ver['estado'] == 'subiu'){ echo 'Subiu'; }else if($ver['estado'] == 'caiu'){ echo 'Caiu'; }else{ echo 'Manteve'; }?></th>
        <th><img src="assets/uplouds/<?php echo $ver['icone']; ?>"></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php
}
?>