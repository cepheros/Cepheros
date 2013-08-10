<?php
class Crm_Model_Marketing_Campanhas extends Zend_Db_Table_Abstract{
	protected $_name = 'tblmarketing_campanhas';
	protected $_primary = 'id_registro';
	
	
	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomecampanha'];
		}
		return $rdepto;
	
	}
	
	public static function getRegistroPadrao(){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNomeDepto($id){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomecampanha;
	}
	
	public static function getMesssages($id){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchRow("id_registro = '$id'");
		$data = array('new'=>$dados->msgnew,'reply'=>$dados->msgreply);
		return $data;
	}
	
	public static function getDataCampanha($id){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		
		return $dados;
	}
	
	
	public static function renderCombo(){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomecampanha'];
		}
		return $rdepto;
	
	}
	
	public static function getDeptoConfigs($id){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
		
	}	
		
	public static function getGerenteDepto($id){
		$db = new Crm_Model_Marketing_Campanhas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->deptolider;
	}
	
	
	
	

}