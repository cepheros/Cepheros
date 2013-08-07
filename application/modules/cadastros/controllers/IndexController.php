<?php
class Cadastros_IndexController extends Zend_Controller_Action{
	
	
	public $log;
	public $configs;
	public $cache;
	
	
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
	
}