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
	$delete = $pdo->query("DELETE FROM artes_cat WHERE id='$id'");
    echo 1;
}else{
    if($_POST){
        $categoria = $_POST['categoria'];
        if(empty($categoria)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
    		$inserir = $pdo->prepare("INSERT INTO artes_cat(categoria) VALUES(:categoria)");
            $inserir->bindParam(':categoria',$categoria);
    		$inserir->execute();
    		echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
	   }
    }
?>
<form method="POST" autocomplete="off">
	Categoria:<br>
	<input type="text" class="text" name="categoria"><br>
	<input type="submit" class="button" value="Adicionar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Categoria</th>
    </tr>
    <?php
	$i = 1;
    $sql = $pdo->query("SELECT * FROM artes_cat ORDER BY id DESC");
	while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['categoria']; ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php
}
?>