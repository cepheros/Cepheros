<?php
class System_Model_ChecklistEtapas extends Zend_Db_Table_Abstract{
	protected $_name = 'tblchecklist_etapas';
	protected $_primary = 'id_registro';
	

	public static function  getnome($id){
		$db = new System_Model_ChecklistEtapas;
		$dados = $db->fetchRow("id_registro = '$id'");
		$nome = $dados->nomeetapa;
		return $nome;
	}
	
	
	public static function countetapas($id){
		$db = new System_Model_ChecklistEtapas;
		$dados = $db->fetchRow("id_prospect = '$id'");
		if(isset($dados)){
			$contador = count($dados->toArray());
		}else{
			$contator = '1';
		}
		
		return $contador;
		
		
		
	}
		
	

}
