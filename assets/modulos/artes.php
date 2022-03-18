<?php
	if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $ver = $pdo->query("SELECT * FROM artes WHERE id='$id'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $titulo = $_POST['titulo'];
        $url_arte = Site::Url($titulo);
        $categoria = $_POST['categoria'];
        $status = $_POST['status'];
        $descricao = $_POST['descricao'];
        if(empty($titulo) || empty($descricao)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $update = $pdo->prepare("UPDATE artes SET titulo=:titulo, categoria=:categoria, url=:url, status=:status, descricao=:descricao WHERE id='$id'");
            $update->bindParam(':titulo', $titulo);
            $update->bindParam(':categoria', $categoria);
            $update->bindParam(':url', $url_arte);
            $update->bindParam(':status', $status);
            $update->bindParam(':descricao', $descricao);
            $update->execute();
            echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
        }
    }
?>
<form method="POST" style="width: 100%" autocomplete="off">
    Titulo:<br>
    <input type="text" class="text" name="titulo" value="<?php echo $ver['titulo'] ?>"><br><br><br>
    Categoria:<br>
    <select class="select" name="categoria">
<?php
    $topicos_cat = $pdo->query("SELECT * FROM artes_cat");
    while ($ver_cat = $topicos_cat->fetch(PDO::FETCH_ASSOC)) {
?>
    <option value="<?php echo $ver_cat['id'] ?>" <?php if($ver['categoria'] == $ver_cat['id']){ echo 'selected'; } ?>><?php echo $ver_cat['categoria'] ?></option>
<?php
    }
?>
    </select><br><br><br>
    Autor:<br>
    <input type="text" class="text" readonly value="<?php echo $ver['autor'] ?>"><br><br><br>
    Status:<br>
    <select class="select" name="status">
        <option value="true" <?php if($ver['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
        <option value="true" <?php if($ver['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
    </select><br><br><br>
    Descrição:<br>
    <textarea class="texto" name="descricao"><?php echo $ver['descricao'] ?></textarea>
    <input type="submit" class="button" value="Editar">
</form>
<?php
	}else{
	$quantidade = 20;
    $registros = $pdo->query("SELECT * FROM artes")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
?>

<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px">
	  	<th><img src="assets/img/editar.png"></th>
	    <th><b>Arte</b></th>
        <th><b>Categoria</b></th>
		<th><b>Autor</b></th>
	    <th><b>Data</b></th>
	    <th><b>Status</b></th>
	</tr>
	<?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM artes ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
        $categoria = $pdo->query("SELECT * FROM artes_cat WHERE id='".$ver['categoria']."'")->fetch(PDO::FETCH_ASSOC);
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id'] ?>"><img src="assets/img/editar.png"></a></th>
        <th><img style="max-height: 200px;" src="assets/uplouds/<?php echo $ver['imagem']; ?>"></th>
        <th><?php echo $categoria['categoria']; ?></th>
        <th><?php echo $ver['autor']; ?></th>
        <th><?php echo date('d/m/Y - H:i',@$ver['time']); ?></th>
        <th><?php if($ver['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; } ?></th>
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