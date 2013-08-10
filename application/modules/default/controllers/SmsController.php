<?php
class SmSController extends Zend_Controller_Action{

	public function init(){


		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');

		$this->view->parameters = $this->_request->getParams();

		$this->DocsPath = $this->configs->SYS->DocsPath;

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

	}

	public function indexAction(){

	}

	public function callbackAction(){
		if($this->_request->isPost()){
			$data = $this->_request->getPost();
			$idmessage = $data['id'];
			$retorno = substr($data['status'], 0, 3);
			$dbup['calbackcode'] = $retorno;
			$dbup['callbackmessage'] = $data['status'];
			
			$db = new System_Model_SMS();
			$db->update($dbup,"id_registro = '$idmessage'");		
			
		}
		

	}
	
	
	public function replyAction(){
		
		if($_GET){
			
			
			
		}
		
	}



}