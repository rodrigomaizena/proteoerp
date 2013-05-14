<?php
$maxlin=33; //Maximo de lineas de items.

if(count($parametros) < 0) show_error('Faltan parametros');
$id=$parametros[0];

$sel=array('a.tipo_doc','a.numero','a.cod_cli','a.fecha','a.monto','a.abonos','a.tipo_doc'
,'b.nombre','TRIM(b.nomfis) AS nomfis','CONCAT_WS(\'\',TRIM(b.dire11),b.dire12) AS direc','b.rifci'
,'CONCAT_WS(\' \',observa1,observa2) AS observa','b.rifci','a.transac');
$this->db->select($sel);
$this->db->from('smov AS a');
$this->db->join('scli AS b'  ,'a.cod_cli=b.cliente');
$this->db->where('a.id'   , $id);
$this->db->where('a.tipo_doc','AN');

$mSQL_1 = $this->db->get();
if($mSQL_1->num_rows()==0) show_error('Registro no encontrado');

$row = $mSQL_1->row();
$tipo_doc = trim($row->tipo_doc);
$numero   = $row->numero;
$cliente  = htmlspecialchars(trim($row->cod_cli));
$tipo_doc = trim($row->tipo_doc);
$fecha    = $row->fecha;
$hfecha   = dbdate_to_human($row->fecha);
$monto    = nformat($row->monto);
$montole  = strtoupper(numletra($row->monto));
$abonos   = $row->abonos;
$nombre   = (empty($row->nomfis))? htmlspecialchars(trim($row->nombre)) : htmlspecialchars($row->nomfis);
$rifci    = trim($row->rifci);
$direc    = htmlspecialchars(trim($row->direc));
$observa  = wordwrap(trim(str_replace(',',', ',$row->observa)), 100, '<br>');
$transac  = $row->transac;


$sel=array('a.tipo','a.monto','a.num_ref','a.fecha','a.cambio','b.nomb_banc AS banco');
$this->db->select($sel);
$this->db->from('sfpa AS a');
$this->db->join('tban AS b','a.banco=b.cod_banc','left');
$this->db->where('a.transac',$transac);
$mSQL_3 = $this->db->get();
$detalle2 = $mSQL_3->result();

$det3encab = 5; //Tamanio del encadezado de la segunda tabla
$npagos=$mSQL_3->num_rows()+$det3encab;

$lineas=0;
?><html>
<head>
<title>Anticipo recibido de cliente <?php echo $numero ?></title>
<link rel="stylesheet" href="<?php echo $this->_direccion ?>/assets/default/css/formatos.css" type="text/css" >
</head>
<body style="margin-left: 30px; margin-right: 30px;">

<script type="text/php">
	if (isset($pdf)) {
		$texto = array();
		$font  = Font_Metrics::get_font("verdana");
		$size  = 6;
		$color = array(0,0,0);
		$text_height = Font_Metrics::get_font_height($font, $size);
		$w     = $pdf->get_width();
		$h     = $pdf->get_height();
		$y     = $h - $text_height - 24;

		//***Inicio cuadro
		//**************VARIABLES MODIFICABLES***************
		$texto[]="ELABORADO POR:";
		$texto[]="AUTORIA";
		$texto[]="AUTORIZADO POR:";
		$texto[]="APROBADO";

		$cuadros = 0;   //Cantidad de cuadros (en caso de ser 0 calcula la cantidad)
		$margenh = 40;  //Distancia desde el borde derecho e izquierdo
		$margenv = 80;  //Distancia desde el borde inferior
		$alto    = 50;  //Altura de los cuadros
		$size    = 9;   //Tamanio del texto en los cuadros
		$color   = array(0,0,0); //Color del marco
		$lcolor  = array(0,0,0); //Color de la letra
		//**************************************************

		$cuadros = ($cuadros>0) ? $cuadros : count($texto);
		$cuadro  = $pdf->open_object();
		$margenl = $margenv-$alto+$text_height+5;    //Margen de la letra desde el borde inferior
		$ancho   = intval(($w-2*$margenh)/$cuadros); //Ancho de cada cuadro
		for($i=0;$i<$cuadros;$i++){
			$pdf->rectangle($margenh+$i*$ancho, $h-$margenv, $ancho, $alto,$color, 1);
			if(isset($texto[$i])){
				$width = Font_Metrics::get_text_width($texto[$i],$font,$size);
				$pdf->text($margenh+$i*$ancho+intval($ancho/2)-intval($width/2), $h-$margenl, $texto[$i], $font, $size, $lcolor);
			}
		}
		//***Fin del cuadro

		$pdf->close_object();
		$pdf->add_object($cuadro,'add');

		$text = "PP {PAGE_NUM} de {PAGE_COUNT}";

		// Center the text
		$width = Font_Metrics::get_text_width('PP 1 de 2', $font, $size);
		$pdf->page_text($w / 2 - $width / 2, $y, $text, $font, $size, $color);
	}
