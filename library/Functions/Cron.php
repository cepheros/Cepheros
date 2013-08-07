<?php
class Functions_Cron{
	
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	}

/**
 * Funcao para se gerar os protocolos
 */
	public function emailTicketCron(){
		$db = new System_Model_Tickets_Parser();
		$parser = $db->fetchAll()->toArray();

		foreach($parser as $parse){
			$dbupdate['lastrun'] = date('Y-m-d H:i:s');
			$db->update($dbupdate, "id_registro = '{$parse['id_registro']}'");
			$ConectionParams['host'] = $parse['host'];
			$ConectionParams['user'] = $parse['user'];
			$ConectionParams['password'] = $parse['password'];
			$ConectionParams['port'] = $parse['port'];
		
			if($parse['ssl'] == 1){
				$ConectionParams['ssl'] = $parse['ssl'];
			}
		
			try{
				if($parse['parsertype'] == 'IMAP'){
					$email = new Zend_Mail_Storage_Imap($ConectionParams);
				}else{
					$email = new Zend_Mail_Storage_Pop3($ConectionParams);
		
				};
			}catch (Exception $e){
				echo $e->getMessage();
				print_r($ConectionParams);
					
			}
		
			foreach($email as $messageNum => $message){
				$messageSubject = $message->subject;
				if(preg_match("/[a-zA-Z]{3}-\d{3}-\d{5}/", $messageSubject,$achado)){
					$dbtickets = new Crm_Model_TicketsBasicos();
					$getdata = $dbtickets->fetchRow("protocolo = '{$achado[0]}'");
					$id_ticket_sys = $getdata->id_registro;
					$dtreply['id_ticket'] = $id_ticket_sys;
					$dbreply = new Crm_Model_TicketsReplys();
						
		
					if($message->isMultipart()){
				
						$dtreply['assuntoreply'] =  $message->subject;
						$dtreply['nomereply'] = strip_tags(str_replace('"','',$message->from));
						$dtreply['emailreply'] =  filter_var($message->from, FILTER_VALIDATE_EMAIL);
						$dtreply['textreply'] = '';
						$dtreply['staffreply'] = '1';
						$dtreply['replydate'] = date('Y-m-d H:i:s');
						foreach (new RecursiveIteratorIterator($message) as $part) {
							$content = explode(';',$part->contentType);
							if($content[0] == 'text/plain' || $content[0] == "text/html"){
								$dtreply['textreply'] .= $part;
							}else{
									
								$this->_saveFile($id_ticket_sys, base64_decode($part), $part->contentDisposition);
									
							}
		
						}
						$dbreply->insert($dtreply);
						if($parse['deleteemail'] == 1){
							$email->removeMessage($messageNum);
						}
					}else{
						
						$dtreply['assuntoreply'] =  utf8_encode($message->subject);
						$dtreply['nomereply'] = strip_tags($message->from);
						$dtreply['textreply'] = $message->getContent();
						$dtreply['emailreply'] =  filter_var($message->from, FILTER_VALIDATE_EMAIL);
						$dtreply['staffreply'] = '0';
						$dtreply['replydate'] = date('Y-m-d H:i:s');
						$dbreply->insert($dtreply);
						if($parse['deleteemail'] == 1){
							$email->removeMessage($messageNum);
						}
		
					}
		
		
					if($parse['sendemail'] == 1){
						$this->_sendEmail($id_ticket_sys,'reply',$parse['id_registro']);
					}
		
		
				}else{
					$id_ticket_sys = 0;
					$dbtickets = new Crm_Model_TicketsBasicos();
		
					$protocolo = Functions_Tickets::gerenateProtocol();
					$form['atribuidoa'] = '0';
		
		
					if($message->isMultipart()){
						
							
						$dados['protocolo'] = $protocolo;
						$dados['id_empresa'] = System_Model_Empresas::getEmpresaPadrao();
						$dados['solicitante'] = '0';
						$dados['nomesolicitante'] = strip_tags(str_replace('"','',$message->from));
						$dados['emailsolicitante'] =  filter_var($message->from, FILTER_VALIDATE_EMAIL);
						$dados['celularsolicitante'] = "";
						$dados['departamento'] = $parse['depto_id'];
						$dados['atribuidoa'] = "0";
						$dados['tipoticket'] =  $parse['tipo_ticket'];
						$dados['prioridadeticket'] =  $parse['prioridadeticket'];
						$dados['tags'] = "";
						$dados['assuntoticket'] = utf8_encode($message->subject);
						$dados['dadosticket'] = "";
						$dados['dateopen'] = date("Y-m-d H:i:s");
						$dados['datefirstreply'] =  null;
						$dados['datelastreply'] = null;
						$dados['dateclosed'] = null;
						$dados['datereopen'] = null;
						$dados['statusticket'] =  $parse['statusticket'];
						$dados['staffopen'] =  '1';
						$dados['stafffirstreply'] = 0;
						$dados['stafflastreply'] = 1;
						$dados['flagticket'] = 0;
						$dados['datedue'] = Functions_Datas::inverteData(Functions_Datas::SomaData(date('d/m/Y'), Crm_Model_TicketsPrioridades::getDueTipo($parse['prioridadeticket'])));
						$tickets = new Crm_Model_TicketsBasicos();
						$id_this_ticket = $tickets->insert($dados);
						$this->log->log("Ticket Criado {$protocolo}, usuário: {SISTEMA} ",Zend_Log::INFO);
							
						foreach (new RecursiveIteratorIterator($message) as $part) {
		
		
		
							$dtreply['dadosticket'] = '';
							$content = explode(';',$part->contentType);
							if($content[0] == 'text/plain' || $content[0] == "text/html"){
								$dtreply['dadosticket'] .= utf8_encode(strip_tags($part));
							}else{
		
								$this->_saveFile($id_this_ticket, base64_decode($part), $part->contentDisposition);
		
							}
		
						}
						$tickets->update($dtreply,"id_registro = '$id_this_ticket'");
						if($parse['deleteemail'] == 1){
							$email->removeMessage($messageNum);
						}
		
					}else{
		
		
						
						$dados['protocolo'] = $protocolo;
						$dados['id_empresa'] = System_Model_Empresas::getEmpresaPadrao();
						$dados['solicitante'] = '0';
						$dados['nomesolicitante'] = strip_tags(str_replace('"','',$message->from));
						$dados['emailsolicitante'] =  filter_var($message->from, FILTER_VALIDATE_EMAIL);
						$dados['celularsolicitante'] = "";
						$dados['departamento'] = $parse['depto_id'];
						$dados['atribuidoa'] = "0";
						$dados['tipoticket'] =  $parse['tipo_ticket'];
						$dados['prioridadeticket'] =  $parse['prioridadeticket'];
						$dados['tags'] = "";
						$dados['assuntoticket'] = utf8_encode($message->subject);
						$dados['dadosticket'] = strip_tags($message->getContent());
						$dados['dateopen'] = date("Y-m-d H:i:s");
						$dados['datefirstreply'] =  null;
						$dados['datelastreply'] = null;
						$dados['dateclosed'] = null;
						$dados['datereopen'] = null;
						$dados['statusticket'] =  $parse['statusticket'];
						$dados['staffopen'] =  '1';
						$dados['stafffirstreply'] = 0;
						$dados['stafflastreply'] = 0;
						$dados['flagticket'] = 0;
						$dados['datedue'] = Functions_Datas::inverteData(Functions_Datas::SomaData(date('d/m/Y'), Crm_Model_TicketsPrioridades::getDueTipo($parse['prioridadeticket'])));
						$tickets = new Crm_Model_TicketsBasicos();
						$id_this_ticket = $tickets->insert($dados);
						if($parse['deleteemail'] == 1){
							$email->removeMessage($messageNum);
						}
							
					}
		
					if($parse['sendemail'] == 1){
						$this->_sendEmail($id_this_ticket,'novo',$parse['id_registro']);
					}
						
				}
			}
		}
	}
	
	
	protected function _saveFile($id,$file,$filename){
		$dbfile = new System_Model_Files();
		$data2['accesshash'] = sha1(md5(microtime()));
		$data2['tipofile'] = 'tickets';
		$data2['idreg'] = $id;
		$data2['obsfile'] = "Arquivo enviado por ticket";
		$data2['nomeamigavel'] = "Arquivo Enviado junto a resposta";
		$data2['tags'] = "";
		$id_file = $dbfile->insert($data2);
		if(!is_dir($this->DocsPath .'/'.$data2['tipofile'])){
			mkdir($this->DocsPath .'/'.$data2['tipofile']);
		}
		if(!is_dir($this->DocsPath ."/{$data2['tipofile']}/{$data2['idreg']}")){
			mkdir($this->DocsPath ."/{$data2['tipofile']}/{$data2['idreg']}");
		}
		if(!is_dir($this->DocsPath ."/{$data2['tipofile']}/{$data2['idreg']}/{$id_file}")){
			mkdir($this->DocsPath ."/{$data2['tipofile']}/{$data2['idreg']}/{$id_file}");
		}
		$destinationFolder =  $this->DocsPath ."/{$data2['tipofile']}/{$data2['idreg']}/{$id_file}";
		$filename = $filename;
		$hash =     md5($file);
		$filesdata = explode(';',$filename);
		$fileNames = explode('=',$filesdata[1]);
		$data3['filename'] = $destinationFolder . '/' . $fileNames[1];
		$data3['filetype'] = $filesdata[0];
		$data3['nomeamigavel'] = "Arquivo Enviado junto a resposta: {$fileNames[1]}";
		$data3['filehash'] = $hash;
		$data3['dateadded'] = date('Y-m-d H:i:s');
		$data3['useradded'] = '0';
		$dbfile->update($data3, "id_registro = '$id_file'");
		$fh = fopen($data3['filename'], 'w');
		fwrite($fh, $file);
		fclose($fh);
	
		return true;
	
	}
	
	
	public function osMailCron(){
		$ConectionParams['host'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServer");
		$ConectionParams['user'] =  System_Model_SysConfigs::getConfig("SysOSDefaultMailUser");
		$ConectionParams['password'] =  System_Model_SysConfigs::getConfig("SysOSDefaultMailPassword");
		$ConectionParams['port'] =  System_Model_SysConfigs::getConfig("SysOSDefaultMailServerReceivePort");
		
		if( System_Model_SysConfigs::getConfig("SysOSDefaultMailServerSSL") == 1){
			$ConectionParams['ssl'] ='tls';
		}
		
		try{
			if(System_Model_SysConfigs::getConfig("SysOSDefaultMailServerType") == 'IMAP'){
				$email = new Zend_Mail_Storage_Imap($ConectionParams);
			}else{
				$email = new Zend_Mail_Storage_Pop3($ConectionParams);
		
			};
		}catch (Exception $e){
			echo $e->getMessage();
			print_r($ConectionParams);
				
		}
		
		foreach($email as $messageNum => $message){
			$messageSubject = $message->subject;
			if(preg_match("/[a-zA-Z]{3}-\d{3}-\d{5}/", $messageSubject,$achado)){
				$dbtickets = new Crm_Model_Os_Basicos();
				$getdata = $dbtickets->fetchRow("cod_os = '{$achado[0]}'");
				$id_ticket_sys = $getdata->id_registro;
				$dtreply['id_os'] = $id_ticket_sys;
				$dbreply = new Crm_Model_Os_Anotacoes();
		
		
				if($message->isMultipart()){
					$dtreply['usuario_note'] =  '0';
					$dtreply['nome_usuario'] = strip_tags(str_replace('"','',$message->from));
					$dtreply['data_note'] = date('Y-m-d H:i:s');
					foreach (new RecursiveIteratorIterator($message) as $part) {
						$content = explode(';',$part->contentType);
						if($content[0] == 'text/plain' || $content[0] == "text/html"){
							$dtreply['anotacao'] .= strip_tags($part);
						}else{
								
						//	$this->_saveFile($id_ticket_sys, base64_decode($part), $part->contentDisposition);
								
						}
		
					}
					$dbreply->insert($dtreply);
					$email->removeMessage($messageNum);
					
				}
			}
		}
	}
				
	
	
	protected function _sendEmail($id_ticket, $tipo = 'novo', $parser){
		$db = new Crm_Model_TicketsBasicos();
		$data = $db->fetchRow("id_registro = '$id_ticket'")->toArray();
	
		$dbdepto = new Crm_Model_TicketsDeptos();
		$dadosDepto = $dbdepto->fetchRow("id_registro = '{$data['departamento']}'")->toArray();
	
		$dataparser = new System_Model_Tickets_Parser();
		$datap = $dataparser->fetchRow("id_registro = '$parser'")->toArray();
	
	
		switch($tipo){
			case 'novo':
				$mensagem = System_Model_MensagensSistema::getMensagem($datap['messagefornew']);
				break;
			case 'reply':
				$mensagem = System_Model_MensagensSistema::getMensagem($datap['messageforreply']);
				break;
					
		}
	
		$mensagem = str_replace(
				array('{NOMEREMETENTE}',
						'{NOMEDEPTO}',
						'{PROTOCOLO}'),
				array($data['nomesolicitante'],
						$dadosDepto['nomedepto'],
						$data['protocolo']),
				$mensagem->htmlmensagem);
	
		$configs = Zend_Registry::get('configs');
		$transport = new Zend_Mail_Transport_Smtp($dadosDepto['deptomailserver'],array(
				'auth'=>'login',
				'username'=>$dadosDepto['deptoemail'],
				'password'=>$dadosDepto['deptomailpassword'],
				'port'=>$dadosDepto['deptomailserverport']
		));
		$mail = new Zend_Mail();
		$mail->setBodyHtml(utf8_decode($mensagem));
		$mail->setBodyText(strip_tags(utf8_decode($mensagem)));
		$mail->setFrom($dadosDepto['deptoemail'],$dadosDepto['nomedepto'])
		->addTo($data['emailsolicitante'],$data['nomesolicitante'])
		->setSubject(utf8_decode("{$dadosDepto['nomedepto']} ({$data['protocolo']}) {$data['assuntoticket']}"));
		
		$mail->send($transport);
	
	}
	
	public function financeiroCron(){
		if(System_Model_SysConfigs::getConfig("FinanEmailResumo") == 1){
			
			$to = explode(',',System_Model_SysConfigs::getConfig("FinanEmailResumoUsers"));
			$message =  System_Model_MensagensSistema::getMensagem(System_Model_SysConfigs::getConfig("FinanEmailResumoMessage"));
			
			
			
			foreach($to as $user){
				$dadosuser = System_Model_Users::whoIsUser($user);
				
				$messagesend = str_replace(array('{TABLE_COMPROMISSOS_PAG}','{TABLE_COMPROMISSOS_REC}',
						'{NOMEUSUARIO}',
						'{EMAILUSUARIO}',
						'{DATA_HOJE}',
						'{INFORMACOES}'
				), $replace, $message->htmlmensagem);
				
				$configs = Zend_Registry::get('configs');
				$transport = new Zend_Mail_Transport_Smtp($configs->Mail->Server,array(
						'auth'=>$configs->Mail->Auth,
						'username'=>$configs->Mail->User,
						'password'=>$configs->Mail->Pass,
						'port'=>$configs->Mail->Port
				));
				$mail = new Zend_Mail();
				$mail->setBodyHtml(utf8_decode($mensagem));
				$mail->setBodyText(strip_tags(utf8_decode($mensagem)));
				$mail->setFrom($configs->Mail->User,$configs->Leader->SysName)
				->addTo($dadosuser->email,$dadosuser->nomecompleto)
				->setSubject(utf8_decode($message->textmensagem));
				
				$mail->send($transport);
				
				
				
				
			}
			
			
			
			
			
			
		}
		
		
		
	}
	
	public function limpaSistemaCron(){
		
	}
	
}