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
    $delete = $pdo->query("DELETE FROM aa_avisos WHERE id='$id'");
    echo 1;
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $item = $pdo->query("SELECT * FROM aa_avisos WHERE id='$id'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $titulo = $_POST['titulo'];
        $texto = $_POST['texto'];
        if(empty($titulo) || empty($texto)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $inserir = $pdo->prepare("UPDATE aa_avisos SET titulo=:titulo, texto=:texto WHERE id='".$id."'");
            $inserir->bindParam(':titulo', $titulo);
            $inserir->bindParam(':texto', $texto);
            $inserir->execute();
            echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
        }
    }
?>
<form method="POST" style="width: 100%" autocomplete="off">
Titulo:<br>
<input type="text" class="text" name="titulo" value="<?php echo $item['titulo'] ?>"><br><br><br>
Texto:<br>
<textarea id="ckeditor" name="texto"><?php echo $item['texto'] ?></textarea>
<input type="submit" class="button" value="Editar">
</form>
<?php
}else{
if($_POST){
    $titulo = $_POST['titulo'];
    $texto = $_POST['texto'];
	if(empty($titulo) || empty($texto)){
		echo Site::Alerta('Preencha todos campos!',false);
	}else{
		$inserir = $pdo->prepare("INSERT INTO aa_avisos(titulo, texto, autor, time) VALUES(:titulo, :texto, :autor, :time)");
		$inserir->bindParam(':titulo', $titulo);
		$inserir->bindParam(':texto', $texto);
		$inserir->bindParam(':autor', $aa_data['usuario']);
		$inserir->bindParam(':time', $time);
		$inserir->execute();
		echo Site::Alerta('Criado com sucesso!','pagina/'.$url);
	}
}
?>
<form method="POST" style="width: 100%" autocomplete="off">
Titulo:<br>
<input type="text" class="text" name="titulo"><br><br><br>
Texto:<br>
<textarea id="ckeditor" name="texto"></textarea>
<input type="submit" class="button" value="Postar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th><img src="assets/img/editar.png"></th>
        <th>Titulo</th>
        <th>Texto</th>
        <th>Autor</th>
        <th>Data</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM aa_avisos ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id'] ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['titulo']; ?></th>
        <th><?php echo $ver['texto']; ?></th>
        <th><?php echo $ver['autor']; ?></th>
        <th><?php echo date('d/m/Y - H:i',@$ver['time']); ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php } ?>