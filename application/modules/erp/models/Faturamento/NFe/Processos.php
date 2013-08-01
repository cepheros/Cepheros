<?php 
class Erp_Model_Faturamento_NFe_Processos extends Zend_Db_Table_Abstract{
	
	protected $_name = 'tblnfe_processos';
	protected $_primary = 'id_registro';
	
	
	public static function contaProcessos($nfe,$processo){
		$db = new Erp_Model_Faturamento_NFe_Processos;
			
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblnfe_processos'),array('count(a.id_registro) as quantidade'))
		->where("id_nfe = '$nfe'")
		->where("tipoProcesso = '$processo'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		
		$total = $dados[0]['quantidade'];
		if(!$total){
			$total = '0';
		}
		return $total;
		
		
		
	}
	
}