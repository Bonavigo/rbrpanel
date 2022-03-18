<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
	$id = $_GET['id'];
    $ver = $pdo->query("SELECT * FROM usuarios WHERE id='".$id."'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $assinatura = $_POST['assinatura'];
        $update = $pdo->prepare("UPDATE usuarios SET assinatura=:assinatura WHERE id='".$id."'");
        $update->bindParam(':assinatura',$assinatura);
        $update->execute();
        echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
    }
?>
<form method="post" autocomplete="off" style="width:100%">
    Assinatura:<br>
    <textarea class="texto" name="assinatura"><?php echo $ver['assinatura'] ?></textarea>
    <input type="submit" class="button" value="Editar">
</form>
<?php
}else{
    if($_POST){
        $usuario = $_POST['usuario'];
        if(empty($usuario)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $existe = $pdo->query("SELECT * FROM usuarios WHERE usuario='".$usuario."'");
            if($existe->rowCount() > 0){
                $ver = $existe->fetch(PDO::FETCH_ASSOC);
                echo '<script>location.href="pagina/'.$url.'/editar/'.$ver['id'].'"</script>';
            }else{
                echo Site::Alerta('Usuário inexistente!',false);
            }
        }
    }
?>
<form method="post">
    Usuário:<br>
    <input type="text" class="text" name="usuario">
    <input type="submit" class="button" value="Procurar">
</form>
<?php
}
?>