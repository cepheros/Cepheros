<?php
class Crm_Model_Prospects_TiposNegocios extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_prospects_tiposdenegocios';
	protected $_primary = 'id_registro';
	
	
	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomedepto'];
		}
		return $rdepto;
	
	}
	
	public static function getRegistroPadrao(){
		$db = new Crm_Model_Prospects_TiposNegocios();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNomeDepto($id){
		$db = new Crm_Model_Prospects_TiposNegocios();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomedepto;
	}
	
	
	public static function renderCombo(){
		$db = new Crm_Model_Prospects_TiposNegocios();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomedepto'];
		}
		return $rdepto;
	
	}
	
	public static function getDeptoConfigs($id){
		$db = new Crm_Model_Prospects_TiposNegocios();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
		
	}	
		
	public static function getGerenteDepto($id){
		$db = new Crm_Model_Prospects_TiposNegocios();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->deptolider;
	}
	
	
	
	

}