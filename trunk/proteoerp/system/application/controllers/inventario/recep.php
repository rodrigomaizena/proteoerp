<?php
class Recep extends Controller {
	var $titp   = 'Movimientos de Mercancia';
	var $tits   = 'Movimientos';
	var $url    = 'inventario/recep/';

	function Recep(){
		parent::Controller();
		$this->load->library('rapyd');
		//$this->datasis->modulo_id(135,1);
	}

	function index(){
		redirect($this->url.'filteredgrid');
	}

	function filteredgrid(){
		$this->rapyd->load('datafilter2','datagrid');

		$filter = new DataFilter2('');

		//$filter->db->select(array("b.cuenta","a.comprob","a.fecha","a.origen","a.debe","a.haber","a.status","a.descrip","a.total"));
		$filter->db->from('recep a');

		$filter->recep = new inputField('Numero', 'recep');
		$filter->recep->size  =10;
		$filter->recep->db_name='a.recep';

		/*$filter->fechad = new dateonlyField("Desde", "fechad",'d/m/Y');
		$filter->fechah = new dateonlyField("Hasta", "fechah",'d/m/Y');
		$filter->fechad->clause  =$filter->fechah->clause ="where";
		$filter->fechad->db_name =$filter->fechah->db_name="a.fecha";
		$filter->fechah->size=$filter->fechad->size=10;
		$filter->fechad->operator=">=";
		$filter->fechah->operator="<=";

		//$filter->fecha = new dateonlyField("Fecha", "fecha");
		//$filter->fecha->size=12;

		$filter->descrip = new inputField("Descripci&oacute;n", "descrip");
		$filter->descrip->db_name="a.descrip";

		$filter->descripd = new inputField("Concepto Detalle", "descripd");
		$filter->descripd->db_name="b.concepto";

		$filter->cuenta = new inputField("Cuenta", "cuenta");
		$filter->cuenta->db_name="b.cuenta";

		$filter->status = new dropdownField("Status", "status");
		$filter->status->db_name="a.status";
		$filter->status->option('','Todos');
		$filter->status->option('A',"Actualizado");
		$filter->status->option('D','Diferido');

		$filter->vdes = new checkboxField("Ver solo asientos descuadrados","vdes",'S','N');
		$filter->vdes->insertValue='N';
		$filter->vdes->clause='';*/

		$filter->buttons('reset','search');

		$filter->build();
		$uri = anchor($this->url.'dataedit/show/<#recep#>','<#recep#>');

		function sta($status){
			switch($status){
				case 'C2':return 'Cuadrado' ;break;
				case 'C1':return 'Pendiente';break;
			}
		}

		function tipo($tipo){
			switch($tipo){
				case 'E':return 'Entrega';break;
				case 'R':return 'Recepci&oacute;n';break;
			}
		}

		$grid = new DataGrid('');
		$grid->order_by('a.recep','desc');
		$grid->per_page = 20;
		$grid->use_function('substr','str_pad');
		$grid->use_function('sta');

		$grid->column_orderby('Numero Recepci&oacute;n',$uri,'numero');
		$grid->column_orderby('Fecha'                  ,'<dbdate_to_human><#fecha#></dbdate_to_human>'  ,'fecha'    ,'align=\'center\''      );
		$grid->column_orderby('Or&iacute;gen'          ,'origen'                                        ,'origen'   ,'align=\'center\''      );
		$grid->column_orderby('Tipo'                   ,'<tipo><#tipo#></tipo>'                         ,'tipo'     ,'align=\'center\''      );
		$grid->column_orderby('Cod.Proveedor/Cliente'  ,'clipro'                                        ,'cod_prov' ,'align=\'center\''      );
		$grid->column_orderby('Observacion'            ,'observa'                                       ,'observa'  ,'align=\'left\'  NOWRAP');

		$grid->add($this->url.'dataedit/create');
		$grid->build();
		//echo $grid->db->last_query();

		//$data['content'] = $filter->output.$grid->output;
		$data['filtro']  = $filter->output;
		$data['content'] = $grid->output;
		$data['script']  = script('jquery.js');
		$data['title']   = heading($this->titp);
		$data['head']    = $this->rapyd->get_head();
		$this->load->view('view_ventanas', $data);
	}

