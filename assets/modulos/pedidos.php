<?php
    $quantidade = 20;
    $registros = $pdo->query("SELECT * FROM aa_pedidos ORDER BY id DESC")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
?>
<table width="100%">
    <tr style="height: 40px;">
        <th><b>Usuario</b></th>
        <th><b>Categoria</b></th>
        <th><b>Mensagem</b></th>
        <th><b>Data</b></th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM aa_pedidos ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><?php echo $ver['usuario']; ?></th>
        <th><?php echo $ver['categoria']; ?></th>
        <th><?php echo $ver['mensagem']; ?></th>
        <th><?php echo date('d/m/Y - H:i', $ver['time']); ?></th>
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