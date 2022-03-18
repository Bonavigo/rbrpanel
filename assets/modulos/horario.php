<script>
	var horario = {
		marcar:function(dia,hora){
        	$.ajax({
                type:'GET',
                url:'pagina/<?php echo $url; ?>/marcar/'+dia+'/'+hora,
                data:{'dia':dia, 'hora':hora},
                success:function(html){
                   alert('Marcado com sucesso!');
                    location.reload();
                }
            });
        }
	}
</script>

<?php
if(isset($_GET['tipo']) && $_GET['tipo'] == 'marcar'){
	$dia_m = $_GET['dia'];
	$hora_m = $_GET['hora'];
	$marcado = $pdo->query("SELECT * FROM aa_horarios WHERE user_id = '0' AND dia = '".$dia_m."' AND hora = '".$hora_m."'")->rowCount();
	if($marcado > 0){
		$horario_m = $pdo->query("UPDATE aa_horarios SET user_id='".$aa_data['id']."' WHERE dia='$dia_m' AND hora='$hora_m'");
		echo 1;
	}
}

	$dia_ingles = date('l',@time());
	if(!isset($_GET['dia'])){	
		if($dia_ingles == 'Sunday'){
			$hor_dia = '7';
		}else if($dia_ingles == 'Monday'){
			$hor_dia = '1';
		}else if($dia_ingles == 'Tuesday'){
			$hor_dia = '2';
		}else if($dia_ingles == 'Wednesday'){
			$hor_dia = '3';
		}else if($dia_ingles == 'Thursday'){
			$hor_dia = '4';
		}else if($dia_ingles == 'Friday'){
			$hor_dia = '5';
		}else if($dia_ingles == 'Saturday'){
			$hor_dia = '6';
		}
	}else{
		$hor_dia = $_GET['dia'];
	}
?>
<table width="100%" style="margin: 10px 0 0 0; float:left">
	<tr style="height: 40px;">
       	<th><a <?php if($hor_dia == '1'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/1">Segunda-Feira</a></th>
        <th><a <?php if($hor_dia == '2'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/2">Terça-Feira</a></th>
        <th><a <?php if($hor_dia == '3'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/3">Quarta-Feira</a></th>
        <th><a <?php if($hor_dia == '4'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/4">Quinta-Feira</a></th>
        <th><a <?php if($hor_dia == '5'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/5">Sexta-Feira</a></th>
        <th><a <?php if($hor_dia == '6'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/6">Sabado</a></th>
        <th><a <?php if($hor_dia == '7'){ echo 'style="color: #888"'; }else{ echo 'style="color: #000"';} ?> href="pagina/<?php echo $url ?>/dia/7">Domingo</a></th>
    </tr>
</table>
<table width="100%" style="margin: 10px 0 0 0; float:left">
     <tr style="height: 40px;">
       	<th>Horário</th>
        <th>Locutor</th>
        <th>Marcar/Desmarcar</th>
    </tr>
<?php
	$horarios = $pdo->query("SELECT * FROM aa_horarios WHERE dia='$hor_dia'");
	while($ver = $horarios->fetch(PDO::FETCH_ASSOC)){
	if($ver['hora'] == '0'){
		$hora = '00:00 ~ 01:00';
	}else if($ver['hora'] == '1'){
		$hora = '01:00 ~ 02:00';
	}else if($ver['hora'] == '2'){
		$hora = '02:00 ~ 03:00';
	}else if($ver['hora'] == '3'){
		$hora = '03:00 ~ 04:00';
	}else if($ver['hora'] == '4'){
		$hora = '04:00 ~ 05:00';
	}else if($ver['hora'] == '5'){
		$hora = '05:00 ~ 06:00';
	}else if($ver['hora'] == '6'){
		$hora = '06:00 ~ 07:00';
	}else if($ver['hora'] == '7'){
		$hora = '07:00 ~ 08:00';
	}else if($ver['hora'] == '8'){
		$hora = '08:00 ~ 09:00';
	}else if($ver['hora'] == '9'){
		$hora = '09:00 ~ 10:00';
	}else if($ver['hora'] == '10'){
		$hora = '10:00 ~ 11:00';
	}else if($ver['hora'] == '11'){
		$hora = '11:00 ~ 12:00';
	}else if($ver['hora'] == '12'){
		$hora = '12:00 ~ 13:00';
	}else if($ver['hora'] == '13'){
		$hora = '13:00 ~ 14:00';
	}else if($ver['hora'] == '14'){
		$hora = '14:00 ~ 15:00';
	}else if($ver['hora'] == '15'){
		$hora = '15:00 ~ 16:00';
	}else if($ver['hora'] == '16'){
		$hora = '16:00 ~ 17:00';
	}else if($ver['hora'] == '17'){
		$hora = '17:00 ~ 18:00';
	}else if($ver['hora'] == '18'){
		$hora = '18:00 ~ 19:00';
	}else if($ver['hora'] == '19'){
		$hora = '19:00 ~ 20:00';
	}else if($ver['hora'] == '20'){
		$hora = '20:00 ~ 21:00';
	}else if($ver['hora'] == '21'){
		$hora = '21:00 ~ 22:00';
	}else if($ver['hora'] == '22'){
		$hora = '22:00 ~ 23:00';
	}else if($ver['hora'] == '23'){
		$hora = '23:00 ~ 00:00';
	}

	if($ver['user_id'] == 0){
		$locutor = 'AutoDJ';
	}else{
		$locutor_c = $pdo->query("SELECT * FROM aa_usuarios WHERE id = '".$ver['user_id']."'")->fetch(PDO::FETCH_ASSOC);
		$locutor = $locutor_c['usuario'];
	}

	if($locutor == 'AutoDJ'){
		$marcar = '<a style="cursor: pointer" onclick="horario.marcar('.$hor_dia.','.$ver['hora'].')">Marcar Horário</a>';
	}else{
		$marcar = '<a style="color: #888">Horário Marcado</a>';
	}
?>
	<tr style="height: 40px; background: #EEE">
       	<th><?php echo $hora ?></th>
        <th><?php echo $locutor ?></th>
        <th><?php echo $marcar ?></th>
    </tr>
<?php
	}
?>
</table>