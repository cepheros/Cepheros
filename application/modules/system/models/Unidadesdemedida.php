<?php
class System_Model_Unidadesdemedida extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_unidadesdemedida';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descricao'];
		}
		return $rdepto;

	}

	public static function getRegistroPadrao(){
		$db = new System_Model_Unidadesdemedida();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static  function getUnCon($id){
		$db = new System_Model_Unidadesdemedida();
		$dados = $db->fetchRow("id_registro = '1' ");
		return $dados->abreviacao;
		
	}

	public static function getNomeEndereco($id){
		$db = new System_Model_Unidadesdemedida();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->descricao;
	}


	public static function renderCombo(){
		$db = new System_Model_Unidadesdemedida();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descricao'];
		}
		return $rdepto;

	}



}