	function dataedit(){
		$this->rapyd->load('dataobject','datadetails');

		$mSPRV=array(
			'tabla'   =>'view_clipro',
			'columnas'=>array(
			    'codigo' =>'C&oacute;digo',
			    'tipo'    =>'Tipo',
			    'rif'     =>'RIF/CI',
			    'nombre'  =>'Nombre'),
			'filtro'  =>array(
			    'tipo'    =>'Tipo',
			    'codigo' =>'C&oacute;digo',
			    'rif'     =>'RIF/CI',
			    'nombre'  =>'Nombre'),
			'retornar'=>array('codigo'=>'clipro'),
			//'script'  =>array('cal_lislr()','cal_total()'),
			'titulo'  =>'Buscar Proveedor / Cliente');

		$bSPRV=$this->datasis->p_modbus($mSPRV,'proveed');

		$do = new DataObject('recep');
		$do->rel_one_to_many('seri', 'seri', array('recep'=>'recep'));

		$edit = new DataDetails($this->tits, $do);
		$edit->back_url = site_url($this->url.'filteredgrid');
		$edit->set_rel_title('itcasi','Rubro <#o#>');

		$edit->pre_process( 'insert','_valida');
		$edit->pre_process( 'update','_valida');
		$edit->post_process('insert','_post_insert');
		$edit->post_process('update','_post_update');
		$edit->post_process('delete','_post_delete');

		$edit->recep  = new inputField('Numero', 'recep');
		$edit->recep->mode ='autohide';
		$edit->recep->when=array('show','modify');

		$edit->clipro  = new inputField('Cliente/Proveedor', 'clipro');
		$edit->clipro->append($bSPRV);
		$edit->clipro->size=5;
		$edit->clipro->readonly=true;

		$edit->tipo = new dropdownField('Tipo','tipo');
		$edit->tipo->option('E','Entrega');
		$edit->tipo->option('R','Recepci&oacute;n');
		$edit->tipo->style='width:110px';

		$edit->refe  = new inputField('Refencia', 'refe');
		$edit->refe->size=10;
		$edit->refe->maxleght=8;

		$edit->refe2  = new inputField('Factura de Compra', 'refe2');
		$edit->refe2->size=10;
		$edit->refe2->maxleght=8;

		$edit->origen = new dropdownField('Objeto', 'origen');
		$edit->origen->style='width:110px';
		$edit->origen->option('scst','Compra');
		$edit->origen->option('sfac','Factura');

		$edit->fecha = new  dateonlyField('Fecha','fecha');
		$edit->fecha->insertValue = date('Y-m-d');
		$edit->fecha->size        =12;
		$edit->fecha->rule        = 'required';

		$edit->observa = new textAreaField('Observaci&oacute;n', 'observa');
		$edit->observa->cols = 80;
		$edit->observa->rows = 1;

		/*$edit->status = new dropdownField("Status", "status");
		$edit->status->style="width:110px";
		$edit->status->option("C1","Cuadrado");
		$edit->status->option("C2","Pendiente");*/

		$edit->itbarras = new inputField('(<#o#>) Barras', 'it_barras_<#i#>');
		$edit->itbarras->rule         ='trim|required';
		$edit->itbarras->size         =20;
		$edit->itbarras->db_name      ='barras';
		$edit->itbarras->rel_id       ='seri';
		$edit->itbarras->autocomplete =false;
		//$edit->itbarras->append($button);

		$edit->itcodigo = new inputField('(<#o#>) Codigo', 'it_codigo_<#i#>');
		$edit->itcodigo->rule         ='trim|required';
		$edit->itcodigo->size         =10;
		$edit->itcodigo->db_name      ='codigo';
		$edit->itcodigo->rel_id       ='seri';
		$edit->itcodigo->autocomplete =false;

		$edit->itdescri = new inputField('(<#o#>) Descrip', 'it_descri_<#i#>');
		$edit->itdescri->rule         ='trim|required';
		$edit->itdescri->size         =40;
		$edit->itdescri->db_name      ='descrip';
		$edit->itdescri->rel_id       ='seri';
		$edit->itdescri->autocomplete =false;

		$edit->itserial = new inputField('(<#o#>) Serial', 'it_serial_<#i#>');
		$edit->itserial->rule         ='trim';
		$edit->itserial->size         =20;
		$edit->itserial->db_name      ='serial';
		$edit->itserial->rel_id       ='seri';
		$edit->itserial->autocomplete =false;

		$edit->itcant = new inputField('(<#o#>) Cantidad', 'it_cant_<#i#>');
		$edit->itcant->rule         = 'trim|numeric|required';
		$edit->itcant->size         = 10;
		$edit->itcant->db_name      = 'cant';
		$edit->itcant->rel_id       = 'seri';
		$edit->itcant->autocomplete = false;
		$edit->itcant->insertValue=1;

		$status=$edit->get_from_dataobjetct('status');
		$edit->buttons('delete','modify','save','undo','back','add_rel');
		$edit->build();

		$smenu['link']   = barra_menu('322');
		$data['smenu']   = $this->load->view('view_sub_menu', $smenu,true);
		$conten['form']  =&  $edit;
		$data['content'] = $this->load->view('recep', $conten,true);
		$data['title']   = heading($this->tits.' Nro. '.$edit->recep->value);
		$data['head']    = $this->rapyd->get_head().script('jquery.js').script('jquery-ui.js').script("plugins/jquery.numeric.pack.js").script('plugins/jquery.meiomask.js').style('vino/jquery-ui.css');
		$this->load->view('view_ventanas', $data);
	}

