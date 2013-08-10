<?php
class Functions_SMS{
	
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
	
	
	public function sendSMS($to,$message,$from = false){
		
		if(!$from){
	    	$from = System_Model_Empresas::getNomeFantasiaEmpresa(System_Model_Empresas::getEmpresaPadrao());
		}
		try{
		$soap = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/sms?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/sms"));
		$messagesms['msg'] = $message;
		$messagesms['from'] = $from;
		$messagesms['mobile'] = str_replace(array('(',')','-'),array('','',''), $to['celular']);
		$messagesms['schedule'] = NULL;
		$sms = $soap->sendSMS($this->configs->Leader->Cliente->codigo , $this->configs->Leader->SoapKey, $messagesms);
		$this->log->log("Envio de  SMS: $sms",Zend_Log::INFO);
		$this->log->log("Envio de  SMS: {$this->configs->Leader->SoapServer}",Zend_Log::INFO);
		$this->log->log("Mensagem: {$messagesms['msg']}",Zend_Log::INFO);
		$this->log->log("De: {$messagesms['from']}",Zend_Log::INFO);
		$this->log->log("Para: {$messagesms['mobile']}",Zend_Log::INFO);
		}catch(Exception $e){
			$this->log->log("Envio de  SMS: Mensagem:  {$e->getMessage()}",Zend_Log::ERR);
		}
		
	}
			
		
		
	public static function SMSNewOs($id){
		$mail = new Functions_SMS;
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
		
		$sendMessage = str_replace($replaces, $toReplate, $message->smsmensagem);
		$sendto = Cadastros_Model_Contatos::getCadastro($dadosOS->id_contato);
		
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		$from = System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa);
		$mail->sendSMS($sendto,$sendMessage,$from);
		
						
			
		
	}
	
	public static function SMSUpdateOs($id){
		$mail = new Functions_SMS;
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
	
	
		$sendMessage = str_replace($replaces, $toReplate, $message->smsmensagem);
		$sendto = Cadastros_Model_Contatos::getCadastroCel($dadosOS->id_contato);
	
		$subject = str_replace($replaces, $toReplate, $message->textmensagem);
		$from = System_Model_Empresas::getNomeFantasiaEmpresa($dadosOS->id_empresa);
		$mail->log->log("Preparando SMS",Zend_Log::INFO);
		if($sendto->celular <> ''){
			$mail->sendSMS($sendto,$sendMessage,$from);
		}else{
			return true;
		}
	
	
			
	
	}
	
	
}