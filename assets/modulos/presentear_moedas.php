<?php
    if($_POST){
        $usuario = $_POST['usuario'];
        $moedas = $_POST['moedas'];
        if(empty($usuario) || empty($moedas)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
            $usuario = explode('>', $usuario);
            foreach($usuario as $user){
                $user = trim($user);
                $total = $pdo->query("SELECT * FROM usuarios WHERE usuario='$user'")->fetch(PDO::FETCH_ASSOC);
                $atual = $total['moedas'] + $moedas;
                $atualizar = $pdo->prepare("UPDATE usuarios SET moedas=:atual WHERE usuario='$user'");
                $atualizar->bindParam(':atual', $atual);
                $atualizar->execute();
                echo 'Moedas entregue à <b>'.$user.'</b> com sucesso! Tinha <b>'.$total['moedas'].'</b> e agora tem <b>'.$atual.'</b>.<br>';
            }
            echo '<br>';
        }
    }
?>
<form method="post" autocomplete="off">
    Usuarios presenteados: <br>(Se for vários, separe por >)<br>Ex: Usuário > Usuário > Usuário<br>
    <input type="text" class="text" name="usuario">
    Quantidade de moedas:<br>
    <input type="number" class="text" name="moedas">
    <input type="submit" class="button" value="Presentear">
</form>