<?php
    $ver = $pdo->query("SELECT * FROM destaques")->fetch(PDO::FETCH_ASSOC);
	if($_POST){
        $usuario = $_POST['usuario'];
        $u_motivo = $_POST['u_motivo'];
        $membro = $_POST['membro'];
        $m_motivo = $_POST['m_motivo'];
        if(empty($usuario) || empty($u_motivo) || empty($membro) || empty($m_motivo)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
    		$update = $pdo->prepare("UPDATE destaques SET usuario=:usuario, u_motivo=:u_motivo, membro=:membro, m_motivo=:m_motivo");
            $update->bindParam(':usuario',$usuario);
            $update->bindParam(':u_motivo',$u_motivo);
            $update->bindParam(':membro',$membro);
            $update->bindParam(':m_motivo',$m_motivo);
    		$update->execute();
    		echo Site::Alerta('Atualizado com sucesso!','pagina/'.$url);
	   }
    }
?>
<form method="POST" autocomplete="off">
	Usuario:<br>
	<input type="text" class="text" name="usuario" value="<?php echo $ver['usuario'] ?>"><br>
    Motivo:<br>
    <input type="text" class="text" name="u_motivo" value="<?php echo $ver['u_motivo'] ?>"><br>
    Membro:<br>
    <input type="text" class="text" name="membro" value="<?php echo $ver['membro'] ?>"><br>
    Motivo:<br>
    <input type="text" class="text" name="m_motivo" value="<?php echo $ver['m_motivo'] ?>"><br>
	<input type="submit" class="button" value="Atualizar">
</form>