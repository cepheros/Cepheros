<?php
class System_Model_Categoriaprodutos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_categoriasprodutos';
	protected $_primary = 'id_registro';
	
	
	public function getCombo(){	
			$dados = $this->fetchAll()->toArray();
			$rdepto[''] = '- Selecione -';
			foreach($dados as $depto){
				$rdepto[$depto['id_registro']] = $depto['nomecategoria'];
			}				
			return $rdepto;

	}
	
	public static function getRegistroPadrao(){
		$db = new System_Model_Categoriaprodutos();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNomeCategoria($id){
		$db = new System_Model_Categoriaprodutos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomecategoria;		
	}
	
	
	public static function renderCombo(){
		$db = new System_Model_Categoriaprodutos();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomecategoria'];
		}
		return $rdepto;
	
	}
	
	

}
