<?php
class Crm_ListaOsController extends Zend_Controller_Action{
	
	public function init(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
	}
	
	
	public function novasAction(){
		
		$status = Crm_Model_Os_Status::getOpenStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$FILTER = "user_open = '$user_id' and status_os in ($openstatus)";
		$aColumns = array( 'id_registro','cod_os', 'dataabertura');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_Os_Basicos();
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
			$datadobanco = Functions_Datas::MyDateTime($data['dataabertura']);
			$totais = Crm_Model_Os_Basicos::totalOs($data['id_registro']);
			$totalprodutos = $totais[0]['totalprodutos'];
			$totalservicos = $totais[0]['totalservicos'];
							
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = $data['cod_os'];
			$row[] = $datadobanco;
			$row[] = Crm_Model_Os_Tipos::getNomeTipo($data['tipo_os']);
			$row[] = Cadastros_Model_Contatos::getNomeContato($data['id_contato']) . " (". Cadastros_Model_Pessoas::getNomeEmpresa($data['id_cliente']) .") ";
			$row[] = 'R$ '.number_format($totalprodutos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos + $totalprodutos,2,',','');
			$row[] = "<a href='/crm/ordem-servico/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-box_edit\"></i></a>	<a href='/crm/ordem-servico/print/id/{$data['id_registro']}' title='Imprimir OS' target=\"_blank\"><i class=\"splashy-printer\"></i></a>";
							$output['aaData'][] = $row;
							$row = null;
		}
		echo json_encode( $output );
		
	}
	public function andamentoAction(){
		

		$status = Crm_Model_Os_Status::getPendentStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$FILTER = "user_open = '$user_id' and status_os in ($openstatus)";
		$aColumns = array( 'id_registro','cod_os', 'dataabertura');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_Os_Basicos();
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
			$datadobanco = Functions_Datas::MyDateTime($data['dataabertura']);
			$totais = Crm_Model_Os_Basicos::totalOs($data['id_registro']);
			$totalprodutos = $totais[0]['totalprodutos'];
			$totalservicos = $totais[0]['totalservicos'];
				
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = $data['cod_os'];
			$row[] = $datadobanco;
			$row[] = Crm_Model_Os_Tipos::getNomeTipo($data['tipo_os']);
			$row[] = Cadastros_Model_Contatos::getNomeContato($data['id_contato']) . " (". Cadastros_Model_Pessoas::getNomeEmpresa($data['id_cliente']) .") ";
			$row[] = 'R$ '.number_format($totalprodutos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos + $totalprodutos,2,',','');
			$row[] = "<a href='/crm/ordem-servico/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-box_edit\"></i></a>	<a href='/crm/ordem-servico/print/id/{$data['id_registro']}' title='Imprimir OS' target=\"_blank\"><i class=\"splashy-printer\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
		
		
	}
	public function encerradasAction(){
		

		$status = Crm_Model_Os_Status::getCloseStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$FILTER = "user_open = '$user_id' and status_os in ($openstatus)";
		$aColumns = array( 'id_registro','cod_os', 'dataabertura');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_Os_Basicos();
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
			$datadobanco = Functions_Datas::MyDateTime($data['dataabertura']);
			$totais = Crm_Model_Os_Basicos::totalOs($data['id_registro']);
			$totalprodutos = $totais[0]['totalprodutos'];
			$totalservicos = $totais[0]['totalservicos'];
				
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = $data['cod_os'];
			$row[] = $datadobanco;
			$row[] = Crm_Model_Os_Tipos::getNomeTipo($data['tipo_os']);
			$row[] = Cadastros_Model_Contatos::getNomeContato($data['id_contato']) . " (". Cadastros_Model_Pessoas::getNomeEmpresa($data['id_cliente']) .") ";
			$row[] = 'R$ '.number_format($totalprodutos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos + $totalprodutos,2,',','');
			$row[] = "<a href='/crm/ordem-servico/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-box_edit\"></i></a>	<a href='/crm/ordem-servico/print/id/{$data['id_registro']}' title='Imprimir OS' target=\"_blank\"><i class=\"splashy-printer\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
		
		
	}
	
	public function faturadasAction(){
		
		

		$status = Crm_Model_Os_Status::getSuspendedStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$FILTER = "user_open = '$user_id' and status_os in ($openstatus)";
		$aColumns = array( 'id_registro','cod_os', 'dataabertura');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$iTotal = count($db->fetchAll($FILTER));
		$dados = new Crm_Model_Os_Basicos();
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
			$datadobanco = Functions_Datas::MyDateTime($data['dataabertura']);
			$totais = Crm_Model_Os_Basicos::totalOs($data['id_registro']);
			$totalprodutos = $totais[0]['totalprodutos'];
			$totalservicos = $totais[0]['totalservicos'];
				
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = $data['cod_os'];
			$row[] = $datadobanco;
			$row[] = Crm_Model_Os_Tipos::getNomeTipo($data['tipo_os']);
			$row[] = Cadastros_Model_Contatos::getNomeContato($data['id_contato']) . " (". Cadastros_Model_Pessoas::getNomeEmpresa($data['id_cliente']) .") ";
			$row[] = 'R$ '.number_format($totalprodutos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos,2,',','');
			$row[] = 'R$ '.number_format($totalservicos + $totalprodutos,2,',','');
		$row[] = "<a href='/crm/ordem-servico/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-box_edit\"></i></a>	<a href='/crm/ordem-servico/print/id/{$data['id_registro']}' title='Imprimir OS' target=\"_blank\"><i class=\"splashy-printer\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		echo json_encode( $output );
		
		
	}
	
	
}
