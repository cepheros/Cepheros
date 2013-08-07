<?php
class Crm_Model_TicketsStatus extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_statustickets';
	protected $_primary = 'id_registro';
	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descritivo'];
		}
		return $rdepto;
	
	}
	
	public static function getRegistroPadrao(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNomeTipo($id){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->descritivo;
	}
	
	
	public static function renderCombo(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descritivo'];
		}
		return $rdepto;
	
	}
	
	public static function getTipoData($id){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	
	}
	
	public static function getDueTipo($id){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->datedue;
	
	}
	
	
	public static function getOpenStatus(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchAll("isdefault = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	public static function getCloseStatus(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchAll("isclosed = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	public static function getSuspendedStatus(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchAll("issuspended = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	public static function getPendentStatus(){
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchAll("ispendent = '1'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_registro}'";
		}
	
		return $retorno;
	}
	
	
	
}