
<?php
class System_Model_Iss extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_iss';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descricaoiss'];
		}
		return $rdepto;

	}

	public static function getRegistroPadrao(){
		$db = new System_Model_Iss();
		$dados = $db->fetchRow("isdefault = '1' ");
		if($dados){
		return $dados->id_registro;
		}else{
			return '0';
		}
	}

	public static function getNomeCategoria($id){
		$db = new System_Model_Iss();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->descricao;
	}


	public static function renderCombo(){
		$db = new System_Model_Iss();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['descricaoiss'];
		}
		return $rdepto;

	}



}