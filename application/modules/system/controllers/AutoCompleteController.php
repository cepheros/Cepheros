<?php
class System_AutoCompleteController extends Zend_Controller_Action{
	
	public function ticketsContatosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$termo = strtoupper($_GET['term']);
		$return = array();
		$db = new Cadastros_Model_Contatos();
		$dados = $db->fetchAll("UPPER(nomecontato) like '%$termo%' or UPPER(contato) like '%$termo%'");
		foreach($dados->toArray() as $data){
			$pessoa = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_pessoa']);
			$retorno['nomecontato'] = $data['nomecontato'];
			$retorno['id_registro'] = $data['id_registro'];
			$retorno['email'] = $data['email'];
			$retorno['celular'] = $data['celular'];
			$retorno['id_pessoa'] = $data['id_pessoa'];
			$retorno['isdefault'] = $data['isdefault'];
			$retorno['value'] = "$pessoa ({$data['nomecontato']})";
			array_push($return,$retorno);
		}
		
		echo json_encode($return);
		
	}
	
	
	public function pessoasAction(){
		$tipo = $this->_getParam('tipo');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$termo = strtoupper($_GET['term']);
		$return = array();
		$db = new Cadastros_Model_Pessoas();
		$select = $db->select();
		if($tipo <> ''){
		$select->where("tipocadastro = '$tipo'");
		}
		$select->where("UPPER(razaosocial) like '%$termo%' ");
		$select->orWhere("UPPER(nomefantasia) like '%$termo%' ")
		->orWhere("UPPER(id_registro) like '%$termo%' ")
		->orWhere("UPPER(cnpj) like '%$termo%' ")->order("razaosocial asc");
		
		
		$dados = $db->fetchAll($select);
		foreach($dados->toArray() as $data){
			$retorno['id_registro'] = $data['id_registro'];
			$retorno['razaosocial'] = $data['razaosocial'];
			$retorno['nomefantasia'] =  $data['nomefantasia'];
			$retorno['cnpj'] = $data['cnpj'];
			$retorno['inscestadual'] =  $data['inscestadual'];
			$retorno['value'] =  $data['razaosocial'];
			array_push($return,$retorno);
		}
	
		echo json_encode($return);
	
	}
	
	
	public function servicosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$termo = strtoupper($_GET['term']);
		$return = array();
		$db = new Cadastros_Model_Servicos();
		$select = $db->select()->where("UPPER(nomeservico) like '%$termo%' ")
		->order("nomeservico asc");
		$dados = $db->fetchAll($select);
		foreach($dados->toArray() as $data){
			$retorno['id_registro'] = $data['id_registro'];
			$retorno['nomeservico'] = $data['nomeservico'];
			$retorno['valorservico'] = number_format($data['valorservico'],2,',','');
			$retorno['tiposervico'] = $data['tiposervico'];
			$retorno['iss'] =  $data['iss'];
			$retorno['value'] =  $data['nomeservico'];
			array_push($return,$retorno);
		}
	
		echo json_encode($return);
	
	}
	
	
	public function produtosAction(){
		/**
		 * Efetua a busca no banco de dados por produtos que não tenham compostos e que não seja o produto que 
		 * se esta criando o composto
		 */
		
		// Verifica no system_configs se o proprio produto pode ser adicionado
		if(System_Model_SysConfigs::getConfig('CanotADDSameProduct') == 1){
			$id = $this->_getParam('id',0);
		}else{
			$id = '0';
		}
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$termo = strtoupper($_GET['term']);
		$return = array();
		
		$db = new Cadastros_Model_Produtos();
		$query = $db->getAdapter()->select()
		->from(array('a'=>'tblprodutos_basicos'),
				array('a.id_registro','a.estoqueminimo', 
						'a.estoquemaximo','a.nomeproduto',
						'a.referenciaproduto',
						'a.precovenda',
						'a.codigointerno',
						'b.nomecategoria as nomecategoria',
						'c.nomesubcategoria as nomesubcategoria',
						'd.abreviacao as unidademedida'))
		->join(array('b'=>'tblapoio_categoriasprodutos'), 'b.id_registro = a.categoriaproduto',array())
		->join(array('c'=>'tblapoio_subcategoriadeprodutos'),'c.id_registro = a.subcategoriaproduto',array())
		->join(array('d'=>'tblapoio_unidadesdemedida'),'d.id_registro = a.unidadedemedida',array());
		
		
		// Verifica se a opç~~ao de nao adicionar outros compostos no composto esta ativada
		if(System_Model_SysConfigs::getConfig('ProdutosNotCompostosADD') == 1 ){
			$query->where("a.produtocomposto = '0'");
		}
		
		
		$query->where("UPPER(nomeproduto) like '%$termo%' ")
		->where("a.id_registro not in ($id)")
		->orWhere("UPPER(referenciaproduto) like '%$termo%' and a.id_registro not in ($id) ")
		->orWhere("UPPER(codigointerno) like '%$termo%' and a.id_registro not in ($id) ")->order("nomeproduto asc");
		$rs = $query->query();
		$dados = $rs->fetchAll();
		foreach($dados as $data){
			$retorno['id_registro'] = $data['id_registro'];
			$retorno['nomeproduto'] = $data['nomeproduto'];
			$retorno['codigointerno'] =  $data['codigointerno'];
			$retorno['referenciaproduto'] = $data['referenciaproduto'];
			$retorno['categoriaproduto'] =  $data['nomecategoria'];
			$retorno['subcategoriaproduto'] =  $data['nomesubcategoria'];
			$retorno['unidadedemedida'] =  $data['unidademedida'];
			$retorno['precovenda'] =  number_format($data['precovenda'],2,',','');
			$retorno['value'] =  $data['nomeproduto'];
			array_push($return,$retorno);
		}
	
		echo json_encode($return);
	
	}
	
}