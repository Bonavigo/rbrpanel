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
    $delete = $pdo->query("DELETE FROM coisas_gratis WHERE id='$id'");
    echo 1;
}else{
if($_POST){
    $titulo = $_POST['titulo'];
    $imagem = $_POST['img'];
    $link = $_POST['link'];
    if(empty($titulo) || empty($imagem) || empty($link)){
        echo Site::Alerta('Preencha todos campos!',false);
    }else{
        $inserir = $pdo->prepare("INSERT INTO coisas_gratis(titulo, imagem, link) VALUES(:titulo, :imagem, :link)");
        $inserir->bindParam(':titulo',$titulo);
        $inserir->bindParam(':imagem',$imagem);
        $inserir->bindParam(':link',$link);
        $inserir->execute();
        echo Site::Alerta('Postado com sucesso!','pagina/'.$url);
    }
}
?>
<form method="post" autocomplete="off">
    Titulo:<br>
    <input type="text" class="text" name="titulo">
    Imagem:<br>
    <input type="text" class="text" name="img">
    Link:<br>
    <input type="text" class="text" name="link">
    <input type="submit" class="button" value="Adicionar">
</form>
<?php
$quantidade = 20;
$registros = $pdo->query("SELECT * FROM coisas_gratis")->rowCount();
$pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
$inicio     = ($quantidade * $pagina) - $quantidade;
$totalPagina = ceil($registros/$quantidade);
?>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px">
	    <th><img src="assets/img/x.png"></th>
	    <th><b>Titulo</b></th>
		<th><b>Imagem</b></th>
	    <th><b>Link</b></th>
	</tr>
	<?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM coisas_gratis ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><a onclick="apagar.sim('<?php echo $ver['id']; ?>')" style="cursor:pointer;"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['titulo']; ?></th>
        <th><img style="margin: 5px; max-width: 70px" src="<?php echo $ver['imagem']; ?>"/></th>
        <th><a href="<?php echo $ver['link']; ?>">Clique aqui</a></th>
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