<?php
class Cadastros_Form_Servicos extends System_Form_Formdecorator{



	public function init()
	{

	}
	
	public function novo(){
		
		$this->setName('clientes');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$tiposervico = new Zend_Form_Element_Select("tiposervico");
		$tiposervico->setLabel('Tipo de Serviço:')->setRequired(true)
		->addErrorMessage("Informe o Tipo de Servico")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_TipoServicos::renderCombo());
		$this->addElement($tiposervico);
		$this->configurandoTamanho('tiposerevico','span3');
		
		$nomeservico = new Zend_Form_Element_Text('nomeservico');
		$nomeservico->setLabel('Nome: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe o nome")
		->addErrorMessage("Informe um nome para o serviço");
		$this->addElement($nomeservico);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('nomeservico','span8');
		
		
		$valorservico = new Zend_Form_Element_Text('valorservico');
		$valorservico->setLabel('Valor:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o valor so serviço');
		$this->addElement($valorservico);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('valorservico','span4');
		
		
		$iss = new Zend_Form_Element_Select("iss");
		$iss->setLabel('ISS:')->setRequired(true)
		->addErrorMessage("Informe o Código ou nome do ISS")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Iss::renderCombo());
		$this->addElement($iss);
		$this->configurandoTamanho('iss','span3');
		
		
		
		
	}
	
}