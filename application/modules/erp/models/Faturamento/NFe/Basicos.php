<?php 
class Erp_Model_Faturamento_NFe_Basicos extends Zend_Db_Table_Abstract{
	
	protected $_name = 'tblnfe_basicos';
	protected $_primary = 'id_registro';
	
	public static function somaBaseCalculo($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_icms'),array('SUM(a.vBC) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalICMS($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_icms'),array('SUM(a.vICMS) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaBaseCalculoST($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_icms'),array('SUM(a.vBCST) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalICMSST($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_icms'),array('SUM(a.vICMSST) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalProdutos($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos'),array('SUM(a.vProd) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	
	public static function somaTotalFrete($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos'),array('SUM(a.vFrete) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalSeguro($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos'),array('SUM(a.vSeg) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalDesconto($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos'),array('SUM(a.vDesc) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalII($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos'),array('SUM(a.vOutro) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalIPI($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_ipi'),array('SUM(a.vIPI) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	
	public static function somaTotalPIS($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_pis'),array('SUM(a.vPIS) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	public static function somaTotalCOFINS($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos_cofins'),array('SUM(a.vCOFINS) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	
	public static function somaTotalOutros($nfe){
		$dados = new Erp_Model_Faturamento_NFe_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblnfe_produtos'),array('SUM(a.vOutro) as valor'))
		->where("id_nfe = '$nfe'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$total = $dados[0]['valor'];
		if(!$total){
			$total = '0.00';
		}
		return $total;
	}
	
	
}
	