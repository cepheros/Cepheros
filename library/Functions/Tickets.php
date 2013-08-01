<?php
class Functions_Tickets{
	
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	}
	
	/**
	 * gererateProtocol
	 * Função utilizada para gerar protocolo unico do ticket;
	 * @return string
	 */
	public static function gerenateProtocol(){
		$tickets = new Functions_Tickets;
	
		$letras = $tickets->gerCodLetras(3);
		$num1 = $tickets->gerCodNumeros(3);
		$num2 = $tickets->gerCodNumeros(5);
		$protocol = "$letras-$num1-$num2";
		
		if(Crm_Model_TicketsBasicos::checkProtocol($protocol)){		
			return $protocol;
		}else{
			Functions_Tickets::gerenateProtocol();
			
		}
		
	}
	
	
	public static function gerenateOsProtocol(){
		$tickets = new Functions_Tickets;
	
		$letras = $tickets->gerCodLetras(3);
		$num1 = $tickets->gerCodNumeros(3);
		$num2 = $tickets->gerCodNumeros(5);
		$protocol = "$letras-$num1-$num2";
	
		if(Crm_Model_Os_Basicos::checkProtocol($protocol)){
			return $protocol;
		}else{
			Functions_Tickets::gerenateOsProtocol();
				
		}
	
	}
	
	public static function gerenateProspect(){
		$tickets = new Functions_Tickets;
	
		$letras = $tickets->gerCodLetras(4);
		$num1 = $tickets->gerCodNumeros(3);
		$num2 = $tickets->gerCodNumeros(4);
		$protocol = "$letras-$num1-$num2";
	
		if(Crm_Model_TicketsBasicos::checkProtocol($protocol)){
			return $protocol;
		}else{
			Functions_Tickets::gerenateProtocol();
				
		}
	
	}
	
	private function gerCodLetras($qtd = 3){
		$newpassword = '';
		$CaracteresAceitos = 'ABCDEFGHIJKLMNOPQRSTUVXYWZ';
		$max = strlen($CaracteresAceitos)-1;
		$password = null;
		for($i=0; $i < $qtd; $i++) {
			$newpassword .= $CaracteresAceitos{mt_rand(0, $max)};
		}
		return $newpassword;
	
	}
	
	private function gerCodNumeros($qtd = 3){
		$newpassword = '';
		$CaracteresAceitos = '1234567890';
		$max = strlen($CaracteresAceitos)-1;
		$password = null;
		for($i=0; $i < $qtd; $i++) {
			$newpassword .= $CaracteresAceitos{mt_rand(0, $max)};
		}
		return $newpassword;
	
	}
	
	
	public function  sendMail($ticket,$tipo = 'novo'){
		$db = new Crm_Model_TicketsBasicos();
		$dadosticket = $db->fetchRow("id_registro = '$ticket'");
	
		$id_messages = Crm_Model_TicketsDeptoMessages::getMessages($dadosticket->departamento);
	
		if($tipo == 'reply'){
			$db2 = new Crm_Model_TicketsReplys();
			$query = $db2->select()->where("id_ticket = '$ticket'")->order("id_registro DESC")->limit(1);
			$dadosreply = $db2->fetchRow($query);
			$mensagem = System_Model_MensagensSistema::getMensagem($id_messages->messagereply);
			$assunto = $dadosreply->assuntoreply;
		}else{
			
			$mensagem = System_Model_MensagensSistema::getMensagem($id_messages->messagenew);
			$dadosreply = null;
			$assunto = $dadosticket->assuntoticket;
		}
		
		$sendmessage = $this->montaMensagem($mensagem->htmlmensagem, $dadosticket,$dadosreply);
		$empresa = System_Model_Empresas::getNomeFantasiaEmpresa($dadosticket->id_empresa);
		$departamento = Crm_Model_TicketsDeptos::getNomeDepto($dadosticket->departamento);

		$datadepto = Crm_Model_TicketsDeptos::getDeptoConfigs($dadosticket->departamento);
		
		$assuntomessage = " {{$dadosticket->protocolo}} ($empresa) $departamento  - {$assunto} ";
		
		$dados['messagehtml'] = $sendmessage;
		$dados['messagetxt'] = strip_tags($sendmessage);
		$dados['formemail'] = $datadepto->deptoemail;
		$dados['fromname'] = "($empresa) $departamento";
		$dados['messageto'] = $dadosticket->emailsolicitante;
		$dados['messagetoname'] = $dadosticket->nomesolicitante;
		$dados['messagesubject'] = $assuntomessage;
		$this->sendMessage($dados);
			
	}
	
	
	public function montaMensagem($mensagem,$dadosnovo,$dadosreply = null){
		
		
		
		if($dadosreply){
			$dadosachar = array(
					'{PROTOCOLO}',
					'{DEPARTAMENTO}',
					'{NOMESOLICITANTE}',
					'{EMAILSOLICITANTE}',
					'{TIPOTICKET}',
					'{ATRIBUIDOA}',
					'{PRIORIDADETICKET}',
					'{TAGS}',
					'{ASSUNTOTICKET}',
					'{DADOSTICKET}',
					'{DATAABERTURA}',
					'{DATAPRIMEIRARESPOSTA}',
					'{DATAULTIMARESPOSTA}',
					'{DATAFECHAMENTO}',
					'{DATAREABERTURA}',
					'{STATUSTICKET}',
					'{USUARIOABERTURA}',
					'{USUARIOPRIMEIRARESPOSTA}',
					'{USUARIOULTIMARESPOSTA}',
					'{FLAG}',
					'{VENCIMENTOTICKET}',
					'{RESP-USUARIO}',
					'{RESP-NOME}',
					'{RESP-EMAIL}',
					'{RESP-ASSUNTO}',
					'{RESP-RESPOSTA}',
					'{RESP-DATA}',
					'{ASSINATURA-USUARIO}'
			);
			$dadossubstituir = array(
					$dadosnovo->protocolo,
					Crm_Model_TicketsDeptos::getNomeDepto($dadosnovo->departamento),
					$dadosnovo->nomesolicitante,
					$dadosnovo->emailsolicitante,
					Crm_Model_TicketsTipos::getNomeTipo($dadosnovo->tipoticket),
					System_Model_Users::whoIs($dadosnovo->atribuidoa),
					Crm_Model_TicketsPrioridades::getNomeTipo($dadosnovo->prioridadeticket),
					$dadosnovo->tags,
					$dadosnovo->assuntoticket,
					nl2br($dadosnovo->dadosticket),
					Functions_Datas::MyDateTime($dadosnovo->dateopen,true),
					Functions_Datas::MyDateTime($dadosnovo->datefirstreply,true),
					Functions_Datas::MyDateTime($dadosnovo->datelastreply,true),
					Functions_Datas::MyDateTime($dadosnovo->dateclosed,true),
					Functions_Datas::MyDateTime($dadosnovo->datereopen,true),
					Crm_Model_TicketsStatus::getNomeTipo($dadosnovo->statusticket),
					System_Model_Users::whoIs($dadosnovo->staffopen),
					System_Model_Users::whoIs($dadosnovo->stafffirstreply),
					System_Model_Users::whoIs($dadosnovo->stafflastreply),
					$dadosnovo->flagticket,
					Functions_Datas::MyDateTime($dadosnovo->datedue,true),
					System_Model_Users::whoIs($dadosreply->staffreply),
					$dadosreply->nomereply,
					$dadosreply->emailreply,
					$dadosreply->assuntoreply,
					nl2br($dadosreply->textreply),
					Functions_Datas::MyDateTime($dadosreply->replydate,true),
					nl2br(Zend_Auth::getInstance()->getStorage()->read()->signature)
			);
			
			$newmessage = str_replace($dadosachar,$dadossubstituir,$mensagem);
			
		}else{
			$dadosachar = array(
					'{PROTOCOLO}',
					'{DEPARTAMENTO}',
					'{NOMESOLICITANTE}',
					'{EMAILSOLICITANTE}',
					'{TIPOTICKET}',
					'{ATRIBUIDOA}',
					'{PRIORIDADETICKET}',
					'{TAGS}',
					'{ASSUNTOTICKET}',
					'{DADOSTICKET}',
					'{DATAABERTURA}',
					'{DATAPRIMEIRARESPOSTA}',
					'{DATAULTIMARESPOSTA}',
					'{DATAFECHAMENTO}',
					'{DATAREABERTURA}',
					'{STATUSTICKET}',
					'{USUARIOABERTURA}',
					'{USUARIOPRIMEIRARESPOSTA}',
					'{USUARIOULTIMARESPOSTA}',
					'{FLAG}',
					'{VENCIMENTOTICKET}',
					'{ASSINATURA-USUARIO}'
			);
			$dadossubstituir = array(
					$dadosnovo->protocolo,
					Crm_Model_TicketsDeptos::getNomeDepto($dadosnovo->departamento),
					$dadosnovo->nomesolicitante,
					$dadosnovo->emailsolicitante,
					Crm_Model_TicketsTipos::getNomeTipo($dadosnovo->tipoticket),
					System_Model_Users::whoIs($dadosnovo->atribuidoa),
					Crm_Model_TicketsPrioridades::getNomeTipo($dadosnovo->prioridadeticket),
					$dadosnovo->tags,
					$dadosnovo->assuntoticket,
					nl2br($dadosnovo->dadosticket),
					Functions_Datas::MyDateTime($dadosnovo->dateopen,true),
					Functions_Datas::MyDateTime($dadosnovo->datefirstreply,true),
					Functions_Datas::MyDateTime($dadosnovo->datelastreply,true),
					Functions_Datas::MyDateTime($dadosnovo->dateclosed,true),
					Functions_Datas::MyDateTime($dadosnovo->datereopen,true),
					Crm_Model_TicketsStatus::getNomeTipo($dadosnovo->statusticket),
					System_Model_Users::whoIs($dadosnovo->staffopen),
					System_Model_Users::whoIs($dadosnovo->stafffirstreply),
					System_Model_Users::whoIs($dadosnovo->stafflastreply),
					$dadosnovo->flagticket,
					Functions_Datas::MyDateTime($dadosnovo->datedue,true),
					nl2br(Zend_Auth::getInstance()->getStorage()->read()->signature)
			);
			$newmessage = str_replace($dadosachar,$dadossubstituir,$mensagem);
		}
		
		return utf8_decode($newmessage);
		
	}
	
	public function sendMessage($dados){
	
		try{
			//	$file = file_get_contents(PUBLIC_PATH.'/'.$dados['files'][0]['filename']);
			$configs = Zend_Registry::get('configs');	
			$transport = new Zend_Mail_Transport_Smtp($configs->Mail->Server,array(
					'auth'=>$configs->Mail->Auth,
					'username'=>$configs->Mail->User,
					'password'=>$configs->Mail->Pass
					));
			$mail = new Zend_Mail();
			$mail->setBodyHtml($dados['messagehtml']);
			$mail->setBodyText($dados['messagetxt']);
			$mail->setFrom($dados['formemail'],$dados['fromname'])
			->addTo($dados['messageto'],$dados['messagetoname'])
			->setSubject(utf8_decode($dados['messagesubject']));
				
			//  $mail->createAttachment($file,'','','','',"file.pdf");
			 
			$mail->send($transport);
			$this->log->log("Envio de email com sucesso para: {$dados['messagetoname']} ",Zend_Log::INFO);
			return true;
		}catch(Zend_Mail_Exception $e){
			return $e->getMessage();
			$this->log->log("Envio de email com erros! ERRO: {$e->getMessage()} ",Zend_Log::ERR);
				
		}
	
	}
	
}