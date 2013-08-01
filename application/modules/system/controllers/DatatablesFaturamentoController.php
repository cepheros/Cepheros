<?php

class System_DatatablesFaturamentoController extends Zend_Controller_Action{
	
	public function init(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function listarNfeEmitidaAction(){
		$status_processo = $this->_getParam('type');
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$iTotal = count($db->fetchAll("status_processo = '$status_processo' and tipo_nfe in (1,2,3)"));
		
		$aColumns = array("b.nomefantasia","a.nNF",'c.xNome','a.dEmi','a.dSaiEnt','a.natOp','d.vNF');
		
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblnfe_basicos'),array('a.id_registro',
				'a.id_empresa',
				'a.nNF',
				'a.dEmi',
				'a.dSaiEnt',
				'a.natOp',
				'a.tipo_nfe',
				'a.status_processo',
				'b.nomefantasia',
				'c.xNome',
				'd.vNF'
			))
			->join(array('b'=>'tblsystem_empresas'),'b.id_registro = a.id_empresa',array())
			->join(array('c'=>'tblnfe_destinatario'), 'c.id_nfe = a.id_registro',array())
			->join(array('d'=>'tblnfe_totais'),'d.id_nfe= a.id_registro',array())
			->where("a.status_processo = '$status_processo'")
			->where("a.tipo_nfe in (1,2,3)");
			//echo $return;
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				
					$sWhere= "UPPER(".$aColumns[$i].") LIKE '%". strtoupper($_GET['sSearch']) ."%'";
			
		
				if($i==0){
					$return->where($sWhere);
				}else{
					$return->orWhere($sWhere);
				}
			}
				
		}
		if ( isset( $_GET['iSortCol_0'] ) )
		{
				
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
					$type =  $_GET['sSortDir_'.$i];
					$return->order("$collum $type");
						
						
				}
			}
				
				
				
				
		}else{
			$return->order("id_registro DESC");
		}
			
		if($_GET['iDisplayLength']){
			$tamanho = $_GET['iDisplayLength'];
		}else{
			$tamanho = 10;
		}
		if($_GET['iDisplayStart']){
			$inicio = $_GET['iDisplayStart'];
		}else{
			$inicio = 0;
		}
			
		$return->limit($tamanho,$inicio);
			
			
			
			
			
		$rs = $return->query();
		$dados = $rs->fetchAll();
			
		$iFilteredTotal = count($dados);
			
			
		$output = array(
				"sEcho" => $_GET['sEcho'],
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
		);
		foreach($dados as $data){
			
			$icons.="<a href=\"/erp/faturamento/imprimir-danfe/id/{$data['id_registro']}\" target=\"_blank\"><img src=\"/img/file_pdf.png\" width=\"20px\" heigth=\"20px\" title=\"Visualizar DANFE\"></a> ";
			$icons.="<a href=\"/erp/faturamento/get-xml/id/{$data['id_registro']}\" target=\"_blank\"><img src=\"/img/xml.png\" width=\"20px\" heigth=\"20px\" title=\"Visualizar XML\"></a> ";
			
			switch($status_processo){
				//Notas Assinadas
				case '1':
					$icons.="<a href=\"/erp/faturamento/emitir-nfe/id/{$data['id_registro']}\"><i class=\"splashy-upload\" title=\"Enviar NFe\"></i></a> ";
					
				break;
				// Notas Enviadas
				case '2':
					$icons.="<a href=\"/erp/faturamento/consulta-retorno/id/{$data['id_registro']}\"><i class=\"splashy-refresh\" title=\"Verificar Resposta NFe\"></i> ";
					
				
				break;
				//Notas Aprovadas
				case '3':
					
					$icons.="<i class=\"splashy-document_letter_marked EmitirCCe\" idNFe=\"{$data['id_registro']}\" title=\"Carta de Correção CCe\"></i> ";
					$icons.="<i class=\"splashy-error_do_not CancelarNFe\" idNFe=\"{$data['id_registro']}\" title=\"Cancelar NFe\"></i> ";
					$icons.="<a data-toggle=\"modal\" href=\"/erp/faturamento/get-eventos/id/{$data['id_registro']}\" data-target=\"#EventosModal\"><i class=\"splashy-documents_edit\" title=\"Visualizar Eventos\"></i></a> ";
					
					
			
				break;
				
				case '4':
				case '5':
					$icons.="<i class=\"splashy-documents_edit\" title=\"Visualizar Eventos\"></i> ";
				break;
				
				case '6':
					$icons.="<a data-toggle=\"modal\" href=\"/erp/faturamento/get-eventos/id/{$data['id_registro']}\" data-target=\"#EventosModal\"><i class=\"splashy-documents_edit\" title=\"Visualizar Eventos\"></i></a> ";
				break;
				//Notas Denegadas
								
			}
			
			$row = array();
			$row[] = $data['nomefantasia'];
			$row[] = $data['nNF'];
			$row[] = $data['xNome'];
			$row[] = Functions_Datas::MyDateTime($data['dEmi']);
			$row[] = Functions_Datas::MyDateTime($data['dSaiEnt']);
			$row[] = $data['natOp'];
			$row[] = number_format($data['vNF'],2,',','');
			$row[] = $icons;
				
				
				
			$output['aaData'][] = $row;
			$row = null;
			$icons = null;
		}
		
		echo json_encode( $output );
		
	}
	
	
	public function listarNfeEmitidaResumoAction(){
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$iTotal = count($db->fetchAll("status_processo in (1,2) and tipo_nfe in (1,2,3)"));
	
		$aColumns = array("b.nomefantasia","a.nNF",'c.xNome','a.dEmi','a.dSaiEnt','a.natOp','d.vNF');
	
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblnfe_basicos'),array('a.id_registro',
				'a.id_empresa',
				'a.nNF',
				'a.dEmi',
				'a.dSaiEnt',
				'a.natOp',
				'a.tipo_nfe',
				'a.status_processo',
				'b.nomefantasia',
				'c.xNome',
				'd.vNF'
		))
		->join(array('b'=>'tblsystem_empresas'),'b.id_registro = a.id_empresa',array())
		->join(array('c'=>'tblnfe_destinatario'), 'c.id_nfe = a.id_registro',array())
		->join(array('d'=>'tblnfe_totais'),'d.id_nfe= a.id_registro',array())
		->where("a.status_processo in (1,2)")
		->where("a.tipo_nfe in (1,2,3)");
		//echo $return;
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
	
				$sWhere= "UPPER(".$aColumns[$i].") LIKE '%". strtoupper($_GET['sSearch']) ."%'";
					
	
				if($i==0){
					$return->where($sWhere);
				}else{
					$return->orWhere($sWhere);
				}
			}
	
		}
		if ( isset( $_GET['iSortCol_0'] ) )
		{
	
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
					$type =  $_GET['sSortDir_'.$i];
					$return->order("$collum $type");
	
	
				}
			}
	
	
	
	
		}else{
			$return->order("id_registro DESC");
		}
			
		if($_GET['iDisplayLength']){
			$tamanho = $_GET['iDisplayLength'];
		}else{
			$tamanho = 10;
		}
		if($_GET['iDisplayStart']){
			$inicio = $_GET['iDisplayStart'];
		}else{
			$inicio = 0;
		}
			
		$return->limit($tamanho,$inicio);
			
			
			
			
			
		$rs = $return->query();
		$dados = $rs->fetchAll();
			
		$iFilteredTotal = count($dados);
			
			
		$output = array(
				"sEcho" => $_GET['sEcho'],
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
		);
		foreach($dados as $data){
				
			$icons.="<a href=\"/erp/faturamento/get-xml/id/{$data['id_registro']}\" target=\"_blank\"><img src=\"/img/file_pdf.png\" width=\"20px\" heigth=\"20px\" title=\"Visualizar DANFE\"></a> ";
			$icons.="<a href=\"/erp/faturamento/imprimir-danfe/id/{$data['id_registro']}\" target=\"_blank\"><img src=\"/img/xml.png\" width=\"20px\" heigth=\"20px\" title=\"Visualizar XML\"></a> ";
				
			switch($data['status_processo']){
				//Notas Assinadas
				case '1':
					$icons.="<a href=\"/erp/faturamento/emitir-nfe/id/{$data['id_registro']}\"><i class=\"splashy-upload\" title=\"Enviar NFe\"></i></a> ";
						
					break;
					// Notas Enviadas
				case '2':
					$icons.="<a href=\"/erp/faturamento/consulta-retorno/id/{$data['id_registro']}\"><i class=\"splashy-refresh\" title=\"Verificar Resposta NFe\"></i> ";
						
	
					break;
					//Notas Aprovadas
				case '3':
						
					$icons.="<i class=\"splashy-document_letter_marked\" title=\"Carta de Correção CCe\"></i> ";
					$icons.="<i class=\"splashy-error_do_not\" title=\"Cancelar NFe\"></i> ";
					$icons.="<i class=\"splashy-documents_edit\" title=\"Visualizar Eventos\"></i> ";
						
						
						
					break;
	
				case '4':
				case '5':
					$icons.="<i class=\"splashy-documents_edit\" title=\"Visualizar Eventos\"></i> ";
					break;
					//Notas Denegadas
	
			}
				
			$row = array();
			$row[] = $data['nomefantasia'];
			$row[] = $data['nNF'];
			$row[] = $data['xNome'];
			$row[] = number_format($data['vNF'],2,',','');
			$row[] = $icons;
	
	
	
			$output['aaData'][] = $row;
			$row = null;
			$icons = null;
		}
	
		echo json_encode( $output );
	
	}
	
	
	public function listarNfeRecebidaAction(){
		$status_processo = $this->_getParam('type');
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$iTotal = count($db->fetchAll("status_processo = '$status_processo' and tipo_nfe in (4,5,6)"));
	
		$aColumns = array("b.nomefantasia","a.nNF",'c.xNome','a.dEmi','a.dSaiEnt','a.natOp','d.vNF');
	
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblnfe_basicos'),array('a.id_registro',
				'a.id_empresa',
				'a.nNF',
				'a.dEmi',
				'a.dSaiEnt',
				'a.natOp',
				'a.tipo_nfe',
				'a.status_processo',
				'b.nomefantasia',
				'c.xNome',
				'd.vNF'
		))
		->join(array('b'=>'tblsystem_empresas'),'b.id_registro = a.id_empresa',array())
		->join(array('c'=>'tblnfe_emitente'), 'c.id_nfe = a.id_registro',array())
		->join(array('d'=>'tblnfe_totais'),'d.id_nfe= a.id_registro',array())
		->where("a.status_processo = '$status_processo'")
		->where("a.tipo_nfe in (4,5,6)");
		//echo $return;
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
	
				$sWhere= "UPPER(".$aColumns[$i].") LIKE '%". strtoupper($_GET['sSearch']) ."%'";
					
	
				if($i==0){
					$return->where($sWhere);
				}else{
					$return->orWhere($sWhere);
				}
			}
	
		}
		if ( isset( $_GET['iSortCol_0'] ) )
		{
	
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
					$type =  $_GET['sSortDir_'.$i];
					$return->order("$collum $type");
	
	
				}
			}
	
	
	
	
		}else{
			$return->order("id_registro DESC");
		}
			
		if($_GET['iDisplayLength']){
			$tamanho = $_GET['iDisplayLength'];
		}else{
			$tamanho = 10;
		}
		if($_GET['iDisplayStart']){
			$inicio = $_GET['iDisplayStart'];
		}else{
			$inicio = 0;
		}
			
		$return->limit($tamanho,$inicio);
			
			
			
			
			
		$rs = $return->query();
		$dados = $rs->fetchAll();
			
		$iFilteredTotal = count($dados);
			
			
		$output = array(
				"sEcho" => $_GET['sEcho'],
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
		);
		foreach($dados as $data){
				
			$icons.="<a href=\"/erp/faturamento/get-xml/id/{$data['id_registro']}\" target=\"_blank\"><img src=\"/img/file_pdf.png\" width=\"20px\" heigth=\"20px\" title=\"Visualizar DANFE\"></a> ";
			$icons.="<a href=\"/erp/faturamento/imprimir-danfe-emitente/id/{$data['id_registro']}\" target=\"_blank\"><img src=\"/img/xml.png\" width=\"20px\" heigth=\"20px\" title=\"Visualizar XML\"></a> ";
				
						
			$row = array();
			$row[] = $data['nomefantasia'];
			$row[] = $data['nNF'];
			$row[] = $data['xNome'];
			$row[] = Functions_Datas::MyDateTime($data['dEmi']);
			$row[] = Functions_Datas::MyDateTime($data['dSaiEnt']);
			$row[] = $data['natOp'];
			$row[] = number_format($data['vNF'],2,',','');
			$row[] = $icons;
	
	
	
			$output['aaData'][] = $row;
			$row = null;
			$icons = null;
		}
	
		echo json_encode( $output );
	
	}
	
	
	
	
	//**INUTILIZADAS
	
	public function listarInutilizadasAction(){
		$status_processo = $this->_getParam('type');
		$db = new Erp_Model_Faturamento_NFe_Inutilizar();
		$iTotal = count($db->fetchAll());
	
		$aColumns = array("b.nomefantasia","a.nIni",'a.nFim','a.xJust','a.cStat','a.datasolicitacao','c.nomecompleto');
	
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblnfe_inutilizadas'),array('a.id_registro',
				'a.id_empresa',
				'a.nIni',
				'a.nFim',
				'a.cStat',
				'a.xMotivo',
				'a.xJust',
				'a.datasolicitacao',
				'a.user_id',
				"b.nomefantasia",
				'c.nomecompleto'
		))
		->join(array('b'=>'tblsystem_empresas'),'b.id_registro = a.id_empresa',array())
		->join(array('c'=>'tblsystem_users'), 'c.id_registro = a.user_id',array());
		//echo $return;
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
	
				$sWhere= "UPPER(".$aColumns[$i].") LIKE '%". strtoupper($_GET['sSearch']) ."%'";
					
	
				if($i==0){
					$return->where($sWhere);
				}else{
					$return->orWhere($sWhere);
				}
			}
	
		}
		if ( isset( $_GET['iSortCol_0'] ) )
		{
	
			for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
			{
				if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
				{
					$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
					$type =  $_GET['sSortDir_'.$i];
					$return->order("$collum $type");
	
	
				}
			}
	
	
	
	
		}else{
			$return->order("id_registro DESC");
		}
			
		if($_GET['iDisplayLength']){
			$tamanho = $_GET['iDisplayLength'];
		}else{
			$tamanho = 10;
		}
		if($_GET['iDisplayStart']){
			$inicio = $_GET['iDisplayStart'];
		}else{
			$inicio = 0;
		}
			
		$return->limit($tamanho,$inicio);
			
			
			
			
			
		$rs = $return->query();
		$dados = $rs->fetchAll();
			
		$iFilteredTotal = count($dados);
			
			
		$output = array(
				"sEcho" => $_GET['sEcho'],
				"iTotalRecords" => $iTotal,
				"iTotalDisplayRecords" => $iTotal,
				"aaData" => array()
		);
		foreach($dados as $data){
		//	$aColumns = array("b.nomefantasia","a.nIni",'a.nFim','a.xJust','a.cStat','a.datasolicitacao','c.nomecompleto');				
				
			$row = array();
			$row[] = $data['nomefantasia'];
			$row[] = $data['nIni'];
			$row[] = $data['nFim'];
			$row[] = $data['xJust'];
			$row[] = "({$data['cStat']}) {$data['xMotivo']}";
			$row[] = Functions_Datas::MyDateTime($data['datasolicitacao']);
			$row[] = $data['nomecompleto'];
			
	
	
	
			$output['aaData'][] = $row;
			$row = null;
			$icons = null;
		}
	
		echo json_encode( $output );
	
	}
	
}
