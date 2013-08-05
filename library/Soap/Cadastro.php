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
	
		if(!Functions_Verificadores::checkSoapAccess($client, $code)){
			return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
		}else{
			$db = new Cadastros_Model_Pessoas();
			$dados['Basicos'] = $db->fetchRow("id_registro = '$id'")->toArray();
			
			$db2 = new Cadastros_Model_Enderecos();
			$dados['Enderecos'] = $db2->fetchAll("id_pessoa = '$id'")->toArray();
			
			$db3 = new Cadastros_Model_Contatos();
			$dados['Contatos'] = $db3->fetchAll("id_pessoa = '$id'")->toArray();
			
			$db4 = new Cadastros_Model_Outros();
			$dados['Outros'] = $db4->fetchRow("id_pessoa = '$id'")->toArray();
			
			
			return $dados;
		}
		
	}
	
	
	/**
	 * Atualiza o cadastro de um determinado cliente no sistema
	 * @param int $client
	 * @param string $code
	 * @param int $id
	 * @param array $dados
	 * @return array
	 */
	
	public function atualizaCadastro($client,$code,$id,$dados){
		
		if(!Functions_Verificadores::checkSoapAccess($client, $code)){
			return array('Status'=>'Fail','Message'=>"Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}");
		}else{
			$db = new Cadastros_Model_Pessoas();
			$db->update($dados['Basicos'], "id_registro = '$id'");
			
			$db2 = new Cadastros_Model_Enderecos();
			foreach($dados['Enderecos'] as $end){
			$db2->update($end, "id_registro = '{$end['id_registro']}'");
			}
					
			$db3 = new Cadastros_Model_Contatos();
			foreach($dados['Contatos'] as $cont){
			$db3->update($cont, "id_registro = '{$cont['id_registro']}'");
			}
					
			$db4 = new Cadastros_Model_Outros();
			$db4->update($dados['Outros'], "id_pessoa = '$id'");

			
			return array('Status'=>'OK','Message'=>'Dados Atualizados Com Sucesso'); 
		}
		
	}
	
	
	/**
	 * Adiciona um novo cadastro de pessoa ao sistema
	 * @param int $client
	 * @param string $code
	 * @param array $dados
	 * @return array
	 */
	
	public function adicionaCadastro($client,$code,$dados){
	
		if(!Functions_Verificadores::checkSoapAccess($client, $code)){
			return array('Status'=>'Fail','Message'=>"Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}");
		}else{
			$db = new Cadastros_Model_Pessoas();
			$id_pessoa = $db->insert($dados['Basicos']);
				
			$db2 = new Cadastros_Model_Enderecos();
			foreach($dados['Enderecos'] as $end){
				$end['id_pessoa'] = $id_pessoa;
				$db2->insert($end);
			}
				
			$db3 = new Cadastros_Model_Contatos();
			foreach($dados['Contatos'] as $cont){
				$cont['id_pessoa'] = $id_pessoa;
				$db3->insert($cont);
			}
				
			$db4 = new Cadastros_Model_Outros();
			$dados['Outros']['id_pessoa'] = $id_pessoa;
			$db4->insert($dados['Outros']);
	
				
			return array('Status'=>'OK','Message'=>'Dados cadastrados Com Sucesso','ID',$id_pessoa);
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