<?php
class Crm_Model_Prospects_Basico extends Zend_Db_Table_Abstract{
	protected $_name = 'tblprospects_basico';
	protected $_primary = 'id_registro';
	
	
	
	
	

	public static function countNaoResolvidos(){
		$status = Crm_Model_Prospects_EstagioProposta::getOpenStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("atribuidoa = '$user_id' and estagioproposta in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
		
	
	public static function countNaoAtribuidos(){
	
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getOpenStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("atribuidoa = '0' and estagioproposta in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	}
	
	public static function countAtualizadoRecente(){
		$last24 = Functions_Datas::SubtraiData(date('d/m/Y'), 2);
		$las24ok = Functions_Datas::inverteData($last24);
		$datainicio = $las24ok." ".date('H:i:s');
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getCloseStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("datelastupdate >= '$datainicio' and estagioproposta not in ($openstatus)  and atribuidoa = '$user_id' ");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;;
		}
	
	
	}
	
	public static function countResolvidoRecente(){
		$last24 = Functions_Datas::SubtraiData(date('d/m/Y'), 2);
		$las24ok = Functions_Datas::inverteData($last24);
		$datainicio = $las24ok." ".date('H:i:s');
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getCloseStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("estagioproposta in ($openstatus) and datelastupdate >= '$datainicio'  and atribuidoa = '$user_id' ");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	public static function countSuspended(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_Prospects_EstagioProposta::getSuspendedStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("estagioproposta in ($openstatus) and atribuidoa = '$user_id'");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	
	public static function countClosed(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$status = Crm_Model_Prospects_EstagioProposta::getCloseStatus();
		$openstatus = implode(',', $status);
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("estagioproposta in ($openstatus) and atribuidoa = '$user_id'");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	
	public static function countPendent(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_Prospects_EstagioProposta::getPendentStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll("estagioproposta in ($openstatus) and atribuidoa = '$user_id'");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	
	
	public static function countGeral(){
		$db = new Crm_Model_Prospects_Basico();
		$dados = $db->fetchAll();
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	}
	
	
}