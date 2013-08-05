<?php
class Crm_Model_TicketsDeptoMessages extends Zend_Db_Table_Abstract{
	protected $_name = 'tbltickets_messages_deptos';
	protected $_primary = 'id_registro';



	public static function getMessages($id){
		$db = new Crm_Model_TicketsDeptoMessages();
		$dados = $db->fetchRow("id_depto = '$id'");
		return $dados;
	}








}