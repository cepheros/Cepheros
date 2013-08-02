<?php
class Cadastros_FuncionariosController extends Zend_Controller_Action{
	
	public $log;
	public $configs;
	public $cache;
	public $typePessoa = "5";
	
	
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
		$form = new Cadastros_Form_Clientes();
		$form->novo();
		$form->populate(array('id_empresa'=>System_Model_Empresas::getEmpresaPadrao()));
		$this->view->form = $form;
		
				
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['id_registro']);
				$formData['tipocadastro'] =  $this->typePessoa;
				$formData['dataabertura'] = Functions_Datas::inverteData($formData['dataabertura']);
				$formData['datacadastro'] = date('Y-m-d H:i:s');
				$formData['userid'] = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
				 
				try{

					
					$db = new Cadastros_Model_Pessoas();
					$id = $db->insert($formData);
					$this->log->log("Novo Cadastro de Cliente Efetuado por: {$userInfo->nomecompleto} Cliente: {$formData['razaosocial']} ",Zend_Log::INFO);
					$db2 = new Cadastros_Model_Outros();
					$data2['id_pessoa'] = $id;
					$db2->insert($data2);
					
					$this->_redirect("cadastros/pessoas/cadastro/id/$id");
					
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro do cliente {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
		
		
	}
	
	public function listarAction(){
		
		$this->view->cadType = $this->typePessoa;
		$action = $this->_getParam('action');
		
		switch($action){
			case 'remove':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro Removido",'sucesso'));
			break;
			default:
			break;
		}		
			
	}
	
	
	public function cadastroAction(){
		$id = $this->_getParam('id');
		$dados = Cadastros_Model_Pessoas::getCadastro($id);
		$form = new Cadastros_Form_Clientes();
		$form->novo();
		$form->populate($dados);
		$this->view->form = $form;
		$this->view->dados = $dados;
		
		$db2 = new Cadastros_Model_Outros();
		$dadosoutros = $db2->fetchRow("id_pessoa = '$id'")->toArray(); 
		$dadosoutros['id_pessoa_outros'] = $dados['id_registro'];
		$dadosoutros['tipocadastro'] = $dados['tipocadastro'];
		
		$formendereco = new Cadastros_Form_Clientes();
		$formendereco->enderecos();
		$formendereco->populate(array("id_pessoa_end"=>$dados['id_registro']));
		$this->view->formendereco = $formendereco;
		
		
		$formfiles = new Cadastros_Form_Clientes();
		$formfiles->files();
		$formfiles->populate(array("idreg"=>$dados['id_registro'],'tipofile'=>'pessoas'));
		$this->view->formfiles = $formfiles;
		
		$forcontatos = new Cadastros_Form_Clientes();
		$forcontatos->contatos();
		$forcontatos->populate(array("id_pessoa_contato"=>$dados['id_registro']));
		$this->view->forcontatos = $forcontatos;
		
		
		$foroutros = new Cadastros_Form_Clientes();
		$foroutros->outros();
		$foroutros->populate($dadosoutros);
		$this->view->formoutros = $foroutros;
		
		$ddend = new Cadastros_Model_Enderecos();
		$dadosendereco = $ddend->fetchAll("id_pessoa = '$id'")->toArray();
		
		
		
		if($dadosendereco){
			$this->view->enderecos = $dadosendereco;
		}else{
			$this->view->enderecos = false;
		}
		
		
		$ddfiles = new System_Model_Files();
		$dadosfiles = $ddfiles->fetchAll("tipofile = 'pessoas' and idreg = '$id'")->toArray();
		
		
		if($dadosfiles){
			$this->view->arquivos = $dadosfiles;
		}else{
			$this->view->arquivos = false;
		}
		
		$ddcontato = new Cadastros_Model_Contatos();
		$dadoscontato= $ddcontato->fetchAll("id_pessoa = '$id'")->toArray();
		
		if($dadoscontato){
			$this->view->contatos = $dadoscontato;
		}else{
			$this->view->contatos = false;
		}
		
		
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['id_registro']);
				$formData['dataabertura'] = Functions_Datas::inverteData($formData['dataabertura']);
				$formData['userlastalt'] = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
				$formData['alteracoes'] = $dados['alteracoes'] + 1;
				$formData['dataultalteracao'] = date('Y-m-d H:i:s');
									
				try{
					$db = new Cadastros_Model_Pessoas();
					$save = $db->update($formData,"id_registro = '$id'");
					$this->log->log("Cadastro do Cliente {$formData['razaosocial']} Efetuado por: {$userInfo->nomecompleto}",Zend_Log::INFO);
					$this->_redirect("cadastros/clientes/cadastro/id/$id");
				}
				catch (Exception $e){
					$this->log->log("Erro na alteração do cadastro do cliente {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
	}
	
	public function imprimirAction(){
		$id = $this->_getParam('id');
		
	}
	
	
	public function outrosdadosAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$form = new Cadastros_Form_Clientes();
		$form->outros();
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				try{
				$db = new Cadastros_Model_Pessoas();
				$db2 = new Cadastros_Model_Outros();
				unset($formData['submitoutros']);
				
				$dados['tipocadastro'] = $formData['tipocadastro'];
				$formData['id_registro'] = $formData['id_registro_outros'];
				$formData['id_pessoa'] = $formData['id_pessoa_outros'];
				unset($formData['id_pessoa_outros']);
				unset($formData['id_registro_outros']);
				unset($formData['id_registro']);
				
				
				$db->update($dados, "id_registro = '{$formData['id_pessoa']}'");
				unset($formData['tipocadastro']);
				$db2->update($formData,"id_pessoa = '{$formData['id_pessoa']}'");
				
				
				}
				catch (Exception $e){
					echo $e->getMessage();
				}
				
			}else{
				
			}
			
		}
		
		

		
	}
	
	public function salvaenderecoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		

		$form = new Cadastros_Form_Clientes();
		$form->enderecos();
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				try{
				$db = new Cadastros_Model_Enderecos();
				unset($formData['submitendereco']);
				if($formData['id_registro_end'] == ''){
					$formData['id_pessoa'] = $formData['id_pessoa_end'];
					unset($formData['id_pessoa_end']);
				unset($formData['id_registro_end']);
				$id = $db->insert($formData);
				$formData['id_registro_end'] = $id;
				$formData['tipoendereco'] = System_Model_Tiposenderecos::getNomeEndereco($formData['tipoendereco']);
				echo json_encode($formData);				
				}else{
					$formData['id_registro'] = $formData['id_registro_end'];
					$formData['id_pessoa'] = $formData['id_pessoa_end'];
					unset($formData['id_pessoa_end']);
					unset($formData['id_registro_end']);
					$db->update($formData,"id_registro = '{$formData['id_registro']}'");
					$formData['tipoendereco'] = System_Model_Tiposenderecos::getNomeEndereco($formData['tipoendereco']);
					echo json_encode($formData);
				}
				}
				catch (Exception $e){
					echo $e->getMessage();
				}
				
			}
		}
	}
	
	
	public function setdefaultendAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$dados = new Cadastros_Model_Enderecos();
		$pessoa = $dados->fetchRow("id_registro = '$id'")->toArray();
		$dados->update(array("isdefault"=>'0'), "id_pessoa = '{$pessoa['id_pessoa']}' ");
		$dados->update(array("isdefault"=>'1'), "id_registro = '{$id}' ");		
		
	}
	
	public function removeendAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$dados = new Cadastros_Model_Enderecos();
		$dados->delete("id_registro = '{$id}' ");
			
	}
	
	public function removecontatoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$dados = new Cadastros_Model_Contatos();
		$dados->delete("id_registro = '{$id}' ");
			
	}
	
	
	public function salvacontatosAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		
		$form = new Cadastros_Form_Clientes();
		$form->contatos();
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				try{
					$db = new Cadastros_Model_Contatos();
					unset($formData['submitcontato']);
					if($formData['id_registro_contato'] == ''){
						$formData['id_pessoa'] = $formData['id_pessoa_contato'];
						unset($formData['id_pessoa_contato']);
						unset($formData['id_registro_contato']);
						$id = $db->insert($formData);
						$formData['id_registro_contato'] = $id;
						$formData['tipocontato'] = System_Model_Tiposcontatos::getNomeEndereco($formData['tipocontato']);
						echo json_encode($formData);
					}else{
						$formData['id_registro'] = $formData['id_registro_contato'];
						$formData['id_pessoa'] = $formData['id_pessoa_contato'];
						unset($formData['id_pessoa_contato']);
						unset($formData['id_registro_contato']);
						$db->update($formData,"id_registro = '{$formData['id_registro']}'");
						$formData['tipocontato'] = System_Model_Tiposcontatos::getNomeEndereco($formData['tipocontato']);
						echo json_encode($formData);
					}
				}
				catch (Exception $e){
					echo $e->getMessage();
				}
		
			}else{
				echo "Formulario incorreto";
			}
		}
		
		
	}

	
	public function setdefaultcontatoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$dados = new Cadastros_Model_Contatos();
		$pessoa = $dados->fetchRow("id_registro = '$id'")->toArray();
		$dados->update(array("isdefault"=>'0'), "id_pessoa = '{$pessoa['id_pessoa']}' ");
		$dados->update(array("isdefault"=>'1'), "id_registro = '{$id}' ");		
		
	}
	
	
	public function saveFileAction(){
		$this->DocsPath = $this->configs->SYS->DocsPath;
		$logdata = $this->log;
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$data = $this->_request->getPost();
		$logdata->log(serialize($data),Zend_Log::INFO);
	
		try{
				
			$db = new System_Model_Files();
			$data2['accesshash'] = sha1(md5(microtime()));
			$data2['tipofile'] = $data['tipofile'];
			$data2['idreg'] = $data['idreg'];
			$data2['obsfile'] = $data['obsfile'];
			$data2['nomeamigavel'] = $data['nomeamigavel'];
			$data2['tags'] = $data['tags'];
			$id_file = $db->insert($data2);
		}catch(Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}
	
		$logdata->log("Path:" . $this->DocsPath .'/'.$data['tipofile'] ,Zend_Log::INFO);
	
		if(!is_dir($this->DocsPath .'/'.$data['tipofile'])){
			mkdir($this->DocsPath .'/'.$data['tipofile']);
		}
	
		if(!is_dir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}")){
			mkdir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}");
		}
	
		if(!is_dir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}/{$id_file}")){
			mkdir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}/{$id_file}");
		}
	
		try{
			$destinationFolder =  $this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}/{$id_file}";
			$upload_adapter = new Zend_File_Transfer_Adapter_Http();
			$upload_adapter->setDestination( $destinationFolder );
			$filename =$upload_adapter->getFileName();
			$hash = $upload_adapter->getHash('md5');
			$minetype = $upload_adapter->getMimeType();
	
			if( $upload_adapter->receive() ){

				$data3['filename'] = $filename;
				$data3['filetype'] = $minetype;
				$data3['filehash'] = $hash;
				$data3['dateadded'] = date('Y-m-d H:i:s');
				$data3['useradded'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
				$db->update($data3, "id_registro = '$id_file'");
					
				$logdata->log("Arquivo Adicionado ao Sistema: Folder: $destinationFolder Arquivo: {$data['filename']}, UsuÃ¡rio: {$data['useradded']} ",Zend_Log::INFO);
	
	
			}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}
		
		$this->_redirect("/cadastros/clientes/cadastro/id/{$data['idreg']}/action/upload");
	
	
	}
	
	public function removerAction(){
		$id = $this->_getParam('id');
		$dados = Cadastros_Model_Pessoas::getCadastro($id);
		$form = new Cadastros_Form_Clientes();
		$form->novo();
		$form->populate($dados);
		$this->view->form = $form;
		$this->view->dados = $dados;
	
	
	}
	
	public function confirmRemoveAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam('id');
		
		$dados = new Cadastros_Model_Pessoas();
		$data = $dados->fetchRow("id_registro = '$id'")->toArray();
		
		
		$dados->delete("id_registro = '$id'");
		
		$this->log->log("Cadastro do Cliente {$data['id_registro']} - {$data['razaosocial']} / {$data['cnpj']} removido por: {$userInfo->nomecompleto}",Zend_Log::INFO);
		
		$this->_redirect("/cadastros/pessoas/listar/action/remove");
	}
	
	
				
			
	
}