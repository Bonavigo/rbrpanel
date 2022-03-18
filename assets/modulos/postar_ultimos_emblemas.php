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
    $delete = $pdo->query("DELETE FROM ultimos_emblemas WHERE id='$id'");
    echo 1;
}else{
    if($_POST){
        $codigo = $_POST['codigo'];
        if(empty($codigo)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $inserir = $pdo->prepare("INSERT INTO ultimos_emblemas(codigo) VALUES(:codigo)");
            $inserir->bindParam(':codigo',$codigo);
            $inserir->execute();
            echo Site::Alerta('Postado com sucesso!','pagina/'.$url);
        }
    }
?>
<form method="post" autocomplete="off">
    Código:<br>
    <input type="text" class="text" name="codigo">
    <input type="submit" class="button" value="Adicionar">
</form>
<?php
$quantidade = 20;
$registros = $pdo->query("SELECT * FROM ultimos_emblemas")->rowCount();
$pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio     = ($quantidade * $pagina) - $quantidade;
$totalPagina = ceil($registros/$quantidade);
?>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px">
	    <th><img src="assets/img/x.png"></th>
	    <th><b>Código</b></th>
		<th><b>Imagem</b></th>
	</tr>
	<?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM ultimos_emblemas ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><a onclick="apagar.sim('<?php echo $ver['id']; ?>')" style="cursor:pointer;"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['codigo']; ?></th>
        <th><img style="margin: 5px; max-width: 70px" src="http://images.habbohotel.com/c_images/album1584/<?php echo $ver['codigo']; ?>.gif"/></th>
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
<?php } ?>