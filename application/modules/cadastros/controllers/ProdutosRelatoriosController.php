<?php
class Cadastros_ProdutosRelatoriosController extends Zend_Controller_Action{

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
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		

	}
	
	public function criticoAction(){
	
	}
	
	public function gerarEstoqueCriticoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		try {
			$report = new Reports_Jasper($this->configs->Jasper);
			/*
			 To send output to browser
			*/
			
			
			$tiporelatorio = $_GET['tiporelatorio'];
			
			
			switch($tiporelatorio){
				case 'PDF':
					header('Content-type: application/pdf');
				//	header("Content-Disposition: inline; filename=critico.pdf");
				break;
				
				case 'XLS':
					header('Content-type: application/xls');
					header("Content-Disposition: inline; filename=critico.xls");
				break;
				
				case 'DOCx':
					header('Content-type: application/docx');
					header("Content-Disposition: inline; filename=critico.docx");
				break;
				
				case 'Text':
					header('Content-type: text/plain');
					header("Content-Disposition: inline; filename=critico.txt");
				break;
				
				case 'XML':
					header('Content-type: text/xml');
					header("Content-Disposition: inline; filename=critico.xml");
				break;
				
				default:
					header('Content-type: application/pdf');
				//	header("Content-Disposition: inline; filename=critico.pdf");
				break;
			}
			
			$cogigocliente = $this->configs->Leader->Cliente->codigo;
			
			echo $report->run("/reports/$cogigocliente/estoquecritico",$tiporelatorio);
		
			//	$report->run('/reports/samples/AllAccounts','PDF', null, true);
		
		} catch (\Exception $e) {
			echo $e->getMessage();
			die;
		}
	}
	
	function fichaProdutoAction(){
		$id = $this->_getParam('id');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		try {
			$report = new Reports_Jasper($this->configs->Jasper);
			/*
			 To send output to browser
			*/
			header('Content-type: application/pdf');
			header("Content-Disposition: inline; filename=critico.pdf");
				
			$cogigocliente = $this->configs->Leader->Cliente->codigo;
				
			echo $report->run("/reports/$cogigocliente/fichaproduto",'PDF',array('id_registro'=>$id));
		
			//	$report->run('/reports/samples/AllAccounts','PDF', null, true);
		
		} catch (\Exception $e) {
			echo $e->getMessage();
			die;
		}
		
	}
	
}