<?php
class System_Model_Prioridades extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_prioridades';
	protected $_primary = 'id_registro';
		
	
	public function getCombo(){	
			$dados = $this->fetchAll()->toArray();
			$rdepto[''] = '- Selecione -';
			foreach($dados as $depto){
				$rdepto[$depto['id_registro']] = $depto['nome'];
			}				
			return $rdepto;

	}
	
	public static function getProridadePadrao(){
		$db = new System_Model_Prioridades();
		$dados = $db->fetchRow("padrao = '1'");
		return $dados->id_registro;
	}
	
	public static function getNomePrioridade($id){
		$db = new System_Model_Prioridades();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nome;		
	}
	
	
	public static function getHtmlPrioridade($id){
		$db = new System_Model_Prioridades();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->rephtml;
	}
	
	public static function renderCombo(){
		$db = new System_Model_Prioridades();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nome'];
		}
		return $rdepto;
	
	}
	
	
	public static function returnAll(){
		$db = new System_Model_Prioridades();
		$dados = $db->fetchAll()->toArray();
		return $dados;
	
	}
	
	

}
