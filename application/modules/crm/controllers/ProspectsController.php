<?php
class Crm_ProspectsController extends Zend_Controller_Action{
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
		$this->view->protocol = Functions_Tickets::gerenateProspect();
		
		if ($this->_request->isPost()) {
			$form = $this->_request->getPost();
			$db = new Crm_Model_Prospects_Basico();
			unset($form['submit']);
			unset($form['solicitante']);
			
			$options['sendemail'] = $form['sendemail'];
			unset($form['sendemail']);
			$options['sendsms'] = $form['sendsms'];
			unset($form['sendsms']);
			$options['acompanhar'] = $form['acompanhar'];
			unset($form['acompanhar']);
			$options['alertagerente'] = $form['alertagerente'];
			unset($form['alertagerente']);
			$options['closeafter'] = $form['closeafter'];
			unset($form['closeafter']);
			if(!$form['atribuidoa']){
				$form['atribuidoa'] = '0';
			}
			
			if(!$options['sendemail']){
				$form['sendmail'] = '0';
			}else{
				$form['sendmail'] = 1;
			}
						
			$form['datalimite'] = Functions_Datas::inverteData($form['datalimite']);
			$form['probabilidadeproposta'] = str_replace(",", ".", $form['probabilidadeproposta']);
			$form['valorproposta'] = str_replace(",", ".", $form['valorproposta']);
			$form['dateopen'] = date('Y-m-d H:i:s');
			$form['useropen'] = $userInfo->id_registro;
			$form['hashprospect'] = md5($form['protocolo']);
			$form['statusprospect'] = '0';
			$form['readtimes'] = '0';
			$id = $db->insert($form);
			
			if($options['sendemail'] == 1){
				Functions_Email::sendMailProspect($id);
			}
			
			$this->_redirect("/crm/prospects/abrir/id/$id");
			
			
			
			
		}
	}
	
	public function abrirAction(){
	
		
		$id = $this->_getParam('id');
		$action = $this->_getParam('tkaction');
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchRow("id_registro = '$id'");
		$this->view->prospect = $dados; 
		
		
		$db2 = new Crm_Model_Prospects_Updates();
		$dados2 = $db2->fetchAll("id_prospect = '$id'","dataresposta DESC");
		$this->view->dadosreply = $dados2;
		
		$db3 = new Crm_Model_Prospects_Estagios();
		$dados3 = $db3->fetchAll("id_prospect = '$id'","dataatualizacao asc");
		$this->view->dadosestagios = $dados3;
		
		$ddfiles = new System_Model_Files();
		$dadosfiles = $ddfiles->fetchAll("tipofile = 'prospects' and idreg = '$id'")->toArray();
		
		
		if($dadosfiles){
			$this->view->arquivos = $dadosfiles;
		}else{
			$this->view->arquivos = false;
		}
		
		$userid = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db4 = new Crm_Model_Prospects_Notes();
		$dados4 = $db4->fetchAll("id_prospect = '$id' and privatenote = '0'");
		$dados5 = $db4->fetchAll("id_prospect = '$id' and privatenote = '1' and id_user = '$userid'");
		$this->view->dadosnotes = $dados4;
		$this->view->dadosprivatenotes = $dados5;
		
		
		$this->view->interacoes = Crm_Model_Prospects_Updates::countReplys($id) + 1;
		$this->view->anotacoes = Crm_Model_Prospects_Notes::countNotes($id);
		
		$db5 = new Crm_Model_Prospects_Checklist();
		$dadoschecklist = $db5->fetchRow("id_prospect = '$id'");
		if($dadoschecklist->id_registro){
			$this->view->haschecklist = true;
			$this->view->checklists = $dadoschecklist;
		//	print_r($dadoschecklist);
			$db6 = new Crm_Model_Prospects_ChecklistItens();
			$itenschecklist = $db6->fetchAll("id_prospect = '$id'");
			//print_r($itenschecklist);
			$this->view->itenschecklist = $itenschecklist;
		}else{
			$this->view->checklist = false;
		}
		
		
		
			
		switch($action){
			default:
				break;
				case 'atribuido':
					$this->view->AlertMessage = array(Functions_Messages::renderAlert("Prospect atribuido a você com sucesso",'sucesso'));
					break;
			case 'newreply':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Nova Etapa adicionada",'sucesso'));
				break;
			case 'upload':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Novo Arquivo adicionado",'sucesso'));
				break;
			case 'newnote':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Nova Nota adicionada",'sucesso'));
				break;
			case 'editreply':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Resposta editada com sucesso",'sucesso'));
				break;
			case 'removereply':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Resposta removida com sucesso",'sucesso'));
				break;
			case 'editnote':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Nota editada com sucesso",'sucesso'));
				break;
			case 'removenote':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Nota Removida com sucesso",'sucesso'));
				break;
			case 'editoptions':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Opções alteradas com sucesso",'sucesso'));
				break;
			case 'mergetickets':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Tickets Unidos com sucesso",'sucesso'));
				break;
					
		}
		
	}
	
	public function replyAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$this->view->protocol = Functions_Tickets::gerenateProspect();
		
		if ($this->_request->isPost()) {
			try{
			$form = $this->_request->getPost();
			$db = new Crm_Model_Prospects_Basico();
			
			$dadosprospect = $db->fetchRow("id_registro = '{$form['id_prospect']}'");
						
			unset($form['submit']);
			unset($form['solicitante']);
				
		
			$formupdate['datalimite'] = Functions_Datas::inverteData($form['datalimite']);
			$formupdate['estagioproposta'] = $form['estagioproposta'];
			$formupdate['probabilidadeproposta'] = str_replace(",", ".", $form['probabilidadeproposta']);
			$formupdate['valorproposta'] = str_replace(",", ".", $form['valorproposta']);
			$formupdate['datelastupdate'] = date('Y-m-d H:i:s');
			$formupdate['userlastupdate'] = $userInfo->id_registro;				
			$db->update($formupdate,"id_registro = '{$form['id_prospect']}'");
			
			$db2 = new Crm_Model_Prospects_Updates();
			$dados['id_prospect'] = $form['id_prospect'];
			$dados['id_user'] = $userInfo->id_registro;
			$dados['nomeresposta']  = $userInfo->nomecompleto;
			$dados['emailresposta'] = $userInfo->email;
			$dados['dataresposta'] = date('Y-m-d H:i:s');
			$dados['estagioproposta'] = $form['estagioproposta'];
			$dados['probabilidadeproposta'] = str_replace(",", ".", $form['probabilidadeproposta']);
			$dados['datalimite'] =  Functions_Datas::inverteData($form['datalimite']);
			$dados['valorproposta'] = str_replace(",", ".", $form['valorproposta']);
			$dados['informacoesresposta'] = $form['informacoesresposta'];
			$dados['hashreply'] = md5(sha1($form['id_prospect'] . microtime()));
			$id_reply = $db2->insert($dados);
			
			$db3 = new Crm_Model_Prospects_Estagios();
			$dtestagio['id_prospect'] = $form['id_prospect'];
			$dtestagio['dataatualizacao'] = date('Y-m-d H:i:s');
			$dtestagio['user_id'] = $form['id_prospect'];
			$dtestagio['westagioproposta'] = $dadosprospect->estagioproposta;
			$dtestagio['iestagioproposta'] = $form['estagioproposta'];
			$dtestagio['wprobabilidadeproposta'] = $dadosprospect->probabilidadeproposta;
			$dtestagio['iprobabilidadeproposta'] = str_replace(",", ".", $form['probabilidadeproposta']);
			$dtestagio['wdatalimite'] = $dadosprospect->datalimite;
			$dtestagio['idatalimite'] =  Functions_Datas::inverteData($form['datalimite']);
			$dtestagio['wvalorproposta'] =  $dadosprospect->valorproposta;
			$dtestagio['ivalorproposta'] = str_replace(",", ".", $form['valorproposta']);
			$db3->insert($dtestagio);
			
			if($dadosprospect->sendmail == 1){
				Functions_Email::sendMailProspect($form['id_prospect'],'reply',$id_reply);
			}		
				
			$this->_redirect("/crm/prospects/abrir/id/{$form['id_prospect']}/tkaction/newreply");
			
			
			}catch (Exception $e){
				echo $e->getMessage();
			}
				
				
				
				
		}
		
		
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
	
		$this->_redirect("/crm/prospects/abrir/id/{$data['idreg']}/tkaction/upload");
	
	
	}
	
	public function saveNoteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		if ($this->_request->isPost()) {
			$db = new Crm_Model_Prospects_Notes();
			$form = $this->_request->getPost();
			if(!isset($form['privatenote'])){
				$form['privatenote'] = '0';
			}
			$savedata['id_prospect'] = $form['id_prospect'];
			$savedata['id_user'] = $userInfo->id_registro;
			$savedata['privatenote'] = $form['privatenote'];
			$savedata['assuntonote'] = $form['assuntoticket'];
			$savedata['textonote'] = $form['dadosticket'];
			$savedata['datanote'] = date('Y-m-d H:i:s');
			$db->insert($savedata);
				
			$this->_redirect("/crm/prospects/abrir/id/{$form['id_prospect']}/tkaction/newnote");
		}
	
	
	}
	
	
	public function getnotedataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Crm_Model_Prospects_Notes();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
	
		echo json_encode($data);
	
	}
	
	
	public function editnoteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		if ($this->_request->isPost()) {
			$form = $this->_request->getPost();
			unset($form['submit']);
			$data['assuntonote'] = $form['assuntoticket'];
			$data['textonote'] = $form['dadosticket'];
	
	
			$db = new Crm_Model_Prospects_Notes();
			$db->update($data, "id_registro = '{$form['id_note']}'");
	
			$dados = $db->fetchRow("id_registro = '{$form['id_note']}'");
	
			$this->_redirect("/crm/prospects/abrir/id/{$dados->id_prospect}/tkaction/editnote");
	
	
		}
	
	}
	
	public function removeNoteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
	
		$db = new Crm_Model_Prospects_Notes();
		$dados = $db->fetchRow("id_registro = '$id'");
		$db->delete("id_registro = '$id'");
	
		$this->_redirect("/crm/prospects/abrir/id/{$dados->id_prospect}/tkaction/removenote");
			
	}
	
	
	public function listarAction(){
		
		$this->view->countNaoResolvidos = Crm_Model_Prospects_Basico::countNaoResolvidos();
		$this->view->countNaoAtribuidos = Crm_Model_Prospects_Basico::countNaoAtribuidos();
		$this->view->countAtualizadoRecente = Crm_Model_Prospects_Basico::countAtualizadoRecente();
		$this->view->countResolvidoRecente = Crm_Model_Prospects_Basico::countResolvidoRecente();
		$this->view->countSuspended = Crm_Model_Prospects_Basico::countSuspended();
		$this->view->countPendent = Crm_Model_Prospects_Basico::countPendent();
		$this->view->countClosed = Crm_Model_Prospects_Basico::countClosed();
		$this->view->countGeral = Crm_Model_Prospects_Basico::countGeral();
		
		
		
		
	}
	
	public function atribuirAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new Crm_Model_Prospects_Basico();
		$dados['atribuidoa'] = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$dados = $db->update($dados,"id_registro = '$id'");
		$this->_redirect("/crm/prospects/abrir/id/{$id}/tkaction/atribuido");
		
	}
	
	public function checklistokAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$data = date('Y-m-d H:i:s');
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$idprospect = $this->_getParam('prospect');
		$iditem =  $this->_getParam('iditem');
		$db = new Crm_Model_Prospects_Basico();
		$db2 = new Crm_Model_Prospects_ChecklistItens();
		$dados = $db2->fetchRow("id_prospect = '$idprospect' and id_item = '$iditem'");
		if($dados->statusitem == '0'){
			$db2->update(array('statusitem'=>1,
					'userid'=>$userInfo->id_registro,
					'dateitem'=> $data), "id_prospect = '$idprospect' and id_item = '$iditem'" );
		}else{
			$db2->update(array('statusitem'=>0,
					'userid'=>NULL,
					'dateitem'=> NULL), "id_prospect = '$idprospect' and id_item = '$iditem'" );
			
		}
		
		
		
	}
	
	
}