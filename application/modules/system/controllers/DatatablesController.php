<?php
class System_DatatablesController extends Zend_Controller_Action{
	
	
	public function init(){
		error_reporting(0);
	}
	
	public function pessoasAction(){
		$cadtype = $this->_getParam('type');
		if($cadtype <> '0'){
			$FILTER = 	"tipocadastro = '$cadtype'";
		}else{
			$FILTER = 	"id_registro > '0'";
		}
		

		$aColumns = array( 'id_registro', 'id_empresa', 'razaosocial', 'cnpj', 'nomefantasia', 'alteracoes' );
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$db = new Cadastros_Model_Pessoas();
		
		$iTotal = count($db->fetchAll($FILTER));
			
		$dados = new Cadastros_Model_Pessoas();
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
			if($data['alteracoes'] == ''){
				$data['alteracoes'] = 0;
			}
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = System_Model_Empresas::getNomeFantasiaEmpresa($data['id_empresa']);
			$row[] = $data['razaosocial'];
			$row[] = $data['cnpj'];
			$row[] = $data['nomefantasia'];
			$row[] = $data['alteracoes'];
			$row[] = "<a href='/cadastros/pessoas/cadastro/id/{$data['id_registro']}' title='Editar'><i class='splashy-document_letter_edit'></i></a><a href='/cadastros/pessoas/remover/id/{$data['id_registro']}' title='Remover'><i class='splashy-document_letter_remove'></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		
		
		
		
		echo json_encode( $output );
	}
	
