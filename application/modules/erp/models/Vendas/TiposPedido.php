<?php
class Erp_Model_Vendas_TiposPedido extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_tipodepedido';
	protected $_primary = 'id_registro';

	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descritivo'];
		}
		return $rdepto;
	
	}
	
	public static function getRegistroPadrao(){
		$db = new Erp_Model_Vendas_TiposPedido();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}
	
	public static function getNome($id){
		$db = new Erp_Model_Vendas_TiposPedido();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->descritivo;
	}
	
	
	public static function renderCombo(){
		$db = new Erp_Model_Vendas_TiposPedido();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descritivo'];
		}
		return $rdepto;
	
	}
}