<?php
class Crm_Model_TicketsReplys extends Zend_Db_Table_Abstract{
	protected $_name = 'tbltickets_replys';
	protected $_primary = 'id_registro';
	
	
	public static function countReplys($ticket){
		$db = new Crm_Model_TicketsReplys();
		$data = $db->fetchAll("id_ticket = '$ticket'")->toArray();
		if($data){			
			return count($data);
		}else{
			return '0';
		}
	}
	
	
	public static function getTicket($id){
		$db = new Crm_Model_TicketsReplys();
		$data = $db->fetchAll("id_ticket = '$id'");
		return $data;
	}
	
}