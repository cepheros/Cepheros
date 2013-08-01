<?php
class Cadastros_Model_Outros extends Zend_Db_Table_Abstract{
	protected $_name = 'tblpessoas_outros';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomefantasia'];
		}
		return $rdepto;

	}


	public static function getNomeEmpresa($id){
		$db = new Cadastros_Model_Pessoas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->razaosocial;
	}


	public static function renderCombo(){
		$db = new Cadastros_Model_Pessoas();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomefantasia'];
		}
		return $rdepto;

	}


	public static function getCadastro($id){
		$db = new Cadastros_Model_Outros();
		$dados = $db->fetchRow("id_pessoa = '$id'");
		return $dados;

	}



}
