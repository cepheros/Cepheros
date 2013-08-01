<?php

class CronController extends Zend_Controller_Action{
	
	public function init(){
		
		set_time_limit(0);
	
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
		$this->view->parameters = $this->_request->getParams();
	
		$this->DocsPath = $this->configs->SYS->DocsPath;
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
	}
	
	
	public function indexAction(){
		
		$date = date('Y-m-d H:i:s');
		$hora = date("H");
		$minuto = date('i');
		
		$cron = new Functions_Cron;
		$cron->emailTicketCron();
		$cron->osMailCron();
		
		
	}
	
	
	public function nfeAction(){
		
		
	}
	
	public function financeiroAction(){
		$cron = new Functions_Cron;
		$cron->financeiroCron();
	}
	
	public function manutencaoAction(){
		
	}
	
}