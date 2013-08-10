<?php
class Crm_Model_TicketsAcompanhantes extends Zend_Db_Table_Abstract{
	protected $_name = 'tbltickets_acompanhantes';
	protected $_primary = 'id_registro';
	
	
	
	
	public static function check($id,$user){
		$db = new Crm_Model_TicketsAcompanhantes();
		$data = $db->fetchRow("id_ticket = '$id' and id_user = '$user'");
		if(isset($data->id_registro)){
			return false;
		}else{
			return true;
		}
	}
	
	public static function getAcompanhamentos(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_TicketsAcompanhantes();
		
		$dados = $db->fetchAll("id_user = '$user_id'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_ticket}'";
		}
	
		return $retorno;
	}
	
}