<?php
class Erp_Model_Producao_Registros extends Zend_Db_Table_Abstract{
	protected $_name = 'tblproducao_registros';
	protected $_primary = 'id_registro';

	
	
	public static function GetSomaOP($op,$etapa = NULL, $format = 2){
		$dados = new Erp_Model_Producao_Registros();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblproducao_registros'),array('SUM(a.quantidade) as quantidade'))
		->where("id_producao = '$op'");
		if($etapa){
			$return->where("etapa in ($etapa)");
		}else{
			$etapa = System_Model_SysConfigs::getConfig("ProducaoEtapaFinal");
			$return->where("etapa in ($etapa)");
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
	
	
	public static function GetSomaOPs($op,$etapa = NULL, $format = 2){
		$dados = new Erp_Model_Producao_Registros();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblproducao_registros'),array('SUM(a.quantidade) as quantidade'))
		->where("id_producao in ('$op')");
		if($etapa){
			$return->where("etapa in ($etapa)");
		}else{
			$etapa = System_Model_SysConfigs::getConfig("ProducaoEtapaFinal");
			$return->where("etapa in ($etapa)");
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