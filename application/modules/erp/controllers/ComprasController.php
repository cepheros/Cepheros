<?php
class Erp_ComprasController extends Zend_Controller_Action{

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
	
	public function novoAction(){
		
	}
	public function listarAction(){
		
	}
	public function entradaNfeAction(){
		
		error_reporting(0);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$nfe = new NFe_ToolsNFePHP(System_Model_EmpresasNF::getConfigNFe('3'),'1',false);
		$modSOAP = '2'; //usando cURL
		$tpAmb = '1';//usando produção
		$indNFe = '0';
		$indEmi = '0';
		$ultNSU = '0';
		$AN = true;
		$retorno = array();
		
		if (!$xml = $nfe->getListNFe($AN, $indNFe, $indEmi, $ultNSU, $tpAmb, $modSOAP, $retorno)){
			header('Content-type: text/html; charset=UTF-8');
			echo "Houve erro !! $nfe->errMsg";
			echo '<br><br><PRE>';
					echo htmlspecialchars($nfe->soapDebug);
					echo '</PRE><BR>';
		} else {
					
					$Lxml = new DOMDocument();
					$Lxml->load($xml);
					
					//$dom = new DOMDocument();
					//$dom->
					//echo $dom->getElementById("xMotivo");
						print_r($retorno);
					//echo hmlspecialchars($nfe->soapDebug);
					////header('Content-type: text/xml; charset=UTF-8');
					//echo($xml);
					}
		
		
		
	}
	public function entradaManualAction(){
		
	}
	
	
}