	public function empresasAction(){
	
			$FILTER = 	"id_registro > '0'";
	
		
		$aColumns = array( 'principal','id_registro', 'razaosocial', 'cnpj','nomefantasia');
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$db = new System_Model_Empresas();
		
		$iTotal = count($db->fetchAll($FILTER));
			
		$dados = new System_Model_Empresas();
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
			if($data['principal'] == 1){
				$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_full enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			}else{
				$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			}
		
			$row = array();
			$row[] = $firststar;
			$row[] = $data['id_registro'];
			$row[] = $data['razaosocial'];
			$row[] = $data['cnpj'];
			$row[] = $data['nomefantasia'];
			$row[] = "<a href='/sistema/empresas/cadastro/id/{$data['id_registro']}' title='Editar'><i class='splashy-document_letter_edit'></i></a><a href='/sistema/empresas/remover/id/{$data['id_registro']}' title='Remover'><i class='splashy-document_letter_remove'></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		
		
		
		
		echo json_encode( $output );
	}
	
	
	public function usuariosAction(){
	
		$FILTER = 	"id_registro <> '1'";
	
	
		$aColumns = array( 'id_registro','nomecompleto', 'id_role', 'celular','email');
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$db = new System_Model_Users();
	
		$iTotal = count($db->fetchAll($FILTER));
			
		$dados = new System_Model_Users();
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
		
			$row = array();
			$row[] = $data['id_registro'];
			$row[] = $data['nomecompleto'];
			$row[] = $data['email'];
			$row[] = $data['phonenumber'];
			$row[] = System_Model_Login_Roles::getNomeRole($data['id_role']);
			$row[] = "<a href='/sistema/usuarios/usuario/id/{$data['id_registro']}' title='Editar'><i class='splashy-document_letter_edit'></i></a><a href='/sistema/usuarios/remover/id/{$data['id_registro']}' title='Remover'><i class='splashy-document_letter_remove'></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
	
	
	
	
		echo json_encode( $output );
	}
	
	
	public function ticketsHistoricoAction(){
		
		$db = new Crm_Model_TicketsBasicos();
		
		$thisticket = $this->_getParam('thisticket');
		$dadosticket = $db->fetchRow("id_registro = '$thisticket'");
	//	print_r($dadosticket);
			
		$FILTER = 	"id_registro <> '$thisticket' and emailsolicitante = '{$dadosticket->emailsolicitante}' ";
		
		
		$aColumns = array( 'id_registro','protocolo', 'departamento', 'nomesolicitante','dateopen','datelastreply','statusticket');
		
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
		//$aColumns = array( 'id_registro','protocolo', 'departamento', 'nomesolicitante','dateopen','datelastreply','statusticket');
		
		foreach($dados as $data){
		//	if(Crm_Model_TicketsFlags::checkFlag($data['id_registro'], Zend_Auth::getInstance()->getStorage()->read())){
		//		$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_full enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
		//	}else{
		//		$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
		//	}
		if($data['statusticket'] <> 3){
	      $abertoa = Functions_Datas::get_time_difference($data['dateopen']);
		}else{
		  $abertoa = Functions_Datas::get_time_difference($data['dateclosed']);
		}
		if(isset($data['datelastreply'])){
			$last = Functions_Datas::get_time_difference($data['datelastreply']);
			$lastreply = "{$last['days']} d {$last['hours']} h";
		}else{
			$lastreply = "Sem Resposta";
		}
		
		$firststar =  "<i style=\"cursor:pointer\" class=\"splashy-star_empty enpstar\"  idemp=\"{$data['id_registro']}\"></i>";
			$row = array();
			$row[] = $firststar;
			$row[] = $data['id_registro'];
			$row[] = $data['protocolo'];
			$row[] = Crm_Model_TicketsDeptos::getNomeDepto($data['departamento']);
			$row[] = $data['nomesolicitante'];
			$row[] = "{$abertoa['days']} d {$abertoa['hours']} h";
			$row[] = $lastreply;
			$row[] = Crm_Model_TicketsStatus::getNomeTipo($data['statusticket']);
			$row[] = "<a href='/crm/tickets/abrir/id/{$data['id_registro']}' title='Abrir'><i class=\"splashy-ticket\"></i></a><a href='javascript:void(0)' onclick=\"agrupartickets({$data['id_registro']},{$dadosticket->id_registro},'{$data['protocolo']}')\" title='Agrupar com este'><i class=\"splashy-ticket_add\"></i></a>";
			$output['aaData'][] = $row;
			$row = null;
		}
		
		
		
		
		echo json_encode( $output );
		}

		
		public function servicosAction(){
			
		
		
			$aColumns = array( 'a.id_registro', 'b.nome', 'nomeservico', 'valorservico', 'c.descricaoiss');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_Servicos();
		
			$iTotal = count($db->fetchAll());
				
			$dados = new Cadastros_Model_Servicos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblservicos_basicos'),array('a.id_registro','a.nomeservico','a.valorservico','b.nome as nometiposervico','c.descricaoiss as nomeiss'))
			->join(array('b'=>'tblapoio_tiposdeservicos'), 'b.id_registro = a.tiposervico',array())
			->join(array('c'=>'tblapoio_iss'),'c.id_registro = a.iss',array());
			
					
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
				$row[] = $data['nometiposervico'];
				$row[] = $data['nomeservico'];
				$row[] = "R$ ".number_format($data['valorservico'],2,',',''); 
				$row[] = $data['nomeiss'];
				$row[] = "<a href='/cadastros/servicos/abrir/id/{$data['id_registro']}' title='Editar'><i class='splashy-document_letter_edit'></i></a><a href='/cadastros/servicos/remover/id/{$data['id_registro']}' title='Remover'><i class='splashy-document_letter_remove'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function produtosAction(){
				
		
		
			$aColumns = array( 'a.id_registro', 'a.codigointerno', 'b.nomecategoria', 'c.nomesubcategoria', 'a.nomeproduto','a.referenciaproduto');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_Produtos();
		
			$iTotal = count($db->fetchAll());
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblprodutos_basicos'),array('a.id_registro','a.estoqueminimo','a.estoquemaximo','a.nomeproduto','a.referenciaproduto','a.codigointerno','b.nomecategoria as nomecategoria','c.nomesubcategoria as nomesubcategoria'))
			->join(array('b'=>'tblapoio_categoriasprodutos'), 'b.id_registro = a.categoriaproduto',array())
			->join(array('c'=>'tblapoio_subcategoriadeprodutos'),'c.id_registro = a.subcategoriaproduto',array());
				
				
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
				$numeroestoque = Erp_Model_Estoque_Movimento::estoqueAtual($data['id_registro'], '1');
				$estoqueatual = number_format($numeroestoque,2,',','');
				$estalert = (($data['estoqueminimo'] / 100) * System_Model_SysConfigs::getConfig('EstoqueAlertPercent')) + $data['estoqueminimo'];
				if($data['estoqueminimo'] <> 0 && $numeroestoque <= $data['estoqueminimo']){
					$quantidadeestoque = "<span class=\"label label-important ttip_t\" title=\"Estoque em Alerta (Minimo: {$data['estoqueminimo']}) \">$estoqueatual</span>";
				}elseif ($data['estoquemaximo'] <> 0 && $numeroestoque >= $data['estoquemaximo']){
					$quantidadeestoque = "<span class=\"label label-important ttip_t\" title=\"Estoque em Alerta (Maximo: {$data['estoquemaximo']})\">$estoqueatual</span>";
				}elseif($data['estoqueminimo'] <> 0 && $numeroestoque <= $estalert){
					$quantidadeestoque = "<span class=\"label label-warning ttip_t\" title=\"Estoque Próximo ao minimo (Minimo: {$data['estoqueminimo']})\">$estoqueatual</span>";
				
				}else{
					$quantidadeestoque = "<span class=\"label label-success ttip_t\" title=\"Estoque Normal\">$estoqueatual</span>";
				}
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['codigointerno'];
				$row[] = $data['nomecategoria'];
				$row[] = $data['nomesubcategoria'];
				$row[] = $data['nomeproduto'];
				$row[] = $data['referenciaproduto'];
				$row[] = $quantidadeestoque;
				$row[] = "<a href='/cadastros/produtos/abrir/id/{$data['id_registro']}' title='Editar'><i class='splashy-document_letter_edit'></i></a><a href='/cadastros/produtos/excluir/id/{$data['id_registro']}' title='Remover'><i class='splashy-document_letter_remove'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function produtosCompostosAction(){
		
		
		
			$aColumns = array( 'a.id_registro', 'a.codigointerno', 'b.nomecategoria', 'c.nomesubcategoria', 'a.nomeproduto','a.referenciaproduto');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_Produtos();
		
			$iTotal = count($db->fetchAll());
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblprodutos_basicos'),array('a.id_registro','a.estoqueminimo','a.estoquemaximo','a.nomeproduto','a.referenciaproduto','a.codigointerno','b.nomecategoria as nomecategoria','c.nomesubcategoria as nomesubcategoria'))
			->join(array('b'=>'tblapoio_categoriasprodutos'), 'b.id_registro = a.categoriaproduto',array())
			->join(array('c'=>'tblapoio_subcategoriadeprodutos'),'c.id_registro = a.subcategoriaproduto',array())
			->where("a.produtocomposto = '0'");
		
		
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
			$numeroestoque = Erp_Model_Estoque_Movimento::estoqueAtual($data['id_registro'], '1');
				$estoqueatual = number_format($numeroestoque,2,',','');
				$estalert = (($data['estoqueminimo'] / 100) * System_Model_SysConfigs::getConfig('EstoqueAlertPercent')) + $data['estoqueminimo'];
				if($data['estoqueminimo'] <> 0 && $numeroestoque <= $data['estoqueminimo']){
					$quantidadeestoque = "<span class=\"label label-important ttip_t\" title=\"Estoque em Alerta (Minimo: {$data['estoqueminimo']}) \">$estoqueatual</span>";
				}elseif ($data['estoquemaximo'] <> 0 && $numeroestoque >= $data['estoquemaximo']){
					$quantidadeestoque = "<span class=\"label label-important ttip_t\" title=\"Estoque em Alerta (Maximo: {$data['estoquemaximo']})\">$estoqueatual</span>";
				}elseif($data['estoqueminimo'] <> 0 && $numeroestoque <= $estalert){
					$quantidadeestoque = "<span class=\"label label-warning ttip_t\" title=\"Estoque Próximo ao minimo (Minimo: {$data['estoqueminimo']})\">$estoqueatual</span>";
				
				}else{
					$quantidadeestoque = "<span class=\"label label-success ttip_t\" title=\"Estoque Normal\">$estoqueatual</span>";
				}
		
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['codigointerno'];
				$row[] = $data['nomecategoria'];
				$row[] = $data['nomesubcategoria'];
				$row[] = $data['nomeproduto'];
				$row[] = $data['referenciaproduto'];
				$row[] = $quantidadeestoque;
				$row[] = "<a href='/cadastros/produtos/criar-composto/id/{$data['id_registro']}' title='Criar Composto'><i class='splashy-documents_add'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function listaProdutosCompostosAction(){
		
		
		
			$aColumns = array( 'a.id_registro', 'a.codigointerno', 'b.nomecategoria', 'c.nomesubcategoria', 'a.nomeproduto','a.referenciaproduto');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_Produtos();
		
			$iTotal = count($db->fetchAll());
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblprodutos_basicos'),array('a.id_registro','a.estoqueminimo','a.estoquemaximo','a.nomeproduto','a.referenciaproduto','a.codigointerno','b.nomecategoria as nomecategoria','c.nomesubcategoria as nomesubcategoria'))
			->join(array('b'=>'tblapoio_categoriasprodutos'), 'b.id_registro = a.categoriaproduto',array())
			->join(array('c'=>'tblapoio_subcategoriadeprodutos'),'c.id_registro = a.subcategoriaproduto',array())
			->where("a.produtocomposto = '1'");
		
		
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
			$numeroestoque = Erp_Model_Estoque_Movimento::estoqueAtual($data['id_registro'], '1');
				$estoqueatual = number_format($numeroestoque,2,',','');
				$estalert = (($data['estoqueminimo'] / 100) * System_Model_SysConfigs::getConfig('EstoqueAlertPercent')) + $data['estoqueminimo'];
				if($data['estoqueminimo'] <> 0 && $numeroestoque <= $data['estoqueminimo']){
					$quantidadeestoque = "<span class=\"label label-important ttip_t\" title=\"Estoque em Alerta (Minimo: {$data['estoqueminimo']}) \">$estoqueatual</span>";
				}elseif ($data['estoquemaximo'] <> 0 && $numeroestoque >= $data['estoquemaximo']){
					$quantidadeestoque = "<span class=\"label label-important ttip_t\" title=\"Estoque em Alerta (Maximo: {$data['estoquemaximo']})\">$estoqueatual</span>";
				}elseif($data['estoqueminimo'] <> 0 && $numeroestoque <= $estalert){
					$quantidadeestoque = "<span class=\"label label-warning ttip_t\" title=\"Estoque Próximo ao minimo (Minimo: {$data['estoqueminimo']})\">$estoqueatual</span>";
				
				}else{
					$quantidadeestoque = "<span class=\"label label-success ttip_t\" title=\"Estoque Normal\">$estoqueatual</span>";
				}
		
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['codigointerno'];
				$row[] = $data['nomecategoria'];
				$row[] = $data['nomesubcategoria'];
				$row[] = $data['nomeproduto'];
				$row[] = $data['referenciaproduto'];
				$row[] = $quantidadeestoque;
				$row[] = "<a href='/cadastros/produtos/abrir-composto/id/{$data['id_registro']}' title='Abrir Composto'><i class='splashy-document_letter_edit'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function produtoCompostoAction(){
		
			$id = $this->_getParam('id');
		
			$aColumns = array( 'a.id_registro', 'b.codigointerno', 'b.nomeproduto'
					,'a.quantidadecomposto','e.abreviacao');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_ProdutosCompostos();
		
			$iTotal = count($db->fetchAll("id_produto = '$id'"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $db->getAdapter()->select()
		->from(array('a'=>'tblprodutos_compostos'),
				array('a.id_registro','a.id_produto', 
						'a.id_composto',
						'a.quantidadecomposto',
						'b.referenciaproduto',
						'b.codigointerno',
						'b.nomeproduto',
						'b.codigointerno',
						'c.nomecategoria as nomecategoria',
						'd.nomesubcategoria as nomesubcategoria',
						'e.abreviacao as unidademedida'))
		->join(array('b'=>'tblprodutos_basicos'), 'b.id_registro = a.id_composto',array())
		->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
		->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array())
		->join(array('e'=>'tblapoio_unidadesdemedida'),'e.id_registro = b.unidadedemedida',array())
		->where("a.id_produto = '$id'");
		
		
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
				$row[] = $data['codigointerno'];
				$row[] = $data['nomeproduto'];
				$row[] = number_format($data['quantidadecomposto'],3,',','');
				$row[] = $data['unidademedida'];
				$row[] = "<a href='#remove' title='Criar Composto' onclick=\"removecomposto({$data['id_registro']})\"><i class='splashy-documents_remove'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		
		public function produtosOsAction(){
		
			$id = $this->_getParam('id');
		
			$aColumns = array( 'b.nomeproduto', 'a.vlunitario', 'a.quantidade',"a.totalitem");
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_ProdutosCompostos();
		
			$iTotal = count($db->fetchAll("id_produto = '$id'"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $db->getAdapter()->select()
			->from(array('a'=>'tblos_produtos'),
					array('a.id_registro','a.id_produto',
							'a.quantidade',
							'a.vlunitario',
							'(a.vlunitario * a.quantidade) as valortotal',
							'b.referenciaproduto',
							'b.codigointerno',
							'b.nomeproduto',
							'b.codigointerno',
							'c.nomecategoria as nomecategoria',
							'd.nomesubcategoria as nomesubcategoria',
							'e.abreviacao as unidademedida'))
							->join(array('b'=>'tblprodutos_basicos'), 'b.id_registro = a.id_produto',array())
							->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
							->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array())
							->join(array('e'=>'tblapoio_unidadesdemedida'),'e.id_registro = b.unidadedemedida',array())
							->where("a.id_os = '$id'");
		
		
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
				$row[] = $data['nomeproduto'];
				$row[] = $data['quantidade'];
				$row[] =number_format($data['vlunitario'],2,',','');
				$row[] = number_format($data['valortotal'],2,',','');
				$row[] = "<a href='#remove' title='Criar Composto' onclick=\"removeproduto({$data['id_registro']})\"><i class='splashy-documents_remove'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function servicosOsAction(){
		
			$id = $this->_getParam('id');
		
			$aColumns = array( 'b.nomeservico', 'a.vlunitario', 'a.quantidade',"a.totalitem");
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Cadastros_Model_ProdutosCompostos();
		
			$iTotal = count($db->fetchAll("id_produto = '$id'"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $db->getAdapter()->select()
			->from(array('a'=>'tblos_servicos'),
					array('a.id_registro','a.id_servico',
							'a.quantidade',
							'a.vlunitario',
							'(a.vlunitario * a.quantidade) as valortotal',
							'b.nomeservico'
							))
							->join(array('b'=>'tblservicos_basicos'), 'b.id_registro = a.id_servico',array())
							->where("a.id_os = '$id'");
		
		
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
				$row[] = $data['nomeservico'];
				$row[] = $data['quantidade'];
				$row[] =number_format($data['vlunitario'],2,',','');
				$row[] = number_format($data['valortotal'],2,',','');
				$row[] = "<a href='#remove' title='Criar Composto' onclick=\"removeservico({$data['id_registro']})\"><i class='splashy-documents_remove'></i></a>";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		public function movimentoEstoqueAction(){
		
		
		
			$aColumns = array( 'a.id_registro', 'a.quantidade', 'a.historico', 'a.data', 'b.localestoque','c.nomeproduto','c.codigointerno','d.nomecompleto');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Estoque_Movimento();
		
			$iTotal = count($db->fetchAll());
		
			$dados = new Erp_Model_Estoque_Movimento();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblestoque_movimentos'),array('a.id_registro','a.quantidade','a.historico','a.data','b.localestoque','c.nomeproduto','c.codigointerno','d.nomecompleto'))
			->join(array('b'=>'tblestoque_locais'), 'b.id_registro = a.id_estoque',array())
			->join(array('c'=>'tblprodutos_basicos'),'c.id_registro = a.id_produto',array())
			->join(array('d'=>'tblsystem_users'),'d.id_registro = a.usuario',array());
					
		
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if($aColumns[$i] == 'a.data'){
						$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch']) ." 00:00:00'";
					}else{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
					}
					
					
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
				$row[] = $data['localestoque'];
				$row[] = "({$data['codigointerno']})-{$data['nomeproduto']}";
				$row[] =  number_format($data['quantidade'],3,',','');;
				$row[] = $data['historico'];
				$row[] = date('d/m/Y H:i',strtotime($data['data']));
				$row[] = $data['nomecompleto'];
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function produtosPedidoVendaAction(){
		
			$id = $this->_getParam('pedido');
			$block = $this->_getParam("blockpedido");
		
			$aColumns = array('b.nomeproduto','a.quantidade','a.vl_unitario','a.vl_total','a.comissao','a.qtd_faturada','a.qtd_afaturar','a.adicional_1','a.adicional_2','a.adicional_3' );
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Produtos();
		
			$iTotal = count($db->fetchAll("id_venda = '$id'"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_produtos'),array('a.id_registro', 'a.quantidade','a.vl_unitario','a.vl_total','a.adicional_1','a.adicional_2', 'a.adicional_3','a.qtd_faturada','a.qtd_afaturar','a.comissao','b.nomeproduto','b.referenciaproduto','b.codigointerno', 'c.nomecategoria as nomecategoria','d.nomesubcategoria as nomesubcategoria'))
			->join(array('b'=>'tblprodutos_basicos'),'b.id_registro = a.id_produto',array())
			->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
			->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array());
			$return->where("id_venda = '$id'");
			
		
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
				
				$row = array();
				$row[] = "({$data['codigointerno']}){$data['nomeproduto']}";
				$row[] = number_format($data['quantidade'],2,',','');
				$row[] = number_format($data['vl_unitario'],2,',','');
				$row[] = number_format($data['vl_total'],2,',','');
				$row[] = number_format($data['comissao'],2,',','');
				$row[] = number_format($data['qtd_faturada'],2,',','');
				$row[] = number_format($data['qtd_afaturar'],2,',','');
				$row[] = $data['adicional_1'];
				$row[] = $data['adicional_2'];
				$row[] = $data['adicional_3'];
				if($block == '' || $block == 0){
					$row[] = "<i style='cursor:pointer' class='splashy-document_letter_edit editproduto'  idregistro='{$data['id_registro']}'></i><i style='cursor:pointer' class='splashy-document_letter_remove removeproduto' idregistro='{$data['id_registro']}'></i>";
				}else{
					$row[] = "<i style='cursor:pointer' class='splashy-document_letter_edit block'  idregistro='{$data['id_registro']}'></i><i style='cursor:pointer' class='splashy-document_letter_remove block' idregistro='{$data['id_registro']}'></i>";
				}
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function pedidosVendasAction(){
				
			$aColumns = array('a.id_registro','b.descritivo','c.razaosocial','c.cnpj','a.pedido_cliente','a.agendamento_entrega','a.alteracoes');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Basicos();
		
			$iTotal = count($db->fetchAll("statusfaturamento not IN ('1','2','3')"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_basicos'),array('a.id_registro','a.agendamento_entrega','a.liberaprod', 'a.pedido_cliente','a.alteracoes','b.descritivo','c.razaosocial','c.cnpj'))
			->join(array('b'=>'tblapoio_tipodepedido'),'b.id_registro = a.tipo_pedido',array())
			->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())
			->where("statusfaturamento not IN ('1','2','3')");							
		
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
				if($aColumns[$i] == 'a.agendamento_entrega'){
						$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch']) ." 00:00:00' and {$aColumns[$i]} IS NULL";
					}else{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
					}
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
		
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['descritivo'];
				$row[] = $data['razaosocial'];
				$row[] = $data['cnpj'];
				$row[] = $data['pedido_cliente'];
				if($data['agendamento_entrega'] <> ''){
					$row[] = Functions_Datas::MyDateTime($data['agendamento_entrega'],true);
				}else{
					$row[] = "Sem Agendamento";
				}
				$row[] = Erp_Model_Vendas_Produtos::totalPedido($data['id_registro']);
				$row[] = $data['alteracoes'];
				if($data['liberaprod'] == '' || $data['liberaprod'] == 0){
				$row[] = "<a href='/erp/vendas/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Editar Pedido'><i class='splashy-document_letter_edit ttip_b' title='Editar Pedido'></i></a> <i style='cursor:pointer' class='splashy-document_letter_remove removepedido' idregistro='{$data['id_registro']}' title='Remover Pedido'></i>  <i style='cursor:pointer' class='splashy-calendar_week_add  liberaprod' idregistro='{$data['id_registro']}' title='Liberar para Produção'></i>  <a href='/erp/vendas/print-pedido/id/{$data['id_registro']}' target='_blank'><i class='splashy-printer ttip_b' title='Imprimir Pedido'></i></a>";
				}else{
					$row[] = "<a href='/erp/vendas/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Editar Pedido'><i class='splashy-document_letter_edit ttip_b' title='Editar Pedido'></i></a> <i style='cursor:pointer' class='splashy-document_letter_remove block' idregistro='{$data['id_registro']}' title='Remover Pedido'></i>  <i style='cursor:pointer' class='splashy-calendar_week_add  block' idregistro='{$data['id_registro']}' title='Liberar para Produção'></i>  <a href='/erp/vendas/print-pedido/id/{$data['id_registro']}' target='_blank'><i class='splashy-printer ttip_b' title='Imprimir Pedido'></i></a>";
				}
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		
		public function pedidosVendasFatAction(){
		
			$aColumns = array('a.id_registro','b.descritivo','c.razaosocial','c.cnpj','a.pedido_cliente','a.agendamento_entrega','a.alteracoes');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Basicos();
		
			$iTotal = count($db->fetchAll("statusfaturamento IN ('1','2','3')"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_basicos'),array('a.id_registro','a.agendamento_entrega','a.liberaprod', 'a.pedido_cliente','a.alteracoes','b.descritivo','c.razaosocial','c.cnpj'))
			->join(array('b'=>'tblapoio_tipodepedido'),'b.id_registro = a.tipo_pedido',array())
			->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())->where("statusfaturamento IN ('1','2','3')");
		
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if($aColumns[$i] == 'a.agendamento_entrega'){
						$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch']) ." 00:00:00' and {$aColumns[$i]} IS NULL";
					}else{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
					}
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
		
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['descritivo'];
				$row[] = $data['razaosocial'];
				$row[] = $data['cnpj'];
				$row[] = $data['pedido_cliente'];
				if($data['agendamento_entrega'] <> ''){
					$row[] = Functions_Datas::MyDateTime($data['agendamento_entrega'],true);
				}else{
					$row[] = "Sem Agendamento";
				}
				$row[] = Erp_Model_Vendas_Produtos::totalPedido($data['id_registro']);
				$row[] = $data['alteracoes'];
				$row[] = "<a href='/erp/faturamento/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Faturar Pedido'><i class='splashy-document_a4_add' title='Faturar Pedido'></i></a> 
				<a href='/erp/vendas/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Editar Pedido'><i class='splashy-document_letter_edit ttip_b' title='Editar Pedido'></i></a> ";				
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function pedidosProduzirAction(){
		
			$aColumns = array('a.id_registro','b.descritivo','c.razaosocial','c.cnpj','a.pedido_cliente','a.agendamento_entrega','a.alteracoes');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Basicos();
		
			$iTotal = count($db->fetchAll("liberaprod = '1' and pedidoemproducao = '0'"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_basicos'),array('a.id_registro','a.agendamento_entrega','a.liberaprod', 'a.pedido_cliente','a.alteracoes','b.descritivo','c.razaosocial','c.cnpj'))
			->join(array('b'=>'tblapoio_tipodepedido'),'b.id_registro = a.tipo_pedido',array())
			->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())
			->where("liberaprod = '1'")
			->where("pedidoemproducao = '0'");
		
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if($aColumns[$i] == 'a.agendamento_entrega'){
						$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch']) ." 00:00:00' and {$aColumns[$i]} IS NULL";
					}else{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
					}
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
				
				$icons.= "<i class=\"splashy-refresh_backwards retornapedido\" id_registro=\"{$data['id_registro']}\" id=\"RetornaPedido\" class='ttip_b' title='Retornar Pedido' style='cursor:pointer' ></i>";
				$icons.="<a href='/erp/producao/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Criar Programação'><i class=\"splashy-box_add\"></i></a>";
				
				
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['descritivo'];
				$row[] = $data['razaosocial'];
				$row[] = $data['cnpj'];
				$row[] = $data['pedido_cliente'];
				if($data['agendamento_entrega'] <> ''){
					$row[] = Functions_Datas::MyDateTime($data['agendamento_entrega'],true);
				}else{
					$row[] = "Sem Agendamento";
				}
				$row[] = Erp_Model_Vendas_Produtos::totalPedido($data['id_registro']);
				$row[] = $data['alteracoes'];
				
				$row[] = $icons;
				
				$output['aaData'][] = $row;
				$row = null;
				$icons = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function pedidosVendasAFatAction(){
		
			$aColumns = array('a.id_registro','b.descritivo','c.razaosocial','c.cnpj','a.pedido_cliente','a.agendamento_entrega','a.alteracoes');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Basicos();
		
			$iTotal = count($db->fetchAll("statusfaturamento not IN ('1','2','3')"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_basicos'),array('a.id_registro','a.agendamento_entrega','a.liberaprod', 'a.pedido_cliente','a.alteracoes','b.descritivo','c.razaosocial','c.cnpj'))
			->join(array('b'=>'tblapoio_tipodepedido'),'b.id_registro = a.tipo_pedido',array())
			->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())->where("statusfaturamento not IN ('1','2','3')");
		
			if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
			{
				for ( $i=0 ; $i<count($aColumns) ; $i++ )
				{
					if($aColumns[$i] == 'a.agendamento_entrega'){
						$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch']) ." 00:00:00' and {$aColumns[$i]} IS NULL";
					}else{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
					}
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
		
				$row = array();
				$row[] = $data['id_registro'];
				$row[] = $data['descritivo'];
				$row[] = $data['razaosocial'];
				$row[] = $data['cnpj'];
				$row[] = $data['pedido_cliente'];
				if($data['agendamento_entrega'] <> ''){
					$row[] = Functions_Datas::MyDateTime($data['agendamento_entrega'],true);
				}else{
					$row[] = "Sem Agendamento";
				}
				$row[] = Erp_Model_Vendas_Produtos::totalPedido($data['id_registro']);
				$row[] = $data['alteracoes'];
				$row[] = "<a href='/erp/faturamento/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Faturar Pedido'><i class='splashy-document_a4_add' title='Faturar Pedido'></i></a>
				<a href='/erp/vendas/abrir-pedido/id/{$data['id_registro']}' class='ttip_b' title='Editar Pedido'><i class='splashy-document_letter_edit ttip_b' title='Editar Pedido'></i></a> ";
				$output['aaData'][] = $row;
				$row = null;
			}
		
		
		
		
				echo json_encode( $output );
		}

		
	
		
		public function produtosPedidoProducaoAction(){
		
			$id = $this->_getParam('pedido');
			$block = $this->_getParam("blockpedido");
		
			$aColumns = array('b.nomeproduto','a.quantidade','a.vl_unitario','a.vl_total','a.adicional_1','a.adicional_2','a.adicional_3' );
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Produtos();
		
			$iTotal = count($db->fetchAll("id_venda = '$id' and id_prod = '0'"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_produtos'),array('a.id_registro', 'a.quantidade','a.vl_unitario','a.vl_total','a.adicional_1','a.adicional_2', 'a.adicional_3','a.qtd_faturada','a.qtd_afaturar','a.comissao','b.nomeproduto','b.referenciaproduto','b.codigointerno', 'c.nomecategoria as nomecategoria','d.nomesubcategoria as nomesubcategoria'))
			->join(array('b'=>'tblprodutos_basicos'),'b.id_registro = a.id_produto',array())
			->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
			->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array());
			$return->where("id_venda = '$id'")->where("id_prod = '0'");
				
		
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
				
				$icons.= "<a href=\"/erp/producao/programar-produto/id/{$data['id_registro']}\"><i class=\"splashy-box_new\" title=\"Programar Produto\"></i></a>";
				$icons.= "";
		
				$row = array();
				$row[] = "({$data['codigointerno']}){$data['nomeproduto']}";
				$row[] = number_format($data['quantidade'],2,',','');
				$row[] = number_format($data['vl_unitario'],2,',','');
				$row[] = number_format($data['vl_total'],2,',','');
				$row[] = $data['adicional_1'];
				$row[] = $data['adicional_2'];
				$row[] = $data['adicional_3'];
				$row[] = $icons;
				
				$output['aaData'][] = $row;
				$row = null;
				$icons = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
		public function produtosEmProducaoAction(){
		
			$id = $this->_getParam('pedido');
			$block = $this->_getParam("blockpedido");
			
			$statusprodenc = System_Model_SysConfigs::getConfig("StatusProdEncerrada");
		
			$aColumns = array('a.id_venda','f.razaosocial','b.nomeproduto','a.quantidade','a.id_prod');
		
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
		
			$db = new Erp_Model_Vendas_Produtos();
		
			$iTotal = count($db->fetchAll("id_prod > '0' and statusproducao  NOT IN ('$statusprodenc')"));
		
			$dados = new Cadastros_Model_Produtos();
			$return = $dados->getAdapter()->select()
			->from(array('a'=>'tblvendas_produtos'),array('a.id_registro','a.id_venda', 'a.quantidade','a.vl_unitario','a.vl_total','a.adicional_1',
					'a.adicional_2', 'a.adicional_3','a.qtd_faturada','a.qtd_afaturar','a.comissao','a.id_prod',
					'b.nomeproduto','b.referenciaproduto','b.codigointerno', 'c.nomecategoria as nomecategoria',
					'd.nomesubcategoria as nomesubcategoria','e.agendamento_entrega',
					'f.razaosocial'
			))
			->join(array('b'=>'tblprodutos_basicos'),'b.id_registro = a.id_produto',array())
			->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
			->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array())
			->join(array('e'=>'tblvendas_basicos'),'e.id_registro = a.id_venda',array())
			->join(array('f'=>'tblpessoas_basicos'),'f.id_registro = e.id_pessoa',array());
			$return->where("id_prod > '0'")
			->where("statusproducao NOT IN ('$statusprodenc')");
		
		//	echo $return;		
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
				
				$concluido = Erp_Model_Producao_Registros::GetSomaOP( $data['id_prod']);
				$falta = $data['quantidade'] - $concluido;
				
				if($concluido == 0){
					$icons.= "<i class=\"splashy-box_remove cancelprod\" id_lancamento=\"{$data['id_prod']}\" title=\"Cancelar Programação\" style='cursor:pointer'></i>";
				}
				
				
				if($falta > 0){
				$icons.= "<a data-toggle=\"modal\" href=\"/erp/producao/lancar-modal/id/{$data['id_prod']}\" data-target=\"#LancarModal\"><i class=\"splashy-box_add\" title=\"Lançar Produção\"></i></a>";
				}
				$icons.="<a data-toggle=\"modal\" href=\"/erp/producao/requisita-material/id/{$data['id_prod']}\" data-target=\"#RequisitaModal\"><i class=\"splashy-box_warning\" title=\"Requisição de Materiais\"></i></a>";
				$icons.="<a data-toggle=\"modal\" href=\"/erp/producao/consulta-completa/id/{$data['id_prod']}\" data-target=\"#ConsultaModal\"><i class=\"splashy-box_locked\" title=\"Consulta Completa\"></i></a>";
				$icons.= "<a href=\"/erp/producao/print-op/id/{$data['id_prod']}\" target='_blank'><i class=\"splashy-printer\" title=\"Imprimir OP\"></i></a>";
		
				$row = array();
				$row[] = $data['id_venda'];
				$row[] = $data['razaosocial'];
				$row[] = "({$data['codigointerno']}){$data['nomeproduto']}";
				$row[] = number_format($data['quantidade'],2,',','');
				$row[] = $data['id_prod'];
				$row[] = $concluido;
				$row[] = $falta;
				$row[] = $icons;
		
				$output['aaData'][] = $row;
				$row = null;
				$icons = null;
			}
		
		
		
		
			echo json_encode( $output );
		}
		
		
				
			
}