<?php 
class Sistema_Form_Empresas extends System_Form_Formdecorator{
	
	
	
	public function init()
	{
	
	}
	
	public function basicos(){
		
		$this->setName('novaempresa');
		$this->setAttrib('class' , 'form');
		$this->setDisableLoadDefaultDecorators(true);
		$this->setMethod('post');
		 
			 
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$id_registro->setRequired(false);
		$fields[] =  $id_registro;
		 
		
		$tiporegistro = new Zend_Form_Element_Select("tiporegistro");
		$tiporegistro->setLabel('Tipo')
		->setAttrib("class", "required");
		$tiporegistro->setMultiOptions(array(''=>'- Selecione- ','1'=>'Matriz','2'=>'Filial'));
		$tiporegistro->setRequired(true)->addValidator('NotEmpty', true);
		
		$fields[] =  $tiporegistro;
		 
		$razaosocial = new Zend_Form_Element_Text('razaosocial');
		$razaosocial->setLabel("Razão Social")
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $razaosocial;
		 
		$cnpj= new Zend_Form_Element_Text('cnpj');
		$cnpj->setLabel(utf8_encode('CNPJ:'))
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado")->setAttrib("class", "required");
		$fields[] =  $cnpj;
		 
		$nomefantasia= new Zend_Form_Element_Text('nomefantasia');
		$nomefantasia->setLabel(utf8_encode('Nome Fantasia'))
		->setRequired(true)
		->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $nomefantasia;
		 
		 
		 
		$inscestadual= new Zend_Form_Element_Text('inscestadual');
		$inscestadual->setLabel("Inscrição Estadual")
		->setRequired(true)
		->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $inscestadual;
		 
		$inscmunicipal= new Zend_Form_Element_Text('inscmunicipal');
		$inscmunicipal->setLabel("Inscrição Municipal")
		->setRequired(true)
		->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $inscmunicipal;
		 
		$cnae= new Zend_Form_Element_Text('cnae');
		$cnae->setLabel(utf8_encode('CNAE'))
		->setRequired(true)
		->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $cnae;
		 
		$regimetributario = new Zend_Form_Element_Select("regimetributario");
		$regimetributario->setLabel("Regime Tributário");
		$regimetributario->setMultiOptions(array(''=>'- Selecione- ','1'=>'Simples Nacional','2'=>'Simples (Excesso Sublimite)','3'=>'Regime Normal'));
		$regimetributario->setRequired(true)->addValidator('NotEmpty', true)->setAttrib("class", "required");
		$fields[] =  $regimetributario;
		 
		 
		$email= new Zend_Form_Element_Text('email');
		$email->setLabel(utf8_encode('E-mail'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $email;
		 
		$website = new Zend_Form_Element_Text('website');
		$website->setLabel(utf8_encode('WebSite'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $website;
		 
		$telefone= new Zend_Form_Element_Text('telefone');
		$telefone->setLabel(utf8_encode('Telefone(s)'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $telefone;
		 
		$fax= new Zend_Form_Element_Text('fax');
		$fax->setLabel(utf8_encode('Fax'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $fax;
		 
		$cep = new Zend_Form_Element_Text('cep');
		$cep->setLabel(utf8_encode('CEP'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $cep;
		 
		$endereco = new Zend_Form_Element_Text('endereco');
		$endereco->setLabel("Endereço")
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $endereco;
		 
		$numeroend = new Zend_Form_Element_Text('numeroend');
		$numeroend->setLabel("Número")
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $numeroend;
		 
		$complementoend = new Zend_Form_Element_Text('complementoend');
		$complementoend->setLabel(utf8_encode('Complemento'))
		->setRequired(true)
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $complementoend;
		
		 
		$bairroend = new Zend_Form_Element_Text('bairroend');
		$bairroend->setLabel(utf8_encode('Bairro'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $bairroend;
		 
		$cidadeend = new Zend_Form_Element_Text('cidadeend');
		$cidadeend->setLabel(utf8_encode('Cidade'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $cidadeend;
		 
		$estadoend = new Zend_Form_Element_Text('estadoend');
		$estadoend->setLabel(utf8_encode('Estado'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $estadoend;
		 
		$codmun = new Zend_Form_Element_Text('codmun');
		$codmun->setLabel(utf8_encode('Cod Mun'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $codmun;
		
		$coduf = new Zend_Form_Element_Text('coduf');
		$coduf->setLabel(utf8_encode('Cod UF'))
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $coduf;
		
		$codpais = new Zend_Form_Element_Text('codpais');
		$codpais->setLabel('Cod País')
		->setRequired(true)->setAttrib("class", "required")
		->addValidator('NotEmpty', true)
		->addErrorMessage("Nome deve ser informado");
		$fields[] =  $codpais;
		 
		
		
		$this->addElements( $fields);
		
		$this->configurandoTamanho('tiporegistro','span3');
		$this->configurandoTamanho('cnpj','span3');
		$this->configurandoTamanho('razaosocial','span3');
		$this->configurandoTamanho('inscestadual','span3');
		$this->montandoGrupo(array('tiporegistro','cnpj','razaosocial','inscestadual'), 'grupo1',array('legend'=>'PORRA'));
		$this->configurandoTamanho('nomefantasia','span3');
		$this->configurandoTamanho('inscmunicipal','span3');
		$this->configurandoTamanho('cnae','span3');
		$this->configurandoTamanho('regimetributario','span3');
		$this->montandoGrupo(array("nomefantasia",'inscmunicipal','cnae','regimetributario'), 'grupo2',array('legend'=>'PORRA'));
		
		$this->configurandoTamanho('email','span3');
		$this->configurandoTamanho('website','span3');
		$this->configurandoTamanho('telefone','span3');
		$this->configurandoTamanho('fax','span3');
		$this->montandoGrupo(array('email','website','telefone','fax'), 'grupo3',array('legend'=>'PORRA'));
		
		$this->configurandoTamanho('cep','span3');
		$this->configurandoTamanho('endereco','span3');
		$this->configurandoTamanho('numeroend','span3');
		$this->configurandoTamanho('complementoend','span3');
		$this->montandoGrupo(array('cep','endereco','numeroend','complementoend'), 'grupo4',array('legend'=>'PORRA'));
		
		$this->configurandoTamanho('bairroend','span3');
		$this->configurandoTamanho('cidadeend','span3');
		$this->configurandoTamanho('estadoend','span3');
		$this->configurandoTamanho('codmun','span3');
		$this->montandoGrupo(array('bairroend','cidadeend','estadoend','codmun'), 'grupo5',array('legend'=>'PORRA'));
		
		$this->configurandoTamanho('coduf','span3');
		$this->configurandoTamanho('codpais','span3');
		$this->montandoGrupo(array('coduf','codpais'), 'grupo6',array('legend'=>'PORRA'));
		
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
	
	public function nfe(){
	
	$this->setName('confignfe');
	$this->setAttrib('class' , 'form');
	$this->setDisableLoadDefaultDecorators(true);
	$this->setMethod('post');
    $this->setAction("/sistema/empresas/updatenfconfig");
	
	$id_registro = new Zend_Form_Element_Hidden('id_registro');
	$id_registro->setRequired(false);
	$fields[] =  $id_registro;
	
	$id_empresa = new Zend_Form_Element_Hidden('id_empresa');
	$id_empresa->setRequired(false);
	$fields[] =  $id_empresa;
	
	
	
	$senhacertificado = new Zend_Form_Element_Text('senhacertificado');
	$senhacertificado->setLabel(utf8_encode('Senha do Certificado'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $senhacertificado;
	 
	$frasecertificado = new Zend_Form_Element_Text('frasecertificado');
	$frasecertificado->setLabel(utf8_encode('Pass Phrase do Certificado'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $frasecertificado;
	
	$tiporegistro = new Zend_Form_Element_Select("ambienteemissao");
	$tiporegistro->setLabel('Ambiente Sefaz');
	$tiporegistro->setMultiOptions(array(''=>'- Selecione- ','1'=>'Produção','2'=>'Homologação'));
	$tiporegistro->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $tiporegistro;
	
	$versaonfe = new Zend_Form_Element_Text('versaonfe');
	$versaonfe->setLabel("Versão NFe")
	->setRequired(true)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $versaonfe;
	
	$modelonfe = new Zend_Form_Element_Text('modelonfe');
	$modelonfe->setLabel(utf8_encode('Modelo NFe'))
	->setRequired(true)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $modelonfe;
	
	$serienfe = new Zend_Form_Element_Text('serienfe');
	$serienfe->setLabel("Série")
	->setRequired(true)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $serienfe;
	
	
	$lastnfe = new Zend_Form_Element_Text('lastnfe');
	$lastnfe->setLabel("Última NFe")
	->setRequired(true)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $lastnfe;
	
	$schemes = new Zend_Form_Element_Select("schemes");
	$schemes->setLabel('Esquema Sefaz');
	$schemes->setMultiOptions(Functions_Auxilio::getNFeSchemes());
	$schemes->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $schemes;
	
	$danfepapel = new Zend_Form_Element_Select("danfepapel");
	$danfepapel->setLabel('Papel Danfe');
	$danfepapel->setMultiOptions(array(''=>'- Selecione- ','A4'=>utf8_encode('A4'),'Letter'=>utf8_encode('Letter')));
	$danfepapel->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $danfepapel;
	
	$danfeformato = new Zend_Form_Element_Select("danfeformato");
	$danfeformato->setLabel("Formato Impressão");
	$danfeformato->setMultiOptions(array(''=>'- Selecione- ','P'=>utf8_encode('Retrato'),'L'=>utf8_encode('Paisagem')));
	$danfeformato->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $danfeformato;
	
	$danfecanhoto = new Zend_Form_Element_Select("danfecanhoto");
	$danfecanhoto->setLabel(utf8_encode('Canhoto Danfe'));
	$danfecanhoto->setMultiOptions(array(''=>'- Selecione- ','1'=>utf8_encode('Imprimir'),'0'=>'Não Imprimir'));
	$danfecanhoto->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $danfecanhoto;
	
	$danfelogopos = new Zend_Form_Element_Select("danfelogopos");
	$danfelogopos->setLabel("Posição Logo");
	$danfelogopos->setMultiOptions(array(''=>'- Selecione- ','C'=>utf8_encode('Centralizado'),'L'=>utf8_encode('Esquerda'),'R'=>utf8_encode('Direita')));
	$danfelogopos->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $danfelogopos;
	
	
	$danfefonte = new Zend_Form_Element_Select("danfefonte");
	$danfefonte->setLabel("Fonte Padrão DANFE");
	$danfefonte->setMultiOptions(array(''=>'- Selecione- ','Times'=>utf8_encode('Times New Romam'),'Courier'=>utf8_encode('Courier '),'Arial'=>utf8_encode('Arial')));
	$danfefonte->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $danfefonte;
	
	
	$sendtocontabil = new Zend_Form_Element_Select("sendtocontabil");
	$sendtocontabil->setLabel(utf8_encode('Envia para o Contador'));
	$sendtocontabil->setMultiOptions(array(''=>'- Selecione- ','1'=>utf8_encode('Sim'),'0'=>'Não'));
	$sendtocontabil->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $sendtocontabil;
	
	$contabilname = new Zend_Form_Element_Text('contabilname');
	$contabilname->setLabel(utf8_encode('Nome Contador'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $contabilname;
	
	$contabilemail = new Zend_Form_Element_Text('contabilemail');
	$contabilemail->setLabel(utf8_encode('Email Contador'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $contabilemail;
	
	
	$emailhostname = new Zend_Form_Element_Text('emailhostname');
	$emailhostname->setLabel(utf8_encode('Servidor de Email'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $emailhostname;
	
	$emailsendport = new Zend_Form_Element_Text('emailsendport');
	$emailsendport->setLabel(utf8_encode('Porta Servidor'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $emailsendport;
	
	$emailusername = new Zend_Form_Element_Text('emailusername');
	$emailusername->setLabel('Usuário Email')
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $emailusername;
	
	$emailpassword = new Zend_Form_Element_Text('emailpassword');
	$emailpassword->setLabel(utf8_encode('Senha Email'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $emailpassword;
	
	$emailname = new Zend_Form_Element_Text('emailname');
	$emailname->setLabel(utf8_encode('Nome Email'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $emailname;
	
	$processreceivednfe = new Zend_Form_Element_Select("processreceivednfe");
	$processreceivednfe->setLabel(utf8_encode('Processa Recebidas'));
	$processreceivednfe->setMultiOptions(array(''=>'- Selecione- ','1'=>utf8_encode('Sim'),'0'=>'Não'));
	$processreceivednfe->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $processreceivednfe;
	
	
	
	$mailreceiveport = new Zend_Form_Element_Text('emailreceiveport');
	$mailreceiveport->setLabel(utf8_encode('Porta Entrada'))
	->setRequired(false)
	->addErrorMessage("Nome deve ser informado");
	$fields[] =  $mailreceiveport;

	$emailsend = new Zend_Form_Element_Select("emailsend");
	$emailsend->setLabel('Mensagem para Novas NFes');
	$emailsend->setMultiOptions(System_Model_MensagensSistema::renderCombo());
	$emailsend->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] = $emailsend;
	
	$emailcancel = new Zend_Form_Element_Select("emailcancel");
	$emailcancel->setLabel('Mensagem NFes Canceladas');
	$emailcancel->setMultiOptions(System_Model_MensagensSistema::renderCombo());
	$emailcancel->setRequired(true)->addValidator('NotEmpty', true)
	->setDecorators(array('Leader25'));
	$fields[] = $emailcancel;
	
	$sendemailtocliente = new Zend_Form_Element_Select("sendemailtocliente");
	$sendemailtocliente->setLabel(utf8_encode('Envia para o Cliente'));
	$sendemailtocliente->setMultiOptions(array(''=>'- Selecione- ','1'=>utf8_encode('Sim'),'0'=>'Não'));
	$sendemailtocliente->setRequired(true)->addValidator('NotEmpty', true);
	$fields[] =  $sendemailtocliente;
	
	
	$this->addElements($fields);
	
	
	
	$this->configurandoTamanho('senhacertificado','span3');
	$this->configurandoTamanho('frasecertificado','span3');
	$this->configurandoTamanho('ambienteemissao','span3');
	$this->configurandoTamanho('versaonfe','span3');
	$this->montandoGrupo(array("senhacertificado",'frasecertificado','ambienteemissao','versaonfe'), 'grupo2',array('legend'=>'PORRA'));
	
	$this->configurandoTamanho('modelonfe','span3');
	$this->configurandoTamanho('serienfe','span3');
	$this->configurandoTamanho('lastnfe','span3');
	$this->configurandoTamanho('schemes','span3');
	$this->montandoGrupo(array('modelonfe','serienfe','lastnfe','schemes'), 'grupo3',array('legend'=>'PORRA'));
	
	$this->configurandoTamanho('danfepapel','span3');
	$this->configurandoTamanho('danfeformato','span3');
	$this->configurandoTamanho('danfecanhoto','span3');
	$this->configurandoTamanho('danfelogopos','span3');
	$this->montandoGrupo(array('danfepapel','danfeformato','danfecanhoto','danfelogopos'), 'grupo4',array('legend'=>'PORRA'));
	
	$this->configurandoTamanho('danfefonte','span3');
	$this->configurandoTamanho('sendtocontabil','span3');
	$this->configurandoTamanho('contabilname','span3');
	$this->configurandoTamanho('contabilemail','span3');
	$this->montandoGrupo(array('danfefonte','sendtocontabil','contabilname','contabilemail'), 'grupo5',array('legend'=>'PORRA'));
	
	$this->configurandoTamanho('emailhostname','span3');
	$this->configurandoTamanho('emailsendport','span3');
	$this->configurandoTamanho('emailusername','span3');
	$this->configurandoTamanho('emailpassword','span3');
	$this->montandoGrupo(array('emailhostname','emailsendport','emailusername','emailpassword'), 'grupo6',array('legend'=>'PORRA'));
	
	$this->configurandoTamanho('emailname','span3');
	$this->configurandoTamanho('processreceivednfe','span3');
	$this->configurandoTamanho('emailreceiveport','span3');
	$this->configurandoTamanho('sendemailtocliente','span3');
	$this->montandoGrupo(array('emailname','processreceivednfe','emailreceiveport','sendemailtocliente'), 'grupo7',array('legend'=>'PORRA'));
	
	$this->configurandoTamanho('emailsend','span3');
	$this->configurandoTamanho('emailcancel','span3');
	$this->montandoGrupo(array('emailsend','emailcancel'), 'grupo8',array('legend'=>'PORRA'));
	
	$submit = new Zend_Form_Element_Submit('submitnfe');
	$submit->setLabel('Salvar')
	->setAttrib('class', 'btn btn-large btn-primary')
	->setIgnore(true);
	$this->addElement($submit);
	
	$limpar = new Zend_Form_Element_Reset('limparnfe');
	$limpar->setLabel('Limpar')
	->setAttrib('class', 'btn btn-large btn-warning')
	->setIgnore(true);
	$this->addElement($limpar);
	
	
	//os botões por padrão ficarão sempre centralizados desde que façam parte do grupo botoes
	$this->montandoGrupo(array('limparnfe','submitnfe'), 'botoes');
}
	
}