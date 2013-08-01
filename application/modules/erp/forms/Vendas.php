<?php
class Erp_Form_Vendas extends System_Form_Formdecorator{



	public function init()
	{
		
	}
	
	public function basicos(){
		$this->setName('VendasBasicos');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa');
		$id_pessoa->setRequired(true);
		$this->addElement($id_pessoa);
		
		$nomepessoa = new Zend_Form_Element_Text('nomepessoa');
		$nomepessoa->setLabel('Cliente: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Selecione o cliente para a venda')
		->setAttrib('class', 'span12 required ttip_b')
		->setAttrib("placeholder","Procure por Razao Social (Nome), id, CNPJ/CPF ou Nome Fantasia (Apelido) ")
		->addErrorMessage("Você deve selecionar um cliente para incluir uma venda");
		$this->addElement($nomepessoa);
		
		$id_vendedor = new Zend_Form_Element_Hidden('id_vendedor');
		$this->addElement($id_vendedor);
		
		$nomevendedor = new Zend_Form_Element_Text('nomevendedor');
		$nomevendedor->setLabel('Vendedor:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome do vendedor ')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","Procure por Razao Social (Nome), id, CNPJ/CPF ou Nome Fantasia (Apelido) ");
		$this->addElement($nomevendedor);
		
		$comissao = new Zend_Form_Element_Text('comissao');
		$comissao->setLabel('Comissao:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Comissao Geral')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","0,00");
		$this->addElement($comissao);
		
		
		$pedido_cliente = new Zend_Form_Element_Text('pedido_cliente');
		$pedido_cliente->setLabel('Pedido do Cliente:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o numero do pedido do cliente se houver')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","######");
		$this->addElement($pedido_cliente);
		
		
		$datainspecao = new Zend_Form_Element_Text('datainspecao');
		$datainspecao->setLabel('Data de Inspeção:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe uma data de inspeção se houver')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","##/##/####");
		$this->addElement($datainspecao);
		
		
		$entrega_de = new Zend_Form_Element_Text('entrega_de');
		$entrega_de->setLabel('Periodo inicial de Entrega:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe uma data inicial para o periodo de entrega')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","##/##/####");
		$this->addElement($entrega_de);
		
		$entrega_ate = new Zend_Form_Element_Text('entrega_ate');
		$entrega_ate->setLabel('Periodo Final de Entrega:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe uma data final para o periodo de entrega')
		->setAttrib('class', 'span12 ttip_b')
		->setAttrib("placeholder","##/##/####");
		$this->addElement($entrega_ate);
		
		
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
		
		$tipo_inspecao = new Zend_Form_Element_Select("tipo_inspecao");
		$tipo_inspecao->setLabel('Tipo de Inspeção:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pedudido")
		->setRequired(false)
		->setMultiOptions(Erp_Model_Vendas_TiposInspecao::renderCombo());
		$this->addElement($tipo_inspecao);
		
		
		$perfilfaturamento = new Zend_Form_Element_Select("perfilfaturamento");
		$perfilfaturamento->setLabel('Perfil Faturamento:')->setRequired(true)
		->addErrorMessage("Informe o perfil do faturamento")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Faturamento_Perfil::getCombo());
		$this->addElement($perfilfaturamento);
		
	}
	
}