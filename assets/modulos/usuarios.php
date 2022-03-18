<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
	$id = $_GET['id'];
    $ver = $pdo->query("SELECT * FROM usuarios WHERE id='".$id."'")->fetch(PDO::FETCH_ASSOC);
?>
<form method="post" autocomplete="off">
    Usuário:<br>
    <input type="text" class="text" readonly value="<?php echo $ver['usuario'] ?>">
    Email:<br>
    <input type="text" class="text" readonly value="<?php echo $ver['email'] ?>">
    Registro:<br>
    <input type="text" class="text" readonly value="<?php echo date('d/m/Y - H:i',@$ver['registro_time']) ?>">
    Ultimo IP:<br>
    <input type="text" class="text" readonly value="<?php echo $ver['ultimo_ip'] ?>">
    Ultimo Login:<br>
    <input type="text" class="text" readonly value="<?php echo date('d/m/Y - H:i',@$ver['ultimo_time']) ?>">
    Banido:<br>
    <select class="select" name="banido">
       <option value="true" <?php if($ver['banido'] == 'true'){ echo 'selected'; } ?>>Sim</option>
        <option value="false" <?php if($ver['banido'] == 'false'){ echo 'selected'; } ?>>Não</option>
    </select>
    Motivo:<br>
    <input type="text" class="text" value="<?php echo $ver['motivo_ban'] ?>">
    <input type="submit" class="button" value="Editar">
</form>
<?php
}else{
    if($_POST){
        $usuario = $_POST['usuario'];
        $existe = $pdo->query("SELECT * FROM usuarios WHERE usuario='".$usuario."'");
        if($existe->rowCount() > 0){
            $ver = $existe->fetch(PDO::FETCH_ASSOC);
            echo '<script>location.href="pagina/'.$url.'/editar/'.$ver['id'].'"</script>';
        }else{
            echo Site::Alerta('Usuário inexistente!',false);
        }
    }
?>
<form method="post" autocomplete="off">
    Usuário:<br>
    <input type="text" class="text" name="usuario">
    <input type="submit" class="button" value="Procurar">
</form>
<?php
}
?>