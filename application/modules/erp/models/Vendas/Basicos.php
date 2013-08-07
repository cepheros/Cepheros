<?php
class Erp_Model_Vendas_Basicos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblvendas_basicos';
	protected $_primary = 'id_registro';

	
	
	/**
	 * getPedNumber
	 * Retorna o ultimo registro da tabela de pedidos acrescido de 1
	 * @return number id do proximo pedido
	 */
	
	public static function getPedNumber(){
		$db = new Erp_Model_Vendas_Basicos();
		$data = $db->fetchRow('id_registro > 0',"id_registro DESC","0,1");
		$esteid  = $data->id_registro;
		$proximo = $esteid + 1;
		return $proximo;
	}
	

	/**
	 * getLastPedidos
	 * Retorna um array de dados com os ultimos pedidos incluídos no sistema
	 * @param number $qtd quantidade de registros
	 * @return array 
	 */
	
	public static function getLastPedidos($qtd = 10){
		$dados = new Erp_Model_Vendas_Basicos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblvendas_basicos'),array('a.id_registro','a.agendamento_entrega','a.liberaprod', 'a.pedido_cliente','a.alteracoes','b.descritivo','c.razaosocial','c.cnpj'))
		->join(array('b'=>'tblapoio_tipodepedido'),'b.id_registro = a.tipo_pedido',array())
		->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())
		->order("id_registro DESC")
		->limit("$qtd");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		
		return $dados;
		
	}
	
}