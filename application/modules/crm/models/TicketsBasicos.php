<?php
class Crm_Model_TicketsBasicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tbltickets_basicos';
	protected $_primary = 'id_registro';
	
	
	public static function checkProtocol($protocol){
		$db = new Crm_Model_TicketsBasicos();
		$data = $db->fetchRow("protocolo = '$protocol'");
		if(isset($data->id_registro)){
			return false;
		}else{
			return true;
		}
	}
	
	
	public static function getTicket($id){
		$db = new Crm_Model_TicketsBasicos();
		$data = $db->fetchRow("protocolo = '$protocol'");
		return $data;
	}
	
	
	public static function countNaoResolvidos(){
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("atribuidoa = '$user_id' and statusticket in ($openstatus)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
		
		
	}
	
	public static function countAcompanhamentos(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = Crm_Model_TicketsAcompanhantes::getAcompanhamentos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("atribuidoa = '$user_id' and statusticket in ($openstatus) and id_registro in ($acompanhados)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
		
		
	}
	
	
	public static function countDeptos(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("statusticket in ($openstatus) and departamento in ($acompanhados)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	public static function countNaoAtribuidos(){
		
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("atribuidoa = '0' and statusticket in ($openstatus) and departamento in ($acompanhados)");
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
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getOpenStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("departamento in ($acompanhados) and datelastreply >= '$datainicio' ");
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
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getCloseStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("statusticket in ($openstatus) and dateclosed >= '$datainicio' ");
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
		$status = Crm_Model_TicketsStatus::getSuspendedStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("statusticket in ($openstatus) and departamento in ($acompanhados)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	
	public static function countClosed(){
		$user_id = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$acompanhamentos = System_Model_UsersDeptos::getDeptos();
		$status = Crm_Model_TicketsStatus::getCloseStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("statusticket in ($openstatus) and departamento in ($acompanhados)");
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
		$status = Crm_Model_TicketsStatus::getPendentStatus();
		$openstatus = implode(',', $status);
		$acompanhados = implode(',',$acompanhamentos);
		$db = new Crm_Model_TicketsBasicos();
		$dados = $db->fetchAll("statusticket in ($openstatus) and departamento in ($acompanhados)");
		if(isset($dados)){
			return count($dados->toArray());
		}else{
			$numer = '0';
			return $numer;
		}
	
	
	}
	
	
	public static function TicketsPorCanais(){
		$db = new Crm_Model_TicketsBasicos();
		$select = $db->select()->from($db,array("count(id_registro) as total","tipoticket"))->group("tipoticket");
	
		$dados = $db->fetchAll($select)->toArray();
		return $dados;
	}
	
	public static function countTotalTickets(){
		$db = new Crm_Model_TicketsBasicos();
		$select = $db->select()->from($db,array("count(id_registro) as total"));
		$dados = $db->fetchRow($select);
		return $dados->total;
		
	}
	
	public static  function tickesLastMonth(){

		$db = new Crm_Model_TicketsBasicos();

		for($i = 30; $i < 1; $i--){
			$data = Functions_Datas::SubtraiData(date('d/m/Y'),$i);
			$dataseletc = Functions_Datas::inverteData($data);
			$select = $db->select()->from($db,array("count(id_registro) as total"))->where("dateopen >= '$dataseletc 00:00:00' and dateopen <= '$dataseletc 23:59:59'");
			$dados = $db->fetchRow($select);
			if(isset($dados->total)){
				$quantidade = $dados->total;
			}else{
				$quantidade = '0';
			}
			$retorno[$data] = $quantidade;		
		}
		
	
		
		return $retorno;
		
	}
	
	
	public static function TicketsPorStaff(){
		$db = new Crm_Model_TicketsBasicos();
		$select = $db->select()->from($db,array("count(id_registro) as total","staffopen"))->where("staffopen <> 0")->group("staffopen")->order("total desc")->limit(5);
	//	echo $select;	
		$dados = $db->fetchAll($select)->toArray();
		return $dados;
	}
	
	public static function TicketsPorDepto(){
		$db = new Crm_Model_TicketsBasicos();
		$select = $db->select()->from($db,array("count(id_registro) as total","departamento"))->group("departamento");
	
		$dados = $db->fetchAll($select)->toArray();
		return $dados;
	}
	
	
	
	
	
	
	
}