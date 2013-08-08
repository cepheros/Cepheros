<?php
class Sistema_CacheController extends Zend_Controller_Action{
	
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
	
	
	public function limpaCacheAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		try{
			$this->cache->clean();	
			echo "Cache Limpo em " . date('d/m/Y H:i');
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
		
	}
	
	/**
	 * limpaTempAction()
	 * 
	 * @todo Rotina que varre a pasta de temporarios do sistema e apaga todos os arquivos
	 */
	public function limpaTempAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		
		
	}
	
	/**
	 * limpaTempNfeAction()
	 *
	 * @todo Rotina que varre a pasta de temporarios de nfe do sistema e apaga todos os arquivos
	 */
	
	public function limpaTempNfeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		
	}
	
}