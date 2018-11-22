<?php
function calcula_numero_dia_semana($dia,$mes,$ano){
	$numerodiasemana = date('w', mktime(0,0,0,$mes,$dia,$ano));
	if ($numerodiasemana == 0) 
		$numerodiasemana = 6;
	else
		$numerodiasemana--;
	return $numerodiasemana;
}

//funcion que devuelve el último día de un mes y año dados
function ultimoDia($mes,$ano){
	$ultimo_dia=28;
	while (checkdate($mes,$ultimo_dia,$ano)){
	        $ultimo_dia++;
	}    
	$ultimo_dia--;
	return $ultimo_dia;
}

function dame_nombre_mes($mes){
	 switch ($mes){
	 	case 1:
			$nombre_mes="Enero";
			break;
	 	case 2:
			$nombre_mes="Febrero";
			break;
	 	case 3:
			$nombre_mes="Marzo";
			break;
	 	case 4:
			$nombre_mes="Abril";
			break;
	 	case 5:
			$nombre_mes="Mayo";
			break;
	 	case 6:
			$nombre_mes="Junio";
			break;
	 	case 7:
			$nombre_mes="Julio";
			break;
	 	case 8:
			$nombre_mes="Agosto";
			break;
	 	case 9:
			$nombre_mes="Septiembre";
			break;
	 	case 10:
			$nombre_mes="Octubre";
			break;
	 	case 11:
			$nombre_mes="Noviembre";
			break;
	 	case 12:
			$nombre_mes="Diciembre";
			break;
	}
	return $nombre_mes;
}


function mostrar_calendario($mes,$ano){
	//tomo el nombre del mes que hay que imprimir
	$nombre_mes = dame_nombre_mes($mes);
	
	//construyo la tabla general
	echo '<table class="tablacalendario" cellspacing="3" cellpadding="2" border="0">';
	echo '<tr><td colspan="7" class="tit">';
	//tabla para mostrar el mes el año y los controles para pasar al mes anterior y siguiente
	echo '<table width="100%" cellspacing="2" cellpadding="2" border="0"><tr><td class="messiguiente">';
	//calculo el mes y ano del mes anterior
	$mes_anterior = $mes - 1;
	$ano_anterior = $ano;
	if ($mes_anterior==0){
		$ano_anterior--;
		$mes_anterior=12;
	}
	//Construimos el enlace $GET para la flecha anterior
	echo '<a href="index.php?nuevo_mes=' . $mes_anterior . '&nuevo_ano=' . $ano_anterior .'"><span>&lt;&lt;</span></a></td>'; 
	   echo '<td class="titmesano">' . $nombre_mes . " " . $ano . '</td>';
	   echo '<td class="mesanterior">';
	//calculo el mes y ano del mes siguiente
	$mes_siguiente = $mes + 1;
	$ano_siguiente = $ano;
	if ($mes_siguiente==13){
		$ano_siguiente++;
		$mes_siguiente=1;
	}
	//Construimos el enlace $GET para la flecha posterior
	echo '<a href="index.php?nuevo_mes=' . $mes_siguiente . '&nuevo_ano=' . $ano_siguiente . '"><span>&gt;&gt;</span></a></td>';
	//finalizo la tabla de cabecera
	echo '</tr></table>';
	echo '</td></tr>';
	//fila con todos los días de la semana
	echo '	<tr>
				<td width="14%" class="diasemana"><span>L</span></td>
				<td width="14%" class="diasemana"><span>M</span></td>
				<td width="14%" class="diasemana"><span>X</span></td>
				<td width="14%" class="diasemana"><span>J</span></td>
				<td width="14%" class="diasemana"><span>V</span></td>
				<td width="14%" class="diasemana"><span>S</span></td>
				<td width="14%" class="diasemana"><span>D</span></td>
			</tr>';
	
	//Variable para llevar la cuenta del dia actual
	$dia_actual = 1;
	
	//calculo el numero del dia de la semana del primer dia
	$numero_dia = calcula_numero_dia_semana(1,$mes,$ano);
	//echo "Numero del dia de demana del primer: $numero_dia <br>";
	
	//calculo el último dia del mes
	$ultimo_dia = ultimoDia($mes,$ano);

		
	//Ajustamos el primer y último día del mes para hayar los eventos
	$primer_dia_mes = date('Y-m-d', mktime(0,0,0, $mes, 1, $ano));
	$ultimo_dia_mes = date('Y-m-d', mktime(0,0,0, $mes, $ultimo_dia, $ano));

	//Ahora accedo a la base de datos y obtengo en un array los números de todos los días con eventos y guardar en 
	//otros arrays las horas y los comentarios	
	$eventos_mes = encontrar_eventos($primer_dia_mes, $ultimo_dia_mes);
	
	//Después al imprimirlo en pantalla tengo que comparar los dias con el array y si coinciden rellenar un formulario con la información
	//cuando se pulse sobre el día

	//escribo la primera fila de la semana

	echo "<tr>";

	for ($i=0;$i<7;$i++){
		if ($i < $numero_dia){
			//si el dia de la semana i es menor que el numero del primer dia de la semana no pongo nada en la celda
			echo '<td class="diainvalido"><span></span></td>';
		} else {
			echo tipo_dia($eventos_mes, $dia_actual) . 'nuevo_mes=' . $mes . '&nuevo_ano=' . $ano  . '">' . $dia_actual . '</span></td>';		
			$dia_actual++;
		}
	}
	echo "</tr>";
	
	//recorro todos los demás días hasta el final del mes
	$numero_dia = 0;
	while ($dia_actual <= $ultimo_dia){
		//si estamos a principio de la semana escribo el <TR>
		if ($numero_dia == 0)
			echo "<tr>";
		
		echo tipo_dia($eventos_mes, $dia_actual) . 'nuevo_mes=' . $mes . '&nuevo_ano=' . $ano . '">' . $dia_actual . '</span></td>';
		$dia_actual++;
		$numero_dia++;
		//si es el uñtimo de la semana, me pongo al principio de la semana y escribo el </tr>
		if ($numero_dia == 7){
			$numero_dia = 0;
			echo "</tr>";
		}
	}
	
	//compruebo que celdas me faltan por escribir vacias de la última semana del mes
	for ($i=$numero_dia;$i<7;$i++){
		echo '<td class="diainvalido"><span></span></td>';
	}
	
	echo "</tr>";
	echo "</table>";
}


