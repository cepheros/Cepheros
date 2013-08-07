<?php
class System_ServicosController extends Zend_Controller_Action{
	
	
	
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
	
	public function issAction(){
		$dados = new System_Model_Iss();
		$data = $dados->fetchAll();
		$this->view->dados = $data;
		
	}
	public function getissdataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Iss();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$dados['valoriss'] = number_format($dados['valoriss'],2,',','');
		$dados['valorpis'] = number_format($dados['valorpis'],2,',','');
		$dados['valorcofins'] = number_format($dados['valorcofins'],2,',','');
		echo json_encode($dados);
	}
	
	public function removeissAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Iss();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/system/servicos/iss");
	
	}
	
	public function novoIssAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_Iss();
		$data['valoriss'] = str_replace(",", ".", $data['valoriss']);
		$data['valorpis'] = str_replace(",", ".", $data['valorpis']);
		$data['valorcofins'] = str_replace(",", ".", $data['valorcofins']);
		
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/system/servicos/iss");
	
	}
	
	public function tiposServicosAction(){
		$dados = new System_Model_TipoServicos();
		$data = $dados->fetchAll();
		$this->view->dados = $data;
		
	}
	
	public function gettipodataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_TipoServicos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function novoTipoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_TipoServicos();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/system/servicos/tipos-servicos");
	
	}
	
	public function removetipoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_TipoServicos();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/system/servicos/tipos-servicos");
	
	}
	
	
	
	
		
}