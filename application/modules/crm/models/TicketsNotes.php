<?php
class Crm_Model_TicketsNotes extends Zend_Db_Table_Abstract{
	protected $_name = 'tbltickets_notes';
	protected $_primary = 'id_registro';
	
	
	public static function countNotes($ticket){
		$db = new Crm_Model_TicketsNotes();
		$data = $db->fetchAll("id_ticket = '$ticket'")->toArray();
		if($data){			
			return count($data);
		}else{
			return '0';
		}
	}
	
	
	public static function getNotes($id){
		$db = new Crm_Model_TicketsNotes();
		$data = $db->fetchAll("id_ticket = '$id'");
		return $data;
	}
	
}