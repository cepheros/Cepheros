<?php
class Erp_Model_Producao_Etapas extends Zend_Db_Table_Abstract{
	protected $_name = 'tblproducao_etapas';
	protected $_primary = 'id_registro';

	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeetapa'];
		}
		return $rdepto;
	
	}
	
	
	public static function getNome($id){
		$db = new Erp_Model_Producao_Etapas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomeetapa;
	}
	
	
	public static function renderCombo(){
		$db = new Erp_Model_Producao_Etapas();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeetapa'];
		}
		return $rdepto;
	
	}
}