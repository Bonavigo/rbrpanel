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
    if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $item = $pdo->query("SELECT * FROM timeline WHERE id='".$id."'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $mensagem = $_POST['mensagem'];
        $status = $_POST['status'];
        if(empty($mensagem)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $update = $pdo->prepare("UPDATE timeline SET mensagem=:mensagem, status=:status WHERE id='".$id."'");
            $update->bindParam(':mensagem',$mensagem);
            $update->bindParam(':status',$status);
            $update->execute();
            echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
        }
    }
?>
<form method="POST">
    Usuário:<br>
    <input type="text" class="text" readonly value="<?php echo $item['usuario'] ?>">
    Mensagem:<br>
    <textarea class="text" name="mensagem" style="height: 50px; resize: none"><?php echo $item['mensagem'] ?></textarea><br>
    Status:<br>
    <select class="select" name="status">
        <option value="true" <?php if($item['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
        <option value="false" <?php if($item['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
    </select>
    <input type="submit" class="button" value="Editar">
</form>
<?php
    }else if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
        $id = (int) $_GET['id'];
        $delete = $pdo->query("DELETE FROM timeline WHERE id='$id'");
        echo 1;
    }else{
?>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th><img src="assets/img/editar.png"></th>
        <th>Usuário</th>
        <th>Mensagem</th>
        <th>Data</th>
        <th>Status</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM timeline ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id'] ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['usuario']; ?></th>
        <th><?php echo $ver['mensagem']; ?></th>
        <th><?php echo date('d/m/Y - H:i',$ver['time']); ?></th>
        <th><?php if($ver['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; } ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php } ?>