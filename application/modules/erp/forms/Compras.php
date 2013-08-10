<?php
class Erp_Form_Compras extends System_Form_Formdecorator{



	public function init()
	{
		
	}
	
	public function basicos(){
		$this->setName('ComprasBasicos');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa');
		$id_pessoa->setRequired(true);
		$this->addElement($id_pessoa);
		
		$nomepessoa = new Zend_Form_Element_Text('nomepessoa');
		$nomepessoa->setLabel('Fornecedor: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Selecione o fornecedor para a compra')
		->setAttrib('class', 'span12 required ttip_b')
		->setAttrib("placeholder","Procure por Razao Social (Nome), id, CNPJ/CPF ou Nome Fantasia (Apelido) ")
		->addErrorMessage("Você deve selecionar um cliente para incluir uma compra");
		$this->addElement($nomepessoa);
	
	
		
		
		$agendamento_entrega = new Zend_Form_Element_Text('agendamento_entrega');
		$agendamento_entrega->setLabel('Data Agendamento:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe uma data de agendamento')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","DD/MM/AAAA");
		$this->addElement($agendamento_entrega);
		
		$agendamento_entrega_hora = new Zend_Form_Element_Text('agendamento_entrega_hora');
		$agendamento_entrega_hora->setLabel('Hora Agendamento:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe uma hora de agendamento')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","HH:MM:SS");
		$this->addElement($agendamento_entrega_hora);
		
		
		$obspedido = new Zend_Form_Element_Textarea('obspedido');
		$obspedido->setLabel("Observações do Pedido:")
		->setRequired(false)
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($obspedido);
		
		$obsfaturamento = new Zend_Form_Element_Textarea('obsfaturamento');
		$obsfaturamento->setLabel("Observações do Faturamento:")
		->setRequired(false)
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($obsfaturamento);
		
		
		$tipo_pedido = new Zend_Form_Element_Select("tipo_pedido");
		$tipo_pedido->setLabel('Tipo de Pedido:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pedudido")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Vendas_TiposPedido::renderCombo());
		$this->addElement($tipo_pedido);
		
		
		
	}
	
}