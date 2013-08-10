<?php
class Crm_Model_Prospects_Notes extends Zend_Db_Table_Abstract{
	protected $_name = 'tblprospects_notes';
	protected $_primary = 'id_registro';
	
	public static function countNotes($ticket){
		$db = new Crm_Model_Prospects_Notes();
		$data = $db->fetchAll("id_prospect = '$ticket'")->toArray();
		if($data){
			return count($data);
		}else{
			return '0';
		}
	}
	
}