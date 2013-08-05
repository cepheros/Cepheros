<?php
class Sistema_EmpresasController extends Zend_Controller_Action{
	
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
	
	
	public function novaAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$form = new Sistema_Form_Empresas();
		$form->basicos();
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				unset($formData['id_registro']);
			
					
				try{
		
						
					$db = new System_Model_Empresas();
					$id = $db->insert($formData);
					$this->log->log("Novo Cadastro de Emnpresa Efetuado por: {$userInfo->nomecompleto} Cliente: {$formData['razaosocial']} ",Zend_Log::INFO);
					$db2 = new System_Model_EmpresasNF();
					$db2->insert(array("id_empresa"=>$id));
						
					$this->_redirect("sistema/empresas/cadastro/id/$id");
						
						
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de empresa  {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
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
	
	public function setdefaultenpAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$dados = new System_Model_Empresas();
		$pessoa = $dados->fetchRow("id_registro = '$id'")->toArray();
		$dados->update(array("principal"=>'0'), "id_registro > 0");
		$dados->update(array("principal"=>'1'), "id_registro = '{$id}' ");
	}
	
	public function cadastroAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$id = $this->_getParam("id");
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$form = new Sistema_Form_Empresas();
		$form->basicos();
		$form->populate($dados);
		$this->view->form = $form;
		$this->view->dados = $dados;
		
		$db2 = new System_Model_EmpresasNF();
		$dadosnf = $db2->fetchRow("id_empresa = '$id'")->toArray();
		
		$this->view->dadosnf = $dadosnf;
		
		$formnfe = new Sistema_Form_Empresas();
		$formnfe->nfe();
		$formnfe->populate($dadosnf);
		$this->view->formnfe = $formnfe;
		
		$ddfiles = new System_Model_Files();
		$dadosfiles = $ddfiles->fetchAll("tipofile = 'empresas' and idreg = '$id'")->toArray();
		
		
		if($dadosfiles){
			$this->view->arquivos = $dadosfiles;
		}else{
			$this->view->arquivos = false;
		}
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
							
					
				try{
		
		
					$db = new System_Model_Empresas();
					$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
					$this->log->log("Cadastro de Emnpresa atualizado por: {$userInfo->nomecompleto} Cliente: {$formData['razaosocial']} ",Zend_Log::INFO);
					
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de empresa  {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
			
	}
	
	
	public function updatenfconfigAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$formData = $this->_request->getPost();
		
		$id = $this->_getParam("id");
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("id_registro = '{$formData['id_empresa']}'")->toArray();
		$form = new Sistema_Form_Empresas();
		$form->basicos();
		$form->populate($dados);
		$this->view->form = $form;
		$this->view->dados = $dados;
		
		$db2 = new System_Model_EmpresasNF();
		$dadosnf = $db2->fetchRow("id_empresa = '{$formData['id_empresa']}'")->toArray();
		
		
		$formnfe = new Sistema_Form_Empresas();
		$formnfe->nfe();
		$formnfe->populate($dadosnf);
		$this->view->formnfe = $formnfe;
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($formnfe->isValid($formData)) {
				unset($formData['submitnfe']);
					
					
				try{
		
		
					$id = $db2->update($formData,"id_empresa = '{$formData['id_empresa']}'");
					$this->log->log("Cadastro de NFe de Emnpresa Efetuado por: {$userInfo->nomecompleto} Cliente: {$formData['id_empresa']} ",Zend_Log::INFO);
						
					$this->_redirect("/sistema/empresas/cadastro/id/{$formData['id_empresa']}");
		
		
				}
				catch (Exception $e){
					$this->log->log("Erro No cadastro de empresa  {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
				
				}
		
			}else{
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios",'erro'),Functions_Messages::renderAlert("Prerencha os Campos obrigatórios e tente enviar novamente",'erro'));
				$form->populate($formData);
			}
		}
		
		
	}
	
	public function logosistemaAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$this->DocsPath = $this->configs->SYS->DocsPath;
		$logdata = $this->log;
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$data = $this->_request->getPost();
		$logdata->log(serialize($data),Zend_Log::INFO);
		
		
		if(!is_dir($this->DocsPath .'/empresas')){
			mkdir($this->DocsPath .'/empresas');
		}
		
		if(!is_dir($this->DocsPath ."/empresas/logos")){
			mkdir($this->DocsPath ."/empresas/logos");
		}
		
		if(!is_dir($this->DocsPath ."/empresas/logos/{$data['id_empresa']}")){
			mkdir($this->DocsPath ."/empresas/logos/{$data['id_empresa']}");
		}
		
		try{
			$destinationFolder =  $this->DocsPath ."/empresas/logos/{$data['id_empresa']}";
			$upload_adapter = new Zend_File_Transfer_Adapter_Http();
			$upload_adapter->setDestination( $destinationFolder );
			$filename =$upload_adapter->getFileName();
			$hash = $upload_adapter->getHash('md5');
			$minetype = $upload_adapter->getMimeType();
				
			$db = new System_Model_Empresas();
		
		
			if( $upload_adapter->receive() ){
		
				$data3['logotipo'] = $filename;
		
				$db->update($data3, "id_registro = '{$data['id_empresa']}'");
					
				$logdata->log("Arquivo Adicionado ao Sistema: Folder: $destinationFolder Arquivo: {$data3['filename']} ",Zend_Log::INFO);
		
		
			}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}
		
		$this->_redirect("/sistema/empresas/cadastro/id/{$data['id_empresa']}/action/upload");
		
	}
	
	

