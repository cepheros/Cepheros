<?php
class System_ProducaoController extends Zend_Controller_Action{
	
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
		
		$this->DocsPath = $this->configs->SYS->DocsPath;
	
	}
	
	
	public function configsAction(){
		
		if ($this->_request->isPost()) {
				
		
			if($_POST['StatusProdEncerrada'] <> ''){
				System_Model_SysConfigs::updateConfig("StatusProdEncerrada", $_POST['StatusProdEncerrada']);
			}else{
				System_Model_SysConfigs::updateConfig("StatusProdEncerrada", '0');
			}
			
			if($_POST['TravaProducaoEstoque'] <> ''){
				System_Model_SysConfigs::updateConfig("TravaProducaoEstoque", $_POST['TravaProducaoEstoque']);
			}else{
				System_Model_SysConfigs::updateConfig("TravaProducaoEstoque", '0');
			}
			
			if($_POST['ProducaoTinyUrl'] <> ''){
				System_Model_SysConfigs::updateConfig("ProducaoTinyUrl", $_POST['ProducaoTinyUrl']);
			}else{
				System_Model_SysConfigs::updateConfig("ProducaoTinyUrl", '0');
			}
			
			if($_POST['ProducaoEtapaFinal'] <> ''){
				System_Model_SysConfigs::updateConfig("ProducaoEtapaFinal", $_POST['ProducaoEtapaFinal']);
			}else{
				System_Model_SysConfigs::updateConfig("ProducaoEtapaFinal", '0');
			}
			
			if($_POST['ProducaoEstoqueProdutos'] <> ''){
				System_Model_SysConfigs::updateConfig("ProducaoEstoqueProdutos", $_POST['ProducaoEstoqueProdutos']);
			}else{
				System_Model_SysConfigs::updateConfig("ProducaoEstoqueProdutos", '0');
			}
			
			if($_POST['ProducaoStatusAndamento'] <> ''){
				System_Model_SysConfigs::updateConfig("ProducaoStatusAndamento", $_POST['ProducaoStatusAndamento']);
			}else{
				System_Model_SysConfigs::updateConfig("ProducaoStatusAndamento", '0');
			}
		}
		
	}
	
	/**
	 * Controller das configurações de estapas da produção
	 */
	
	public function etapasAction(){
		
		$db = new Erp_Model_Producao_Etapas();
	
		
		if ($this->_request->isPost()) {
		
			
			$recaptcha = Functions_Auxilio::renderCaptcha();
			$result = $recaptcha->verify(
					$_POST['recaptcha_challenge_field'],
					$_POST['recaptcha_response_field']
			);
			if (!$result->isValid()) {
				$messages[] = Functions_Messages::renderAlert("As palavras digitadas estão incorretas:",'erro');
			}else{
				$db->delete("id_registro = '{$_POST['id_registro']}'");
				$messages[] = Functions_Messages::renderAlert("Etapa Excluída:",'sucesso');
			}
		}
		$this->view->AlertMessage = $messages;
		
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		
		
	}
	
	public function salvaEtapaAction(){
		
		if ($this->_request->isPost()) {
			
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
						
			$formData = $this->_request->getPost();
			unset($formData['submit']);
			$db = new Erp_Model_Producao_Etapas();
			if($formData['id_registro'] == '' || $formData['id_registro'] == 0){
				$db->insert($formData);
			}else{
				$db->update($formData,"id_registro = '{$formData['id_registro']}'");	
			}
			
		}

		$this->_redirect("/system/producao/etapas");
	}
	
	
	public function editaEtapaAction(){
		$this->_helper->layout()->setLayout('modal');
		$id = $this->_getParam('id');
		$db = new Erp_Model_Producao_Etapas();
		$dados = $db->fetchRow("id_registro = '$id'");
		$this->view->dados= $dados;
		
	}
	
	
	
	
}