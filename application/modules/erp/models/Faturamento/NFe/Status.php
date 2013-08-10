<?php 
class Erp_Model_Faturamento_NFe_Status extends Zend_Db_Table_Abstract{
	
	protected $_name = 'tblapoio_statusnfe';
	protected $_primary = 'id_registro';
	
	
	public static function getStatus($id){
		$db = new Erp_Model_Faturamento_NFe_Status();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->descritivo;
	}
	
	
	public static function getCombo(){
		$db = new Erp_Model_Faturamento_NFe_Status();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descritivo'];
		}
		return $rdepto;
	}
	
}