<?php
class DatatablesController extends Zend_Controller_Action{
	
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
	
		$FILTER = 	"id_registro > '0'";
	
	
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
}