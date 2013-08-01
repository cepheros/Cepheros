<?php
class ArquivosController extends Zend_Controller_Action{
	
	public function init(){
	
		
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
		$this->view->parameters = $this->_request->getParams();
		
		$this->DocsPath = $this->configs->SYS->DocsPath;
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
	}
	
	
	public function getFileAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('hash');
		$db = new System_Model_Files();
		$data = $db->fetchRow("accesshash = '$id'");
		//	print_r($data);
		$file = file_get_contents($data->filename);
		$filesize = filesize($data->filename);
		$filename = explode(".",$data->filename);
		$number = count($filename);
		$filerextension = $filename[$number -1];
		$name = $data->filehash.".".$filerextension;
		header("Content-type: {$data->filetype}");
		header("Content-length: $filesize");
		header("Content-Disposition: attachment; filename=$name");
		header("Content-Description: PHP Generated Data");
		
		echo $file;
		
	}
	
	
	
	public function renderAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('hash');
		$db = new System_Model_Files();
		$data = $db->fetchRow("accesshash = '$id'");
		//	print_r($data);
		$filename = explode(".",$data->filename);
		$number = count($filename);
		//$file = file_get_contents($data->filename);
		$filename = explode(".",$data->filename);
		$filerextension = $filename[$number -1];
		$name = $data->filehash.".".$filerextension;
		header("Content-type: {$data->filetype}");
		header('Content-Length: ' . filesize($data->filename));
		header("Content-Disposition: inline; filename=$name");
		readfile($data->filename);
	
	}
	
	public function getLogoReportAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		if(!$id){
			$id = System_Model_Empresas::getEmpresaPadrao();
		}
		$db = new System_Model_Empresas();
		$dadsos = $db->fetchRow("id_registro = '$id'");
		header("Content-type: image");
		header('Content-Length: ' . filesize($dadsos->logotipo));
		readfile($dadsos->logotipo);
		
	}
	
	public function getLogoDanfeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$db = new System_Model_EmpresasNF();
		$dadsos = $db->fetchRow("id_registro = '$id'");
		header("Content-type: image");
		header('Content-Length: ' . filesize($dadsos->logotipodanfe));
		readfile($dadsos->logotipodanfe);
	
	}
	
	public function renderOpCodeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$file = APPLICATION_PATH . '/data/temp' . DIRECTORY_SEPARATOR . "op_$id.png";
		header("Content-type: image/png");
		header('Content-Length: ' . filesize($$file));
		header("Content-Disposition: inline; filename=\"op.png\"");
		readfile($file);
	}

	
	public function xmlNfeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$dbH = new Erp_Model_Faturamento_NFe_HashAcesso();
		$DadosAc = $dbH->fetchRow("hashacesso = '$id'");
		
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '{$DadosAc->id_nfe}'");
		
		header("Content-type: xml");
		header("Content-Disposition: inline; filename=\"{$DadosAc->Id}.xml\"");
		
		readfile($Dados->localxml);
	}
	
	public function danfeNfeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$dbH = new Erp_Model_Faturamento_NFe_HashAcesso();
		$DadosAc = $dbH->fetchRow("hashacesso = '$id'");
		
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '{$DadosAc->id_nfe}'");
		
		$arq = $Dados->localxml;
		$logotipo = System_Model_EmpresasNF::getLogo($Dados->id_empresa);
		if($logotipo <> ''){
			$logo = $logotipo;
		}
		
		if ( is_file($arq) ){
			$docxml = file_get_contents($arq);
			$danfe = new NFe_DanfeNFePHP($docxml, 'P', 'A4',$logo,'I','');
			$id = $danfe->montaDANFE();
			$teste = $danfe->printDANFE($id.'.pdf','I');
		}
		
		
	}
	
	
	
}