	function _valida($do){
		$error  ='';
		$recep  =$do->get('recep');
		$tipo   =$do->get('tipo');
		$refe   =$do->get('refe');
		$origen =$do->get('origen');
		$refee  =$this->db->escape($refe);

		if(empty($recep)){
			$ntransac = $this->datasis->fprox_numero('nrecep');
			$do->set('recep',$ntransac);
			$do->pk    =array('recep'=>$ntransac);
		}

		//se trae cliente y proveedor depende del numero
		//if($origen=='scst'){
		//	$clipro=$this->datasis->dameval("SELECT proveed FROM scst WHERE control=$refee");
		//}elseif($origen=='sfac'){
		//	$clipro=$this->datasis->dameval("SELECT cod_cli FROM sfac  WHERE numero=$refee AND tipo_doc='F'");
		//}
		//$do->set('clipro',$clipro);

		/*INICIO VALIDA ORIGEN=SFAC Y ENTREGADO
		se trae las cantidad disponibles a despachar por factura
		*/
		$sface=array();
		if($origen=='sfac' && $tipo=='E'){
			$query="SELECT codigo,SUM(cant) cant FROM (
				SELECT codigoa codigo,desca descrip, cana cant
				FROM sitems a WHERE numa=$refee
				UNION ALL 
				SELECT b.codigo,b.descrip,-1*b.cant 
				FROM seri b 
				JOIN recep c ON b.recep=c.recep
				WHERE c.refe=$refee AND c.origen='sfac' AND c.recep<>'$recep'
			)t 
			GROUP BY codigo";
			$sface=$this->datasis->consularray($query);
		}
		/*FIN VALIDA ORIGEN=SFAC Y ENTREGADO*/

		/*INICIO VALIDA ORIGEN=SFAC Y DEVUELVE
		se trae todos los item de la factura con las cantidades entregadas
		*/
		$sfac=array();$sfacs=array();
		if($origen=='sfac' && $tipo=='R'){
			$query="
			SELECT codigo,SUM(cant) cant FROM (
			SELECT codigoa codigo,desca descrip, 0 cant
			FROM sitems a WHERE numa=$refee
			UNION ALL 
			SELECT b.codigo,b.descrip,b.cant 
			FROM seri b 
			JOIN recep c ON b.recep=c.recep
			WHERE c.refe=$refee AND c.origen='sfac' AND c.recep<>'$recep'
			)t 
			GROUP BY codigo";
			$sfac=$this->datasis->consularray($query);

			$query="
			SELECT b.codigo,b.serial
			FROM seri b 
			JOIN recep c ON b.recep=c.recep
			WHERE c.refe=$refee AND c.origen='sfac' AND c.recep<>'$recep'";
			$sfacs=$this->datasis->consularray($query);
		}
		/*FIN VALIDA ORIGEN=SFAC Y DEVUELVE*/

