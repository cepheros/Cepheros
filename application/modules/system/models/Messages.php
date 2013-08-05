<?php
class System_Model_Messages extends Zend_Db_Table_Abstract{
	
	protected $_name = 'tblusers_messages';
	protected $_primary = 'id_registro';
	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['assuntomessage'];
		}
		return $rdepto;

	}

	public static function getMessage($id){
		$db = new System_Model_Messages();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	}

	
	
	public static function getMyMessages($user){
		$db = new System_Model_Messages();
		$dados = $db->fetchAll("user_to = '$user' and statusmessage = '1' ")->toArray();
		return $dados;
		
	}
	
	public static function countMyMessages($user){
		$db = new System_Model_Messages();
		$dados = $db->fetchAll("user_to = '$user' and statusmessage = '1' ");
		$qtd = count($dados);
		return $qtd;
	
	}
	
	public static function getAllMessages($user){
		
		$db = new System_Model_Messages();
		$dados = $db->fetchAll("user_to = '$user' and statusmessage <> '4' ")->toArray();
		return $dados;
		
		
	}
	
	public static function getAllSendMessages($user){
	
		$db = new System_Model_Messages();
		$dados = $db->fetchAll("user_from = '$user'")->toArray();
		return $dados;
	
	
	}
	
	public static function getAllGarbageMessages($user){
	
		$db = new System_Model_Messages();
		$dados = $db->fetchAll("user_to = '$user' and statusmessage = '4' ")->toArray();
		return $dados;
	
	
	}
	



}