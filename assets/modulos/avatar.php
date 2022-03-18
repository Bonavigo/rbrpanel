<?php
    if($_POST){
        $usuario = $_POST['usuario'];
        if(empty($usuario)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $existe = $pdo->query("SELECT * FROM usuarios WHERE usuario='".$usuario."'")->rowCount();
            if($existe > 0){
                $remover = $pdo->query("UPDATE usuarios SET avatar='removido.png' WHERE usuario='".$usuario."'");
                echo Site::Alerta('Removido com sucesso!',false);
            }else{
                echo Site::Alerta('Usuário inexistente!',false);
            }
        }
    }
?>
<form method="post">
    Usuário:<br>
    <input type="text" class="text" name="usuario">
    <input type="submit" class="button" value="Remover">
</form>