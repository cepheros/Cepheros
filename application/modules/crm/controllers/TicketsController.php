<?php
class Crm_TicketsController extends Zend_Controller_Action{

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
		$this->view->totalTickets = Crm_Model_TicketsBasicos::countTotalTickets();
		$this->view->ticketsPorCanal = Crm_Model_TicketsBasicos::TicketsPorCanais();
		$this->view->TicketsPorStaff = Crm_Model_TicketsBasicos::TicketsPorStaff();
		$this->view->TicketsPorDepto = Crm_Model_TicketsBasicos::TicketsPorDepto();
		
		
		$db = new Crm_Model_TicketsBasicos();
		
		for($iat = 1; $iat > 30; $iat++){
			$data = Functions_Datas::SubtraiData(date('d/m/Y'),$iat);
			$dataseletc = Functions_Datas::inverteData($data);
			$select = $db->select()->from($db,array("count(id_registro) as total"))->where("dateopen >= '$dataseletc 00:00:00' and dateopen <= '$dataseletc 23:59:59'");
			echo $select;
			$dados = $db->fetchRow($select);
			if(isset($dados->total)){
				$quantidade = $dados->total;
			}else{
				$quantidade = '0';
			}
			$retorno[$data] = $quantidade;
		}
		
		
	
	}
	
	public function listarAction(){
		$this->view->countNaoResolvidos = Crm_Model_TicketsBasicos::countNaoResolvidos();
		$this->view->countAcompanhamentos = Crm_Model_TicketsBasicos::countAcompanhamentos();
		$this->view->countDeptos = Crm_Model_TicketsBasicos::countDeptos();
		$this->view->countNaoAtribuidos = Crm_Model_TicketsBasicos::countNaoAtribuidos();
		$this->view->countAtualizadoRecente = Crm_Model_TicketsBasicos::countAtualizadoRecente();
		$this->view->countResolvidoRecente = Crm_Model_TicketsBasicos::countResolvidoRecente();
		$this->view->countSuspended = Crm_Model_TicketsBasicos::countSuspended();
		$this->view->countPendent = Crm_Model_TicketsBasicos::countPendent();
		$this->view->countClosed = Crm_Model_TicketsBasicos::countClosed();
		
		$this->view->totalTickets = Crm_Model_TicketsBasicos::countTotalTickets();
		$this->view->ticketsPorCaral = Crm_Model_TicketsBasicos::TicketsPorCanais();
		
		
		
	}
	
	public function novoAction(){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		$this->view->protocol = Functions_Tickets::gerenateProtocol();
		if ($this->_request->isPost()) {
			$form = $this->_request->getPost();
			if(!$form['atribuidoa']){
				$form['atribuidoa'] = '0';
			}
			
			$dados['protocolo'] = $form['protocolo'];
			$dados['id_empresa'] = $form['empresa'];
			$dados['solicitante'] = $form['solicitante'];
			$dados['nomesolicitante'] = $form['nomesolicitante'];
			$dados['emailsolicitante'] = $form['emailsolicitante'];
			$dados['celularsolicitante'] = $form['celularsolicitante'];
			$dados['departamento'] = $form['departamento'];
			$dados['atribuidoa'] = $form['atribuidoa'];
			$dados['tipoticket'] = $form['tipoticket'];
			$dados['prioridadeticket'] = $form['prioridadeticket'];
			$dados['tags'] = $form['tags'];
			$dados['assuntoticket'] = $form['assuntoticket'];
			$dados['dadosticket'] = $form['dadosticket'];
			$dados['dateopen'] = date("Y-m-d H:i:s");
			$dados['datefirstreply'] =  null;
			$dados['datelastreply'] = null;
			$dados['dateclosed'] = null;
			$dados['datereopen'] = null;
			$dados['statusticket'] = '1';
			$dados['staffopen'] = $userInfo->id_registro; 
			$dados['stafffirstreply'] = 0;
			$dados['stafflastreply'] = 0;
			$dados['flagticket'] = 0;
			$dados['datedue'] = Functions_Datas::inverteData(Functions_Datas::SomaData(date('d/m/Y'), Crm_Model_TicketsPrioridades::getDueTipo($form['prioridadeticket'])));
			$tickets = new Crm_Model_TicketsBasicos();
			$id = $tickets->insert($dados);
			$this->log->log("Ticket Criado {$form['protocolo']}, usuário: {$userInfo->nomecompleto} ",Zend_Log::INFO);
			
			if(isset($form['sendemail']) && $form['sendemail'] == 1){
				$this->log->log("Enviando Email",Zend_Log::INFO);
				$mail = new Functions_Tickets;
				$mail->sendMail($id);
				
			}
			
			if(isset($form['sendsms']) && $form['sendsms'] == 1){
			
			}
			
			if(isset($form['acompanhar']) && $form['acompanhar'] == 1){
				if(Crm_Model_TicketsAcompanhantes::check($id, $userInfo->id_registro)){
					$dataac = new Crm_Model_TicketsAcompanhantes();
					$dataac->insert(array('id_ticket'=>$id,'id_user'=>$userInfo->id_registro));
				}
					
			}
			
			if(isset($form['alertagerente']) && $form['alertagerente'] == 1){
				
				if(Crm_Model_TicketsAcompanhantes::check($id, Crm_Model_TicketsDeptos::getGerenteDepto($form['departamento']))){
					$dataac = new Crm_Model_TicketsAcompanhantes();
					$dataac->insert(array('id_ticket'=>$id,'id_user'=> Crm_Model_TicketsDeptos::getGerenteDepto($form['departamento'])));
					
					$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
					$dbm = new System_Model_Messages();
					$savedata['user_from'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
					$savedata['user_to'] =  Crm_Model_TicketsDeptos::getGerenteDepto($form['departamento']);
					$savedata['statusmessage'] = '1';
					$savedata['assuntomessage'] = "Aviso de Ticket";
					$savedata['contentmessage'] = "<strong>Caro,</strong><br>Foi Solicitado pelo usuário {$usuario} o acompanhamento do ticket {$form['protocolo']} acesse o link abaixo para abrir o ticket <br> <a href=\"/crm/tickets/abrir/id/$id\">Abrir Ticket {$form['protocolo']}</a> ";
					$savedata['datemessage'] = date('Y-m-d H:i:s');
					$savedata['messageprioridade'] = "1";
					$savedata['flagmessage'] = "1";
					$dbm->insert($savedata);
				}
				
					
			}

			if(isset($form['closeafter']) && $form['closeafter'] == 1){
				$db->update(array("statusticket"=>'3',
						'dateclosed'=>date('Y-m-d H:i:s')), "id_registro = '{$dadosticket['id_registro']}'");
			}
			
			
			$this->_redirect("/crm/tickets/abrir/id/$id");
			
			
			
			
			
			
		}
		
		
		
		
	}
	
	public function abrirAction(){
		$id = $this->_getParam('id');
		$action = $this->_getParam('tkaction');
		switch($action){
			default:
			break;
			case 'newreply':
				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Nova Resposta adicionada",'sucesso'));
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
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchRow("id_registro = '$id'");
		$this->view->dadosticket = $dados;
		
		$db2 = new Crm_Model_TicketsReplys();
		$dados2 = $db2->fetchAll("id_ticket = '$id'","replydate DESC");
		$this->view->dadosreplys = $dados2;
		
		$userid = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		
		$db3 = new Crm_Model_TicketsNotes();
		$dados3 = $db3->fetchAll("id_ticket = '$id' and privatenote = '0'");
		$dados4 = $db3->fetchAll("id_ticket = '$id' and privatenote = '1' and id_user = '$userid'");
		$this->view->dadosnotes = $dados3;
		$this->view->dadosprivatenotes = $dados4;
		
		$this->view->interacoes = Crm_Model_TicketsReplys::countReplys($id) + 1;
		$this->view->anotacoes = Crm_Model_TicketsNotes::countNotes($id);
		
		$ddfiles = new System_Model_Files();
		$dadosfiles = $ddfiles->fetchAll("tipofile = 'tickets' and idreg = '$id'")->toArray();
		
		
		if($dadosfiles){
			$this->view->arquivos = $dadosfiles;
		}else{
			$this->view->arquivos = false;
		}
		
	}
	
	
	public function replyAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		if ($this->_request->isPost()) {
			$db = new Crm_Model_TicketsBasicos();
			$db2 = new Crm_Model_TicketsReplys();
			$form = $this->_request->getPost();
			$interacoes = Crm_Model_TicketsReplys::countReplys($form['id_ticket']) + 1;
						
			$dadosticket = $db->fetchRow("id_registro = '{$form['id_ticket']}'")->toArray();
			try{
			$savereply['id_ticket'] = $form['id_ticket'];
			$savereply['staffreply'] = $userInfo->id_registro;
			$savereply['nomereply'] = $userInfo->nomecompleto;
			$savereply['emailreply'] = $userInfo->email;
			$savereply['assuntoreply'] = $form['assuntoticket'];
			$savereply['textreply'] = $form['dadosticket'];
			$savereply['replydate'] = date('Y-m-d H:i:s');
			$idreply = $db2->insert($savereply);
			$this->log->log("Ticket Atualizado {$dadosticket['protocolo']}, usuário: {$userInfo->nomecompleto} ",Zend_Log::INFO);
			}catch (Exception $e){
				$this->log->log("Erro na Atualização do ticket {$dadosticket['protocolo']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
			}
			
			if($interacoes > 1){
				
				$db->update(array('datelastreply'=>date('Y-m-d H:i:s'),'stafflastreply'=>$userInfo->id_registro),"id_registro = '{$dadosticket['id_registro']}'");
				
				
			}else{
				
				$db->update(array('datefirstreply'=>date('Y-m-d H:i:s'),'datelastreply'=>date('Y-m-d H:i:s'),'stafffirstreply'=>$userInfo->id_registro,'stafflastreply'=>$userInfo->id_registro),"id_registro = '{$dadosticket['id_registro']}'");
			}
			
			
			if(isset($form['sendemail']) && $form['sendemail'] == 1){
				$this->log->log("Enviando Email",Zend_Log::INFO);
				$mail = new Functions_Tickets;
				$mail->sendMail($dadosticket['id_registro'],'reply');
			
			}
				
			if(isset($form['sendsms']) && $form['sendsms'] == 1){
					
			}
				
			if(isset($form['acompanhar']) && $form['acompanhar'] == 1){
				if(Crm_Model_TicketsAcompanhantes::check($id, $userInfo->id_registro)){
					$dataac = new Crm_Model_TicketsAcompanhantes();
					$dataac->insert(array('id_ticket'=>$dadosticket['id_registro'],'id_user'=>$userInfo->id_registro));
				}
					
			}
				
			if(isset($form['alertagerente']) && $form['alertagerente'] == 1){
			
				if(Crm_Model_TicketsAcompanhantes::check($dadosticket['id_registro'], Crm_Model_TicketsDeptos::getGerenteDepto($dadosticket['departamento']))){
					$dataac = new Crm_Model_TicketsAcompanhantes();
					$dataac->insert(array('id_ticket'=>$dadosticket['id_registro'],'id_user'=> Crm_Model_TicketsDeptos::getGerenteDepto($dadosticket['departamento'])));
				}
				
				
				try{
					$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
					$dbm = new System_Model_Messages();
					$savedata['user_from'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
					$savedata['user_to'] =  Crm_Model_TicketsDeptos::getGerenteDepto($dadosticket['departamento']);
					$savedata['statusmessage'] = '1';
					$savedata['assuntomessage'] = "Aviso de Ticket";
					$savedata['contentmessage'] = "<strong>Caro,</strong><br>Foi Solicitado pelo usuário {$usuario} o acompanhamento do ticket {$dadosticket['protocolo']} acesse o link abaixo para abrir o ticket <br> <a href=\"/crm/tickets/abrir/id/{$dadosticket['id_registro']}\">Abrir Ticket {$dadosticket['protocolo']}</a> ";
					$savedata['datemessage'] = date('Y-m-d H:i:s');
					$savedata['messageprioridade'] = "1";
					$savedata['flagmessage'] = "1";
					$dbm->insert($savedata);
					$this->log->log("Mensagem a Gerencia {$dadosticket['protocolo']}, usuário: {$userInfo->nomecompleto}",Zend_Log::INFO);
				}catch (Exception $e){
					$this->log->log("Erro na chamada de Gerencia{$dadosticket['protocolo']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
				}
				
			
					
			}
			
			if(isset($form['closeafter']) && $form['closeafter'] == 1){
				$db->update(array("statusticket"=>'3',
						'dateclosed'=>date('Y-m-d H:i:s')), "id_registro = '{$dadosticket['id_registro']}'");
			}
				
				
			$this->_redirect("/crm/tickets/abrir/id/{$dadosticket['id_registro']}/tkaction/newreply");
			
			
			
			
			
			
				
			
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
	
		$this->_redirect("/crm/tickets/abrir/id/{$data['idreg']}/tkaction/upload");
	
	
	}
	
	public function saveNoteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		if ($this->_request->isPost()) {
			$db = new Crm_Model_TicketsNotes();
			$form = $this->_request->getPost();
			if(!isset($form['privatenote'])){
				$form['privatenote'] = '0';
			}
			$savedata['id_ticket'] = $form['id_ticket'];
			$savedata['id_user'] = $userInfo->id_registro;
			$savedata['privatenote'] = $form['privatenote'];
			$savedata['assuntonote'] = $form['assuntoticket'];
			$savedata['textonote'] = $form['dadosticket'];
			$savedata['datanote'] = date('Y-m-d H:i:s');
			$db->insert($savedata);
			
			$this->_redirect("/crm/tickets/abrir/id/{$form['id_ticket']}/tkaction/newnote");
		}
		
		
	}
	
	public function getreplydataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Crm_Model_TicketsReplys();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		
		echo json_encode($data);
		
	}
	
	public function editreplyAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		if ($this->_request->isPost()) {
			$form = $this->_request->getPost();
			unset($form['submit']);
			$data['assuntoreply'] = $form['assuntoticket'];
			$data['textreply'] = $form['dadosticket'];
					 
			
			$db = new Crm_Model_TicketsReplys();
			$db->update($data, "id_registro = '{$form['id_reply']}'");
			
			$dados = $db->fetchRow("id_registro = '{$form['id_reply']}'");
			
			$this->_redirect("/crm/tickets/abrir/id/{$dados->id_ticket}/tkaction/editreply");
			
			
		}
		
	}
	
	
	
	public function removeReplyAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
				
			$db = new Crm_Model_TicketsReplys();
			$dados = $db->fetchRow("id_registro = '$id'");
			$db->delete("id_registro = '$id'");
				
			$this->_redirect("/crm/tickets/abrir/id/{$dados->id_ticket}/tkaction/removereply");
			
	}
	
	
	public function getnotedataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Crm_Model_TicketsNotes();
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
	
				
			$db = new Crm_Model_TicketsNotes();
			$db->update($data, "id_registro = '{$form['id_note']}'");
				
			$dados = $db->fetchRow("id_registro = '{$form['id_note']}'");
				
			$this->_redirect("/crm/tickets/abrir/id/{$dados->id_ticket}/tkaction/editnote");
				
				
		}
	
	}
	
	public function removeNoteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
	
		$db = new Crm_Model_TicketsNotes();
		$dados = $db->fetchRow("id_registro = '$id'");
		$db->delete("id_registro = '$id'");
	
		$this->_redirect("/crm/tickets/abrir/id/{$dados->id_ticket}/tkaction/removenote");
			
	}
	
	
	public function editTicketAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		if ($this->_request->isPost()) {
			$form = $this->_request->getPost();
			unset($form['submit']);
			$db = new Crm_Model_TicketsBasicos();
			
			$data['assuntoticket'] = $form['assuntoticket'];
			$data['statusticket'] = $form['statusticket'];
			if($form['statusticket'] == 3){
				$data['dateclosed'] = date('Y-m-d H:i:s');
			}
			$data['id_empresa'] = $form['empresa'];
			$data['departamento'] = $form['departamento'];
			$data['tipoticket'] = $form['tipoticket'];
			$data['prioridadeticket'] = $form['prioridadeticket'];
			$data['tags'] = $form['tags'];
			$data['atribuidoa'] = $form['atribuidoa'];
			
			$db->update($data, "id_registro = '{$form['id_ticket']}'");			
			
			$this->_redirect("/crm/tickets/abrir/id/{$form['id_ticket']}/tkaction/editoptions");
			
		}
		
		
	}
	
	public function aguparTicketAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$idnovoticket = $this->_getParam('idnovo');
		try{
		$db = new Crm_Model_TicketsBasicos();
		$dadosantigo = $db->fetchRow("id_registro = '$id'");
		
		$db2 = new Crm_Model_TicketsReplys();
		$datanew['id_ticket'] = $idnovoticket;
		$datanew['staffreply'] = $dadosantigo->staffopen;
		$datanew['nomereply'] = $dadosantigo->nomesolicitante;
		$datanew['emailreply'] = $dadosantigo->emailsolicitante;
		$datanew['assuntoreply'] = $dadosantigo->assuntoticket;
		$datanew['textreply'] = $dadosantigo->dadosticket;
		$datanew['replydate'] = $dadosantigo->dateopen;
		
		$db2->insert($datanew);
		
		$db2->update(array('id_ticket'=>$idnovoticket), "id_ticket = '$id'");
		
		$db3 = new Crm_Model_TicketsNotes();
		$db3->update(array('id_ticket'=>$idnovoticket), "id_ticket = '$id'");
		
		$db4 = new System_Model_Files();
		$db4->update(array('idreg'=>$idnovoticket),"tipofile = 'tickets' and idreg = '$id'");
		
		$db5 = new Crm_Model_TicketsAcompanhantes();
		$db5->update(array('id_ticket'=>$idnovoticket), "id_ticket = '$id'");
		
		$db6 = new Crm_Model_TicketsFlags();
		$db6->update(array('id_ticket'=>$idnovoticket), "id_ticket = '$id'");
			
		$db->delete("id_registro = '$id'");
		$this->_redirect("/crm/tickets/abrir/id/$idnovoticket/tkaction/mergetickets");
		}catch (Exception $e){
			echo $e->getMessage();
			echo $e->getLine();
			echo $e->getTrace();
			echo $e->getTraceAsString();
			
		}
	
		
		
	}
}