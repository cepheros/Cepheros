<?php
class Crm_Model_Prospects_EstagioProposta extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_prospects_estagioproposta';
	protected $_primary = 'id_registro';
	
	
	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nome'];
		}
		return $rdepto;
	
	}
	
	public static function getRegistroPadrao(){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNomeDepto($id){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nome;
	}
	
	
	public static function renderCombo(){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nome'];
		}
		return $rdepto;
	
	}
	
	public static function getDeptoConfigs($id){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
		
	}	
		
	public static function getGerenteDepto($id){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->deptolider;
	}
	
	
	
	public static function getOpenStatus(){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchAll("isopen = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	public static function getCloseStatus(){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchAll("isclosed = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	public static function getSuspendedStatus(){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchAll("issuspended = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	public static function getPendentStatus(){
		$db = new Crm_Model_Prospects_EstagioProposta();
		$dados = $db->fetchAll("ispendent = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	
	
	

}