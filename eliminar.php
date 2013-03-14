<?php 
include('../../php/validar_administrador.php');
require ("../../Connections/conexion.php") ;
require ("../../calendario/configuracion.inc.php") ;
include ("../../calendario/constructor.php") ;
include('../../php/funciones_generales.php');
?>
<?php 
if(!isset($id_propuesta) or $id_propuesta==0){
	header ("Location:index.php");
}

function cambiaf_a_normal($fecha){ 
	ereg( "([0-9]{2,4})-([0-9]{1,2})-([0-9]{1,2})", $fecha, $mifecha); 
	$lafecha=$mifecha[3]."/".$mifecha[2]."/".$mifecha[1]; 
	return $lafecha; 
}

//-----------recuperando datos de la solicitud
if (!isset($_POST["enviarform"])){
$sql_propuesta="
		SELECT p.id_propuesta, p.nombre_propuesta, ta.nombre_tipoactividad, p.ano_produccion, a.nombre_area, pa.nombre_pais, p.nombre_ciudad, p.descripcion_propuesta, p.estado, r.id_reserva, r.fecha_reserva, p.leido_usuario
		FROM PROPUESTA p, AREA a, TIPOACTIVIDAD ta, RESERVA r, PAIS pa
		WHERE p.id_propuesta='$id_propuesta' AND p.id_area=a.id_area AND p.id_tipoactividad=ta.id_tipoactividad AND p.id_propuesta=r.id_propuesta AND p.id_pais=pa.id_pais";
$query_propuesta=mysql_query($sql_propuesta) or die(mysql_error());
$row_propuesta=mysql_fetch_array($query_propuesta);

$id_propuesta=$row_propuesta['id_propuesta'];
$nombre_propuesta=htmlentities(utf8_decode($row_propuesta['nombre_propuesta']));
$tipoactividad=utf8_decode($row_propuesta['nombre_tipoactividad']);
$area=utf8_decode($row_propuesta['nombre_area']);
$estado=$row_propuesta['estado'];
$id_reserva=$row_propuesta['id_reserva'];
$fecha_reserva=substr($row_propuesta['fecha_reserva'],0,10);//solo una parte del timestamp
$fecha_mostrar=cambiaf_a_normal($fecha_reserva);
$ano_produccion=$row_propuesta['ano_produccion'];
$pais=utf8_decode($row_propuesta['nombre_pais']);
$ciudad=utf8_decode($row_propuesta['nombre_ciudad']);
$descripcion=nl2br(utf8_decode($row_propuesta['descripcion_propuesta']));
$leido=$row_propuesta['leido_usuario'];

if($leido==0){
	$sql_leido="UPDATE PROPUESTA SET leido_usuario='1'
	WHERE id_propuesta='$id_propuesta'";
	$query_leido=mysql_query($sql_leido) or die(mysql_error());  
}
		
//para observaciones si es 
if($estado=="Aprobado" or $estado=="Rechazado" or $estado=="Suspendido"){
	$sql_observaciones="SELECT h.observaciones
	FROM HISTORIAL_PROPUESTA h
	WHERE h.id_propuesta='$id_propuesta' AND h.operacion='$estado'
	ORDER BY fecha_hora DESC";
	$query_observaciones=mysql_query($sql_observaciones) or die(mysql_error());
	$row_observaciones=mysql_fetch_assoc($query_observaciones);
	$observaciones=nl2br(utf8_decode($row_observaciones['observaciones']));
}

/*Saco las salas reservadas*/
$sql_salas="SELECT a.id_ambiente, nombre_ambiente, codigo_ambiente
FROM RESERVA r, RESERVAAMBIENTE ra, AMBIENTE a 
WHERE r.id_propuesta='$id_propuesta' AND r.id_reserva=ra.id_reserva AND ra.id_ambiente=a.id_ambiente";
$query_salas=mysql_query($sql_salas) or die(mysql_error());
}
//-----------Fin de recuperar datos de la solicitud

