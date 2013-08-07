<?php
class Erp_Model_Producao_Basicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblproducao_basicos';
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
	
	public static function getTotalProducao(){
		$dados = new Erp_Model_Producao_Registros();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblvendas_produtos'),array('SUM(a.quantidade) as quantidade'))
		->where("id_prod > 0");
		$return->where("statusproducao <> '3'");

		$rs = $return->query();
		$dados = $rs->fetchAll();
		$estoqueatual = $dados[0]['quantidade'];
		if(!$estoqueatual){
			$estoqueatual = '0';
		}
		
		return $estoqueatual;
		
	}
	
	
	public static function getItensProducao(){
		$dados = new Erp_Model_Producao_Registros();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblvendas_produtos'),array('a.id_prod'))
		->where("id_prod > 0");
		$return->where("statusproducao <> '3'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		foreach($dados as $item){
			$ret[]= $item['id_prod'];
		}
		
		return $ret;
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