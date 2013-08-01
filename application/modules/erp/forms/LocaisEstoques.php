<?php
class Erp_Form_LocaisEstoques extends System_Form_Formdecorator{



	public function init()
	{
		$this->setName('LocaisEstoques');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');

		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);

		$localestoque = new Zend_Form_Element_Text('localestoque');
		$localestoque->setLabel('Descrição: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","Informe a descrição")
		->addErrorMessage("Descrição do local de estoque deve ser informada");
		$this->addElement($localestoque);

		$informacoes = new Zend_Form_Element_Textarea('informacoes');
		$informacoes->setLabel("Observações e Informações Importantes:")
		->setRequired(false)
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($informacoes);

		$tipoestoque = new Zend_Form_Element_Select("tipoestoque");
		$tipoestoque->setLabel('Tipo:')->setRequired(true)
		->addErrorMessage("Informe o tipo de estoque")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Tiposestoques::renderCombo());
		$this->addElement($tipoestoque);


		$canmanualmove = new Zend_Form_Element_Radio("canmanualmove");
		$canmanualmove->setLabel("Movimento Manual?")
		->addMultiOption('1','Sim')
		->addMultiOption('0','Não')
		->setAttrib('class', 'required')
		->setSeparator(" ");
		$this->addElement($canmanualmove);










	}

}