//eliminar registros. para usuario eliminar:PROPUESTA, RESERVA, RESERVAAMBIENTE e insertar en HISTORIAL_PROPUESTA
if(isset($_POST['enviarform']) && $_POST['enviarform']!=''){
	//delete propuesta
	$sql_propuesta="DELETE FROM PROPUESTA WHERE id_propuesta='$id_propuesta'";
	$query_propuesta=mysql_query($sql_propuesta) or die(mysql_error());
	
	//delete RESERVA
		//antes obtener id_reserva
		$sql_id_reserva="SELECT id_reserva FROM RESERVA WHERE id_propuesta='$id_propuesta'";
		$query_id_reserva=mysql_query($sql_id_reserva) or die(mysql_error());
		$row_id_reserva=mysql_fetch_assoc($query_id_reserva);
		$id_reserva=$row_id_reserva['id_reserva'];
		
	$sql_reserva="DELETE FROM RESERVA WHERE id_propuesta='$id_propuesta'";
	$query_reserva=mysql_query($sql_reserva) or die(mysql_error());
	
	//delete RESERVAAMBIENTE
	$sql_reservaambi="DELETE FROM RESERVAAMBIENTE WHERE id_reserva='$id_reserva'";
	$query_reservaambi=mysql_query($sql_reservaambi) or die(mysql_error());
	
	//insertar en el registro
	$id_usuario=$_SESSION['MM_IdUsuario'];//el usuario que está logueado
	$operacion="Eliminado";
	$fecha_hora=date("Y-m-d H:i:s");
	$observaciones="";
	$sql_historial="INSERT INTO HISTORIAL_PROPUESTA (id_propuesta, id_usuario, fecha_hora, operacion, observaciones) values ('$id_propuesta', '$id_usuario','$fecha_hora', '$operacion', '$observaciones')";
	$query_historial=mysql_query($sql_historial) or die(mysql_error());
	
	header ("Location:index.php");
	
}

?>
<?php
	if (!isset($_SESSION)) {
  	session_start();
	}
	
	require('../../libs/Smarty.class.php');
	$smarty = new Smarty;

	$smarty->template_dir =	'../../templates/';
	$smarty->compile_dir =	'../../templates_c/';
	$smarty->config_dir =	'../../configs/';
	$smarty->cache_dir =	'../../cache/';

	$page = 'solicitud';
	$path='../../';
	if (isset ($_GET['page'])) {
		$page = $_GET['page'];
	}
	
	$smarty -> assign ('page', $page);
	$smarty -> assign ('path', $path);
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<meta http-equiv="Content-Language" content="es" />
<link rel="stylesheet" type="text/css" href="../../css/schemes/default.css" />
<link rel="stylesheet" type="text/css" href="../../css/fonts/default.css" />
<link rel="stylesheet" type="text/css" href="../../css/borders/default.css" />
<link rel="stylesheet" type="text/css" href="../../css/colors/default.css" />
<link rel="stylesheet" type="text/css" href="../../css/aligns/default.css" />
<link rel="stylesheet" type="text/css" href="../../css/menuhoriz/menuhoriz.css" />

<link rel="shortcut icon" href="../../imagenes/favicon.ico" type="image/x-icon" />

<script type="text/javascript" src="../../javascript/utils.js"></script>
<script type="text/javascript" src="../../javascript/prototype.js"></script>
<script type="text/javascript" src="../../javascript/scriptaculous.js"></script>
<script type="text/javascript" src="../../javascript/effects.js"></script>

