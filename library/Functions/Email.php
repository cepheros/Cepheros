<?php
class Functions_Email{
	
	protected $mail;
	public $log;
	public $configs;
	public $cache;
	public $userInfo;
	public $baseURL;
	
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->userInfo = Zend_Auth::getInstance()->getStorage()->read();	

		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		$this->baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		
	}
	
	
	public function sendMail($to,$message,$subject,$from = false){
	//	print_r($to);
		if(!$from){
			$from['deptomailserver'] = $this->configs->Mail->Server;
			$from['deptoemail']= $this->configs->Mail->User;
			$from['deptomailpassword'] = $this->configs->Mail->Pass;
			$from['deptomailserverport'] = $this->configs->Mail->Port;		
			$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa(System_Model_Empresas::getEmpresaPadrao());
		}
		
		$transport = new Zend_Mail_Transport_Smtp($from['deptomailserver'],array(
				'auth'=>'login',
				'username'=>$from['deptoemail'],
				'password'=>$from['deptomailpassword'],
				'port'=>$from['deptomailserverport']
		));

		try{
			
		$mail = new Zend_Mail();
		$mail->setBodyHtml($message);
		$mail->setFrom($from['deptoemail'],$from['nomedepto'])
		->addTo($to['email'],$to['nomecontato'])
		->setSubject($subject);
		$mail->send($transport);
		
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
	}
			
		
		
	public static function mailNewOs($id){
		$mail = new Functions_Email;
		$osdata = new Crm_Model_Os_Basicos();
		$dadosOS = $osdata->fetchRow("id_registro = '$id'");
		$message = System_Model_MensagensSistema::getMensagem(System_Model_SysConfigs::getConfig("DefaultMailNewOS"));
		$replaces = array('{CODIGO_OS}',
						  '{CLIENTE_OS}',
						  '{CONTATO_OS}',
						  '{ABERTURA_OS}',
						  '{LINK_CONSULTA}',
						  '{USUARIO_ABERTURA}',
						  '{STATUS_OS}',
						  '{TIPO_OS}',
						  '{EMPRESA}'
						  		
						   );
		$toReplate = array($dadosOS->cod_os,
				           Cadastros_Model_Pessoas::getNomeEmpresa($dadosOS->id_cliente),
						   Cadastros_Model_Contatos::getNomeContato($dadosOS->id_contato),
						   Functions_Datas::MyDateTime($dadosOS->dataabertura,true),
						   $mail->baseURL .'/consulta/os/cod/'.$dadosOS->accesshash,
				           System_Model_Users::whoIs($dadosOS->user_open),
						   Crm_Model_Os_Status::getNomeTipo($dadosOS->status_os),
						   Crm_Model_Os_Tipos::getNomeTipo($dadosOS->tipo_os),
						   System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa)
						   );
		
		$sendMessage = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$sendto = Cadastros_Model_Contatos::getCadastro($dadosOS->id_contato);
		
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		
		$from['deptomailserver'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServer");
		$from['deptoemail']=  System_Model_SysConfigs::getConfig("SysOSDefaultMailUser");
		$from['deptomailpassword'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailPassword");
		$from['deptomailserverport'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServerSendPort");
		$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa);
		
		$mail->sendMail($sendto,$sendMessage,$subject,$from);
		
						
			
		
	}
	
	
	public static function mailUpdateOs($id){
		$mail = new Functions_Email;
		$osdata = new Crm_Model_Os_Basicos();
		$dadosOS = $osdata->fetchRow("id_registro = '$id'");
		$message = System_Model_MensagensSistema::getMensagem(System_Model_SysConfigs::getConfig("DefaultMailUpdateOS"));
		$replaces = array('{CODIGO_OS}',
				'{CLIENTE_OS}',
				'{CONTATO_OS}',
				'{ABERTURA_OS}',
				'{LINK_CONSULTA}',
				'{USUARIO_ABERTURA}',
				'{STATUS_OS}',
				'{TIPO_OS}',
				'{EMPRESA}',
				'{USUARIO_ATUALIZA}',
				'{DATA_ATUALIZACAO}'
				
	
		);
		$toReplate = array($dadosOS->cod_os,
				Cadastros_Model_Pessoas::getNomeEmpresa($dadosOS->id_cliente),
				Cadastros_Model_Contatos::getNomeContato($dadosOS->id_contato),
				Functions_Datas::MyDateTime($dadosOS->dataabertura,true),
				$mail->baseURL .'/consulta/os/cod/'.$dadosOS->accesshash,
				System_Model_Users::whoIs($dadosOS->user_open),
				Crm_Model_Os_Status::getNomeTipo($dadosOS->status_os),
				Crm_Model_Os_Tipos::getNomeTipo($dadosOS->tipo_os),
				System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa),
				System_Model_Users::whoIs($dadosOS->user_lastupdate),
				Functions_Datas::MyDateTime($dadosOS->datelastupdate,true),
		);
	
		$sendMessage = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$sendto = Cadastros_Model_Contatos::getCadastro($dadosOS->id_contato);
	
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		
		
		$from['deptomailserver'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServer");
		$from['deptoemail']=  System_Model_SysConfigs::getConfig("SysOSDefaultMailUser");
		$from['deptomailpassword'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailPassword");
		$from['deptomailserverport'] = System_Model_SysConfigs::getConfig("SysOSDefaultMailServerSendPort");
		$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa);
	
		$mail->sendMail($sendto,$sendMessage,$subject,$from);
	
	
			
	
	}
	
	
	public static function sendMailProspect($id,$new = 'novo',$idreply = '0'){
		$mail = new Functions_Email();
		$dbprosp = new Crm_Model_Prospects_Basico();
		$dataprosp = $dbprosp->fetchRow("id_registro = '$id'")->toArray();
		
		$replaces = array('{CODIGO_PROSPECT}',
				'{EMPRESA}',
				'{CONTATO}',
				'{EMAIL}',
				'{LOGOTIPO}',
				'{MENSAGEM}',
				'{ASSINATURA}'
		);
		
		
		
		$messages = Crm_Model_Marketing_Campanhas::getMesssages($dataprosp['campanhaproposta']);
		
		if($new == 'novo'){
			$message = $messages['new'];
			$toReplate = array($dataprosp['protocolo'],
					$dataprosp['nomeempresa'],
					$dataprosp['nomesolicitante'],
					$dataprosp['emailsolicitante'],
					$mail->baseURL."/consulta/prospect/id/".$dataprosp['hashprospect'],
					nl2br($dataprosp['descritivoproposta']),
					nl2br($mail->userInfo->signature)
			);
			
		}else{
			$message = $messages['reply'];
			$db2 = new Crm_Model_Prospects_Updates();
			$datar = $db2->fetchRow("id_registro = '$idreply'");
			
			$toReplate = array($dataprosp['protocolo'],
					$dataprosp['nomeempresa'],
					$dataprosp['nomesolicitante'],
					$dataprosp['emailsolicitante'],
					$mail->baseURL."/consulta/prospect-reply/id/".$datar['hashreply'],
					nl2br($datar['informacoesresposta']),
					nl2br($mail->userInfo->signature)
			);
		}
		
		$message = System_Model_MensagensSistema::getMensagem($message);
		
		
		$sendMessage = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$sendto = array('email'=>$dataprosp['emailsolicitante'],'nomecontato'=>$dataprosp['nomesolicitante'])	;
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		
		//$from['deptomailserver'] = System_Model_SysConfigs::getConfig("SysProspectDefaultMailServer");
		//$from['deptoemail']=  System_Model_SysConfigs::getConfig("SysProspectDefaultMailUser");
		//$from['deptomailpassword'] = System_Model_SysConfigs::getConfig("SysProspectDefaultMailPassword");
		//$from['deptomailserverport'] = System_Model_SysConfigs::getConfig("SysProspectDefaultMailServerSendPort");
		//$from['nomedepto'] = System_Model_Empresas::getNomeFantasiaEmpresa(System_Model_Empresas::getEmpresaPadrao());
		
		$mail->sendMail($sendto,$sendMessage,$subject,$from);
		
		
		
		
	}
	
	
	public static function SendNFeEmail($id,$idEmpresa){
		
		$Configs = System_Model_EmpresasNF::getDadosConfigNFe($idEmpresa);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '$id'");
		
		$dbEmi = new Erp_Model_Faturamento_NFe_Emitente();
		$DadosEmi = $dbEmi->fetchRow("id_nfe = '$id'");
		
		$dbDest = new Erp_Model_Faturamento_NFe_Destinatario();
		$DadosDest = $dbDest->fetchRow("id_nfe = '$id'");
		
		$dbTot = new Erp_Model_Faturamento_NFe_Totais();
		$dadosTot = $dbTot->fetchRow("id_nfe = '$id'");
		
		if($DadosNFe->status_processo <> '6'){
			$message = System_Model_MensagensSistema::getMensagem($Configs->emailsend);
		}else{
			$message = System_Model_MensagensSistema::getMensagem($Configs->emailcancel);
		}
		
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		$baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		
		$data['id_nfe'] = $DadosNFe->id_registro;
		$data['hashacesso'] = sha1(md5($DadosNFe->id_registro . microtime()));
		$dbAcesso = new Erp_Model_Faturamento_NFe_HashAcesso();
		$dbAcesso->insert($data);
		
		$linkXML = "$baseURL/arquivos/xml-nfe/id/{$data['hashacesso']}";
		$linkDANFE= "$baseURL/arquivos/danfe-nfe/id/{$data['hashacesso']}";
		
		
		
		$replaces = array('{NUMERONF}','{EMITENTE}','{DESTINATARIO}','{SERIENF}','{TOTALNFE}','{LINKXML}','{LINKDANFE}');
		
		
		
		$toReplate = array($DadosNFe->nNF,$DadosEmi->xNome,$DadosDest->xNome, $DadosNFe->serie,$dadosTot->vNF,$linkXML,$linkDANFE);
		
		$MessagOK = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		$sendto = array('email'=>$DadosDest->email,'nomecontato'=>$DadosDest->xNome);
		
		
		$from['deptomailserver'] = $Configs->emailhostname;
		$from['deptoemail']=  $Configs->emailusername;
		$from['deptomailpassword'] =$Configs->emailpassword;
		$from['deptomailserverport'] = $Configs->emailsendport;
		$from['nomedepto'] = $DadosEmi->xNome;

		$mail = new Functions_Email();
		$mail->sendMail($sendto,$MessagOK,$subject,$from);
		
		
		
		
		
		
	
	}
	
	
	public static function SendNFeEmailContabil($id,$idEmpresa){
		

		$Configs = System_Model_EmpresasNF::getDadosConfigNFe($idEmpresa);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '$id'");
		
		$dbEmi = new Erp_Model_Faturamento_NFe_Emitente();
		$DadosEmi = $dbEmi->fetchRow("id_nfe = '$id'");
		
		$dbDest = new Erp_Model_Faturamento_NFe_Destinatario();
		$DadosDest = $dbDest->fetchRow("id_nfe = '$id'");
		
		$dbTot = new Erp_Model_Faturamento_NFe_Totais();
		$dadosTot = $dbTot->fetchRow("id_nfe = '$id'");
		
		if($DadosNFe->status_processo <> '6'){
			$message = System_Model_MensagensSistema::getMensagem($Configs->emailsend);
		}else{
			$message = System_Model_MensagensSistema::getMensagem($Configs->emailcancel);
		}
		
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		$baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		
		$data['id_nfe'] = $DadosNFe->id_registro;
		$data['hashacesso'] = sha1(md5($DadosNFe->id_registro . microtime()));
		$dbAcesso = new Erp_Model_Faturamento_NFe_HashAcesso();
		$dbAcesso->insert($data);
		
		$linkXML = "$baseURL/arquivos/xml-nfe/id/{$data['hashacesso']}";
		$linkDANFE= "$baseURL/arquivos/danfe-nfe/id/{$data['hashacesso']}";
		
		
		
		$replaces = array('{NUMERONF}','{EMITENTE}','{DESTINATARIO}','{SERIENF}','{TOTALNFE}','{LINKXML}','{LINKDANFE}');
		
		
		
		$toReplate = array($DadosNFe->nNF,$DadosEmi->xNome,$DadosDest->xNome, $DadosNFe->serie,$dadosTot->vNF,$linkXML,$linkDANFE);
		
		$MessagOK = str_replace($replaces, $toReplate, $message->htmlmensagem);
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		$sendto = array('email'=>$Configs->contabilemail,'nomecontato'=>$Configs->contabilname);
		
		
		$from['deptomailserver'] = $Configs->emailhostname;
		$from['deptoemail']=  $Configs->emailusername;
		$from['deptomailpassword'] =$Configs->emailpassword;
		$from['deptomailserverport'] = $Configs->emailsendport;
		$from['nomedepto'] = $DadosEmi->xNome;
		
		$mail = new Functions_Email();
		$mail->sendMail($sendto,$MessagOK,$subject,$from);
		
		
	}
	
}