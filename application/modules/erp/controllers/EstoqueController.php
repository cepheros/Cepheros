<?php
class Erp_EstoqueController extends Zend_Controller_Action{

	public $log;
	public $configs;
	public $cache;
	public $userInfo;

	public function init(){

		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->userInfo = Zend_Auth::getInstance()->getStorage()->read();

		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();

	}

	public function indexAction(){
	}

	public function planilhaMovimentosAction(){
		$action = $this->_getParam('acao');
		if($action == 'moved'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Movimento de estoque realizado com sucesso",'sucesso'));
		}
		
		$form = new Cadastros_Form_Produtos();
		$form->novo();
		$this->view->form = $form;
	}

	public function consultaMovimentosAction(){

	}
	
	public function movimentoProdutoAction(){
		
	}

	public function cadastroEstoquesAction(){
		$form = new Erp_Form_LocaisEstoques();
		$this->view->form = $form;
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$db = new Erp_Model_Estoque_Locais();
				$id = $db->insert($formData);
				$this->log->log("Novo Cadastro de Local de Estoque: {$this->userInfo->nomecompleto} Produto: {$formData['localestoque']} ",Zend_Log::INFO);
				$this->_redirect("erp/estoque/edita-local/id/$id/acao/novo");

				}else{
					$error =  $form->getMessages();
					$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');

					foreach($error as $erro){
						$messages[] = Functions_Messages::renderAlert($erro[0],'info');
					}

					$this->view->AlertMessage = $messages;
					$form->populate($formData);
			}

		}
	}

	public function editaLocalAction(){
		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		if($action == 'novo'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro de produto Efetuado",'sucesso'));
		}
		$db = new Erp_Model_Estoque_Locais();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$form = new Erp_Form_LocaisEstoques();
		$form->populate($data);
		$this->view->form = $form;

		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$db = new Erp_Model_Estoque_Locais();
				$db->update($formData,"id_registro = '{$formData['id_registro']}'");
				$this->log->log("Novo Cadastro de Local de Estoque editado: {$this->userInfo->nomecompleto} Produto: {$formData['localestoque']} ",Zend_Log::INFO);
				$messages[] = Functions_Messages::renderAlert("Local editado com sucesso",'sucesso');
				$this->view->AlertMessage = $messages;
			}else{
				$error =  $form->getMessages();
				$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');

				foreach($error as $erro){
					$messages[] = Functions_Messages::renderAlert($erro[0],'info');
				}

				$this->view->AlertMessage = $messages;
				$form->populate($formData);
			}

		}

	}

	public function listaLocalAction(){
		$action = $this->_getParam('acao');
		if($action == 'removed'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Local removido com sucesso",'sucesso'),Functions_Messages::renderAlert("Estoque reprocessado para o novo local",'info'));
		}
		
		$locais = Erp_Model_Estoque_Locais::gelAllLocais();
		$this->view->locais = $locais;
		$form = new Erp_Form_LocaisEstoques();
		$this->view->form = $form;
	}
	
	public function removerLocalAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$localantigo = $_POST['	'];
		$novolocal = $_POST['novolocal'];
		$dbest = new Erp_Model_Estoque_Movimento();
		$data = $dbest->update(array("id_estoque"=>$novolocal),"id_estoque = '$localantigo'");
		$dblocal = new Erp_Model_Estoque_Locais();
		$data = $dblocal->delete("id_registro = '$localantigo'");
		
		$this->_redirect("/erp/estoque/lista-local/acao/removed");
		
		
	}

	public function relatoriosAction(){

	}

	public function setdefaultlocationAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Erp_Model_Estoque_Locais();
		$db->update(array('is_default'=>'0'),"is_default = '1'");
		$db->update(array('is_default'=>'1'),"id_registro = '$id'");

	}
	
	public function getprodutosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$cat = $_POST['categoriaproduto'];
		$subcat = $_POST['subcategoriaproduto'];
		$local = $_POST['localestoque'];
		
		
		$dados = new Cadastros_Model_Produtos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblprodutos_basicos'),array('a.id_registro','a.estoqueminimo','a.estoquemaximo','a.nomeproduto','a.referenciaproduto','a.codigointerno','b.nomecategoria as nomecategoria','c.nomesubcategoria as nomesubcategoria'))
		->join(array('b'=>'tblapoio_categoriasprodutos'), 'b.id_registro = a.categoriaproduto',array())
		->join(array('c'=>'tblapoio_subcategoriadeprodutos'),'c.id_registro = a.subcategoriaproduto',array());
		
		if($cat <> 0 && $cat <> ''){
			$return->where("a.categoriaproduto = '$cat'");
		};
		if($subcat <> 0 && $subcat <> ''){
			$return->where("a.subcategoriaproduto = '$subcat'");
		};
		
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$ret = null;
		
		foreach($dados as $data){
			$estoqueaqual = Erp_Model_Estoque_Movimento::estoqueAtual($data['id_registro'],$local,0);
			if($estoqueaqual <=  0){
				$travatransf = true;
			}else{
				$travatransf = false;
			}
			$ret[] = array('id_registro'=>$data['id_registro'],
							'localatual'=>$local,
						   'nomeproduto'=>"({$data['codigointerno']})-{$data['nomeproduto']}",
						   'estoqueatual'=> Erp_Model_Estoque_Movimento::estoqueAtual($data['id_registro'],$local),
			'travatransf'=>$travatransf);
			}
		
		echo json_encode($ret);
		
		
		
	}
	
	public function movimentaPlanilhaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Erp_Model_Estoque_Movimento();
		$dados = $_POST;
		
		print_r($dados);
		for($i=0;$i<count($dados['localatual']);$i++){
			$estoque = $dados['localatual'][$i];
			$produto = $dados['idproduto'][$i];
			$entradas = $dados['entradas'][$i];
			$saidas = $dados['saidas'][$i];
			$transferencias = $dados['transferencias'][$i];
			$novolocal = $dados['novolocal'][$i];
			$historico = $dados['historico'][$i];
			
			if($entradas <> '' && $entradas <> '0'){
				$entradas = str_replace(",", ".",$entradas);
				$datainsert = array('id_produto'=>$produto,
									'id_estoque'=>$estoque,
									'quantidade'=>$entradas,
									'historico'=>$historico,
									'usuario'=>$this->userInfo->id_registro,
									'data'=>date('Y-m-d H:i:s')						
				);
				
				$db->insert($datainsert);
				$datainsert = null;	
				
			}
			
			if($saidas <> '' && $saidas <> '0'){
				$saidas = str_replace(",", ".",$saidas);
				$datainsert = array('id_produto'=>$produto,
						'id_estoque'=>$estoque,
						'quantidade'=> "-$saidas",
						'historico'=>$historico,
						'usuario'=>$this->userInfo->id_registro,
						'data'=>date('Y-m-d H:i:s')
				);
			
				$db->insert($datainsert);
				$datainsert = null;
			
			}
			
			if($transferencias <> '' && $transferencias <> 0){
				echo "Achei Transferencia";
				$transferencias = str_replace(",", ".",$transferencias);
				
				$datainserttransf = array('id_produto'=>$produto,
						'id_estoque'=>$estoque,
						'quantidade'=>"-$transferencias",
						'historico'=>"(TRANSFERENCIA) - ".$historico,
						'usuario'=>$this->userInfo->id_registro,
						'data'=>date('Y-m-d H:i:s')
				);
				
				$db->insert($datainserttransf);
				
								
				$datainserttransf2 = array('id_produto'=>$produto,
						'id_estoque'=>$novolocal,
						'quantidade'=>"$transferencias",
						'historico'=>"(TRANSFERENCIA) - ".$historico,
						'usuario'=>$this->userInfo->id_registro,
						'data'=>date('Y-m-d H:i:s')
				);
				
				$db->insert($datainserttransf2);
				$datainserttransf2 = null;
				$datainserttransf = null;
				
			}
			
			
			
			
			
			
		}
		$this->_redirect("/erp/estoque/planilha-movimentos/acao/moved");
	}
}