		/*INICIO VALIDA ORIGEN=SCST Y DEVUELVE
		se trae todos los Las cantidades recibidas*/
		$scst=array();$scsts=array();
		if($origen=='scst' && $tipo=='E'){
			$query="
			SELECT b.codigo,SUM(b.cant) cant 
			FROM seri b 
			JOIN recep c ON b.recep=c.recep
			WHERE c.refe=$refee AND c.origen='scst' AND c.recep<>'$recep'
			GROUP BY codigo";
			$scst=$this->datasis->consularray($query);

			//se trae los seriales recibidos para la recepcion 
			$query="
			SELECT b.codigo,b.serial
			FROM seri b 
			JOIN recep c ON b.recep=c.recep
			WHERE c.refe=$refee AND c.origen='scst' AND c.recep<>'$recep'";
			$scst=$this->datasis->consularray($query);
		}
		/*FIN VALIDA ORIGEN=Scst Y DEVUELVE*/

		$se=array();$sinv=0;
		for($i=0;$i < $do->count_rel('seri');$i++){
			$codigo =$do->get_rel('seri','codigo',$i);
			$barras =$do->get_rel('seri','barras',$i);
			$serial =$do->get_rel('seri','serial',$i);
			$descrip=$do->get_rel('seri','descrip',$i);
			$cant   =$do->get_rel('seri','cant',$i);
			$codigoe=$this->db->escape($codigo);
			$barrase=$this->db->escape($barras);
			$seriale=$this->db->escape($serial);

			$where='';
			
			if(!empty($recep)){
				$recepe=$this->db->escape($recep);
				$where=" AND a.recep<>$recepe ";
			}

			if(!($cant>0))
			$error.=" La cantidad debe ser positiva para el codigo $codigo y barras $barras</br>";

			$t=$this->datasis->dameval("SELECT a.tipo FROM recep a JOIN seri b ON a.recep=b.recep WHERE codigo=$codigoe AND serial=$seriale $where ORDER BY a.fecha,a.recep desc LIMIT 1");

			if($tipo=='R'){
				if($t=='E' && empty($t))
				$error.="No se puede recibir debido a que esta recibido</br>";
			}elseif($tipo=='E' ){
				if($t!='R')
				$error.="No se puede entegar debido a que fue entregado o no ha sido recibido</br>";
			}else{
				$error.="ERROR. el tipo no es Entregar, ni Recibir</br>";
			}

			if(empty($error) && $tipo=='E'){
				$t=$this->datasis->dameval("SELECT SUM(IF(tipo='R',b.cant,-1*b.cant)) FROM recep a JOIN seri b ON a.recep=b.recep WHERE codigo=$codigoe AND serial=$seriale $where ORDER BY a.fecha desc LIMIT 1");
				if($cant>$t)
				$error.="La cantidad a entregar es mayor a la existente</br>";
			}

			if(empty($error))
			$sinv=$this->datasis->damerow("SELECT descrip,modelo,marca,clave,unidad,serial FROM sinv WHERE codigo=$codigoe AND barras=$barrase");

			if(count($sinv)>0){
				if($sinv['serial']=='S' && empty($serial)){
					$error.="El serial es obligatorio para el codigo $codigo y barras $barras</br>";
				}else{
					if(strlen($serial)>0)
					$do->set_rel('seri','cant',1,$i);

					if(in_array($codigo.$barras.$serial.$cant,$se)){
						$error.="El Serial $serial ya existe para el codigo $codigo y barras $barras</br>";
					}else{
						$se[]=$codigo.$barras.$serial;
					}
				}
			}else{
				$error.="El Codigo $codigo y barras $barras no existe.</br>";
			}

			/*INICIO VALIDA ORIGEN=SFAC Y ENTREGADO*/

			if($origen=='sfac' && $tipo=='E'){
				echo "aqui";
				if(array_key_exists($codigo,$sface)){

					if($cant>$sface[$codigo])
					$error.="ERROR. la cantidad a despachar del producto $codigo es mayor a la disponible ".nformat($sface[$codigo])." por despachar ";
				}else{
					$error.="ERROR. el producto ($codigo) $descrip no pertenece  la factura $refe</br>";
				}
			}
			/*FIN VALIDA ORIGEN=SFAC Y ENTREGADO*/
			
			/*INICIO VALIDA ORIGEN=SFAC Y DEVUELVE
			chequea que cada codigo ingresado pertenzca a la factura a devolver*/
			
			if($origen=='sfac' && $tipo=='R'){
			//print_r($sfac);
				if(array_key_exists($codigo,$sfac)){
					if($cant>$sfac[$codigo])
					$error.="ERROR. la cantidad a devolver del producto $codigo es mayor a la entregada para la factura $refee ".nformat($sface[$codigo])." por despachar ";
				}else{
					$error.="ERROR. el producto ($codigo) $descrip no pertenece  la factura $refe</br>";
				}
				//chequea que el serial ingresado pertenezca a la factura a devolver
				if(!(in_array($serial,$sfacs))){
					$error.="ERROR. el producto ($codigo) $descrip  serial $serial no pertenece  la factura $refe</br>";
				}
			}

			/*FIN VALIDA ORIGEN=SFAC Y DEVUELVE*/

			/*INICIO VALIDA ORIGEN=SCST Y DEVUELVE
			chequea que cada codigo ingresado pertenzca a la factura a devolver*/
			if($origen=='scst' && $tipo=='E'){
				if(array_key_exists($codigo,$scst)){
					if($cant>$scst[$codigo])
					$error.="ERROR. la cantidad a devolver del producto $codigo es mayor a la recibida para la factura $refee ".nformat($scst[$codigo])." ";
				}else{
					$error.="ERROR. el producto ($codigo) $descrip no pertenece a la recepcion de la factura $refe</br>";
				}
				//chequea que el serial ingresado pertenezca a la factura a devolver
				if(!(in_array($serial,$scst))){
					$error.="ERROR. el producto ($codigo) $descrip  serial $serial no pertenece  la factura $refe recibida</br>";
				}
			}
			/*FIN VALIDA ORIGEN=SCST Y DEVUELVE*/
		}

