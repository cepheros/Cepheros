<?php
class System_Model_Empresas extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_empresas';
	protected $_primary = 'id_registro';
	protected $_dependentTables = array('tblsystem_empresas_nf');
	
	
	public function getCombo(){	
			$dados = $this->fetchAll()->toArray();
			$rdepto[''] = '- Selecione -';
			foreach($dados as $depto){
				$rdepto[$depto['id_registro']] = $depto['nomefantasia'];
			}				
			return $rdepto;

	}
	
	public static function getEmpresaPadrao(){
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("principal = '1'");
		if($dados->id_registro){
			return $dados->id_registro;
		}else{
			return false;
		}
	}
	
	public static function getNomeEmpresa($id){
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->razaosocial;		
	}
	
	
	public static function getDataEmpresaCNPJ($CNPJ){
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("cnpj = '$CNPJ'");
		return $dados;
	}
	
	public static function getNomeFantasiaEmpresa($id){
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomefantasia;
	}
	
	public static function getDataEmpresa($id){
		$db = new System_Model_Empresas();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	}
	
	
	
	public static function renderCombo(){
		$db = new System_Model_Empresas();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomefantasia'];
		}
		return $rdepto;
	
	}
	
	

}
