<?php
class Soap_Sms{
	
	/**
	 * Envia um SMS para um determinado numero usando a API 
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @param array $data Array de dados com as informações do sms
	 * @return string $response
	 */
	
	public $configs;
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
	
	}
	
	public function sendSMS($clientecod,$code,$data){
		$cliente = new Cadastros_Model_Outros();
		$dados = $cliente->fetchRow("id_pessoa = '$clientecod'");
		if($dados->chavesoap <> $code){
			return "Codigo de acesso a API incorreto para o cliente ($clientecod) e o codigo {$code}";
		}else{
			try{
			$dadosa['id_cliente'] = $clientecod;
			$dadosa['msg'] = $data['msg'];
			$dadosa['from'] = $data['from'];
			$dadosa['celular'] = "55".$data['mobile'];
			$dadosa['callback'] = '1';
			$dadosa['schedule'] = $data['schedule'];
			$dadosa['senddate'] = date('Y-m-d H:i:s');
						
			$sms = new System_Model_SMS();
			$id = $sms->insert($dadosa);
			
			$soap = new Zend_Soap_Client($this->configs->SMSGateway->Server,array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_1));
			//$soap->setLocation($this->configs->SMSGateway->Server);
			$other = $soap->SendSms(array('account'=>$this->configs->SMSGateway->User,
					'code'=>$this->configs->SMSGateway->Pass,
					'msg'=>$dadosa['msg'],
					'from'=>$dadosa['from'],
					'mobile'=>$dadosa['celular'],
					'id'=>$id,
					'schedule'=>$dadosa['schedule'],
					'callbackOption'=>$this->configs->SMSGateway->CallBack));
			
			$this->log->log("SOAP: {$other}",Zend_Log::INFO);
			
			$retorno = substr($other, 0, 3);
			
			$dbup['retornocode'] = $retorno;
			
			$dbup['retornomessage'] = $other;

			$sms->update($dbup, "id_registro = '$id'");
			
			return "OK - Mensagem Enviada";
			}catch (Exception $e){
				return "ERRO - " .$e->getMessage() . " ---- " . $this->configs->SMSGateway->Server;
				$this->log->log("SOAP: {$e->getMessage()}",Zend_Log::ERR);
			}
		}
		
	}
	public function getSMS(){
		
	}
	public function getReply(){
		
	}
	
}