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
        $delete = $pdo->query("DELETE FROM aa_tickets WHERE id='$id'");
        echo 1;
    }else{
    $quantidade = 20;
    $registros = $pdo->query("SELECT * FROM aa_tickets")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
?>
<table width="100%">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th><b>Id</b></th>
        <th><b>Usuario</b></th>
        <th><b>Assunto</b></th>
        <th><b>Mensagem</b></th>
        <th><b>Data</b></th>
        <th><b>Ip</b></th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM aa_tickets ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><a onclick="apagar.sim('<?php echo $ver['id']; ?>')" style="cursor:pointer;"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['id']; ?></th>
        <th><?php echo $ver['usuario']; ?></th>
        <th><?php echo $ver['assunto']; ?></th>
        <th><?php echo $ver['mensagem']; ?></th>
        <th><?php echo date('d/m/Y - H:i', $ver['time']); ?></th>
        <th><?php echo $ver['ip']; ?></th>
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