<?php
class System_Model_UsersDeptos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblusers_deptos';
	protected $_primary = 'id_registro';
	
	
	
		
	public static function getDeptos(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new System_Model_UsersDeptos();
		
		$dados = $db->fetchAll("id_user = '$user_id'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_depto}'";
		}
	
		return $retorno;
	}
	
	public static function getUserDeptos($id){
		$db = new System_Model_UsersDeptos();
		$dados = $db->fetchAll("id_user = '$id'");
		foreach($dados as $dado){
			$retorno[] = "'{$dado->id_depto}'";
		}
		return $retorno;
	}
	
}