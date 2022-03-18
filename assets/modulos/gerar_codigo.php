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
    $codigo = rand(10000,99999);
	if($_POST){
        $valor = $_POST['valor'];
        $estoque = $_POST['estoque'];
        if(empty($valor) || empty($estoque)){
            echo Site::Alerta('Preencha todos campos!',false);
        }else{
    		$inserir = $pdo->prepare("INSERT INTO moedas(valor, codigo, estoque) VALUES(:valor, :codigo, :estoque)");
            $inserir->bindParam(':valor',$valor);
            $inserir->bindParam(':codigo',$codigo);
            $inserir->bindParam(':estoque',$estoque);
    		$inserir->execute();

            $logs = $pdo->prepare("INSERT INTO aa_logs_moedas(codigo, valor, autor, time) VALUES(:codigo, :valor, :estoque, '".$aa_data['usuario']."', '".time()."')");
            $logs->bindParam(':valor',$valor);
            $logs->bindParam(':codigo',$codigo);
            $logs->bindParam(':estoque',$estoque);
            $logs->execute();

    		echo Site::Alerta('Criado com sucesso!','pagina/'.$url);
	   }
    }
    if(isset($_GET['tipo']) && $_GET['tipo'] == 'apagar'){
        $id = (int) $_GET['id'];
        $delete = $pdo->query("DELETE FROM moedas WHERE id='$id'");
        echo 1;
    }else{
?>
<form method="POST" autocomplete="off">
	Código:<br>
	<input type="text" class="text" value="<?php echo $codigo ?>"><br>
    Valor:<br>
    <input type="number" min="1" class="text" name="valor"><br>
    Estoque:<br>
    <input type="number" min="1" class="text" name="estoque"><br>
	<input type="submit" class="button" value="Criar">
</form>
<table width="100%" style="margin: 10px 0 0 0; float:left">
    <tr style="height: 40px;">
        <th><img src="assets/img/x.png"></th>
        <th>Código</th>
        <th>Valor</th>
        <th>Estoque</th>
    </tr>
    <?php
    $i = 1;
    $sql = $pdo->query("SELECT * FROM moedas ORDER BY id DESC");
    while($ver = $sql->fetch(PDO::FETCH_ASSOC)){
        $css = $i%2==0 ? '' : 'background: #EEE;';
        $estoque = $pdo->query("SELECT * FROM moedas_usadas WHERE id='".$ver['id']."'")->rowCount();
    ?>
    <tr style="height: 40px; <?php echo $css;?>">
        <th><a style="cursor: pointer" onclick="apagar.sim('<?php echo $ver['id']; ?>')"><img src="assets/img/x.png"></a></th>
        <th><?php echo $ver['codigo']; ?></th>
        <th><?php echo $ver['valor']; ?></th>
        <th><?php echo $ver['estoque']-$estoque.'/'.$ver['estoque']; ?></th>
    </tr>
    <?php $i++;} ?>
</table>
<?php } ?>