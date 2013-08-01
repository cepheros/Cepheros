<?php
class Soap_Cadastro{
	
	
	public $configs;
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
	
	}
	
	/**
	 * Solicita os dados de cadastro de um id
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @param int $id ID do cadastro que deve ser pesquisado
	 * @return array $dados Dados do Cadastro localizado
	 */
	
	public function getCadastro($client,$code,$id){
	
		if(!Functions_Verificadores::checkSoapAccess($cliente, $code)){
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}else{
			$db = new Cadastros_Model_Pessoas();
			$dados = $db->fetchRow("id_registro = '$id'")->toArray();
			return $dados;
		}
		
	}
	

	/**
	 * Verifica a situação do sistema do cliente
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @return array $dados Dados de Informação do Sistema Cliente
	 */
	
	public function checkSistema($client,$code){
		if(Functions_Verificadores::checkSoapAccess($client, $code)){
			
			
		}else{
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}
	}
	
	
	/**
	 * Verifica as mensagens do sistema
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @return array $dados dados com as mensagens a serem exibidas para o cliente
	 */
	
	public function getSysMessages($client,$code){
		
		if(Functions_Verificadores::checkSoapAccess($client, $code)){
			
				
				
		}else{
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}
		
		
	}
	
	
	
	
}