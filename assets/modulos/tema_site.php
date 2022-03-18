<?php
    $item = $pdo->query("SELECT * FROM tema")->fetch(PDO::FETCH_ASSOC);
    if($_POST){
        $primaria = $_POST['cor_primaria'];
        $secundaria = $_POST['cor_secundaria'];
        $terciaria = $_POST['cor_terciaria'];
        $header = $_POST['background'];
        $rolar = $_POST['rolar'];
        $rep = $_POST['repetir'];
        $inserir = $pdo->prepare("UPDATE tema SET cor_primaria=:cor_primaria, cor_secundaria=:cor_secundaria, cor_terciaria=:cor_terciaria, background=:background, rolar=:rolar, repetir=:repetir");
        $inserir->bindParam(':cor_primaria',$primaria);
        $inserir->bindParam(':cor_secundaria',$secundaria);
        $inserir->bindParam(':cor_terciaria',$terciaria);
        $inserir->bindParam(':background',$header);
        $inserir->bindParam(':rolar',$rolar);
        $inserir->bindParam(':repetir',$rep);
        $inserir->execute();
        echo Site::Alerta('Atualizado com sucesso!','pagina/'.$url);
    }
?>
<form method="post" autocomplete="off">
    Paleta de Cores<br>
    <input id="background-color" type="color" onchange="javascript:document.getElementById('chosen-color').value = document.getElementById('background-color').value;">&nbsp;&nbsp;<input type="text" class="text" readonly id="chosen-color" value="#000000"/><br>
    Cor Primária (TOM NORMAL):<br>Oficial: ...<br>
        <input type="color" disabled value="<?php echo $item['cor_primaria']; ?>"/>
        <input type="text" class="text" id="cor_primaria" onchange="javascript:document.getElementById('new_primaria').value = document.getElementById('cor_primaria').value;" name="cor_primaria" value="<?php echo $item['cor_primaria']; ?>">
        <input type="color" id="new_primaria" disabled value="<?php echo $item['cor_primaria']; ?>"/><br>
    Cor Secundária (TOM ESCURO):<br>Oficial: ...<br>
        <input type="color" disabled value="<?php echo $item['cor_secundaria']; ?>"/>
        <input type="text" class="text" id="cor_secundaria" onchange="javascript:document.getElementById('new_secundaria').value = document.getElementById('cor_secundaria').value;" name="cor_secundaria" value="<?php echo $item['cor_secundaria']; ?>">
        <input type="color" id="new_secundaria" disabled value="<?php echo $item['cor_secundaria']; ?>"/><br>
    Cor Terciária (TOM CLARO):<br>Oficial: ...<br>
        <input type="color" disabled value="<?php echo $item['cor_terciaria']; ?>"/>
        <input type="text" class="text" id="cor_terciaria" onchange="javascript:document.getElementById('new_terciaria').value = document.getElementById('cor_terciaria').value;" name="cor_terciaria" value="<?php echo $item['cor_terciaria']; ?>">
        <input type="color" id="new_terciaria" disabled value="<?php echo $item['cor_terciaria']; ?>"/><br>
    Fundo do Topo:<br>Oficial: ...<br>
    <input type="text" class="text" name="background" value="<?php echo $item['background']; ?>"><br>
    Fundo Rolando:<br>Oficial: ...<br>
    <select name="rolar" class="select">
        <option <?php if($item['rolar'] == "true"){ echo "selected"; } ?> value="true">Sim</option>
        <option <?php if($item['rolar'] == "false"){ echo "selected"; } ?> value="false">Não</option>
    </select><br>
    Fundo Repetindo:<br>Oficial: ...<br>
    <select name="repetir" class="select">
        <option <?php if($item['repetir'] == "true"){ echo "selected"; } ?> value="true">Sim</option>
        <option <?php if($item['repetir'] == "false"){ echo "selected"; } ?> value="false">Não</option>
    </select><br>
    <input type="submit" class="button" value="Editar">
</form>