<?php
class System_Model_ChecklistBasicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblchecklist_basicos';
	protected $_primary = 'id_registro';
	
	
	
	public static function  getnome($id){
		$db = new System_Model_ChecklistBasicos;
		$dados = $db->fetchRow("id_registro = '$id'");
		$nome = $dados->nomechecklist;
		return $nome;
	}

	
	public static function renderCombo($tipo=null){
		if($tipo){
			$query = "tipochecklist = '$tipo'";
		}else{
			$query = null;
		}
		$db = new System_Model_ChecklistBasicos();
		$dados = $db->fetchAll($query)->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomechecklist'];
		}
		return $rdepto;
	
	}
	

}
