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
    $delete = $pdo->query("DELETE FROM aa_vinhetas WHERE id='$id'");
    echo 1;
}else{
    if($_POST){
        $nome = $_POST['nome'];
        $foto = $_FILES['audio'];
        if(empty($foto["name"]) || empty($nome)){
            echo Site::Alerta('Preencha todos campos!',db2_field_display_size(stmt, column));
        }else if(!empty($foto["name"])){
                preg_match("/\.(mp4|mp3|mpeg3|wma){1}$/i", $foto["name"], $ext);
                $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                $caminho_imagem = "assets/vinhetas/".$nome_imagem;
                move_uploaded_file($foto["tmp_name"], $caminho_imagem);
                if($caminho_imagem){
                    $inserir = $pdo->prepare("INSERT INTO aa_vinhetas(nome, audio) VALUES(:nome, :audio)");
                    $inserir->bindParam(':nome',$nome);
                    $inserir->bindParam(':audio',$nome_imagem);
                    $inserir->execute();
                    echo Site::Alerta('Adicionado com sucesso!','pagina/'.$url);
                }
        }
    }
?>
<form method="POST" enctype="multipart/form-data" autocomplete="off">
    Nome da Vinheta:<br>
    <input type="text" class="text" name="nome"><br>
    Vinheta:<br>
    <input type="file" class="text" name="audio"><br>
    <input type="submit" class="button" value="Adicionar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Vinheta</th>
        <th>Ouvir</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM aa_vinhetas ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['nome']; ?></th>
        <th><audio style="margin: 5px 0 0 0" controls><source src="assets/vinhetas/<?php echo $ver['audio']; ?>" type="audio/mp3"></audio></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php
}
?>