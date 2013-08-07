<?php
class Crm_ListaProspectsController extends Zend_Controller_Action{
	
	public function init(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
	}
	
	public function ativosAction(){
		$status = Crm_Model_Prospects_EstagioProposta::getOpenStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "atribuidoa = '$user_id' and estagioproposta in ($openstatus)";
		$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_Prospects_Basico();
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
			$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
			$datehoje = strtotime(date('Y-m-d'));
			$datecompar = strtotime($data['datalimite']);
			
			if( $datecompar <  $datehoje){
				$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>"; 
			}else{
				$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>"; 
			}
			
			
			$row = array();
			$row[] = $data['protocolo'];
			$row[] = $data['nomeempresa'];
			$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
			$row[] = number_format($data['probabilidadeproposta'],0).'%';
			$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
			$row[] = $rowdate;
			$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
	}
	
	
	public function naoatribuidosAction(){
		
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getOpenStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "atribuidoa = '0' and estagioproposta in ($openstatus)";
		
		$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_Prospects_Basico();
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
			$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
			$datehoje = strtotime(date('Y-m-d'));
			$datecompar = strtotime($data['datalimite']);
				
			if( $datecompar <  $datehoje){
				$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
			}else{
			$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
			}
				
				
			$row = array();
			$row[] = $data['protocolo'];
					$row[] = $data['nomeempresa'];
							$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
							$row[] = number_format($data['probabilidadeproposta'],0).'%';
							$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
							$row[] = $rowdate;
							$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
							$output['aaData'][] = $row;
							$row = null;
		}
			echo json_encode( $output );
		}
		
		
		public function suspensosAction(){
		
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_Prospects_EstagioProposta::getSuspendedStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "estagioproposta in ($openstatus) and atribuidoa = '$user_id'";
		
			$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$iTotal = count($db->fetchAll($FILTER));
			$dados = new Crm_Model_Prospects_Basico();
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
				$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
				$datehoje = strtotime(date('Y-m-d'));
				$datecompar = strtotime($data['datalimite']);
		
				if( $datecompar <  $datehoje){
					$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
				}else{
					$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
				}
		
		
				$row = array();
				$row[] = $data['protocolo'];
				$row[] = $data['nomeempresa'];
				$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
				$row[] = number_format($data['probabilidadeproposta'],0).'%';
				$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
				$row[] = $rowdate;
				$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
			echo json_encode( $output );
		}
		
		
		
		public function pendentesAction(){
		
			$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_Prospects_EstagioProposta::getPendentStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "estagioproposta in ($openstatus) and atribuidoa = '$user_id'";
		
			$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$iTotal = count($db->fetchAll($FILTER));
			$dados = new Crm_Model_Prospects_Basico();
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
				$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
				$datehoje = strtotime(date('Y-m-d'));
				$datecompar = strtotime($data['datalimite']);
		
				if( $datecompar <  $datehoje){
					$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
				}else{
					$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
				}
		
		
				$row = array();
				$row[] = $data['protocolo'];
				$row[] = $data['nomeempresa'];
				$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
				$row[] = number_format($data['probabilidadeproposta'],0).'%';
				$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
				$row[] = $rowdate;
				$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
			echo json_encode( $output );
		}
		
		
		public function atualizadosrecenteAction(){
		
			$last24 = Functions_Datas::SubtraiData(date('d/m/Y'), 2);
		$las24ok = Functions_Datas::inverteData($last24);
		$datainicio = $las24ok." ".date('H:i:s');
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getCloseStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "datelastupdate >= '$datainicio' and estagioproposta not in ($openstatus)  and atribuidoa = '$user_id' ";
		
			$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$iTotal = count($db->fetchAll($FILTER));
			$dados = new Crm_Model_Prospects_Basico();
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
				$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
				$datehoje = strtotime(date('Y-m-d'));
				$datecompar = strtotime($data['datalimite']);
		
				if( $datecompar <  $datehoje){
					$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
				}else{
					$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
				}
		
		
				$row = array();
				$row[] = $data['protocolo'];
				$row[] = $data['nomeempresa'];
				$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
				$row[] = number_format($data['probabilidadeproposta'],0).'%';
				$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
				$row[] = $rowdate;
				$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
			echo json_encode( $output );
		}
		
		
		
		public function encerradosrecenteAction(){
		
			$last24 = Functions_Datas::SubtraiData(date('d/m/Y'), 2);
		$las24ok = Functions_Datas::inverteData($last24);
		$datainicio = $las24ok." ".date('H:i:s');
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getCloseStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "estagioproposta in ($openstatus) and datelastupdate >= '$datainicio'  and atribuidoa = '$user_id' ";
		
			$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$iTotal = count($db->fetchAll($FILTER));
			$dados = new Crm_Model_Prospects_Basico();
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
				$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
				$datehoje = strtotime(date('Y-m-d'));
				$datecompar = strtotime($data['datalimite']);
		
				if( $datecompar <  $datehoje){
					$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
				}else{
					$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
				}
		
		
				$row = array();
				$row[] = $data['protocolo'];
				$row[] = $data['nomeempresa'];
				$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
				$row[] = number_format($data['probabilidadeproposta'],0).'%';
				$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
				$row[] = $rowdate;
				$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
			echo json_encode( $output );
		}
		
		
		
		public function encerradosAction(){
		
			$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getCloseStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$FILTER = "estagioproposta in ($openstatus) and atribuidoa = '$user_id'";
		
			$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$iTotal = count($db->fetchAll($FILTER));
			$dados = new Crm_Model_Prospects_Basico();
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
				$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
				$datehoje = strtotime(date('Y-m-d'));
				$datecompar = strtotime($data['datalimite']);
		
				if( $datecompar <  $datehoje){
					$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
				}else{
					$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
				}
		
		
				$row = array();
				$row[] = $data['protocolo'];
				$row[] = $data['nomeempresa'];
				$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
				$row[] = number_format($data['probabilidadeproposta'],0).'%';
				$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
				$row[] = $rowdate;
				$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
			echo json_encode( $output );
		}
		
		
		
		public function geralAction(){
			$db = new Crm_Model_Prospects_Basico();
			
			$FILTER = "id_registro > 0";
		
			$aColumns = array( 'protocolo','nomeempresa', 'estagioproposta', 'probabilidadeproposta','valorproposta','datalimite');
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$iTotal = count($db->fetchAll($FILTER));
			$dados = new Crm_Model_Prospects_Basico();
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
				$datadobanco = Functions_Datas::MyDateTime($data['datalimite']);
				$datehoje = strtotime(date('Y-m-d'));
				$datecompar = strtotime($data['datalimite']);
		
				if( $datecompar <  $datehoje){
					$rowdate = "<span class=\"label label-important ttip_t\" title=\"Atrasado\">$datadobanco</span>";
				}else{
					$rowdate = "<span class=\"label label-success ttip_t\" title=\"Em Dia\">$datadobanco</span>";
				}
		
		
				$row = array();
				$row[] = $data['protocolo'];
				$row[] = $data['nomeempresa'];
				$row[] = Crm_Model_Prospects_EstagioProposta::getNomeDepto($data['estagioproposta']);
				$row[] = number_format($data['probabilidadeproposta'],0).'%';
				$row[] = 'R$ '.number_format($data['valorproposta'],2,',','');
				$row[] = $rowdate;
				$row[] = "<a href='/crm/prospects/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
			echo json_encode( $output );
		}
	

}