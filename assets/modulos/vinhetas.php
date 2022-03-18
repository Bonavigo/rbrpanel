<?php
    $quantidade = 20;
    $registros = $pdo->query("SELECT * FROM aa_vinhetas")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
?>
<table width="100%">
    <tr style="height: 40px;">
        <th><b>Nome</b></th>
        <th><b>Vinheta</b></th>
        <th><b>Download</b></th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM aa_vinhetas ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><?php echo $ver['nome']; ?></th>
        <th><audio style="margin: 5px 0 0 0" controls><source src="assets/vinhetas/<?php echo $ver['audio']; ?>" type="audio/mp3"></audio></th>
        <th><a href="assets/vinhetas/<?php echo $ver['audio'] ?>" download>Clique aqui</a></th>
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
</div>