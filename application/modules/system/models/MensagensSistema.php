<?php
class System_Model_MensagensSistema extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_mensagens';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomemensagem'];
		}
		return $rdepto;

	}

	

	public static function getNomeMensagem($id){
		$db = new System_Model_MensagensSistema();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomemensagem;
	}
	
	
	public static function getMensagem($id){
		$db = new System_Model_MensagensSistema();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	}
	
	


	public static function renderCombo(){
		$db = new System_Model_MensagensSistema();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomemensagem'];
		}
		return $rdepto;

	}
	
	public static function renderALL(){
		$db = new System_Model_MensagensSistema();
		$dados = $db->fetchAll()->toArray();
		return $dados;
		
	}



}
