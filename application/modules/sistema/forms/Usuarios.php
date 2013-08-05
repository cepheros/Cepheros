<?php
class Sistema_Form_Usuarios extends System_Form_Formdecorator{



	public function init()
	{

	}
	
	public function cadastro(){
		$this->setName('novousuario');
		$this->setAttrib('class' , 'form');
		$this->setDisableLoadDefaultDecorators(true);
		$this->setMethod('post');
			
		
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$id_registro->setRequired(false);
		$fields[] =  $id_registro;
		
		$nomecompleto = new Zend_Form_Element_Text('nomecompleto');
		$nomecompleto->setLabel("Nome Completo")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $nomecompleto;
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel("Email")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $email;
		
		$phonenumber = new Zend_Form_Element_Text('phonenumber');
		$phonenumber->setLabel("Celular:")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $phonenumber;
		
			
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel("Nome de Usuário")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required ");
		$fields[] =  $username;
		
		
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel("Senha")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required ");
		$fields[] =  $password;
		
		$password2 = new Zend_Form_Element_Password('password2');
		$password2->setLabel("Repita a Senha")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required ");
		$fields[] =  $password2;
		
	
		
		
		$id_role  = new Zend_Form_Element_Select("id_role");
		$id_role->setLabel('Tipo Usuário')
		->setAttrib("class", "required");
		$id_role->setMultiOptions(System_Model_Login_Roles::renderCombo());
		$id_role->setRequired(true)->addValidator('NotEmpty', true);
		$fields[] =  $id_role;
		
		
		$departamentos = new Zend_Form_Element_Multiselect("departamentos");
		$departamentos->setLabel('Departamentos:')
		->setAttrib("class", "required");
		$departamentos->setMultiOptions(Crm_Model_TicketsDeptos::renderCombo());
		$departamentos->setRequired(true)->addValidator('NotEmpty', true);
		$fields[] =  $departamentos;
		
		
		
		$superadmin = new Zend_Form_Element_Select("superadmin");
		$superadmin->setLabel('Super Admin?')
		->setAttrib("class", "required");
		$superadmin->setMultiOptions(array(''=>'- Selecione- ','1'=>'Sim','0'=>'Não'));
		$superadmin->setRequired(true)->addValidator('NotEmpty', true);
		$fields[] =  $superadmin;
		
		
		$this->addElements( $fields);
		
		$signature = new Zend_Form_Element_Textarea('signature');
		$signature->setLabel('Assinatura')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($signature);
		$this->configurandoTamanho('signature','span11');
		
		
		$this->configurandoTamanho('nomecompleto','span3');
		$this->configurandoTamanho('email','span3');
		$this->configurandoTamanho('phonenumber','span3');
		$this->configurandoTamanho('username','span3');
	    $this->montandoGrupo(array('nomecompleto','email','phonenumber','username'), 'grupo1',array('legend'=>'PORRA'));
		$this->configurandoTamanho('password','span2');
		$this->configurandoTamanho('password2','span2');
		$this->configurandoTamanho('superadmin','span2');
		$this->configurandoTamanho('departamentos','span2');
		$this->configurandoTamanho('id_role','span3');
		$this->montandoGrupo(array("password",'password2','id_role','superadmin','departamentos'), 'grupo2',array('legend'=>'PORRA'));
		
		$this->montandoGrupo(array("signature"), 'grupo4',array('legend'=>'PORRA'));
		
		
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Salvar')
		->setAttrib('class', 'btn btn-large btn-primary')
		->setIgnore(true);
		$this->addElement($submit);
		
		$limpar = new Zend_Form_Element_Reset('limpar');
		$limpar->setLabel('Limpar')
		->setAttrib('class', 'btn btn-large btn-warning')
		->setIgnore(true);
		$this->addElement($limpar);
				
		//os botões por padrão ficarão sempre centralizados desde que façam parte do grupo botoes
		$this->montandoGrupo(array('limpar','submit'), 'botoes');
		
	}
	
	
	
	public function profile(){
		$this->setName('novousuario');
		$this->setAttrib('class' , 'form');
		$this->setDisableLoadDefaultDecorators(true);
		$this->setMethod('post');
			
	
	
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$id_registro->setRequired(false);
		$fields[] =  $id_registro;
	
		$nomecompleto = new Zend_Form_Element_Text('nomecompleto');
		$nomecompleto->setLabel("Nome Completo")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $nomecompleto;
	
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel("Email")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $email;
	
		$phonenumber = new Zend_Form_Element_Text('phonenumber');
		$phonenumber->setLabel("Celular:")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $phonenumber;
	
			
		$username = new Zend_Form_Element_Text('username');
		$username->setLabel("Nome de Usuário")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required ")->setAttrib("readonly", "readonly ");
		$fields[] =  $username;
	
	
		$password = new Zend_Form_Element_Password('password');
		$password->setLabel("Senha")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required ");
		$fields[] =  $password;
	
		$password2 = new Zend_Form_Element_Password('password2');
		$password2->setLabel("Repita a Senha")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required ");
		$fields[] =  $password2;
	
	
				
		$this->addElements( $fields);
	
		$signature = new Zend_Form_Element_Textarea('signature');
		$signature->setLabel('Assinatura')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($signature);
		$this->configurandoTamanho('signature','span11');
	
	
		$this->configurandoTamanho('nomecompleto','span3');
		$this->configurandoTamanho('email','span3');
		$this->configurandoTamanho('phonenumber','span3');
		$this->configurandoTamanho('username','span3');
		$this->montandoGrupo(array('nomecompleto','email','phonenumber','username'), 'grupo1',array('legend'=>'PORRA'));
		$this->configurandoTamanho('password','span3');
		$this->configurandoTamanho('password2','span3');
	
		$this->montandoGrupo(array("password",'password2'), 'grupo2',array('legend'=>'PORRA'));
	
		$this->montandoGrupo(array("signature"), 'grupo4',array('legend'=>'PORRA'));
	
	
		$submit = new Zend_Form_Element_Submit('submit');
		$submit->setLabel('Salvar')
		->setAttrib('class', 'btn btn-large btn-primary')
		->setIgnore(true);
		$this->addElement($submit);
	
		$limpar = new Zend_Form_Element_Reset('limpar');
		$limpar->setLabel('Limpar')
		->setAttrib('class', 'btn btn-large btn-warning')
		->setIgnore(true);
		$this->addElement($limpar);
	
		//os botões por padrão ficarão sempre centralizados desde que façam parte do grupo botoes
		$this->montandoGrupo(array('limpar','submit'), 'botoes');
	
	}
	
}