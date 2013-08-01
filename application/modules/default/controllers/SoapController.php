<?php
class SoapController extends Zend_Controller_Action{
	
	public function init(){
		ini_set("soap.wsdl_cache_enabled", "0");
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
	
	public function smsAction(){
		
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
			$this->hadleSMSWSDL();
		} else {
			$this->handleSMSSOAP();
		}
		
	}
	
	public function cepAction(){
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
			$this->hadleCEPWSDL();
		} else {
			$this->handleCEPSOAP();
		}
	}
	
	public function systemusersAction(){
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
			$this->hadleSYSTEMUSERSWSDL();
		} else {
			$this->handleSYSTEMUSERSSOAP();
		}
	}
	
	public function cadastroAction(){
		
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
		$autodiscover = new Zend_Soap_AutoDiscover();
		$autodiscover->setClass('Soap_Cadastro');
		$autodiscover->handle();
		} else {
		$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/cadastro?wsdl");
		$soap->setClass('Soap_Cadastro');
		$soap->handle();
		}
		
		
	}
	
	public function produtosAction(){
		
	}
	
	public function servicosAction(){
		
	}
	
	
	public function estoquesAction(){
		
		
	}
	
	
	
	public function functionsAction(){
		
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
			$autodiscover = new Zend_Soap_AutoDiscover();
			$autodiscover->setClass('Soap_Functions');
			$autodiscover->handle();
		} else {
			$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/functions?wsdl");
			$soap->setClass('Soap_Functions');
			$soap->handle();
		}
		
	}
	
	
	public function sysnewsAction(){
	
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
			$autodiscover = new Zend_Soap_AutoDiscover();
			$autodiscover->setClass('Soap_SysNews');
			$autodiscover->handle();
		} else {
			$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/sysnews?wsdl");
			$soap->setClass('Soap_SysNews');
			$soap->handle();
		}
	
	}
	
	
	public function sysValidAction(){
	
		$this->_helper->viewRenderer->setNoRender();
		if(isset($_GET['wsdl'])) {
			$autodiscover = new Zend_Soap_AutoDiscover();
			$autodiscover->setClass('Soap_System');
			$autodiscover->handle();
		} else {
			$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/sys-valid?wsdl");
			$soap->setClass('Soap_System');
			$soap->handle();
		}
	
	}
	
	
	
	
	
	
	
	/**
	 * Handles do Sistema !!!!!!!!!
	 * 
	 */
	
	
	private function hadleSMSWSDL() {
		$autodiscover = new Zend_Soap_AutoDiscover();
		$autodiscover->setClass('Soap_Sms');
		$autodiscover->handle();
	}
	
	private function handleSMSSOAP() {
		$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/sms?wsdl");
		$soap->setClass('Soap_Sms');
		$soap->handle();
	}
	
	private function hadleCEPWSDL() {
		$autodiscover = new Zend_Soap_AutoDiscover();
		$autodiscover->setClass('Soap_Cep');
		$autodiscover->handle();
	}
	
	private function handleCEPSOAP() {
		$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/cep?wsdl");
		$soap->setClass('Soap_Cep');
		$soap->handle();
	}
	
	private function hadleSYSTEMUSERSWSDL() {
		$autodiscover = new Zend_Soap_AutoDiscover();
		$autodiscover->setClass('Soap_SystemUsers');
		$autodiscover->handle();
	}
	
	private function handleSYSTEMUSERSSOAP() {
		$soap = new Zend_Soap_Server($this->configs->Leader->SoapServer . "/systemusers?wsdl");
		$soap->setClass('Soap_SystemUsers');
		$soap->handle();
	}
}