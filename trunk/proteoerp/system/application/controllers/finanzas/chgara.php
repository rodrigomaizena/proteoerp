<?php
class Chgara extends Controller {
	var $mModulo='CHGARA';
	var $titp='Cheques en Garantia';
	var $tits='Cheques en Garantia';
	var $url ='finanzas/chgara/';

	function Chgara(){
		parent::Controller();
		$this->load->library('rapyd');
		$this->load->library('jqdatagrid');
		//$this->datasis->modulo_nombre( $modulo, $ventana=0 );
	}

	function index(){
		if ( !$this->datasis->iscampo('chgara','enviado') ) {
			$this->db->query('ALTER TABLE chgara ADD COLUMN enviado DATE NULL AFTER deposito');
		};
		if ( !$this->datasis->iscampo('chgara','codbanc') ) {
			$this->db->query('ALTER TABLE chgara ADD COLUMN codbanc CHAR(2) NULL AFTER enviado');
		};
		if ( !$this->datasis->iscampo('chgara','fdeposito') ) {
			$this->db->query('ALTER TABLE chgara ADD COLUMN fdeposito DATE NULL AFTER codbanc');
		};
		if ( !$this->datasis->iscampo('chgara','transac') ) {
			$this->db->query('ALTER TABLE chgara ADD COLUMN transac VARCHAR(8) NULL AFTER fdeposito');
		};
		if ( $this->datasis->isindice('bmov','idunico') ){
			$this->db->query('ALTER TABLE bmov DROP INDEX idunico, ADD INDEX principal (codbanc, tipo_op, numero)');
		}
		redirect($this->url.'jqdatag');
	}