		if(!empty($error)){
			$do->error_message_ar['pre_ins']="<div class='alert'>".$error."</div>";
			$do->error_message_ar['pre_upd']="<div class='alert'>".$error."</div>";
			return false;
		}else{
			$do->set('estampa', 'CURDATE()', FALSE);
			$do->set('user', $this->session->userdata('usuario'));
			//GUARDA EN SNOT E ITSNOT
		}
	}

	function crea_snot($do){
		$refe2   = $do->get('refe2');
		$refe    = $do->get('refe');
		$fecha   = $do->get('fecha');
		$clipro  = $do->get('clipro');
		$origen  = $do->get('origen');
		$recep   = $do->get('recep');
		$tipo    = $do->get('tipo');
		$refee   = $this->db->escape($refe);
		$fechae  = $this->db->escape($fecha);
		$cliproe = $this->db->escape($clipro);

		/*CREA SNOT E ITSNOT CUANDO ES ENTREGA DE FACTURA*/
		if($origen=='sfac' && $tipo=='E'){
			$sfac  =$this->datasis->damerow("SELECT fecha,almacen,nombre FROM sfac WHERE numero=$refee AND tipo_doc='F'");
			if(empty($refe2)){
				$refe2 = $this->datasis->fprox_numero('nsnot');
				$query="INSERT INTO snot (`precio`,`numero`,`fecha`,`factura`,`cod_cli`,`fechafa`,`nombre`,`almaorg`,`almades`)
				VALUES (0,'$refe2',$fechae,$refee,$cliproe,'".$sfac['fecha']."','".$sfac['nombre']."','".$sfac['almacen']."','".$sfac['almacen']."')";
				$this->db->query($query);
			}else{
				$query="UPDATE snot  SET
				fecha=$fechae,
				factura=$refee,
				cod_cli=$cliproe,
				fechafa='".$sfac['fecha']."',
				nombre='".$sfac['nombre']."',
				almaorg='".$sfac['almacen']."',
				almades='".$sfac['almacen']."'
				";
				$this->db->query($query);
			}
			$this->db->query("DELETE FROM itsnot WHERE numero='$refe2'");
			$query="
			INSERT INTO itsnot (`numero`,`codigo`,`descrip`,`cant`,`saldo`,`entrega`,`factura`)
			SELECT '$refe2' numero,codigo,a.descrip,b.cana cant,(b.cana-SUM(a.cant)) saldo,SUM(a.cant) entrega,$refee 
			FROM recep c
			JOIN seri a ON a.recep=c.recep
			JOIN sitems b ON a.codigo=b.codigoa AND c.refe=b.numa 
			WHERE c.recep='$recep' AND b.tipoa='F' AND b.numa=$refee
			GROUP BY codigo";
			$this->db->query($query);
		}
		/*fin CREA SNOT */
	}

	function _post_insert($do){
		$this->crea_snot($do);
		$numero = $do->get('recep');
		logusu('recep',"Creo recepcion  $numero");
		//redirect($this->url."actualizar/$numero");
	}

	function _post_update($do){
		$this->crea_snot($do);
		$numero = $do->get('recep');
		logusu('casi'," Modifico recepcion $numero");
	}

	function _post_delete($do){
		$numero = $do->get('recep');
		logusu('casi'," Elimino recepcion $numero");
	}

	function instalar(){
		$query="ALTER TABLE `seri` ADD COLUMN `recep` CHAR(8) NOT NULL";
		$this->db->simple_query($query);

		$query="ALTER TABLE `seri` ADD COLUMN `frecep` DATE NOT NULL";
		$this->db->simple_query($query);

		$query="ALTER TABLE `seri`  ADD COLUMN `barras` VARCHAR(50) NOT NULL";
		$this->db->simple_query($query);

		if (!$this->db->table_exists('recep')) {
			$mSQL="CREATE TABLE `recep` (
			    `recep` char(8) NOT NULL DEFAULT '',
			    `fecha` date DEFAULT NULL,
			    `clipro` varchar(5) DEFAULT NULL,
			    `refe` char(8) DEFAULT NULL,
			    `tipo` char(2) DEFAULT NULL,
			    `observa` text,
			    `status` char(2) DEFAULT NULL,
			    `user` varchar(50) DEFAULT NULL,
			    `estampa` timestamp NULL DEFAULT NULL,
			    PRIMARY KEY (`recep`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1";
			$this->db->simple_query($mSQL);
		}

		$query="ALTER TABLE `recep`  ADD PRIMARY KEY (`recep`)";
		$this->db->simple_query($query);
		$query="ALTER TABLE `seri`  ADD COLUMN `cant` DECIMAL(19,2) NOT NULL DEFAULT '1'";
		$this->db->simple_query($query);
		$query="ALTER TABLE `recep`  CHANGE COLUMN `numero` `refe` CHAR(8) NULL DEFAULT NULL";
		$this->db->simple_query($query);
		$query="ALTER TABLE `recep`  CHANGE COLUMN `tipo_doc` `tipo` CHAR(2) NULL DEFAULT NULL";
		$this->db->simple_query($query);
		$query="ALTER TABLE `recep`  CHANGE COLUMN `cod_prov` `clipro` VARCHAR(5) NULL DEFAULT NULL";
		$this->db->simple_query($query);
		$query="ALTER TABLE `recep` ADD COLUMN `refe2` VARCHAR(20) NULL DEFAULT NULL AFTER `origen`";
		$this->db->simple_query($query);
		$query="ALTER TABLE `recep` ADD COLUMN `origen2` VARCHAR(20) NULL DEFAULT NULL AFTER `refe2`";
		$this->db->simple_query($query);
	}
}