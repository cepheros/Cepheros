<?php
class Erp_Model_Financeiro_StatusLancamentos extends Zend_Db_Table_Abstract{

	protected $_name = 'tblapoio_statuslancamentos';
	protected $_primary = 'id_registro';


	public static function getCombo(){
		$db = new Erp_Model_Financeiro_StatusLancamentos();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomestatus'];
		}
		return $rdepto;
	}


	public static function getStatusName($id){
		$db = new Erp_Model_Financeiro_StatusLancamentos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomestatus;

	}



}