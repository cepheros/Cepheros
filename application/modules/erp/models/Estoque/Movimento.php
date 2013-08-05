<?php
class Erp_Model_Estoque_Movimento extends Zend_Db_Table_Abstract{
	protected $_name = 'tblestoque_movimentos';
	protected $_primary = 'id_registro';
	
	
	public static function estoqueAtual($produto,$estoques = false, $format = 2){
		$dados = new Erp_Model_Estoque_Movimento();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblestoque_movimentos'),array('SUM(a.quantidade) as quantidade'))
		->where("id_produto = '$produto'");
		if($estoques){
			$return->where("id_estoque in ($estoques)");
		}
		
		$rs = $return->query();
		
		$dados = $rs->fetchAll();
		
		$estoqueatual = $dados[0]['quantidade'];
		if(!$estoqueatual){
			$estoqueatual = '0';
		}
		
		if($format > 0){
			$estoqueatual = number_format($estoqueatual,$format,',','');
		}
		
		return $estoqueatual;
	}
	
}