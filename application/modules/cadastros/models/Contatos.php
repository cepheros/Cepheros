<?php
class Cadastros_Model_Contatos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblpessoas_contatos';
	protected $_primary = 'id_registro';
	

	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomecontato'];
		}
		return $rdepto;

	}

	
	public static function getNomeContato($id){
		$db = new Cadastros_Model_Contatos();
		$dados = $db->fetchRow("id_registro = '$id'");
		if($dados){
			return $dados->nomecontato;
		}else{
			return "Nao Localizado"; 
		}
	}


	public static function renderCombo($tipo = false,$id_cliente = false){
		$db = new Cadastros_Model_Contatos();
		if(!$tipo){
		$dados = $db->fetchAll()->toArray();
		}elseif($tipo){
			$dados = $db->fetchAll("tipocontato = '$tipo'")->toArray();
		}elseif($id_cliente){
			$dados = $db->fetchAll("id_pessoa = '$id_cliente'")->toArray();
		}
		
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomecontato'];
		}
		return $rdepto;

	}
	
	
	public static function getCadastro($id){
		$db = new Cadastros_Model_Contatos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		return $dados;
		
	}
	
	public static function getCadastroCel($id){
		$db = new Cadastros_Model_Contatos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$cel = $db->fetchRow("nomecontato = '{$dados['nomecontato']}' and tipocontato = '4'")->toArray();
		return $cel;
		
	}
	
	public static function getContato($id){
		
		$db = new Cadastros_Model_Contatos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
		
		
		
	}
	
	



}
