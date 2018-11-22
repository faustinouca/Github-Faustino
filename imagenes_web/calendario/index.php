<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Calendario PHP</title>
	<link rel="STYLESHEET" type="text/css" href="estilo.css">
</head>

<body>
<div align="center">
<?php
require ("calendario.php");
// Dependiendo de si llega el mes y el año por $POST, $GET o si no toma la fecha actual
$dia_del_evento = 0;

if ($_POST) {
	$mes = $_POST["nuevo_mes"]; 
	$ano = $_POST["nuevo_ano"];
}elseif ($_GET){
	$mes = $_GET["nuevo_mes"];
	$ano = $_GET["nuevo_ano"];
}else{
	$tiempo_actual = time();
	$mes = date("n", $tiempo_actual);
	$ano = date("Y", $tiempo_actual);
}

mostrar_calendario($mes,$ano);
formularioCalendario($mes,$ano);

if (isset($_GET["dia"])){
	//Cuando viene definido el día 
	//Mostrar los datos en formulario	
	$dia_del_evento = $_GET["dia"];
	$hora_evento = $_GET["hora"];
	$comentario_evento = $_GET["comentario"];
//	echo $contador;
	?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post">
		<div class="campoform">
			Dia: 
			<input type="text" name="dia_del_evento" size=2 maxlength="2" value="<?php echo $dia_del_evento . ' ';?>"/>			
			Horario : 
			<input type="text" name="hora_del_evento" size=3 maxlength="4" value="<?php echo $hora_evento;?>"/>
		</div>
			<div class="campoform">
			Evento :
			<br />
			<textarea cols=18 rows=5 name="cuerpo_evento"/><?php echo $comentario_evento;?></textarea>
		</div>
	</form>	
<?php	
}
?>
</div>
</body>
</html>
