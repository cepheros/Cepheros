<?php
class Cadastros_ServicosController extends Zend_Controller_Action{
	
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
	
	public function novoAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Cadastros_Form_Servicos();
		$form->novo();
		$form->populate(array('tiposervico'=>System_Model_TipoServicos::getRegistroPadrao(),'iss'=>System_Model_Iss::getRegistroPadrao()));
		
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['id_registro']);
				$formData['valorservico'] = str_replace(",", ".", $formData['valorservico']);
							
				try{
					$db = new Cadastros_Model_Servicos();
					$id = $db->insert($formData);
					$this->log->log("Novo Cadastro de Servico Efetuado por: {$userInfo->nomecompleto} Cliente: {$formData['nomeservico']} ",Zend_Log::INFO);
					$this->_redirect("cadastros/servicos/abrir/id/$id/action/novo");
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro do servico {$formData['nomeservico']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
	}
	
	public function listarAction(){
		
	}
	
	public function abrirAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam('id');
		if($this->_getParam('action') == 'novo'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro de Serviço Efetuado",'sucesso'));
		}
		$dados = new Cadastros_Model_Servicos();
		$info = $dados->fetchRow("id_registro = '$id'")->toArray();
		$info['valorservico'] = number_format($info['valorservico'],2,',','');
		$form = new Cadastros_Form_Servicos();
		$form->novo();
		$form->populate($info);		
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$formData['valorservico'] = str_replace(",", ".", $formData['valorservico']);
					
				try{
					$db = new Cadastros_Model_Servicos();
					$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
					$this->log->log("Novo Cadastro de Servico editado  por: {$userInfo->nomecompleto} Cliente: {$formData['nomeservico']} ",Zend_Log::INFO);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Dados Atualizados com Sucesso",'info'));
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro do servico {$formData['nomeservico']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
		
	}

	public function excluirAction(){
		
	}
	
	public function removerAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam('id');
		$this->view->AlertMessage = array(Functions_Messages::renderAlert("Confirme a remoção deste registro",'erro'));
		
		$dados = new Cadastros_Model_Servicos();
		$info = $dados->fetchRow("id_registro = '$id'")->toArray();
		$info['valorservico'] = number_format($info['valorservico'],2,',','');
		$form = new Cadastros_Form_Servicos();
		$form->novo();
		$form->populate($info);
		$this->view->form = $form;
	
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$formData['valorservico'] = str_replace(",", ".", $formData['valorservico']);
					
				try{
					$db = new Cadastros_Model_Servicos();
					$id = $db->delete("id_registro = '{$formData['id_registro']}'");
					$this->log->log(" Cadastro de Servico removido  por: {$userInfo->nomecompleto} Servico: {$formData['nomeservico']} ",Zend_Log::INFO);
					$this->_redirect("cadastros/servicos/listar");
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro do servico {$formData['nomeservico']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				}
	
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
	
	
	}
	
	
}
