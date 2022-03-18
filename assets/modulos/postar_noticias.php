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
	if(isset($_GET['tipo']) && $_GET['tipo'] == 'criar'){
        if($_POST){
            $titulo = $_POST['titulo'];
            $url_not = Site::Url($titulo);
            $descricao = $_POST['descricao'];
            $categoria = $_POST['categoria'];
            $foto = $_FILES['imagem'];
            $texto = $_POST['texto'];
            if(empty($titulo) || empty($descricao) || empty($categoria) || empty($foto["name"]) || empty($texto)){
                echo Site::Alerta('Preencha todos campos!',false);
            }else{
                if(@!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $foto["type"])){
                    echo Site::Alerta('Isso não é uma imagem!',false);
                }else{
                    preg_match("/\.(gif|bmp|png|jpg|jpeg){1}$/i", $foto["name"], $ext);
                    $nome_imagem = md5(uniqid(time())) . "." . $ext[1];
                    $caminho_imagem = "assets/uplouds/".$nome_imagem;
                    move_uploaded_file($foto["tmp_name"], $caminho_imagem);
                    if($caminho_imagem){
                        $inserir = $pdo->prepare("INSERT INTO noticias(titulo, descricao, categoria, imagem, autor, revisado, revisador, time, status, fixo, url, texto, visualizacao, evento, evento_time) VALUES(:titulo, :descricao, :categoria, :imagem, :autor, 'false', 'Nenhum', '".$time."', 'false', 'false', :url, :texto, '0', 'false', '0')");
                        $inserir->bindParam(':titulo', $titulo);
                        $inserir->bindParam(':descricao', $descricao);
                        $inserir->bindParam(':categoria', $categoria);
                        $inserir->bindParam(':imagem', $nome_imagem);
                        $inserir->bindParam(':autor', $aa_data['usuario']);
                        $inserir->bindParam(':url', $url_not);
                        $inserir->bindParam(':texto', $texto);
                        $inserir->execute();
                        echo Site::Alerta('Postado com sucesso!','pagina/'.$url);
                    }
                }
            }
        }
?>
<form method="POST" enctype="multipart/form-data" style="width: 100%" autocomplete="off">
    Titulo:<br>
    <input type="text" class="text" name="titulo"><br><br><br>
    Descrição:<br>
    <input type="text" class="text" name="descricao"><br><br><br>
    Categoria:<br>
    <select class="select" name="categoria">
<?php
    $noticias_cat = $pdo->query("SELECT * FROM noticias_cat");
    while($ver = $noticias_cat->fetch(PDO::FETCH_ASSOC)){?>
        <option value="<?php echo $ver['id'] ?>"><?php echo $ver['categoria'] ?></option>
<?php
    }
?>
    </select><br><br><br>
    Imagem:<br>
    <input type="file" class="text" name="imagem"><br><br><br>
    Texto:<br>
    <textarea id="ckeditor" name="texto"></textarea>
    <input type="submit" class="button" value="Postar">
</form>
<?php
	}else{
	    $quantidade = 20;
        $registros = $pdo->query("SELECT * FROM noticias WHERE evento='false' AND autor='".$aa_data['usuario']."'")->rowCount();
        $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
        $inicio     = ($quantidade * $pagina) - $quantidade;
        $totalPagina = ceil($registros/$quantidade);
?>
<a href="pagina/<?php echo $url;?>/criar"><input type="button" class="button" value="Criar Notícia"></a>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px">
	    <th><b>Id</b></th>
		<th><b>Imagem</b></th>
	    <th><b>Titulo</b></th>
	    <th><b>Criador</b></th>
	    <th><b>Data</b></th>
        <th><b>Status</b></th>
	    <th><b>Fixo</b></th>
	</tr>
	<?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM noticias WHERE evento='false' AND autor='".$aa_data['usuario']."' ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><?php echo $ver['id']; ?></th>
        <th><img style="margin: 5px; max-width: 100px" src="assets/uplouds/<?php echo $ver['imagem']; ?>"/></th>
        <th><?php echo $ver['titulo']; ?></th>
        <th><?php echo $ver['autor']; ?></th>
        <th><?php echo date('d/m/Y - H:i', $ver['time']); ?></th>
        <th><?php if($ver['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; } ?></th>
        <th><?php if($ver['fixo'] == 'true'){ echo 'Sim'; }else{ echo 'Não'; } ?></th>
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