function formularioCalendario($mes,$ano){
echo '
	<table class="tablacalendario" align="center" cellspacing="2" cellpadding="2" border="0">
	<tr><form action="index.php" method="POST">';
echo '
    <td align="center" valign="top">
		Mes: <br>
		<select name=nuevo_mes>
		<option value="1"';
if ($mes==1)
 echo "selected";
echo'>Enero</option>
		<option value="2" ';
if ($mes==2) 
	echo "selected";
echo'>Febrero</option>
		<option value="3" ';
if ($mes==3) 
	echo "selected";
echo'>Marzo</option>
		<option value="4" ';
if ($mes==4) 
	echo "selected";
echo '>Abril</option>
		<option value="5" ';
if ($mes==5) 
		echo "selected";
echo '>Mayo</option>
		<option value="6" ';
if ($mes==6) 
	echo "selected";
echo '>Junio</option>
		<option value="7" ';
if ($mes==7) 
	echo "selected";
echo '>Julio</option>
		<option value="8" ';
if ($mes==8) 
	echo "selected";
echo '>Agosto</option>
		<option value="9" ';
if ($mes==9) 
	echo "selected";
echo '>Septiembre</option>
		<option value="10" ';
if ($mes==10) 
	echo "selected";
echo '>Octubre</option>
		<option value="11" ';
if ($mes==11) 
	echo "selected";
echo '>Noviembre</option>
		<option value="12" ';
if ($mes==12) 
    echo "selected";
echo '>Diciembre</option>
		</select>
		</td>';
echo '		
	    <td align="center" valign="top">
		A&ntilde;o: <br>
		<select name=nuevo_ano>
	';
//este bucle se podría hacer dependiendo del número de año que se quiera mostrar
//yo voy a mostar 10 años atrás y 10 adelante de la fecha mostrada en el calendario
for ($anoactual=$ano-10; $anoactual<=$ano+10; $anoactual++){
	echo '<option value="' . $anoactual . '" ';
	if ($ano==$anoactual) {
		echo "selected";
	}
	echo '>' . $anoactual . '</option>';
}
echo '</select>
		</td>';
echo '
	</tr>
	<tr>
	    <td colspan="2" align="center"><input type="Submit" class="boton" value="[ IR A ESE MES ]"></td>
	</tr>
	</table>

	</form>';
}

function conecta_base_datos(){
	$conexion = mysql_connect("localhost", "root", "");
	mysql_select_db("hermandad", $conexion);
	return $conexion;
}

function encontrar_eventos($fecha_desde, $fecha_hasta){
	$dias = array();
	$conexion = conecta_base_datos();
	$ssql = "SELECT * FROM eventos WHERE fecha_evento BETWEEN '$fecha_desde' AND '$fecha_hasta'";
	$rs = mysql_query($ssql);
	while($evento = mysql_fetch_array($rs)){
		//$dias_eventos debe de estar definida en index para que se vea desde todos sitios
		//$dias_eventos debe ser un array de dos dimensiones y recoger todos los datos de los dias con eventos del mes
		$dias[] = (int)date("d", strtotime($evento["fecha_evento"]));
		$dias[] = $evento["hora_evento"];
		$dias[] = $evento["comentario_evento"];
	}
//	echo count($dias_eventos) . "<br>";
//	for ($i=0; $i<count($dias_eventos); $i++){
//			echo $dias_eventos[$i] . "<br>";
//	}

	mysql_close($conexion);
	return $dias;
}

function tipo_dia($eventos, $dia){
	for ($i=0; $i<count($eventos); $i = $i + 3){
		//Solamente el dia del evento es un dato numerico en el array
		if ($eventos[$i] == $dia){
			return '<td class="diaevento"><span><a href="index.php?dia=' . $dia . '&hora=' . $eventos[$i + 1] . '&comentario=' . $eventos[$i + 2] . '&';
		}
	}
	return '<td class="diavalido"><span><a href="index.php?';	
}				