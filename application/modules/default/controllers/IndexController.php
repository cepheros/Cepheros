<?php
class IndexController extends Zend_Controller_Action{

	public function init()
	{
	
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		 
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
		
		if(System_Model_SysConfigs::getConfig("UseWebSite") == 0){
			$this->_redirect('/default/login');
		}
		
		/* Initialize action controller here */
		$this->_helper->_layout->setLayout('site/layout');
		
	}
	
	public function indexAction(){
		
		
		 
	
		 
	}
	
		public function logoutAction() {
			$auth = Zend_Auth::getInstance();
			$auth->clearIdentity();
    	$this->_redirect('/default/index');
		}
	
		
	
	
   
	
	
}