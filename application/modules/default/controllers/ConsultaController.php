<?php
class ConsultaController extends Zend_Controller_Action{

	public function init(){


		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');

		$this->view->parameters = $this->_request->getParams();

		$this->DocsPath = $this->configs->SYS->DocsPath;

		
		$this->_helper->layout()->setLayout('outside');

	}
	
	
	public function indexAction(){
		$this->_redirect("/default/index/index");
	}
	
	public function osAction(){
		$os = $this->_getParam('cod');
		$db= new Crm_Model_Os_Basicos();
		$dados = $db->fetchRow("accesshash = '$os'");
		if(isset($dados)){
		$this->view->osfound = true;
			$this->view->dados = $dados;
			
			$datanotes = new Crm_Model_Os_Anotacoes();
			$dadosnotes = $datanotes->fetchAll("id_os = '{$dados->id_registro}'");
			$this->view->datanotes = $dadosnotes;
			
		}else{
			$this->view->osfound = false;
		$this->view->localizador =  $os;
		}
		
	}
	
	public function printOsAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		//	print_r($this->configs->Jasper);
		//	echo $this->configs->Leader->Cliente->codigo;
			
		try {
			$report = new Reports_Jasper($this->configs->Jasper);
				
			$cogigocliente = $this->configs->Leader->Cliente->codigo;
	
			$report = $report->run("/reports/$cogigocliente/OrdemDeServicos",'PDF',array('ID_OS'=>$id),false);
		//	header("Content-type: application/pdf");
		//	header('Content-Length: ' . filesize($report));
		//	header("Content-Disposition: inline; filename=os,pdf");
		//	readfile($report);
		echo $report;
	
		} catch (\Exception $e) {
			echo $e->getMessage();
			die;
		}
	
	
	
	
	}
	
	public function prospectAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$pros = new Crm_Model_Prospects_Basico();
		$data = $pros->fetchRow("hashprospect = '$id'");
		$DATA2['statusprospect'] = 1;
		$DATA2['dateread'] = date('Y-m-d H:i:s');
		$DATA2['readtimes'] = $data['readtimes'] + 1;
		$pros->update($DATA2, "id_registro = '{$data['id_registro']}'");
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new System_Model_Empresas();
		$id_empresa = System_Model_Empresas::getEmpresaPadrao();
		$data = $db->fetchRow("id_registro = '$id_empresa'");
		//	print_r($data);
		$filename = explode(".",$data->logotipo);
		$number = count($filename);
		//$file = file_get_contents($data->filename);
		$filename = explode(".",$data->logotipo);
		$filerextension = $filename[$number -1];
		$name = md5($data->logotipo).".".$filerextension;
		header("Content-type: {$filerextension}");
		header('Content-Length: ' . filesize($data->logotipo));
		header("Content-Disposition: inline; filename=$name");
		readfile($data->logotipo);
		
	}
	
	public function prospectReplyAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$pros = new Crm_Model_Prospects_Updates();
		$data = $pros->fetchRow("hashreply = '$id'");
		$DATA2['dateread'] = date('Y-m-d H:i:s');
		$DATA2['timesread'] = $data['readtimes'] + 1;
		$pros->update($DATA2, "id_registro = '{$data['id_registro']}'");
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new System_Model_Empresas();
		$id_empresa = System_Model_Empresas::getEmpresaPadrao();
		$data = $db->fetchRow("id_registro = '$id_empresa'");
		//	print_r($data);
		$filename = explode(".",$data->logotipo);
		$number = count($filename);
		//$file = file_get_contents($data->filename);
		$filename = explode(".",$data->logotipo);
		$filerextension = $filename[$number -1];
		$name = md5($data->logotipo).".".$filerextension;
		header("Content-type: {$filerextension}");
		header('Content-Length: ' . filesize($data->logotipo));
		header("Content-Disposition: inline; filename=$name");
		readfile($data->logotipo);
	
	}
	

}