<title>Detalle de Propuesta</title>
</head>
<body>
<a id="head" name="head"></a> 
<div id="wrapper1"> 
  <div id="wrapper2"> 
    <div id="header" style="margin-top:-4px;"> 
      <?php					$smarty -> display ('_header.html'); ?> 
    </div> 
    <div id="container"> 
      <div id="bar"> 
        <?php					$smarty -> display ('_bar.html'); ?> 
      </div> 
      <div id="tools">
    <?php					
						if(isset($_SESSION["MM_Username"])){
						$smarty -> assign ('session', 'abierto');
						}
					    $smarty -> assign ('nombre', $_SESSION['MM_NombrePersona']);
						$smarty -> assign ('rol', $_SESSION['MM_Rol']);
						$smarty -> display ('_tools.html'); ?>
  </div> 
      <div id="extra"> 
        <div id="menu"> 
          <?php					$smarty -> display ('_adminmenu.html'); ?> 
        </div> 
      </div>
      <div id="main"> 
        <div id="title"> 
          <h1>Eliminar Solicitud </h1> 
        </div> 
        <div id="formularios"> 
          <div class="form_presentacion">
		  	<table width="100%" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="form_presentacion_font"><img src="../../imagenes/iconos/alert_icon.gif" alt=" " width="122" height="120" /></td>
					<td class="form_presentacion_font"><span class="alerta">¿Está seguro de eliminar la siguiente solicitud?</span></td>
				</tr>
			</table>
		  </div>
          <form id="delete_form" action="<?php $_SERVER['PHP_SELF'] ?>" method="post">
		  	<input type="hidden" name="id_propuesta" value="<?php echo $id_propuesta ?>" />
			<input type="hidden" name="enviarform" value="1" />
 			<table align="center" width="459" cellpadding="2" cellspacing="2" border="0">
			  <tr>
				<td>Nombre:</td>
				<td><?php echo $nombre_propuesta ?></td>
			  </tr>
			  <tr>
			  	<td>Estado:</td>
				<td><?php echo $estado ?></td>
			  </tr>
			  <?php if($estado=="Aprobado" or $estado=="Rechazado" or $estado=="Suspendido"){?>
			  <tr>
			  	<td>Observaciones</td>
				<td>
				<?php if($observaciones!="") echo $observaciones;
						else echo "----";?>				</td>
			  </tr>
			  <?php }?>
			  <tr>
				<td>Tipo de Actividad:</td>
				<td><?php echo $tipoactividad ?></td>
			  </tr>
			  <?php if($ano_produccion!=0){ ?>
			  <tr>
				<td>A&ntilde;o de producci&oacute;n:</td>
				<td><?php echo $ano_produccion ?></td>
			  </tr>
			  <?php } ?>
			  <tr>
				<td>&Aacute;rea:</td>
				<td><?php echo $area ?></td>
			  </tr>
			  <tr>
				<td>Pa&iacute;s:</td>
				<td><?php echo $pais ?></td>
			  </tr>
			  <?php if($ciudad!=''){ ?>
			  <tr>
				<td>Ciudad:</td>
				<td><?php echo $ciudad ?></td>
			  </tr>
			  <?php } ?>
			  <tr>
				<td valign="top">Descripci&oacute;n:</td>
				<td><?php echo $descripcion ?></td>
			  </tr>
			  <tr>
				<td>Fecha de envio:</td>
				<td><?php echo $fecha_mostrar ?></td>
			  </tr>
			  <tr>
				<td valign="top">Salas reservadas:</td>
				<td><ul style="float:left; ">
					<?php 
					while($row_salas=mysql_fetch_assoc($query_salas)){
						$nombre_sala=utf8_decode($row_salas['nombre_ambiente']);
						$codigo_sala=strtolower($row_salas['codigo_ambiente']);	
					?>
						<li><?php echo "$nombre_sala [$codigo_sala]" ?></li>
					<?php }	?>
					</ul>
					<img src="../../imagenes/iconos/espacios_form.png" alt="espacios martadero" width="121" height="179" style="float:right; top:-30px;"/>				</td>
			  </tr>
			  <tr>
						<td valign="top">fechas reservadas:</td>
						<td>
							<?php
			$sql_fechas="
			SELECT DISTINCT date_format(fecha,'%c'), date_format(fecha,'%Y')
			FROM FECHA
			WHERE id_propuesta='$id_propuesta'
			ORDER BY fecha ASC";
			$query_fechas=mysql_query($sql_fechas) or die(mysql_error());
			$num_fechas=mysql_num_rows($query_fechas);
			while($row_fechas=mysql_fetch_array($query_fechas)){
			$mes=$row_fechas[0];
			$ano=$row_fechas[1];			
			mes_sin_ambiente($mes,$ano,true,$id_propuesta,$estado) ;
			}
			?>						</td>
			  </tr>
		  </table>
			<!--<div style="clear:both"></div>-->
		  <div align="center" style="margin-top:20px">
		  		  <input name="eliminar" type="submit" value="Eliminar"/>
				  <input name="volver" type="button" value="Cancelar" onClick="javascript: window.location.href='index.php'"/>
		  </div>
		  </form>			
        </div> 
      </div> 
    </div> 
    <div id="footer"> 
      <?php					$smarty -> display ('_footer.html'); ?> 
    </div> 
  </div> 
</div> 
</body>
</html>