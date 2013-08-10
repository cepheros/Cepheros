<?php
class Dashboard_UserController extends Zend_Controller_Action{
	
	public $log;
	public $configs;
	public $cache;
	public $typePessoa = "1";
	
	
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
	
	
	public function profileAction(){
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = 	$userInfo->id_registro;
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
			
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Usuarios();
		$form->profile();
		$form->populate($data);
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['password2']);
				$formData['password'] = md5($formData['password']);
				$formData['passdate'] = date('Y-m-d H:i:s');
				$formData['recoverpass'] = 0;
				try{
						
					$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
					$this->log->log("Novo Cadastro de Usuario Atualizado por: {$userInfo->nomecompleto} Cliente: {$formData['nomecompleto']} ",Zend_Log::INFO);
					$form->populate($formData);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Seus dados foram atualizados com sucesso!",'sucesso'));
					
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de usuario {$formData['nomecompleto']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a solicitação:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
		
	}
}