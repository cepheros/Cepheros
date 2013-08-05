<?php
class Crm_Model_TicketsFlags extends Zend_Db_Table_Abstract{
	protected $_name = 'tbltickets_flags';
	protected $_primary = 'id_registro';
	
	
	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['tipoticket'];
		}
		return $rdepto;
	
	}
	
	public static function getRegistroPadrao(){
		$db = new Crm_Model_TicketsFlags();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNomeTipo($id){
		$db = new Crm_Model_TicketsFlags();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->tipoticket;
	}
	
	
	public static function renderCombo(){
		$db = new Crm_Model_TicketsFlags();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['tipoticket'];
		}
		return $rdepto;
	
	}
	
	public static function getTipoData($id){
		$db = new Crm_Model_TicketsFlags();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
		
	}
	
	public static function getDueTipo($id){
		$db = new Crm_Model_TicketsFlags();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->datedue;
		
	}
	
	
	public static function checkFlag($ticket,$user){
		
		$db = new Crm_Model_TicketsFlags();
		
		$dados = $db->fetchRow("id_ticket = '$ticket' and id_user = '$user'");
		
		if(isset($dados)){
			return true;
		}else{
			return false;
		}
		
		
	}
	
	

}