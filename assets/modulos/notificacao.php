<?php    
	if($_POST){
        $usuario = $_POST['usuario'];
        $texto = strip_tags($_POST['texto']);
        if(empty($usuario) || empty($texto)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
    		$inserir = $pdo->prepare("INSERT INTO aa_notificacao(usuario, texto, time, visto) VALUES(:usuario, :texto, '".time()."', 'false')");
    		$inserir->bindParam(':usuario',$usuario);
            $inserir->bindParam(':texto',$texto);
    		$inserir->execute();
    		echo Site::Alerta('Enviado com sucesso!','pagina/'.$url);
	   }
    }
?>
<form method="POST" autocomplete="off">
	Usuário:<br>
	<input type="text" class="text" name="usuario"><br>
    Texto:<br>
    <input type="text" class="text" name="texto"><br>
	<input type="submit" class="button" value="Enviar">
</form>