<?php
class Soap_SystemUsers{
	
	
	public $configs;
	
	public function __construct(){
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
	
	}
	
	/**
	 * Cadastra um usuário do sistema como contato do cliente no sistema principal
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @param array $data Array de dados com as informações do contato
	 * @return string $response
	 */
	
	public function useradd($client,$code,$data){
		$cliente = new Cadastros_Model_Outros();
		$dados = $cliente->fetchRow("id_pessoa = '$client'");
		if($dados->chavesoap <> $code){
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}else{
			$db = new Cadastros_Model_Contatos();
			$dadosa['id_pessoa'] = $client;
			$dadosa['tipocontato'] = '2';
			$dadosa['nomecontato'] = $data['nomecompleto'];
			$dadosa['celular'] = $data['phonenumber'];
			$dadosa['email'] = $data['email'];
			$db->insert($dadosa);
			return "Cadastro OK";
		}		
	}
	
	/**
	 * Atualiza um usuário do sistema como contato do cliente no sistema principal
	 *
	 * @param string $client Codigo do cliente leader
	 * @param string $code Codigo de Acesso a API
	 * @param array $data Array de dados com as informações do contato
	 * @return string $response
	 */
	
	public function userupdate($client,$code,$data){
		$cliente = new Cadastros_Model_Outros();
		$dados = $cliente->fetchRow("id_pessoa = '$client'");
		if($dados->chavesoap <> $code){
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}else{
			$db = new Cadastros_Model_Contatos();
			$dataup = $db->fetchRow("email = {$data['email']}");
			$dadosa['id_pessoa'] = $client;
			$dadosa['tipocontato'] = '2';
			$dadosa['nomecontato'] = $data['nomecompleto'];
			$dadosa['celular'] = $data['phonenumber'];
			$dadosa['email'] = $data['email'];
			$db->update($dados,"id_registro = '{$dataup->id_registro}'");
			return "Cadastro OK";		
		}
	}
				
}