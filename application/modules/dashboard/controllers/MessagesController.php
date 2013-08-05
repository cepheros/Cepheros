<?php
class Dashboard_MessagesController extends Zend_Controller_Action{
	
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
		$this->view->UserInfo = Zend_Auth::getInstance()->getStorage()->read();
	
	}
	
	public function listAction(){
		
		$this->view->messages = System_Model_Messages::getAllMessages(Zend_Auth::getInstance()->getStorage()->read()->id_registro);
		$this->view->sentmessages = System_Model_Messages::getAllSendMessages(Zend_Auth::getInstance()->getStorage()->read()->id_registro);
		$this->view->garbagemessages = System_Model_Messages::getAllGarbageMessages(Zend_Auth::getInstance()->getStorage()->read()->id_registro);
		
		$this->view->sysusers = System_Model_Users::renderMultiCombo(System_Model_Messages::getAllGarbageMessages(Zend_Auth::getInstance()->getStorage()->read()->id_registro));
		$this->view->prioridades = System_Model_Prioridades::returnAll();
		
		
		if ($this->_request->isPost()) {
		$formData = $this->_request->getPost();
		
			for($i=0;$i<count($formData['users_to']);$i++){
			$savedate['user_from'] = $formData['user_from'];
			$savedate['user_to'] = $formData['users_to'][$i];
			$savedate['statusmessage'] = '1';
			$savedate['assuntomessage'] = $formData['assuntomessage'];
			$savedate['contentmessage'] = $formData['contentmessage'];
			$savedate['messageprioridade'] = $formData['messageprioridade'];
			$savedate['flagmessage'] = '0';
			$savedate['datemessage'] = date('Y-m-d H:i:s');
			$db = new System_Model_Messages();
			$db->insert($savedate);
			
		}
		
		$this->view->AlertMessage = array(Functions_Messages::renderAlert("Mensagem Enviada!",'sucesso'));
		
			
			
		}
		
		
	}
	
	
	public function flagmessageAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new System_Model_Messages();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		if($dados['flagmessage'] == 0){
			$db->update(array('flagmessage'=>'1'),"id_registro = '$id'");
		}else{
			$db->update(array('flagmessage'=>'0'),"id_registro = '$id'");
		}
		
		
		
	}
	
	public function deletemessageAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new System_Model_Messages();
		$db->update(array('statusmessage'=>'4'),"id_registro = '$id'");
	
		
		
	}
	
	public function replymessageAction(){
		
	}
	
	public function fowardmessageAction(){
		
	}
	
	public function restoremessageAction(){
		
	}
	
	public function readmessageAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new System_Model_Messages();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		
		$mensagemde = System_Model_Users::whoIs($dados['user_from']);
		$mensagempara = System_Model_Users::whoIs($dados['user_to']);
		$datamensagem = Functions_Datas::MyDateTime($dados['datemessage'],true);
		$prioridade = System_Model_Prioridades::getHtmlPrioridade($dados['messageprioridade']);
		$conteudomensagem = nl2br($dados['contentmessage']);
		
				
		echo "{$dados['id_registro']}|{$mensagemde}|{$mensagempara}|{$dados['statusmessage']}|{$dados['assuntomessage']}|{$conteudomensagem}|{$datamensagem}|{$prioridade}|{$dados['flagmessage']}";
		
		$db->update(array('statusmessage'=>'0'),"id_registro = '$id'");
		
		
		
	}
	
}