<?php
class System_Model_Users extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_users';
	
	public static function whoIs($id){
		$db = new System_Model_Users();
		$dados =  $db->fetchRow("id_registro = '$id'");
		if($dados->nomecompleto <> ''){
			return $dados->nomecompleto;
		}else{
			return "Administrador do Sistema";
		}
	}
	
	public static function whoIsUser($id){
		$db = new System_Model_Users();
		$dados =  $db->fetchRow("id_registro = '$id'");
		return $dados;	
	}
	
	
	public static function renderCombo($id = null){
		
		$db = new System_Model_Users();
		if($id){
			$dados = $db->fetchAll("id_registro <> '$id' ")->toArray();
		}else{
			$dados = $db->fetchAll()->toArray();
		}
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomecompleto'];
		}
		return $rdepto;
	
	}
	
	
	public static function renderMultiCombo($id = false){
	
		$db = new System_Model_Users();
		if($id){
			$dados = $db->fetchAll("id_registro <> '$id' ")->toArray();
		}else{
			$dados = $db->fetchAll()->toArray();
		}

		return $dados;
	
	}
	
	
}