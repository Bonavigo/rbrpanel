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
    if($_POST){
        $nome = $_POST['nome'];
        $dono = $_POST['dono'];
        $link = $_POST['link'];
        if(empty($nome) || empty($dono) || empty($link)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $inserir = $pdo->prepare("INSERT INTO quartos_parceiros(quarto, dono, link) VALUES(:quarto, :dono, :link)");
            $inserir->bindParam(':quarto',$nome);
            $inserir->bindParam(':dono',$dono);
            $inserir->bindParam(':link',$link);
            $inserir->execute();
            echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
       }
    }
    if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
        $id = (int) $_GET['id'];
        $delete = $pdo->query("DELETE FROM quartos_parceiros WHERE id='$id'");
        echo 1;
    }else{
?>
<form method="POST" autocomplete="off">
    Quarto:<br>
    <input type="text" class="text" name="nome"><br>
    Proprietário:<br>
    <input type="text" class="text" name="dono"><br>
    Link:<br>
    <input type="text" class="text" name="link"><br>
    <input type="submit" class="button" value="Adicionar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Nome</th>
        <th>Proprietário</th>
        <th>Link</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM quartos_parceiros ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['quarto']; ?></th>
        <th><?php echo $ver['dono']; ?></th>
        <th><a href="<?php echo $ver['link']; ?>">Clique aqui</a></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php } ?>