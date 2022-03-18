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
if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $item_ver = $pdo->query("SELECT * FROM loja WHERE id='".$id."'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $item = $_POST['item'];
        $valor = $_POST['valor'];
        $comprado = $_POST['comprado'];
        $comprador = $_POST['comprador'];
        $status = $_POST['status'];
        $foto = $_FILES['imagem'];
        if(empty($item) || empty($valor) || empty($comprador)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else if(!empty($foto["name"]) && !eregi("^image\/(pjpeg|icon|jpeg|png|gif|bmp)$", $foto["type"])){
            echo Site::Alerta('Isso não é uma imagem!',false);
        }else{
            if(empty($foto["name"])){
                $nome_imagem = $item_ver['imagem'];
            }else{
                preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
                $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                $caminho_imagem = "assets/uplouds/".$nome_imagem;
                move_uploaded_file($foto["tmp_name"], $caminho_imagem);
            }
            $inserir = $pdo->prepare("UPDATE loja SET imagem=:imagem, item=:item, valor=:valor, comprado=:comprado, comprador=:comprador, status=:status WHERE id='".$id."'");
            $inserir->bindParam(':imagem',$nome_imagem);
            $inserir->bindParam(':item',$item);
            $inserir->bindParam(':valor',$valor);
            $inserir->bindParam(':comprado',$comprado);
            $inserir->bindParam(':comprador',$comprador);
            $inserir->bindParam(':status',$status);
            $inserir->execute();
            echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
       }
    }
?>
<form method="POST" enctype="multipart/form-data" autocomplete="off">
    <img src="assets/uplouds/<?php echo $item_ver['imagem'] ?>"><br>
    Imagem:<br>
    <input type="file" class="text" name="imagem"><br>
    Item:<br>
    <input type="text" class="text" name="item" value="<?php echo $item_ver['item']; ?>"><br>
    Valor:<br>
    <input type="number" min="1" class="text" name="valor" value="<?php echo $item_ver['valor']; ?>"><br>
    Comprado:<br>
    <select name="comprado" class="select">
        <option value="true" <?php if($item_ver['comprado'] == 'true'){ echo 'selected'; } ?>>Sim</option>
        <option value="false" <?php if($item_ver['comprado'] == 'false'){ echo 'selected'; } ?>>Não</option>
    </select><br>
    Comprador:<br>
    <input type="text" class="text" name="comprador" value="<?php echo $item_ver['comprador']; ?>"><br>
    Status:<br>
    <select name="status" class="select">
        <option value="true" <?php if($item_ver['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
        <option value="false" <?php if($item_ver['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
    </select><br>
    <input type="submit" class="button" value="Editar">
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
        $id = (int) $_GET['id'];
        $delete = $pdo->query("DELETE FROM loja WHERE id='$id'");
        echo 1;
}else{
	if($_POST){
        $item = $_POST['item'];
        $valor = $_POST['valor'];
        $foto = $_FILES['imagem'];
        if(empty($item) || empty($valor) || empty($foto["name"])){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
             preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
            $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
            $caminho_imagem = "assets/uplouds/".$nome_imagem;
            move_uploaded_file($foto["tmp_name"], $caminho_imagem);
    		$inserir = $pdo->prepare("INSERT INTO loja(imagem, item, valor, vendedor, comprado, comprador, time, status) VALUES(:imagem, :item, :valor, '".$aa_data['usuario']."', 'false', 'Nenhum', '".$time."', 'true')");
            $inserir->bindParam(':imagem',$nome_imagem);
            $inserir->bindParam(':item',$item);
            $inserir->bindParam(':valor',$valor);
    		$inserir->execute();
    		echo Site::Alerta('Inserido com sucesso!','pagina/'.$url);
	   }
    }
?>
<form method="POST" enctype="multipart/form-data" autocomplete="off">
    Imagem:<br>
    <input type="file" class="text" name="imagem"><br>
	Item:<br>
	<input type="text" class="text" name="item"><br>
    Valor:<br>
    <input type="number" min="1" class="text" name="valor"><br>
	<input type="submit" class="button" value="Criar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th><img src="assets/img/editar.png"></th>
        <th>Imagem</th>
        <th>Item</th>
        <th>Valor</th>
        <th>Vendedor</th>
        <th>Comprado</th>
        <th>Comprador</th>
        <th>Status</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM loja ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id']; ?>"><img src="assets/img/editar.png"></a></th>
        <th><img src="assets/uplouds/<?php echo $ver['imagem']; ?>"></th>
        <th><?php echo $ver['item']; ?></th>
        <th><?php echo $ver['valor']; ?></th>
        <th><?php echo $ver['vendedor']; ?></th>
        <th><?php if($ver['comprado'] == 'true'){ echo 'Sim'; }else{ echo 'Não'; }; ?></th>
        <th><?php echo $ver['comprador']; ?></th>
        <th><?php if($ver['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; }; ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php } ?>