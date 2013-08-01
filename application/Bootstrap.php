<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	/**
	 * Verifica a existência e cria as pastas de dados do sistema
	 * onde ficam armazenados todos os dados, imagens, documentos
	 * temporários, caches e arquivos fiscais
	 * 
	 */
	public function _initSystemPaths(){
		if(!is_dir(APPLICATION_PATH.'/data')){
			mkdir(APPLICATION_PATH.'/data');
		}
		if(!is_dir(APPLICATION_PATH.'/data/logs')){
			mkdir(APPLICATION_PATH.'/data/logs');
		}
		if(!is_dir(APPLICATION_PATH.'/data/temp')){
			mkdir(APPLICATION_PATH.'/data/temp');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/cache')){
			mkdir(APPLICATION_PATH.'/data/cache');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/files')){
			mkdir(APPLICATION_PATH.'/data/files');
		}
		if(!is_dir(APPLICATION_PATH.'/data/certs')){
			mkdir(APPLICATION_PATH.'/data/certs');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/updates')){
			mkdir(APPLICATION_PATH.'/data/updates');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/updates/sql')){
			mkdir(APPLICATION_PATH.'/data/updates/sql');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/updates/dll')){
			mkdir(APPLICATION_PATH.'/data/updates/dll');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/files/nfe')){
			mkdir(APPLICATION_PATH.'/data/files/nfe');
		}
		if(!is_dir(APPLICATION_PATH.'/data/files/nfes')){
			mkdir(APPLICATION_PATH.'/data/files/nfes');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/files/empresas')){
			mkdir(APPLICATION_PATH.'/data/files/empresas');
		}
		
		if(!is_dir(APPLICATION_PATH.'/data/files/produtos')){
			mkdir(APPLICATION_PATH.'/data/files/produtos');
		}
						
	}

	/**
	 * Inicializa os Autoloaders das classes especiais do sistema
	 * @return void
	 * @author Daniel Chaves
	 */
	public function _initAutoLoad(){
		$autoloader = Zend_Loader_Autoloader::getInstance();
		$autoloader->registerNamespace('Functions_');
		$autoloader->registerNamespace('Vendor_');
		$autoloader->registerNamespace('Twitter_');
		$autoloader->registerNamespace('Reports_');
		$autoloader->registerNamespace('NFe_');
		$autoloader->registerNamespace('Soap_');
	}
	 
	/**
	 * Inicializa o Log do Sistema
	 * @return void
	 * @author Daniel Chaves
	 */
	public function _initLog(){
		$date = date('d-m-Y');
		$writer = new Zend_Log_Writer_Stream(APPLICATION_PATH."/data/logs/$date.txt");
		$log = new Zend_Log($writer);
		Zend_Registry::set('log',$log);
	}
	
	/**
	 * Salva as configurações no registry
	 * @return void
	 * @author Daniel Chaves
	 */
	
	public function _initConfigs(){
		$config = new Zend_Config($this->getApplication()->getOptions(),true);
		Zend_Registry::set("configs", $config);
	}
	
	/**
	 * Inicializa o cache do sistema
	 * @return void
	 * @author Daniel Chaves
	 */
	
	public function _initCache(){
		$log = Zend_Registry::get('log');
	
		try{
			$config = Zend_Registry::get('configs')->cache;
			$frontendOptions = array(
					'lifetime'=>$config->frontend->lifetime,
					'automatic_serialization'=>$config->frontend->automatic_serialization
			);
			$backendOptions = $config->backend->options->toArray();
			$cache = Zend_Cache::factory('core',
					$config->backend->adapter,
					$frontendOptions,
					$backendOptions);
			Zend_Registry::set('cache',$cache);
			Zend_Db_Table_Abstract::setDefaultMetadataCache($cache);
		}catch (Exception $e){
			$log->log($e->getMessage(),Zend_Log::ERR);
		}
		 
		 
	}
	
	/**
	 * Inicializa o banco de dados pricipal.
	 */
	
	public function _initDb(){
		$db = $this->getPluginResource('db')->getDbAdapter();
		Zend_Db_Table::setDefaultAdapter($db);
		Zend_Registry::set('db',$db);
	}
	
	/**
	 * Inicializa os dados da empresa principal do sistema
	 */
	
	
	public function _initEmpresa(){
		$db = Zend_Registry::get('db');
		$select = new Zend_Db_Select($db);
		$select->from('tblsystem_empresas')->where("principal = '1'")->limit("0,1");
		$rs = $select->query();
		$data = $rs->fetch();		
		Zend_Registry::set("Empresa", $data);
	}
	
	
	public function _initHuman(){
		require_once(LIBRARY_PATH.DIRECTORY_SEPARATOR.'Vendor'.DIRECTORY_SEPARATOR.'Human'.DIRECTORY_SEPARATOR.'HumanClientMain.php');
	}


}

