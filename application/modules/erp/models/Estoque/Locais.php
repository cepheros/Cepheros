<?php
class Erp_Model_Estoque_Locais extends Zend_Db_Table_Abstract{

	protected $_name = 'tblestoque_locais';
	protected $_primary = 'id_registro';



	public static function getEstoquePadrao(){
		$db = new Erp_Model_Estoque_Locais();
		$dados = $db->fetchRow("is_default = '1' ");
		return $dados->id_registro;
	}


	public static function getCombo(){
		$db = new Erp_Model_Estoque_Locais();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['localestoque'];
		}
		return $rdepto;
	}

	public static function  gelAllLocais(){
		$data = new Erp_Model_Estoque_Locais();
		$dados = $data->fetchAll();
		return $dados;
	}


}