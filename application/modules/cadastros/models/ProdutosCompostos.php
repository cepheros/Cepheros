<?php

class Cadastros_Model_ProdutosCompostos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblprodutos_compostos';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeproduto'];
		}
		return $rdepto;

	}
	
	
	
	public static function countCompostos($produto){
		$db = new Cadastros_Model_ProdutosCompostos;
		$dados = $db->fetchAll("id_produto = '$produto'")->toArray();
		if($dados){
			$contagem  = count($dados);
			return $contagem;
		}else{
			return "0";
		}
		
	}


	public static function getNomeProduto($id){
		$db = new Cadastros_Model_Produtos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomeproduto;
	}


	public static function renderCombo(){
		$db = new Cadastros_Model_Produtos();
		if(!$tipo){
			$dados = $db->fetchAll()->toArray();
		}else{
			$dados = $db->fetchAll("tipocadastro = '$tipo'")->toArray();
		}
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeproduto'];
		}
		return $rdepto;

	}


	public static function getProduto($id){
		$db = new Cadastros_Model_Produtos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		return $dados;

	}





}
