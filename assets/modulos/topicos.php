<?php
	if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $ver = $pdo->query("SELECT * FROM topicos WHERE id='$id'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $titulo = $_POST['titulo'];
        $url_top = Site::Url($titulo);
        $categoria = $_POST['categoria'];
        $moderado = $_POST['moderado'];
        $fixo = $_POST['fixo'];
        $status = $_POST['status'];
        $texto = $_POST['texto'];
        if(empty($titulo) || empty($texto)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $update = $pdo->prepare("UPDATE topicos SET titulo=:titulo, categoria=:categoria, url=:url, moderado=:moderado, moderador='".$aa_data['usuario']."', fixo=:fixo, status=:status, texto=:texto WHERE id='$id'");
            $update->bindParam(':titulo', $titulo);
            $update->bindParam(':categoria', $categoria);
            $update->bindParam(':url', $url_top);
            $update->bindParam(':moderado', $moderado);
            $update->bindParam(':status', $status);
            $update->bindParam(':fixo', $fixo);
            $update->bindParam(':texto', $texto);
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
    $topicos_cat = $pdo->query("SELECT * FROM topicos_cat");
    while ($ver_cat = $topicos_cat->fetch(PDO::FETCH_ASSOC)) {
?>
    <option value="<?php echo $ver_cat['id'] ?>" <?php if($ver['categoria'] == $ver_cat['id']){ echo 'selected'; } ?>><?php echo $ver_cat['categoria'] ?></option>
<?php
    }
?>
    </select><br><br><br>
    Autor:<br>
    <input type="text" class="text" readonly value="<?php echo $ver['autor'] ?>"><br><br><br>
    Moderador:<br>
    <input type="text" class="text" readonly value="<?php echo $aa_data['usuario'] ?>"><br><br><br>
    Moderado:<br>
    <select class="select" name="moderado">
        <option value="moderado" <?php if($ver['moderado'] == 'moderado'){ echo 'selected'; } ?>>Moderado</option>
        <option value="fechado" <?php if($ver['moderado'] == 'fechado'){ echo 'selected'; } ?>>Fechado</option>
    </select><br><br><br>
    Fixo:<br>
    <select class="select" name="fixo">
        <option value="true" <?php if($ver['fixo'] == 'true'){ echo 'selected'; } ?>>Sim</option>
        <option value="true" <?php if($ver['fixo'] == 'false'){ echo 'selected'; } ?>>Não</option>
    </select><br><br><br>
    Status:<br>
    <select class="select" name="status">
        <option value="true" <?php if($ver['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
        <option value="true" <?php if($ver['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
    </select><br><br><br>
    Texto:<br>
    <textarea class="texto" name="texto"><?php echo $ver['texto'] ?></textarea>
    <input type="submit" class="button" value="Editar">
</form>
<?php
	}else{
	$quantidade = 20;
    $registros = $pdo->query("SELECT * FROM topicos")->rowCount();
    $pagina     = (isset ($_GET['pagina'])) ? (int)$_GET['pagina'] : 1;
    $inicio     = ($quantidade * $pagina) - $quantidade;
    $totalPagina = ceil($registros/$quantidade);
?>

<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px">
	  	<th><img src="assets/img/editar.png"></th>
	    <th><b>Titulo</b></th>
		<th><b>Autor</b></th>
	    <th><b>Data</b></th>
	    <th><b>Status</b></th>
	    <th><b>Moderado</b></th>
	    <th><b>Moderador</b></th>
	</tr>
	<?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM topicos ORDER BY id DESC LIMIT $inicio, $quantidade");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
    ?>
    <tr style="height: 40px; <?php echo $css; ?>">
        <th><a href="pagina/<?php echo $url; ?>/editar/<?php echo $ver['id'] ?>"><img src="assets/img/editar.png"></a></th>
        <th><?php echo $ver['titulo']; ?></th>
        <th><?php echo $ver['autor']; ?></th>
        <th><?php echo date('d/m/Y - H:i',@$ver['time']); ?></th>
        <th><?php if($ver['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; } ?></th>
        <th><?php if($ver['moderado'] == 'moderado'){ echo 'Moderado'; }else if($ver['moderado'] == 'fechado'){ echo 'Fechado'; }else{ echo 'Pendente'; } ?></th>
        <th><?php if($ver['moderador'] == ''){ echo '-'; }else{ echo $ver['moderador']; } ?></th>
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