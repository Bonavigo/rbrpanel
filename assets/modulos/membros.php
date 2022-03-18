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
$aa_cargo = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_cargos c WHERE r.cargo_id=c.cargo_id AND r.user_id='".$aa_data['id']."' ORDER BY c.ordem ASC")->fetch(PDO::FETCH_ASSOC);
if(isset($_GET['tipo']) && $_GET['tipo'] == 'criar'){
    if($_POST){
        $usuario = $_POST['usuario'];
        $senha = substr(md5($_POST['senha']), 4);
        $pin = $_POST['pin'];
        $status = $_POST['status'];
        $ver_user = $pdo->query("SELECT * FROM aa_usuarios WHERE usuario = '$usuario'")->rowCount();
        if(empty($_POST['cargo']) || empty($usuario) || empty($senha) || empty($pin)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else if($ver_user > 0){
            echo Site::Alerta('Usuário Existente!',false);
        }else{
            foreach ($_POST['cargo'] as $key => $cargo) {
                $ver_ant = $pdo->query("SELECT * FROM aa_cargos WHERE cargo_id='".$cargo."'")->fetch(PDO::FETCH_ASSOC);
                if($ver_ant['ordem'] < $aa_cargo['ordem']){
                    echo Site::Alerta('Erro interno!','pagina/'.$url);
                }else{        
                    if($ver_user == 0){
                        $notificacao = $pdo->query("INSERT INTO aa_notificacao(usuario, texto, visto, time) VALUES('".$usuario."', 'Bem-vindo ao KPanel!', 'false', '".$time."')");
                        $inserir = $pdo->prepare("INSERT INTO aa_usuarios(usuario, senha, pin, status, turno, programa, skype, twitter, facebook, advertencia, banido, motivo, ultimo_time, ultimo_ip, online, online_time) VALUES(:usuario, :senha, :pin, :status, 'Tarde', 'Nenhum', 'Nenhum', 'Nenhum', 'Nenhum', '0', 'false', 'Nenhum', '0', '0', 'false', '0')");
                        $inserir->bindParam(':usuario',$usuario);
                        $inserir->bindParam(':senha',$senha);
                        $inserir->bindParam(':pin',$pin);
                        $inserir->bindParam(':status',$status);
                        $inserir->execute();
                    }
                    $ver_user_r = $pdo->query("SELECT * FROM aa_usuarios WHERE usuario='$usuario'")->fetch(PDO::FETCH_ASSOC);          
                    $c_inserir = $pdo->prepare("INSERT INTO aa_usuarios_rel (user_id, cargo_id) VALUES(:user_id, :cargo_id)");
                    $c_inserir->bindParam(':user_id', $ver_user_r['id']);
                    $c_inserir->bindParam(':cargo_id', $cargo);
                    $c_inserir->execute();
                    echo Site::Alerta('Criado com sucesso!','pagina/'.$url);
                }
            }
        }
    }
?>

<form method="post" autocomplete="off">
    Usuário:<br>
    <input type="text" class="text" name="usuario"><br>
    Senha:<br>
    <input type="password" class="text" name="senha"><br>
    PIN:<br>
    <input type="number" class="text" name="pin" maxlenght="5"><br>
    Status:<br>
    <select name="status" class="select">
        <option value="true">Ativo</option>
        <option value="false">Inativo</option>
    </select><br>
    Cargo:<br>
<?php
    $ver_cargo_c = $pdo->query("SELECT * FROM aa_cargos WHERE ordem >= '".$aa_cargo['ordem']."'");
    while($ver_cargo = $ver_cargo_c->fetch(PDO::FETCH_ASSOC)){
?>
    <label><input type="checkbox" name="cargo[<?php echo $ver_cargo['cargo_id']; ?>]" value="<?php echo $ver_cargo['cargo_id']; ?>"><?php echo $ver_cargo['cargo']; ?></label><br>
<?php
    }
?>
    <input type="submit" class="button" value="Criar">
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'editar'){
    $id = $_GET['id'];
    $item = $pdo->query("SELECT * FROM aa_usuarios WHERE id='$id'")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $advertencia = $_POST['advertencia'];
        $motivoadv = 'Você foi advertido pois: '.$_POST['motivoadv'].'';
        $status = $_POST['status'];
        $banido = $_POST['banido'];
        $motivo = $_POST['motivo'];
        if(empty($_POST['senha'])){
            $senha = $item['senha'];
        }else{
            $senha = substr(md5($_POST['senha']), 4);
        }
        if(empty($_POST['pin'])){
            $pin = $item['pin'];
        }else{
            $pin = $_POST['pin'];
        }
        if(empty($_POST['cargo'])){
            echo Site::Alerta('Selecione um cargo!',false);
        }else{
            $limpar_cargos = $pdo->query("DELETE FROM aa_usuarios_rel WHERE user_id='$id'");
            foreach ($_POST['cargo'] as $key => $cargo) {
                $ver_ant = $pdo->query("SELECT * FROM aa_cargos WHERE cargo_id='".$cargo."'")->fetch(PDO::FETCH_ASSOC);
                if($ver_ant['ordem'] < $aa_cargo['ordem']){
                    echo Site::Alerta('Erro interno!','index.php?diretorio='.$url);
                }else{
                    $editar = $pdo->prepare("UPDATE aa_usuarios SET senha=:senha, pin=:pin, status=:status, banido=:banido, motivo=:motivo, advertencia=:advertencia WHERE id=$id");
                    $editar->bindParam(':senha',$senha);
                    $editar->bindParam(':pin',$pin);
                    $editar->bindParam(':status',$status);
                    $editar->bindParam(':banido',$banido);
                    $editar->bindParam(':motivo',$motivo);
                    $editar->bindParam(':advertencia',$advertencia);
                    $editar->execute();
                   
                    $c_inserir = $pdo->prepare("INSERT INTO aa_usuarios_rel (user_id, cargo_id) VALUES(:user_id, :cargo_id)");
                    $c_inserir->bindParam(':user_id', $id);
                    $c_inserir->bindParam(':cargo_id', $cargo);
                    $c_inserir->execute();
                
                    if($item['advertencia'] < $advertencia){
                        $user_adv = $item['usuario'];
                        $adv_inserir = $pdo->prepare("INSERT INTO aa_notificacao (usuario, texto, visto, time) VALUES(:user_adv, :motivoadv, 'false', :time)");
                        $adv_inserir->bindParam(':user_adv',$user_adv);
                        $adv_inserir->bindParam(':motivoadv',$motivoadv);
                        $adv_inserir->bindParam(':time',$time);
                        $adv_inserir->execute();
                    }
                    echo Site::Alerta('Editado com sucesso!','pagina/'.$url);
                }
            }
        }
    }
?>

<form method="post" autocomplete="off">
    Usuário:<br>
    <input type="text" class="text" readonly value="<?php echo $item['usuario']; ?>"><br>
    IP:<br>
    <input type="text" class="text" readonly value="<?php echo $item['ultimo_ip']; ?>"><br>
    Advertências:<br>
    <select name="advertencia" class="select">
        <option value="0" <?php if($item['advertencia'] == '0'){ echo 'selected'; } ?>>0</option>
        <option value="1" <?php if($item['advertencia'] == '1'){ echo 'selected'; } ?>>1</option>
        <option value="2" <?php if($item['advertencia'] == '2'){ echo 'selected'; } ?>>2</option>
        <option value="3" <?php if($item['advertencia'] == '3'){ echo 'selected'; } ?>>3</option>
    </select><br>
    Motivo Adv.:
    <input type="text" class="text" name="motivoadv"><br>
    Senha (Deixe em branco caso não queira mudar):<br>
    <input type="password" class="text" name="senha"><br>
    PIN (Deixe em branco caso não queira mudar):<br>
    <input type="number" class="text" name="pin" maxlenght="5"><br>
    Status:<br>
    <select name="status" class="select">
        <option value="true" <?php if($item['status'] == 'true'){ echo 'selected'; } ?>>Ativo</option>
        <option value="false"<?php if($item['status'] == 'false'){ echo 'selected'; } ?>>Inativo</option>
    </select><br>
    Cargo:<br>
<?php
    $ver_cargo_c = $pdo->query("SELECT * FROM aa_cargos WHERE ordem >= '".$aa_cargo['ordem']."' ORDER BY ordem ASC");
    while($ver_cargo = $ver_cargo_c->fetch(PDO::FETCH_ASSOC)){
    $item_c = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_cargos c WHERE r.cargo_id=c.cargo_id AND r.user_id='".$id."' ORDER BY c.ordem ASC");
?>
    <label><input type="checkbox" name="cargo[<?php echo $ver_cargo['cargo_id']; ?>]" value="<?php echo $ver_cargo['cargo_id']; ?>" <?php while($item_v = $item_c->fetch(PDO::FETCH_ASSOC)){ if($item_v['cargo_id'] == $ver_cargo['cargo_id']){ echo 'checked'; } }  ?>><?php echo $ver_cargo['cargo']; ?></label><br>
<?php
    }
?>
    <br>
    Banido:<br>
    <select name="banido" class="select">
        <option value="true" <?php if($item['banido'] == 'true'){ echo 'selected'; } ?>>Sim</option>
        <option value="false" <?php if($item['banido'] == 'false'){ echo 'selected'; } ?>>Não</option>
    </select><br>
    Motivo:<br>
    <input type="text" class="text" name="motivo" value="<?php echo $item['motivo']; ?>" autocomplete="off"><br>
    <input type="submit" class="button" value="Editar">
</form>
<?php
}else if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
    $id = (int) $_GET['id'];
    $ver_c = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_cargos c WHERE r.cargo_id=c.cargo_id AND r.user_id='".$id."' ORDER BY c.ordem ASC")->fetch(PDO::FETCH_ASSOC);
    if($aa_data['cargo_id'] == 1){
        $delete = $pdo->query("DELETE FROM aa_usuarios WHERE id='$id'");
        $limpar_cargo = $pdo->query("DELETE FROM aa_usuarios_rel WHERE user_id='$id'");
        echo 1;
    }else if($ver_c['ordem'] > $aa_data['ordem']){
        $delete = $pdo->query("DELETE FROM aa_usuarios WHERE id='$id'");
        $limpar_cargo = $pdo->query("DELETE FROM aa_usuarios_rel WHERE user_id='$id'");
        echo 1;
    }
}else{
?>
<a href="pagina/<?php echo $url;?>/criar"><input type="button" class="button" value="Adicionar Membro"></a>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px;">
    	<th><img src="assets/img/editar.png"></th>
        <th><img src="assets/img/x.png"></th>
        <th>Usuário</th>
        <th>Cargo</th>
        <th>Ultimo Login</th>
        <th>Estado</th>
        <th>Status</th>
    </tr>
    <?php
	$i = 1;
    $sql = $pdo->query("SELECT * FROM aa_usuarios u, aa_usuarios_rel r, aa_cargos c WHERE r.user_id = u.id AND r.cargo_id = c.cargo_id GROUP BY r.user_id ORDER BY c.ordem ASC");
	while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
		$css = $i%2==0 ? '' : 'background: #EEE;';
        $ver_ordem = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_cargos c WHERE r.cargo_id=c.cargo_id AND r.user_id='".$ver['id']."' ORDER BY c.ordem ASC")->fetch(PDO::FETCH_ASSOC);
	   if($aa_cargo['ordem'] == 1){
            $editar = '<a href="pagina/'.$url.'/editar/'.$ver['id'].'"><img src="assets/img/editar.png"></a>';
            $excluir = '<a style="cursor: pointer" onclick="apagar.sim(\''.$ver['id'].'\')"><img src="assets/img/x.png"></a>';
       }else if($aa_cargo['ordem'] < $ver_ordem['ordem']){
            $editar = '<a href="pagina/'.$url.'/editar/'.$ver['id'].'"><img src="assets/img/editar.png"></a>';
            $excluir = '<a style="cursor: pointer" onclick="apagar.sim(\''.$ver['id'].'\')"><img src="assets/img/x.png"></a>';
       }else{
            $editar = '<a style="opacity: 0.5"><img src="assets/img/editar.png"></a>';
            $excluir = '<a style="opacity: 0.5"><img src="assets/img/x.png"></a>';
       }
        $cargo = $pdo->query("SELECT * FROM aa_usuarios_rel r, aa_cargos c WHERE r.cargo_id=c.cargo_id AND r.user_id='".$ver['id']."' ORDER BY c.ordem ASC");
        $ver_status = $pdo->query("SELECT * FROM aa_usuarios WHERE id='".$ver['id']."'")->fetch(PDO::FETCH_ASSOC);
    ?>
    <tr style="height: 40px; <?php echo $css;?>; <?php if($ver_status['status'] == 'false'){ echo 'opacity: 0.5'; } ?>">
    	<th><?php echo $editar ?></th>
        <th><?php echo $excluir ?></th>
        <th><?php echo $ver['usuario'] ?></th>
        <th><?php while($ver_cargo = $cargo->fetch(PDO::FETCH_ASSOC)){ echo $ver_cargo['cargo'].'<br>'; } ?></th>
        <th><?php if($ver['ultimo_time'] == '0'){ echo 'Nunca fez login!'; }else{ echo date('d/m/Y - H:i',@$ver['ultimo_time']); } ?></th>
        <th><?php if($ver['online'] == 'true'){echo '<span style="opacity: 1">Online</span>';}else{echo '<span style="opacity: 0.5">Offline</span>';} ?></th>
        <th><?php if($ver_status['status'] == 'true'){ echo 'Ativo'; }else{ echo 'Inativo'; } ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php } ?>