<?php
class System_SmsController extends Zend_Controller_Action{
	
	
	
	public $log;
	public $configs;
	public $cache;
	public $userInfo;
	
	public function init(){
	
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->userInfo = Zend_Auth::getInstance()->getStorage()->read();
	
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
	
	}
	

	public function indexAction(){
		error_reporting(E_ALL);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	//	$this->_helper->viewRenderer->setNeverRender();
	echo $this->configs->Leader->Cliente->codigo;
		$soap = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/sms?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/sms"));
		$message['msg'] = "teste do SOAP";
		$message['from'] = "32.DLL";
		$message['mobile'] = '11945074004';
		$message['schedule'] = NULL;
		 
		
		 print_r($soap->sendSMS($this->configs->Leader->Cliente->codigo , $this->configs->Leader->SoapKey, $message));
		
/*
		$other = $soap->SendSms(array('account'=>$this->configs->SMSGateway->User,
										'code'=>$this->configs->SMSGateway->Pass,
										'msg'=>"Mais um Teste",	
										'from'=>'32DLL',
										'mobile'=>'5511945074004',
										'id'=>'27',
										'schedule'=>'30/01/2013 15:25:00',
										'callbackOption'=>$this->configs->SMSGateway->CallBack));
		
	$retorno = substr($other, 0, 3);
 */
	
		
		
	
	}	
		
}