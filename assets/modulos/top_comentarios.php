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
	$delete = $pdo->query("DELETE FROM topicos_comentarios WHERE id='$id'");
    echo 1;
}else{
?>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Autor</th>
        <th>Comentário</th>
        <th>Tópico</th>
        <th>Data</th>
    </tr>
    <?php
	$i = 1;
    $sql = $pdo->query("SELECT * FROM topicos_comentarios ORDER BY id DESC");
	while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
        $artigo = $pdo->query("SELECT * FROM topicos WHERE id='".$ver['arte_id']."'")->fetch(PDO::FETCH_ASSOC);
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['autor']; ?></th>
        <th><?php echo $ver['comentario']; ?></th>
        <th><?php echo $artigo['titulo']; ?></th>
        <th><?php echo date('d/m/Y - H:i',@$ver['time']); ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php
}
?>