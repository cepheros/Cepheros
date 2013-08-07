<?php
class System_DatatablesSistemaController extends Zend_Controller_Action{
	
	public function recursosAction(){
		
		$aColumns = array( 'id', 'resource', 'descriprion');
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$db = new System_Model_Login_Resources();
		
		$iTotal = count($db->fetchAll());
			
		$dados = new System_Model_Login_Resources();
		$return = $dados->select();
		
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
			
			$row = array();
			$row[] = $data['id'];
			$row[] = $data['resource'];
			$row[] = $data['description'];
			$row[] = "<i style=\"cursor:pointer\" class=\"splashy-document_letter_edit editar\" idr=\"{$data['id']}\" resource=\"{$data['resource']}\" description=\"{$data['description']}\"></i>";
			$output['aaData'][] = $row;
			$row = null;
		}
		
		
		
		
		echo json_encode( $output );
	}
	

	
	public function regrasAction(){
	
		$aColumns = array( 'id', 'role', 'id_parent');
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$db = new System_Model_Login_Roles();
	
		$iTotal = count($db->fetchAll("id <> '3'"));
			
		$dados = new System_Model_Login_Roles();
		$return = $dados->select()->where("id <> '3'");
	
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
				
			$row = array();
			$row[] = $data['id'];
			$row[] = $data['role'];
			$row[] = $data['id_parent'];
			$row[] = "<i style=\"cursor:pointer\" class=\"splashy-document_letter_edit editar\" idr=\"{$data['id']}\" regra=\"{$data['role']}\" id_parent=\"{$data['id_parent']}\"></i>	<a href=\"/sistema/usuarios/permissoes/idrole/{$data['id']}\" title=\"Regras de Acesso\"><i class=\"splashy-group_blue_new\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
	
	
	
	
		echo json_encode( $output );
	}
	
	
	public function menusAction(){
	
		$aColumns = array( 'id_registro', 'module', 'controller','action','parans','nome','position');
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$db = new System_Model_Menus();
	
		$iTotal = count($db->fetchAll());
			
		$dados = new System_Model_Menus();
		$return = $dados->select();
	
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
	
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = $data['module'];
			$row[] = $data['controller'];
			$row[] = $data['action'];
			$row[] = $data['parans'];
			$row[] = $data['nome'];
			$row[] = $data['position'];
			$row[] = "<i style=\"cursor:pointer\" class=\"splashy-document_letter_edit editar\" idr=\"{$data['id_registro']}\" module=\"{$data['module']}\" controller=\"{$data['controller']}\" action=\"{$data['action']}\" parans=\"{$data['parans']}\" nomemenu=\"{$data['nome']}\" position=\"{$data['position']}\"></i>	";
			$output['aaData'][] = $row;
			$row = null;
		}
	
	
	
	
		echo json_encode( $output );
	}
}