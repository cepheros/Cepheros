<?php
class Sistema_UsuariosController extends Zend_Controller_Action{
	
	
	public $log;
	public $configs;
	public $cache;
	public $typePessoa = "1";
	
	
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
		$form = new Sistema_Form_Usuarios();
		$form->cadastro();
		$this->view->form = $form;		
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['id_registro']);
				unset($formData['password2']);
				$departamentos = $formData['departamentos'];
				unset($formData['departamentos']);
				$formData['password'] = md5($formData['password']);
				$formData['passdate'] = date('Y-m-d H:i:s');
				$formData['recoverpass'] = 1;
				try{
					$db = new System_Model_Users();
					$id = $db->insert($formData);
					if(System_Model_SysConfigs::getConfig("AtualizaSistemaCentralUser") == 1){
					$soap = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/systemusers?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/systemusers"));
					$soap->useradd($this->configs->Leader->Cliente->codigo , $this->configs->Leader->SoapKey, $formData);
					}
					$this->log->log("Novo Cadastro de Usuario Efetuado por: {$userInfo->nomecompleto} Cliente: {$formData['nomecompleto']} ",Zend_Log::INFO);
					
					$db2 = new System_Model_UsersDeptos();
					foreach ($departamentos as $key=>$value){
					    $db2->insert(array("id_user"=>$id,"id_depto"=>$value));					    
					}
					
					$this->_redirect("sistema/usuarios/usuario/id/$id");
					
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de usuario {$formData['nomecompleto']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a solicitação:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
	}
	
	public function listarAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$db = new System_Model_Users();
		$dados = $db->fetchAll("id_registro <> '1'")->toArray();
		$this->view->dados = $dados;	
		
		
		
	}
	
	public function usuarioAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$data['departamentos'] = System_Model_UsersDeptos::getUserDeptos($id);
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Usuarios();
		$form->cadastro();
		$form->populate($data);
		$this->view->form = $form;
		
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['password2']);
				$formData['password'] = md5($formData['password']);
				$formData['passdate'] = date('Y-m-d H:i:s');
				$formData['recoverpass'] = 1;
				$departamentos = $formData['departamentos'];
				unset($formData['departamentos']);
				
				try{
					
					$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
					//$soap = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/systemusers?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/systemusers"));
					//$soap->userupdate($this->configs->Leader->Cliente->codigo , $this->configs->Leader->SoapKey, $formData);
					$this->log->log("Novo Cadastro de Usuario Atualizado por: {$userInfo->nomecompleto} Cliente: {$formData['nomecompleto']} ",Zend_Log::INFO);
					$db2 = new System_Model_UsersDeptos();
					$db2->delete("id_user = '{$formData['id_registro']}'");
					foreach ($departamentos as $key=>$value){
						$db2->insert(array("id_user"=>$id,"id_depto"=>$value));
					}
					
					$this->_redirect("sistema/usuarios/usuario/id/{$formData['id_registro']}");
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de usuario {$formData['nomecompleto']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a solicitação:<br>Erro: {$e->getMessage()}",'erro'));
					print_r($formData);
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
		
	}
	
	
	public function removerAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dados = $data;
			
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Usuarios();
		$form->cadastro();
		$form->populate($data);
		$this->view->form = $form;
		
	}
	
	public function confirmRemoverAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Users();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$db->delete("id_registro = '$id'");
		$this->log->log("Cadastro de Usuario Removido por: {$userInfo->nomecompleto} Cliente: {$data['nomecompleto']} ",Zend_Log::INFO);
		$this->_redirect("sistema/usuarios/listar");
			
		
		
	}
	
	
	public function recursosAction(){
		$db = new System_Model_Login_Resources();
		$data = $db->fetchAll();
		$this->view->data = $data;
		
	}
	
	public function salvaRecursosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		if ($this->_request->isPost()) {
			$db = new System_Model_Login_Resources();
			$formData = $this->_request->getPost();
			$dadossalva = array("resource"=>$formData['resource'],
					'description'=>$formData['description']
			);
			if(!$formData['id']){
			$db->insert($dadossalva);
			}else{
				$db->update($dadossalva,"id = '{$formData['id']}'");
			}
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Recurso Cadastrado",'sucesso'));
		}
	}
	
	public function regrasAction(){
		$db = new System_Model_Login_Roles();
				
		
	}
	
	public function permissoesAction(){
		$db = new System_Model_Login_Resources();
		$data = $db->fetchAll('id > 1','resource ASC');
		$this->view->data = $data;
		$id = $this->_getParam("idrole");
		$this->view->idrole = $id;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			$db = new System_Model_Login_Permissions();
			$db->delete("id_role = '{$formData['id_role']}'");
			
			for($i=0;$i<count($formData['id_resource']);$i++){
				$datasave = array('id_role'=>$formData['id_role'],
						'id_resource'=>$formData['id_resource'][$i],
						'permission'=>$formData['permission'][$i],
				);
				
				$db->insert($datasave);
				
				$data = null;
			}
			
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Permissoes Cadastradas",'sucesso'));
			
		}
		
		
	}
	
	
	public function salvaRegraAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		if ($this->_request->isPost()) {
			$db = new System_Model_Login_Roles();
			$formData = $this->_request->getPost();
			$dadossalva = array("role"=>$formData['role'],
					'id_parent'=>$formData['id_parent']
			);
			if(!$formData['id']){
				$db->insert($dadossalva);
			}else{
				$db->update($dadossalva,"id = '{$formData['id']}'");
			}
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Recurso Cadastrado",'sucesso'));
		}
	}
	
		
	
	
	
}