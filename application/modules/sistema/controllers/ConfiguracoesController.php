<?php
class Sistema_ConfiguracoesController extends Zend_Controller_Action{
	
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
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();	
	}
	
	public function indexAction(){
		
	}
	
	public function departamentosAction(){
		$db = new Crm_Model_TicketsDeptos();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
	}
	
	
	public function statusTicketsAction(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
	}
	
	public function filaMensagensAction(){
		
		$db = new System_Model_Tickets_Parser();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;	
		
	}
	
	
	public function tiposTicketsAction(){
		$db = new Crm_Model_TicketsTipos();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
	}
	
	public function prioridadeTicketsAction(){
		
		$db = new Crm_Model_TicketsPrioridades();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
	}
	
	
	public function tiposPessoasAction(){
		$db = new System_Model_Tipopessoas();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		
		
	}
	
	
	public function mensagensSistemaAction(){
		$db = new System_Model_MensagensSistema();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		
	}
	
	
	public function prospectsAction(){
		$db = new System_Model_SysConfigs();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;	
		
	}
	
	public function menusAction(){
		
	}
	
	public function salvaMenuAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		if ($this->_request->isPost()) {
			$db = new System_Model_Menus();
			$formData = $this->_request->getPost();
			$dadossalva = array("module"=>$formData['module'],
					'controller'=>$formData['controller'],
					'action'=>$formData['action'],
					'parans'=>$formData['parans'],
					'nome'=>$formData['nome'],
					'position'=>$formData['position']
			);
			if(!$formData['id_registro']){
				$db->insert($dadossalva);
			}else{
				$db->update($dadossalva,"id_registro = '{$formData['id_registro']}'");
			}
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Recurso Cadastrado",'sucesso'));
		}
	}
	
	
	
}