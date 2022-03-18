<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th>Quantidade</th>
        <th>Usuário</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT `aa_presenca_marcadas`.*,count(usuario) as count FROM aa_presenca_marcadas GROUP BY usuario ORDER BY count DESC LIMIT 100");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><?php echo $ver['count']; ?></th>
        <th><?php echo $ver['usuario']; ?></th>
    </tr>
    <?php $i++; } ?>
</table>