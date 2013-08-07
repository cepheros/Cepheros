<?php
class Crm_Form_OrdemServicos extends System_Form_Formdecorator{



	public function init()
	{

	}

	public function novo(){
		//Functions_Tickets::gerenateProtocol();
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		$id_cliente = new Zend_Form_Element_Hidden('id_cliente');
		$id_cliente->setRequired(true);
		$this->addElement($id_cliente);
					
		$cod_os = new Zend_Form_Element_Text('cod_os');
		$cod_os->setLabel('Código OS: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome')
		->setAttrib('class', 'required span12')
		->setAttrib('readonly', 'readonly')
		->setAttrib("placeholder","Informe o nome")
		->addErrorMessage("Informe a Razão Social");
		$this->addElement($cod_os);

		$nome_cliente = new Zend_Form_Element_Text('nome_cliente');
		$nome_cliente->setLabel('Cliente: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome')
		->setAttrib('class', 'required span12')
		->setAttrib("placeholder","Informe o nome")
		->addErrorMessage("Informe a Razão Social");
		$this->addElement($nome_cliente);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('nome_cliente','span11');
		//definindo o grupo que o elemento pertencerá
		
		$contato_cliente = new Zend_Form_Element_Select("contato_cliente");
		$contato_cliente->setLabel('Contato:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pessoa")
		->setRequired(true)
		->setRegisterInArrayValidator(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('0'=>"Selecione o Cliente"));
		$this->addElement($contato_cliente);
		$this->configurandoTamanho('contato_cliente','span3');
		
		
		$id_empresa = new Zend_Form_Element_Select("id_empresa");
		$id_empresa->setLabel('Empresa:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pessoa")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Empresas::renderCombo());
		$this->addElement($id_empresa);
		$this->configurandoTamanho('id_empresa','span3');
		
		
		
		$status_os = new Zend_Form_Element_Select("status_os");
		$status_os->setLabel('Status:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pessoa")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Crm_Model_Os_Status::renderCombo());
		$this->addElement($status_os);
		$this->configurandoTamanho('status_os','span3');
		
		
		$tipo_os = new Zend_Form_Element_Select("tipo_os");
		$tipo_os->setLabel('Tipo:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pessoa")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Crm_Model_Os_Tipos::renderCombo());
		$this->addElement($tipo_os);
		$this->configurandoTamanho('tipo_os','span3');
		
		
		$relato_cliente = new Zend_Form_Element_Textarea('relato_cliente');
		$relato_cliente->setLabel("Relato do Cliente:")
		->setRequired(true)
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($relato_cliente);
		
		
		$opcoes_os= new Zend_Form_Element_MultiCheckbox("opcoes_os[]");
		$opcoes_os->setLabel("Opções da OS:")
		->addMultiOption('SendMail',' Enviar Email')
		->addMultiOption('SendSMS',' Enviar SMS')
		->addMultiOption('ClientCheck',' Cliente pode Acompanhar')		
		->setSeparator(" ");
		$this->addElement($opcoes_os);
					
	}
	
	public function abrirbasicos($idos){
			$db = new Crm_Model_Os_Basicos();
			$dataos = $db->fetchRow("id_registro = '$idos'");
		
			$id_registro = new Zend_Form_Element_Hidden('id_registro');
			$this->addElement($id_registro);
			$id_cliente = new Zend_Form_Element_Hidden('id_cliente');
			$id_cliente->setRequired(true);
			$this->addElement($id_cliente);
				
			$cod_os = new Zend_Form_Element_Text('cod_os');
			$cod_os->setLabel('Código OS: *')
			->setRequired(true)
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty')
			->setAttrib('title', 'Informe o nome')
			->setAttrib('class', 'required span12')
			->setAttrib('disabled', 'disabled')
			->setAttrib("placeholder","Informe o nome")
			->addErrorMessage("Informe a Razão Social");
			$this->addElement($cod_os);
		
			$nome_cliente = new Zend_Form_Element_Text('nome_cliente');
			$nome_cliente->setLabel('Cliente: *')
			->setRequired(true)
			->setAttrib('disabled', 'disabled')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty')
			->setAttrib('title', 'Informe o nome')
			->setAttrib('class', 'required span12')
			->setAttrib("placeholder","Informe o nome")
			->addErrorMessage("Informe a Razão Social");
			$this->addElement($nome_cliente);
			//definindo a posição do elemento no formulário
			$this->configurandoTamanho('nome_cliente','span11');
			//definindo o grupo que o elemento pertencerá
		
			$contato_cliente = new Zend_Form_Element_Text('contato_cliente');
			$contato_cliente->setLabel('Contato: *')
			->setRequired(true)
			->setAttrib('disabled', 'disabled')
			->addFilter('StripTags')
			->addFilter('StringTrim')
			->addValidator('NotEmpty')
			->setAttrib('title', 'Informe o nome')
			->setAttrib('class', 'required span12')
			->setAttrib("placeholder","Informe o nome")
			->addErrorMessage("Informe a Razão Social");
			$this->addElement($contato_cliente);
		
		
			$id_empresa = new Zend_Form_Element_Select("id_empresa");
			$id_empresa->setLabel('Empresa:')->setRequired(true)
			->addErrorMessage("Informe o tipo de pessoa")
			->setRequired(true)
			->setAttrib('disabled', 'disabled')
			->addValidator("NotEmpty")
			->setAttrib('class', 'required')
			->setMultiOptions(System_Model_Empresas::renderCombo());
			$this->addElement($id_empresa);
			$this->configurandoTamanho('id_empresa','span3');
		
		
		
			$status_os = new Zend_Form_Element_Select("status_os");
			$status_os->setLabel('Status:')->setRequired(true)
			->addErrorMessage("Informe o tipo de pessoa")
			->setRequired(true)
			->addValidator("NotEmpty")
			->setAttrib('class', 'required')
			->setMultiOptions(Crm_Model_Os_Status::renderCombo());
			$this->addElement($status_os);
			$this->configurandoTamanho('status_os','span3');
		
		
			$tipo_os = new Zend_Form_Element_Select("tipo_os");
			$tipo_os->setLabel('Tipo:')->setRequired(true)
			->addErrorMessage("Informe o tipo de pessoa")
			->setRequired(true)
			->addValidator("NotEmpty")
			->setAttrib('class', 'required')
			->setMultiOptions(Crm_Model_Os_Tipos::renderCombo());
			$this->addElement($tipo_os);
			$this->configurandoTamanho('tipo_os','span3');
		
		
			$relato_cliente = new Zend_Form_Element_Textarea('relato_cliente');
			$relato_cliente->setLabel("Relato do Cliente:")
			->setRequired(true)
			->setAttrib('disabled', 'disabled')
			->setAttrib('rows', '5')
			->setAttrib("style", "width:100%")
			->addFilter('StripTags');
			$this->addElement($relato_cliente);
			
			$relato_cliente = new Zend_Form_Element_Textarea('relato_tecnico');
			$relato_cliente->setLabel("Relato Técnico:")
			->setRequired(true)
			->setAttrib('rows', '5')
			->setAttrib("style", "width:100%")
			->addFilter('StripTags');
			$this->addElement($relato_cliente);
		
		
			$opcoes_os= new Zend_Form_Element_MultiCheckbox("opcoes_os[]");
			$opcoes_os->setLabel("Opções da OS:")
			->addMultiOption('SendMail',' Enviar Email')
			->addMultiOption('SendSMS',' Enviar SMS')
			->addMultiOption('ClientCheck',' Cliente pode Acompanhar')
			->setSeparator(" ");
			$this->addElement($opcoes_os);
		
	}

}