	public function salvaArquivoAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
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
	
		$this->_redirect("/sistema/empresas/cadastro/id/{$data['idreg']}/action/upload");
	}
	
	
	public function logodanfeAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$this->DocsPath = $this->configs->SYS->DocsPath;
		$logdata = $this->log;
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$data = $this->_request->getPost();
		$logdata->log(serialize($data),Zend_Log::INFO);
	
	
		if(!is_dir($this->DocsPath .'/nfe')){
			mkdir($this->DocsPath .'/nfe');
		}
	
		if(!is_dir($this->DocsPath ."/nfe/logos")){
			mkdir($this->DocsPath ."/nfe/logos");
		}
	
		if(!is_dir($this->DocsPath ."/nfe/logos/{$data['id_empresa']}")){
			mkdir($this->DocsPath ."/nfe/logos/{$data['id_empresa']}");
		}
	
		try{
			$destinationFolder =  $this->DocsPath ."/nfe/logos/{$data['id_empresa']}";
			$upload_adapter = new Zend_File_Transfer_Adapter_Http();
			$upload_adapter->setDestination( $destinationFolder );
			$filename =$upload_adapter->getFileName();
			$hash = $upload_adapter->getHash('md5');
			$minetype = $upload_adapter->getMimeType();
	
			$db = new System_Model_EmpresasNF();
	
	
			if( $upload_adapter->receive() ){
	
				$data3['logotipodanfe'] = $filename;
	
				$db->update($data3, "id_empresa = '{$data['id_empresa']}'");
					
				$logdata->log("Arquivo Adicionado ao Sistema: Folder: $destinationFolder Arquivo: {$data3['filename']} ",Zend_Log::INFO);
	
	
			}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}
	
		$this->_redirect("/sistema/empresas/cadastro/id/{$data['id_empresa']}/action/upload");
	
	}
	
	
	public function certificadodigitalAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$this->DocsPath = $this->configs->SYS->CertsPath;
		$logdata = $this->log;
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$data = $this->_request->getPost();
		$logdata->log(serialize($data),Zend_Log::INFO);
	
	
	
	
		if(!is_dir($this->DocsPath ."/{$data['id_empresa']}")){
			mkdir($this->DocsPath ."/{$data['id_empresa']}");
		}
	
		try{
			$destinationFolder =  $this->DocsPath ."/{$data['id_empresa']}";
			$upload_adapter = new Zend_File_Transfer_Adapter_Http();
			$upload_adapter->setDestination( $destinationFolder );
			$filename =$upload_adapter->getFileName();
			$hash = $upload_adapter->getHash('md5');
			$minetype = $upload_adapter->getMimeType();
	
			$db = new System_Model_EmpresasNF();
	
	
			if( $upload_adapter->receive() ){
	
				$data3['certificadodigital'] = $filename;
				
			
	
				$db->update($data3, "id_empresa = '{$data['id_empresa']}'");
				
				$certdata = Functions_Verificadores::validCerts($data['id_empresa']);
				
				$data4['validadecertificado'] = Functions_Datas::inverteData($certdata['dataexpire']);
				
				$db->update($data4, "id_empresa = '{$data['id_empresa']}'");
					
				$logdata->log("Arquivo Adicionado ao Sistema: Folder: $destinationFolder Arquivo: {$data3['filename']} ",Zend_Log::INFO);
				
				
	
	
			}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}
	
		$this->_redirect("/sistema/empresas/cadastro/id/{$data['id_empresa']}/action/upload");
	
	}
	
	
	
	
	
	
}