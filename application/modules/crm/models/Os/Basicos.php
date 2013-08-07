<?php
class Crm_Model_Os_Basicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblos_basicos';
	protected $_primary = 'id_registro';
	
	
	public static function checkProtocol($protocol){
		$db = new Crm_Model_Os_Basicos();
		$data = $db->fetchRow("cod_os = '$protocol'");
		if(isset($data->id_registro)){
			return false;
		}else{
			return true;
		}
	}
	
	
	public static function totalOs($id){
		error_reporting(0);
		$db = new Crm_Model_Os_Basicos();
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblos_basicos'),
				array('a.id_registro',
						'(select SUM(totalitem) AS totalservicos from tblos_servicos where id_os = a.id_registro) as totalservicos',
						'(select SUM(totalitem) AS totalprodutos from tblos_produtos where id_os = a.id_registro) as totalprodutos'
						))
				     	->where("a.id_registro = '$id'");
		$rs = $return->query();
		
		$dados = $rs->fetchAll();
		if(isset($dados) && isset($dados[0]['id_registro'])){
					$retorno = $dados;
		}else{
			$retorno = array('totalprodutos'=>'0.00','totalservicos'=>'0.00');
		}
		return $retorno;
	}
	
	
	public static function countAbertos(){
		$status =  Crm_Model_Os_Status::getOpenStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$dados = $db->fetchAll("user_open = '$user_id' and status_os in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	public static function countAndamento(){
		$status =  Crm_Model_Os_Status::getPendentStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$dados = $db->fetchAll("user_open = '$user_id' and status_os in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	public static function countEncerradas(){
		$status =  Crm_Model_Os_Status::getCloseStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$dados = $db->fetchAll("user_open = '$user_id' and status_os in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	public static function countFaturadas(){
		$status =  Crm_Model_Os_Status::getSuspendedStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_Os_Basicos();
		$dados = $db->fetchAll("user_open = '$user_id' and status_os in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
}