</script>

<?php
//************************
//     Encabezado
//************************
$encabezado = <<<encabezado
						<table style="width: 100%;" class="header">
							<tr>
								<td><h1 style="text-align: left">ANTICIPO RECIBIDO DE CLIENTE No. ${numero}</h1></td>
								<td><h1 style="text-align: right">Fecha: ${hfecha}</h1></td>
							</tr><tr>
								<td colspan='2'><h1 style="text-align: center">Por Bs.: ***${monto}***</h1></td>
							</tr>
						</table>
						<table align='center' style="font-size: 8pt;">
							<tr>
								<td><b>Hemos recibido de:</b></td>
								<td>(${cliente}) ${nombre}</td>
							</tr>
							<tr>
								<td><b>Con RIF:</b></td>
								<td>${rifci}</td>
							</tr>
							<tr>
								<td><b>Direcci&oacute;n:</b></td>
								<td>${direc}</td>
							</tr>
							<tr>
								<td><b>La cantidad de:</b></td>
								<td>${montole} Bs.</td>
							</tr>
							<tr>
								<td><b>Por concepto de:</b></td>
								<td>${observa}</td>
							</tr>
						</table>
encabezado;
// Fin  Encabezado

//************************
//   Encabezado Tabla
//************************
$estilo  = "style='color: #111111;background: #EEEEEE;border: 1px solid black;font-size: 8pt;";
$encabezado_tabla="
	<h2>Forma de pago:</h2>
	<table class=\"change_order_items\" style=\"padding-top:0; \">
		<thead>
			<tr>
				<th ${estilo}' >Tipo   </th>
				<th ${estilo}' >Fecha  </th>
				<th ${estilo}' >Banco  </th>
				<th ${estilo}' >N&uacute;mero</th>
				<th ${estilo}' >Monto  </th>
			</tr>
		</thead>
		<tbody>
";
//Fin Encabezado Tabla

//************************
//     Pie Pagina
//************************
$pie_final='</table>';

$pie_continuo=<<<piecontinuo
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" style="text-align: right;">CONTINUA...</td>
			</tr>
		</tfoot>
	</table>
<div style="page-break-before: always;"></div>
piecontinuo;
//Fin Pie Pagina

$mod     = $clinea = false;
$npagina = true;
$i       = 0;
foreach ($detalle2 AS $items2){ $i++;
	do {
		if($npagina){
			$this->incluir('X_CINTILLO');
			echo $encabezado;
			echo $encabezado_tabla;
			$npagina=false;
		}
?>
			<tr class="<?php if(!$mod) echo 'even_row'; else  echo 'odd_row'; ?>">

				<td style="text-align: center" ><?php echo $items2->tipo;            ?></td>
				<td style="text-align: center"><?php echo dbdate_to_human($items2->fecha); ?></td>
				<td style="text-align: left"  ><?php echo $items2->banco;           ?></td>
				<td style="text-align: left"  ><?php echo $items2->num_ref;         ?></td>
				<td style="text-align: right" ><?php echo nformat($items2->monto,2); ?></td>
				<?php
				$lineas++;
				if($lineas > $maxlin){
					$lineas =0;
					$npagina=true;
					echo $pie_continuo;
					break;
				}
				?>
			</tr>
<?php

		$mod = ! $mod;
	} while ($clinea);
}

for(1;$lineas<$maxlin;$lineas++){ ?>
			<tr class="<?php if(!$mod) echo 'even_row'; else  echo 'odd_row'; ?>">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
<?php
	$mod = ! $mod;
}
echo $pie_final;
?></body>
</html>
