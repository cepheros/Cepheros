<?php
class Crm_ListaTicketsController extends Zend_Controller_Action{
	
	public function init(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
	}
	
	public function naoresolvidosAction(){
		$db = new Crm_Model_TicketsBasicos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$FILTER = "atribuidoa = '$user_id' and statusticket in ($openstatus)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
				$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	
	
	public function meusacompanhamentosAction(){
		$db = new Crm_Model_TicketsBasicos();
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = Crm_Model_TicketsAcompanhantes::getAcompanhamentos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "atribuidoa = '$user_id' and statusticket in ($openstatus) and id_registro in ($acompanhados)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	
	
	
	public function naoatribuidosAction(){
		$db = new Crm_Model_TicketsBasicos();
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "atribuidoa = '0'  and statusticket in ($openstatus) and departamento in ($acompanhados)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	
	public function atualizadosrecenteAction(){
		$db = new Crm_Model_TicketsBasicos();
		$last24 = Functions_Datas::SubtraiData(date('d/m/Y'), 2);
		$las24ok = Functions_Datas::inverteData($last24);
		$datainicio = $las24ok." ".date('H:i:s');
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "departamento in ($acompanhados) and datelastreply >= '$datainicio' ";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	
	public function naoresolvidosdeptosAction(){
		$db = new Crm_Model_TicketsBasicos();
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "statusticket in ($openstatus) and departamento in ($acompanhados)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	public function pendentesAction(){
		$db = new Crm_Model_TicketsBasicos();
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getPendentStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "statusticket in ($openstatus) and departamento in ($acompanhados)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	public function resolvidosrecenteAction(){
		$db = new Crm_Model_TicketsBasicos();
		$last24 = Functions_Datas::SubtraiData(date('d/m/Y'), 2);
		$las24ok = Functions_Datas::inverteData($last24);
		$datainicio = $las24ok." ".date('H:i:s');
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getCloseStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "statusticket in ($openstatus) and dateclosed >= '$datainicio' ";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	
	
	public function suspensosAction(){
		$db = new Crm_Model_TicketsBasicos();
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getSuspendedStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "statusticket in ($openstatus) and departamento in ($acompanhados)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}

	
	public function encerradosAction(){
		$db = new Crm_Model_TicketsBasicos();
			$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getCloseStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$FILTER = "statusticket in ($openstatus) and departamento in ($acompanhados)";
		$aColumns = array( 'departamento','protocolo', 'assuntoticket', 'nomesolicitante','dateopen','tipoticket','prioridadeticket');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_TicketsBasicos();
		$return = $dados->select()->where($FILTER);
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
		{
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
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
			$abertoa = Functions_Datas::get_time_difference($data['dateopen']);
			$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['protocolo'];
			$row[] = $data['assuntoticket'];
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = Crm_Model_TicketsTipos::getNomeTipo($data['tipoticket']);
			$row[] = Crm_Model_TicketsPrioridades::getNomeTipo($data['prioridadeticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
}