	//***************************
	//Layout en la Ventana
	//
	//***************************
	function jqdatag(){

		$grid = $this->defgrid();
		$param['grids'][] = $grid->deploy();

		$mSQL  = "SELECT codbanc, CONCAT(codbanc, ' ', trim(banco),' ', numcuent) banco ";
		$mSQL .= "FROM banc WHERE activo='S'  AND tbanco<>'CAJ' ";
		$mSQL .= "ORDER BY (tbanco='CAJ'), codbanc ";
		$obanc = $this->datasis->llenaopciones($mSQL, true,  'cuenta' );
		$obanc = str_replace('"',"'", $obanc);


		$bodyscript = '
<script type="text/javascript">
$(function() {
	$( "input:submit, a, button", ".otros" ).button();
});

jQuery("#listado").click( function(){
	window.open(\''.base_url().'reportes/ver/CHGARA/\', \'_blank\', \'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=((screen.availHeight/2)-400), screeny=((screen.availWidth/2)-300)\');
});


$( "#depositar" ).click(function() {
	var grid = jQuery("#newapi'.$param['grids'][0]['gridname'].'");
	var s = grid.getGridParam(\'selarrrow\');
	if(s.length){
		meco = sumamonto(0);
		$.prompt( "<h1>Enviar a Depositar ?</h1>", {
			buttons: { Guardar: true, Cancelar: false },
			submit: function(e,v,m,f){
				if (v){
					$.get("'.base_url().$this->url.'chenvia/"+meco,
					function(data){
						alert(data);
						grid.trigger("reloadGrid");
					});
				}
			}
		});
	} else {
		$.prompt("<h1>Seleccione los Cheques</h1>");
	}
});


$( "#cobrados" ).click(function() {
	var grid = jQuery("#newapi'.$param['grids'][0]['gridname'].'");
	var s = grid.getGridParam(\'selarrrow\');
	if(s.length){
		meco = sumamonto(0);
		$.prompt( "<h1>Marcar como Cobrado?</h1>Marca solo los cheques que fueron previamente Enviados al Cobro<br/>Cuenta Bancaria: '.$obanc.'<br>Fecha del deposito: <br/> <input type=\'text\' id=\'mfecha\' name=\'mfecha\' value=\''.date('d-m-Y').'\' maxlengh=\'10\' size=\'10\' ><br/>Numero de Deposito:<br/><input type=\'text\' id=\'numdep\' name=\'numdep\' value=\'\'><br/>", {
			buttons: { Guardar: true, Cancelar: false },
			submit: function(e,v,m,f){
				if (v){
					mfecha = f.mfecha.substr(6,4)+f.mfecha.substr(3,2)+f.mfecha.substr(0,2);
					if (f.numdep == ""){
						alert("Debe colocar el Nro de deposito!!!");					
					} else {
						$.get("'.base_url().$this->url.'chcobrados/"+meco+"/"+f.numdep+"/"+f.cuenta+"/"+mfecha,
						function(data){
							alert(data);
							grid.trigger("reloadGrid");
						});
					}
				}
			}
		});
		$("#mfecha").datepicker({dateFormat:"dd-mm-yy"});
	} else {
		$.prompt("<h1>Seleccione los Cheques</h1>");
	}
});

$( "#pagar" ).click(function() {
	var grid = jQuery("#newapi'.$param['grids'][0]['gridname'].'");
	var s = grid.getGridParam(\'selarrrow\');
	if(s.length == 1){
		entirerow = grid.jqGrid(\'getRowData\',s[0]);
		if ( entirerow["status"].search("moneda") < 0 ){
			$.prompt("<h1>Cheque no cobrado o ya aplicado</h1>Seleccione uno que este depositado y cobrado");
		} else {
			$.prompt( "<h1>Aplicar Pago?</h1>Aplicar cheque cobrado a Factura? ", {
				buttons: { Guardar: true, Cancelar: false },
				submit: function(e,v,m,f){
					if (v){
						$.get("'.base_url().$this->url.'chpagar/"+entirerow["id"],
						function(data){
							alert(data);
							grid.trigger("reloadGrid");
						});
					}
				}
			});
		}
	} else {
		$.prompt("<h1>Seleccione un solo Cheque</h1>");
	}
});


$( "#devueltos" ).click(function() {
	var grid = jQuery("#newapi'.$param['grids'][0]['gridname'].'");
	var s = grid.getGridParam(\'selarrrow\');
	if(s.length){
		meco = sumamonto(0);
		$.prompt( "<h1>Marcar los cheques Devueltos ?</h1>Marca solo los cheques que fueron previamente Enviados al Cobro", {
			buttons: { Guardar: true, Cancelar: false },
			submit: function(e,v,m,f){
				if (v){
					$.get("'.base_url().$this->url.'chdevueltos/"+meco,
					function(data){
						alert(data);
						grid.trigger("reloadGrid");
					});
				}
			}
		});
	} else {
		$.prompt("<h1>Seleccione los Cheques</h1>");
	}
});



function sumamonto(rowId){ 
	var grid = jQuery("#newapi'.$param['grids'][0]['gridname'].'"); 
	var s; 
	var total = 0; 
	var rowcells=new Array();
	var entirerow;
	var hoy   = new Date();
	var fecha ;
	var meco = "";

	if ( rowId > 0 ) {
		entirerow = grid.jqGrid(\'getRowData\',rowId);
		fecha = new Date(entirerow["fecha"].split("-").join("/"))
		if ( hoy < fecha ){
			alert( "Cheque no vencido" );
		} 
	}

	s = grid.getGridParam(\'selarrrow\'); 
	$("#totaldep").html("");
	if(s.length)
	{
		for(var i=0;i<s.length;i++)
		{
			entirerow = grid.jqGrid(\'getRowData\',s[i]);
			fecha = new Date(entirerow["fecha"].split("-").join("/"))
			if ( hoy >= fecha ){
				total += Number(entirerow["monto"]);
				meco = meco+entirerow["id"]+"-";
			} else {
				if ( rowId == 0 ) {
					grid.resetSelection(s[i]);
				}
			}
		}
		total = Math.round(total*100)/100;
		$("#totaldep").html("Bs. "+nformat(total,2));
		$("#montoform").html("Monto: "+nformat(total,2));
		montotal = total;
	}
	return meco;
};
$(function(){$(".inputnum").numeric(".");});

</script>
';


		#Set url
		$grid->setUrlput(site_url($this->url.'setdata/'));

		$WestPanel = '
<div id="LeftPane" class="ui-layout-west ui-widget ui-widget-content">
	<div class="otros">
	<table id="west-grid">
	<tr><td>
		<div class="tema1"><a style="width:190px;text-align:left;" href="#" id="listado">'.img(array('src' => 'assets/default/images/print.png', 'alt' => 'Listado',  'title' => 'Listado', 'border'=>'0')).'&nbsp;&nbsp;&nbsp;&nbsp;Listado</a></div>
	<tr><td>
		<div class="tema1"><a style="width:190px;text-align:left;" href="#" id="depositar">'.img(array('src' => 'assets/default/images/cheque.png', 'alt' => 'Cheques',  'title' => 'Cheques', 'border'=>'0')).'&nbsp;&nbsp;&nbsp;&nbsp;Enviar a Depositar </a></div>
	</td></tr>
	<tr><td>&nbsp;</td></tr>
	<tr><td>
		<div class="tema1"><a style="width:190px;text-align:left;" href="#" id="cobrados">'.img(array('src' => 'assets/default/images/monedas.png', 'alt' => 'Cobrados',  'title' => 'Cobrados', 'border'=>'0')).'&nbsp;&nbsp;&nbsp;&nbsp;Cheques Cobrados</a></div>
	<tr><td>
		<div class="tema1"><a style="width:190px;text-align:left;" href="#" id="devueltos">'.img(array('src' => 'images/N.gif', 'alt' => 'Devueltos',  'title' => 'Devueltos', 'border'=>'0')).'&nbsp;&nbsp;&nbsp;&nbsp;Cheques Devueltos </a></div><br>
	<tr><td>
		<div class="tema1"><a style="width:190px;text-align:left;" href="#" id="pagar">'.img(array('src' => 'images/face-smile.png', 'alt' => 'Pagar',  'title' => 'Pagar', 'border'=>'0')).'&nbsp;&nbsp;&nbsp;&nbsp;Pagar Factura</a></div><br>
	</td></tr>
	</table>
	</div>
	<div id="totaldep" style="font-size:20px;text-align:center;"></div>
</div> <!-- #LeftPane -->
';

		$SouthPanel = '
<div id="BottomPane" class="ui-layout-south ui-widget ui-widget-content">
<p>'.$this->datasis->traevalor('TITULO1').'</p>
</div> <!-- #BottomPanel -->
';

		$funciones = '
	function fstatus(el, val, opts){
		var meco=\'<div><img src="'.base_url().'images/S.gif" width="20" height="18" border="0" /></div>\';
		if ( el == "E" ){
			meco=\'<div><img src="'.base_url().'assets/default/images/cheque.png" width="20" height="18" border="0" /></div>\';
		} else if (el == "C") {
			meco=\'<div><img src="'.base_url().'assets/default/images/monedas.png" width="20" height="18" border="0" /></div>\';
		} else if (el == "D") {
			meco=\'<div><img src="'.base_url().'images/N.gif" width="20" height="20" border="0" /></div>\';
		} else if (el == "A") {
			meco=\'<div><img src="'.base_url().'images/face-smile.png" width="20" height="20" border="0" /></div>\';
		}
		return meco;
	}
';

		$param['WestPanel']  = $WestPanel;
		$param['funciones']  = $funciones;

		//$param['EastPanel']  = $EastPanel;
		$param['SouthPanel'] = $SouthPanel;
		$param['listados'] = $this->datasis->listados('CHGARA', 'JQ');
		$param['otros']    = $this->datasis->otros('CHGARA', 'JQ');
		$param['temas']     = array('proteo','darkness','anexos1');
		$param['bodyscript'] = $bodyscript;
		$param['tabs'] = false;
		$param['encabeza'] = $this->titp;
		$this->load->view('jqgrid/crud2',$param);
	}

	//*********************************************
	// Guarda los que se enviaron a depositar
	//*********************************************
	function chenvia(){
		$ids = $this->uri->segment(4);
		$ids = str_replace("-",",", $ids);
		$ids = substr($ids,0,-1);
		$mSQL = "UPDATE chgara SET status='E', enviado=curdate() WHERE id IN ($ids) AND status='P' ";
		$this->db->simple_query($mSQL);
		echo "Cheques enviados ";
	}

	//*********************************************
	// Guarda los que se enviaron a depositar
	//*********************************************
	function chcobrados(){
		$ids     = $this->uri->segment(4);
		$numdep  = $this->uri->segment(5);
		$codbanc = $this->uri->segment(6);
		$fecha   = $this->uri->segment(7);
		
		$ids = str_replace("-",",", $ids);
		$ids = substr($ids,0,-1);
		$mSQL = "UPDATE chgara SET status='C', deposito=?, codbanc=?, fdeposito=? WHERE id IN ($ids) AND status='E' ";
		$this->db->query($mSQL, array($numdep, $codbanc, $fecha));
		echo "Cheques marcados como Cobrados ".$fecha;
	}

	//*********************************************
	// Guarda los que se enviaron a depositar
	//*********************************************
	function chdevueltos(){
		$ids = $this->uri->segment(4);
		$ids = str_replace("-",",", $ids);
		$ids = substr($ids,0,-1);
		$mSQL = "UPDATE chgara SET status='P', deposito='DEVUELTO' WHERE id IN ($ids) AND status='E' ";
		$this->db->query($mSQL);
		echo "Cheques marcados como Devueltos ";
	}

	//*********************************************
	// Guarda los que se enviaron a depositar
	//*********************************************
	function chpagar(){
		$id = $this->uri->segment(4);
		$reg    = $this->datasis->damereg("SELECT * FROM chgara WHERE id=$id");

		$data = array();
		
		$chmonto  = $reg["monto"];
		$cod_cli  = $reg["cod_cli"];
		$deposito = $reg["deposito"];
		$codbanc  = $reg["codbanc"];
		$fecha    = $reg["fecha"];

		$regcli = $this->datasis->damereg("SELECT nombre FROM scli WHERE cliente=".$this->db->escape($cod_cli));
		
		// Analiza el Edo Cta
		$saldo = $this->datasis->dameval("SELECT sum(monto-abonos) saldo FROM smov WHERE cod_cli=".$this->db->escape($cod_cli)." AND tipo_doc IN ('FC','ND','GI') ");
		if ( $saldo == '') $saldo = '0.00';
		
		if ($chmonto > $saldo){
			echo "Monto del cheque ($chmonto) es mayor que el saldo deudor ($saldo) , debe generar un Anticipo";
			return;
		}
		
		$efecto = $this->datasis->damereg("SELECT tipo_doc, numero, monto, impuesto FROM smov WHERE cod_cli=".$this->db->escape($cod_cli)." AND monto-abonos>=".$chmonto." AND tipo_doc IN ('FC','ND','GI') ORDER BY fecha limit 1");
		$mSQL   = "UPDATE chgara SET status='P', deposito='DEVUELTO' WHERE id IN ($id) AND status='E' ";

		// CREA EL ABONO
		$xnumero   = str_pad($this->datasis->prox_sql("nabcli"),  8, '0', STR_PAD_LEFT);
		$mcontrol  = str_pad($this->datasis->prox_sql("nsmov"),   8, '0', STR_PAD_LEFT);
		$transac   = str_pad($this->datasis->prox_sql("ntransa"), 8, '0', STR_PAD_LEFT);
		$xningreso = str_pad($this->datasis->prox_sql('ningreso'),8, '0', STR_PAD_LEFT);

		$data = array();
		$data['cod_cli']  = $cod_cli; 
		$data['nombre']   = $regcli['nombre']; 
		$data['tipo_doc'] = 'AB'; 
		$data['numero']   = $xnumero; 
		$data['fecha']    = $fecha; 
		$data['monto']    = $chmonto; 
		
		$data['impuesto'] = $chmonto*$efecto['impuesto']/$efecto['monto'];
		$data['vence']    = $fecha;
		$data['tipo_ref'] = $efecto['tipo_doc'];
		$data['num_ref']  = $efecto['numero'];
		$data['observa1'] = "PAGA: ".$efecto['tipo_doc'].$efecto['numero'];
		$data['observa2'] =  "";
		$data['banco']    = $codbanc; 
		$data['fecha_op'] = $fecha; 
		$data['num_op']   = $deposito; 
		$data['tipo_op']  =  'DE'; 
		$data['reten']    = 0;
		$data['ppago']    = 0;
		$data['cambio']   = 0;
		$data['mora']     = 0;
		$data['control']  = $mcontrol;
		$data['codigo']   =  '';
		$data['descrip']  = '';
		$data['reteiva']  =  0;
		$data['nroriva']  = '';
		$data['emiriva']  = '';
		$data['ningreso'] = $xningreso;

		$data['usuario']    = $this->secu->usuario();
		$data['estampa']    = date('Ymd');
		$data['hora']       = date('H:i:s');
		$data['transac']    = $transac;
		
		$this->db->insert('smov',$data);

		//Detalle en itccli
		$data = array();
		$data['numccli']  = $xnumero;
		$data['tipoccli'] = 'AB';
		$data['cod_cli']  = $cod_cli;
		$data['numero']   = $efecto['numero'];
		$data['tipo_doc'] = $efecto['tipo_doc'];
		$data['fecha']    = $fecha;
		$data['monto']    = $efecto['monto'];
		$data['abono']    = $chmonto;
		$data['reten']    = 0;
		$data['ppago']    = 0;
		$data['cambio']   = 0;
		$data['mora']     = 0;
		$data['reteiva']  = '';

		$data['usuario']    = $this->secu->usuario();
		$data['estampa']    = date('Ymd');
		$data['hora']       = date('H:i:s');
		$data['transac']    = $transac;

		$this->db->insert('itccli',$data);

		//Actualiza la Factura
		$mSQL = "UPDATE smov SET abonos=abonos+? WHERE tipo_doc=? AND cod_cli=? AND numero=?";
		$this->db->query($mSQL,	array( $chmonto, $efecto['tipo_doc'], $cod_cli ,$efecto['numero'] ));

		// FORMA DE PAGO
		$data = array();
		$mcajero = $this->datasis->dameval("SELECT cajero FROM usuario WHERE us_codigo=".$this->db->escape($this->secu->usuario()));

		$data['tipo_doc']  = 'AB';
		$data['numero']    = $xnumero;
		$data['tipo']      = 'DE';
		$data['monto']     = $chmonto;
		$data['num_ref']   = $deposito;
		$data['clave']     = '';
		$data['fecha']     = $fecha;
		$data['banco']     = $codbanc;
		$data['cambio']    = 0;
		$data['f_factura'] = $fecha;
		$data['cod_cli']   = $cod_cli;

		$data['vendedor']  = $mcajero;
		$data['cobrador']  = $mcajero;

		$data['usuario']   = $this->secu->usuario();
		$data['estampa']   = date('Ymd');
		$data['hora']      = date('H:i:s');
		$data['transac']   = $transac;

		$this->db->insert('sfpa',$data);

		$this->datasis->actusal($codbanc, $fecha, $chmonto);
               
		$msql = "SELECT numcuent, banco, moneda, saldo FROM banc WHERE codbanc=".$this->db->escape($codbanc);
		$banreg = $this->datasis->damereg($msql);

		$data = array();
		$data['codbanc']   = $codbanc;
		$data['numcuent']  = $banreg['numcuent'];
		$data['banco']     = $banreg['banco'];
		$data['moneda']    = $banreg['moneda'];
		$data['saldo']     = $banreg['saldo'];
		$data['fecha']     = $fecha;   //mpago[i,5] })  se jode la contabilidad si la fecha no es igual
		$data['benefi']    = '';
		$data['tipo_op']   = 'DE';
		$data['numero']    = str_pad($deposito,12,'0',STR_PAD_LEFT);
		$data['monto']     = $chmonto;
		$data['clipro']    = 'C';
		$data['codcp']     = $cod_cli;
		$data['nombre']    = $regcli['nombre'];
		$data['concepto']  = 'INGRESO POR COBRANZA ';
		$data['concep2']   = 
		$data['concep3']   = 
		$data['status']    = 'P';
		$data['bruto']     = $chmonto;
		$data['negreso']   = $xningreso;

		$data['usuario']   = $this->secu->usuario();
		$data['estampa']   = date('Ymd');
		$data['hora']      = date('H:i:s');
		$data['transac']   = $transac;

		$this->db->insert('bmov',$data);

		$mSQL = "UPDATE chgara SET status='A', transac='$transac' WHERE id=$id  ";
		$this->db->simple_query($mSQL);

		echo "Cuenta por cobrar actualizada ";
	}

	//***************************
	//Definicion del Grid y la Forma
	//***************************
	function defgrid( $deployed = false ){
		$i      = 1;
		$editar = "true";

		$grid  = new $this->jqdatagrid;

		$link  = site_url('ajax/buscascli');
		$afterhtml = '<div id=\"aaaaaa\">Nombre <strong>"+ui.item.nombre+" </strong>RIF/CI <strong>"+ui.item.rifci+" </strong><br>Direccion <strong>"+ui.item.direc+"</strong></div>';
		$auto = $grid->autocomplete( $link, 'cod_cli', 'aaaaa', $afterhtml );


		$grid->addField('status');
		$grid->label('Status');
		$grid->params(array(
			'search'        => 'true',
			'align'         => "'center'",
			'editable'      => 'false',
			'width'         => 40,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 1 }',
			'formatter'     => 'fstatus'
		));


		$grid->addField('cod_cli');
		$grid->label('Cliente');
		$grid->params(array(
				'width'       => 50,
				'editable'    => $editar,
				'edittype'    => "'text'",
				'editrules'   => '{ edithidden:true, required:true }',
				'editoptions' => '{'.$auto.'}'
			)
		);

		$grid->addField('nombre');
		$grid->label('Nombre');
		$grid->params(array(
				'width'       => 160,
				'editable'    => 'false',
				'edittype'    => "'text'",
			)
		);

		$grid->addField('fecha');
		$grid->label('Fecha');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));

		$grid->addField('numero');
		$grid->label('Numero');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 100,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 16 }',
		));

		$grid->addField('monto');
		$grid->label('Monto');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'align'         => "'right'",
			'edittype'      => "'text'",
			'width'         => 100,
			'editrules'     => '{ required:true }',
			'editoptions'   => '{ size:10, maxlength: 10, dataInit: function (elem) { $(elem).numeric(); }  }',
			'formatter'     => "'number'",
			'formatoptions' => '{decimalSeparator:".", thousandsSeparator: ",", decimalPlaces: 2 }'
		));

		$grid->addField('banco');
		$grid->label('Banco');
		$grid->params(array(
			'align'         => "'center'",
			'width'         => 40,
			'editable'      => $editar,
			'edittype'      => "'select'",
			'editrules'     => '{ edithidden:true, required:true }',
			'editoptions'   => '{ dataUrl: "'.base_url().'ajax/ddbanco"}',
			'stype'         => "'text'",
		));

		$grid->addField('cuentach');
		$grid->label('Cuenta Bancaria');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 170,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 22 }',
		));

		$grid->addField('vendedor');
		$grid->label('Vendedor');
		$grid->params(array(
			'width'         => 40,
			'hidden'        => 'true',
			'editable'      => 'true',
			'edittype'      => "'select'",
			'editrules'     => '{ edithidden:true, required:true }',
			'editoptions'   => '{ dataUrl: "'.base_url().'ajax/ddvende"}',
		));

		$grid->addField('observa');
		$grid->label('Observacion');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => $editar,
			'width'         => 200,
			'edittype'      => "'text'",
			'editoptions'   => '{ size:30, maxlength: 250 }',
		));

		$grid->addField('enviado');
		$grid->label('Enviado Cobro');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}'
		));

		$grid->addField('codbanc');
		$grid->label('Cuenta');
		$grid->params(array(
			'align'         => "'center'",
			'width'         => 40,
			'editable'      => 'false',
			'edittype'      => "'text'",
		));

		$grid->addField('fdeposito');
		$grid->label('F Deposito');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha Deposito" }'
		));


		$grid->addField('deposito');
		$grid->label('Deposito');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 120,
			'edittype'      => "'text'",
		));

		$grid->addField('usuario');
		$grid->label('Usuario');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 120,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 12 }',
		));

		$grid->addField('estampa');
		$grid->label('Estampa');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Estampa" }'
		));

		$grid->addField('hora');
		$grid->label('Hora');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 80,
			'edittype'      => "'text'",
			'editrules'     => '{ required:true}',
			'editoptions'   => '{ size:30, maxlength: 8 }',
		));

		$grid->addField('modificado');
		$grid->label('Modificado');
		$grid->params(array(
			'search'        => 'true',
			'editable'      => 'false',
			'width'         => 80,
			'align'         => "'center'",
			'edittype'      => "'text'",
			'editrules'     => '{ required:true,date:true}',
			'formoptions'   => '{ label:"Fecha" }'
		));

		$grid->addField('id');
		$grid->label('Id');
		$grid->params(array(
			'align'         => "'center'",
			'frozen'        => 'true',
			'width'         => 40,
			'editable'      => 'false',
			'search'        => 'false'
		));

		$grid->showpager(true);
		$grid->setWidth('');
		$grid->setHeight('385');
		$grid->setTitle($this->titp);
		$grid->setfilterToolbar(true);
		$grid->setToolbar('false', '"top"');
		$grid->setMultiSelect(true);

		$grid->setonSelectRow('sumamonto');

		$grid->setFormOptionsE('closeAfterEdit:true, mtype: "POST", width: 520, height:350, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];} ');
		$grid->setFormOptionsA('closeAfterAdd:true,  mtype: "POST", width: 520, height:350, closeOnEscape: true, top: 50, left:20, recreateForm:true, afterSubmit: function(a,b){if (a.responseText.length > 0) $.prompt(a.responseText); return [true, a ];} ');
		$grid->setAfterSubmit("$.prompt('Respuesta:'+a.responseText); return [true, a ];");

		#show/hide navigations buttons
		$grid->setAdd(true);
		$grid->setEdit(true);
		$grid->setDelete(true);
		$grid->setSearch(true);
		$grid->setRowNum(30);
		$grid->setShrinkToFit('false');

		#Set url
		$grid->setUrlput(site_url($this->url.'setdata/'));

		#GET url
		$grid->setUrlget(site_url($this->url.'getdata/'));

		if ($deployed) {
			return $grid->deploy();
		} else {
			return $grid;
		}
	}

	/**
	* Busca la data en el Servidor por json
	*/
	function getdata()
	{
		$grid = $this->jqdatagrid;
		$join = array(array('table'=>'scli', 'join'=>'chgara.cod_cli=scli.cliente', 'fields'=>array('nombre')));

		// CREA EL WHERE PARA LA BUSQUEDA EN EL ENCABEZADO
		$mWHERE = $grid->geneSqlWhere('chgara', $join);

		$response   = $grid->getData('chgara', $join , array(), false, $mWHERE, 'status desc,fecha' );
		$rs = $grid->jsonresult( $response);
		echo $rs;
		//print_r($mWHERE);
	}

	/**
	* Guarda la Informacion
	*/
	function setData()
	{
		$this->load->library('jqdatagrid');
		$oper   = $this->input->post('oper');
		$id     = $this->input->post('id');
		$data   = $_POST;
		$check  = 0;
		$status = 'P';

		if ( $id > 0 )
			$status = $this->datasis->dameval("SELECT status FROM chgara WHERE id=$id");

		unset($data['oper']);
		unset($data['id']);
		if($oper == 'add'){
			if(false == empty($data)){
				$data['status']    = 'P';
				$data['usuario']   = $this->secu->usuario();
				$data['estampa']   = date('Ymd');
				$data['hora']      = date('H:i:s');
				$this->db->insert('chgara', $data);
				echo "Registro Agregado";
				logusu('CHGARA',"Registro  INCLUIDO");
			} else
			echo "Fallo Agregado!!!";

		} elseif($oper == 'edit') {
			// Solo modifica los Cheques pendientes
			if ( $status == 'P'){			$this->db->where('id', $id);
				$this->db->update('chgara', $data);
				logusu('CHGARA',"Registro $id MODIFICADO");
				echo "Registro Modificado";
			} else
			echo "Cheque no puede modificarse, no esta pendiente";
			

		} elseif($oper == 'del') {
			//$check =  $this->datasis->dameval("SELECT COUNT(*) FROM chgara WHERE id='$id' ");
			if ($check > 0){
				echo " El registro no puede ser eliminado; tiene movimiento ";
			} else {
				$this->db->simple_query("DELETE FROM chgara WHERE id=$id ");
				logusu('CHGARA',"Registro $id  ELIMINADO");
				echo "Registro Eliminado";
			}
		};
	}
}

?>