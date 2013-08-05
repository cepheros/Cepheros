<?php

class Cadastros_Model_Produtos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblprodutos_basicos';
	protected $_primary = 'id_registro';


	public function getCombo(){
		$dados = $this->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeproduto'];
		}
		return $rdepto;

	}


	public static function getNomeProduto($id){
		$db = new Cadastros_Model_Produtos();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados->nomeproduto;
	}


	public static function renderCombo($tipo = null){
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
	
	public static function getProdutoCodNFe($cod){
		$db = new Cadastros_Model_ProdutosVinculos();
		$dados = $db->fetchRow("codvinculado = '$cod'");
		return $dados;
	
	}
	
	public static function countProdutosAlerta(){
		$db = new Cadastros_Model_Produtos();
		$data = $db->getAdapter()->query("SELECT
sum(quantidade) AS estoqueatual,
tblestoque_movimentos.id_produto,
tblprodutos_basicos.nomeproduto,
tblprodutos_basicos.referenciaproduto,
tblprodutos_basicos.codigointerno,
tblprodutos_basicos.pesoproduto,
tblprodutos_basicos.contaestoque,
tblprodutos_basicos.estoqueminimo,
tblprodutos_basicos.estoquemaximo,
tblprodutos_basicos.avisarestoque,
tblprodutos_basicos.orcarautomatico,
tblapoio_categoriasprodutos.nomecategoria,
tblapoio_subcategoriadeprodutos.nomesubcategoria,
tblapoio_unidadesdemedida.abreviacao AS unidademedida
FROM
tblestoque_movimentos
INNER JOIN tblprodutos_basicos ON tblestoque_movimentos.id_produto = tblprodutos_basicos.id_registro
INNER JOIN tblapoio_categoriasprodutos ON tblprodutos_basicos.categoriaproduto = tblapoio_categoriasprodutos.id_registro
INNER JOIN tblapoio_subcategoriadeprodutos ON tblprodutos_basicos.subcategoriaproduto = tblapoio_subcategoriadeprodutos.id_registro
INNER JOIN tblapoio_unidadesdemedida ON tblprodutos_basicos.unidadedemedida = tblapoio_unidadesdemedida.id_registro
WHERE
(SELECT sum(quantidade) FROM tblestoque_movimentos WHERE id_produto = tblprodutos_basicos.id_registro) < tblprodutos_basicos.estoqueminimo 
AND contaestoque = '1'
GROUP BY tblestoque_movimentos.id_produto
				");
		
		$rows = $data->fetchAll();
		$contador = count($rows);
		return $contador;
		
	}
	
	
	public static function getProdutosAlerta(){
		$db = new Cadastros_Model_Produtos();
		$data = $db->getAdapter()->query("SELECT
sum(quantidade) AS estoqueatual,
tblestoque_movimentos.id_produto,
tblprodutos_basicos.nomeproduto,
tblprodutos_basicos.referenciaproduto,
tblprodutos_basicos.codigointerno,
tblprodutos_basicos.pesoproduto,
tblprodutos_basicos.contaestoque,
tblprodutos_basicos.estoqueminimo,
tblprodutos_basicos.estoquemaximo,
tblprodutos_basicos.avisarestoque,
tblprodutos_basicos.orcarautomatico,
tblapoio_categoriasprodutos.nomecategoria,
tblapoio_subcategoriadeprodutos.nomesubcategoria,
tblapoio_unidadesdemedida.abreviacao AS unidademedida
FROM
tblestoque_movimentos
INNER JOIN tblprodutos_basicos ON tblestoque_movimentos.id_produto = tblprodutos_basicos.id_registro
INNER JOIN tblapoio_categoriasprodutos ON tblprodutos_basicos.categoriaproduto = tblapoio_categoriasprodutos.id_registro
INNER JOIN tblapoio_subcategoriadeprodutos ON tblprodutos_basicos.subcategoriaproduto = tblapoio_subcategoriadeprodutos.id_registro
INNER JOIN tblapoio_unidadesdemedida ON tblprodutos_basicos.unidadedemedida = tblapoio_unidadesdemedida.id_registro
WHERE
(SELECT sum(quantidade) FROM tblestoque_movimentos WHERE id_produto = tblprodutos_basicos.id_registro) < tblprodutos_basicos.estoqueminimo
AND contaestoque = '1'
GROUP BY tblestoque_movimentos.id_produto
				");
	
		$rows = $data->fetchAll();
		
		return $rows;
	
	}





}
