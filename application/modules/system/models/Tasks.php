<?php
class System_Model_Tasks extends Zend_Db_Table_Abstract{
	protected $_name = 'tblusers_tasks';
	protected $_primary = 'id_registro';
	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['assunto'];
		}
		return $rdepto;

	}

	public static function getTask($id){
		$db = new System_Model_Tasks();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	}

	
	public static function getMyTasks($user){
		$db = new System_Model_Tasks();
		$dados = $db->fetchAll("user_to = '$user' and status <> 'Fechado' ")->toArray();
		return $dados;
		
	}
	
	public static function countMyTasks($user){
		$db = new System_Model_Tasks();
		$dados = $db->fetchAll("user_to = '$user' and status <> 'Fechado' ")->toArray();
		$qtd = count($dados);
		return $qtd;
	
	}
	



}
