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
    $delete = $pdo->query("DELETE FROM usuarios_emblemas WHERE id='$id'");
    echo 1;
}else{
    if($_POST){
        $usuario = $_POST['usuario'];
        $descricao = $_POST['descricao'];
        $imagem = $_POST['img'];
        if(empty($usuario) || empty($descricao) || empty($imagem)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $usuario = explode('>', $usuario);
            foreach($usuario as $user){
                $user = trim($user);
                $emblemar = $pdo->prepare("INSERT INTO usuarios_emblemas(usuario, imagem, descricao) VALUES(:usuario, :imagem, :descricao)");
                $emblemar->bindParam(':usuario', $user);
                $emblemar->bindParam(':imagem', $imagem);
                $emblemar->bindParam(':descricao', $descricao);
                $emblemar->execute();
                echo 'Emblema entregue à <b>'.$user.'</b> com sucesso!<br>';
            }
            echo '<br>';
        }
    }
?>
<form method="post" autocomplete="off">
    Usuarios: <br>(Se for vários, separe por >)<br>Ex: Usuário > Usuário > Usuário<br>
    <input type="text" class="text" name="usuario">
    Descricao:<br>
    <input type="text" class="text" name="descricao">
    Imagem:<br>
    <input type="text" class="text" name="img">
    <input type="submit" class="button" value="Emblemar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Usuário</th>
        <th>Sobre</th>
        <th>Imagem</th>
    </tr>
    <?php
    $quantidade = 20;
    $registros = $pdo->query("SELECT * FROM usuarios_emblemas")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
    $i = 1;
    $sql = $pdo->query("SELECT * FROM usuarios_emblemas ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['usuario']; ?></th>
        <th><?php echo $ver['descricao']; ?></th>
        <th><img style="margin: 5px; max-height: 70px" src="<?php echo $ver['imagem'] ?>"></th>
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