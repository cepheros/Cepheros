<?php
class Crm_Model_Prospects_ChecklistItens extends Zend_Db_Table_Abstract{
	protected $_name = 'tblprospects_checklist_itens';
	protected $_primary = 'id_registro';
	
	
	public static function nomeetapa($id){
		$db = new Crm_Model_Prospects_ChecklistItens;
		$dados = $db->fetchRow("id_registro = '$id'");
		$nome = $dados->nomeetapa;
		return $nome;
		
	}
	
	

	public static function countetapas($id){
		$db = new Crm_Model_Prospects_ChecklistItens;
		$dados = $db->fetchAll("id_prospect = '$id' and statusitem = '0'");
		if(isset($dados)){
			$contador = count($dados->toArray());
		}else{
			$contador = '0';
		}
	
		return $contador;
	
	
	
	}
	
	
	
	
}