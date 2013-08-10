<?php

class Cadastros_Model_Servicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblservicos_basicos';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeservico'];
		}
		return $rdepto;

	}


	public static function getNomeServico($id){
		$db = new Cadastros_Model_Servicos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomeservico;
	}


	public static function renderCombo($tipo = false){
		$db = new Cadastros_Model_Servicos();
		if(!$tipo){
			$dados = $db->fetchAll()->toArray();
		}else{
			$dados = $db->fetchAll("tipocadastro = '$tipo'")->toArray();
		}

		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeservico'];
		}
		return $rdepto;

	}


	public static function getServico($id){
		$db = new Cadastros_Model_Servicos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		return $dados;

	}





}
