
<?php
class System_Model_TipoServicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblapoio_tiposdeservicos';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nome'];
		}
		return $rdepto;

	}

	public static function getRegistroPadrao(){
		$db = new System_Model_TipoServicos();
		$dados = $db->fetchRow("isdefault = '1' ");
		return $dados->id_registro;
	}

	public static function getNomeCategoria($id){
		$db = new System_Model_TipoServicos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->descricao;
	}


	public static function renderCombo(){
		$db = new System_Model_TipoServicos();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nome'];
		}
		return $rdepto;

	}



}