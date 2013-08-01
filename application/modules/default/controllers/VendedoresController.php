<?php
class VendedoresController extends Zend_Controller_Action{

	public function init(){

		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');

		$this->view->parameters = $this->_request->getParams();

		$this->DocsPath = $this->configs->SYS->DocsPath;

		$this->_helper->layout()->setLayout('vendedor/layout');
	}
	
	
	public function indexAction(){
		
		$this->_helper->layout->disableLayout();
			
		$log = Zend_Registry::get('log');
		$configs = Zend_Registry::get('configs');
		$agent = getenv("HTTP_USER_AGENT");
		if (preg_match("/MSIE/i", $agent)) {
			$this->_redirect('/login/index/browser-error');
		
		}
		$this->view->SysName = $configs->Leader->SysName;
		$this->view->LoginError = false;
			
		$request = $this->getRequest();
		if($this->getRequest()->isPost()){
			$authAdapter = $this->getAuthAdapter();
		
			# get the username and password from the form
			$username = $this->_request->getPost('username');
			$password = $this->_request->getPost('password');
			
		
					# pass to the adapter the submitted username and password
					$authAdapter->setIdentity($username)
					->setCredential($password);
		
					$auth = Zend_Auth::getInstance();
					$result = $auth->authenticate($authAdapter);
		
					# is the user a valid one?
					if($result->isValid())
					{
					# all info about this user from the login table
						# ommit only the password, we don't need that
						$userInfo = $authAdapter->getResultRowObject(null, 'password');
		
						# the default storage is a session with namespace Zend_Auth
						$authStorage = $auth->getStorage();
						$authStorage->write($userInfo);
		
						$log->log("Logado no sistema: {$userInfo->username}",Zend_Log::INFO);
			//			$userdata = new System_Model_Users();
			//	$data['lastlogin'] = date('Y-m-d H:i:s');
			//	$data['lastloginip'] = $_SERVER['REMOTE_ADDR'];
			//	$userdata->update($data, "id_registro = {$userInfo->id_registro}");
						$this->_redirect('/default/vendedores/dashboard');
			}
					else
					{
					$this->view->LoginError = true;
					$log->log("Tentativa de login errada - [Usuário: {$username}] [Senha: {$password}]",Zend_Log::WARN);
					}
					}
		
	}
	
	public function dashboardAction(){
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/default/vendedores/index');
		}
		
		
		
		
		
	}
	
	
	protected function getAuthAdapter()
	{
		$dbAdapter = Zend_Db_Table::getDefaultAdapter();
		$authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
	
		$authAdapter->setTableName('tblsytem_vendedor_users')
		->setIdentityColumn('username')
		->setCredentialColumn('password')
		->setCredentialTreatment('MD5(?)');
	
		return $authAdapter;
	}
	
	
}
