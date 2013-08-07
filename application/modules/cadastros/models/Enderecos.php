<?php
class Cadastros_Model_Enderecos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblpessoas_enderecos';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['logradouro'];
		}
		return $rdepto;

	}



	public static function getCadastro($id){
		$db = new Cadastros_Model_Enderecos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$dados['dataabertura'] = Functions_Datas::MyDateTime($dados['dataabertura']);
		return $dados;

	}
	
	
	public static function getEnderecoCadastro($id){
		$db = new Cadastros_Model_Enderecos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	
	}

	
	public static function getEnderecoPadraoCadastro($id){
		$db = new Cadastros_Model_Enderecos();
		$dados = $db->fetchRow("id_pessoa = '$id' and isdefault = '1'");
		return $dados;
	
	}


}
