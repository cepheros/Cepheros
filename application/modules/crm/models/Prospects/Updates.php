<?php
class Crm_Model_Prospects_Updates extends Zend_Db_Table_Abstract{
	protected $_name = 'tblprospects_updates';
	protected $_primary = 'id_registro';
	
	
	public static function countReplys($ticket){
		$db = new Crm_Model_Prospects_Updates();
		$data = $db->fetchAll("id_prospect = '$ticket'")->toArray();
		if($data){
			return count($data);
		}else{
			return '0';
		}
	}
	
	
}