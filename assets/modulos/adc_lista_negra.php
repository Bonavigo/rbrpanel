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
    $delete = $pdo->query("DELETE FROM aa_lista_negra WHERE id='$id'");
    echo 1;
}else{
    if($_POST){
        $usuario = $_POST['usuario'];
        $motivo = $_POST['motivo'];
        $inserir = $pdo->prepare("INSERT INTO aa_lista_negra(usuario, motivo) VALUES(:usuario, :motivo)");
        $inserir->bindParam(':usuario',$usuario);
        $inserir->bindParam(':motivo',$motivo);
        $inserir->execute();
        echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
    }
?>
<form method="POST" autocomplete="off">
    Usuário:<br>
    <input type="text" class="text" name="usuario"><br>
    Motivo:<br>
    <input type="text" class="text" name="motivo"><br>
    <input type="submit" class="button" value="Adicionar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Usuário</th>
        <th>Motivo</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM aa_lista_negra ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['usuario']; ?></th>
        <th><?php echo $ver['motivo']; ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php
}
?>