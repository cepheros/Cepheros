<?php 
class Cadastros_Form_Clientes extends System_Form_Formdecorator{
	
	
	
	public function init()
	{
	
	}
	
	public function novo(){
		$this->setName('clientes');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		
		/************************
		 *  Criação dos elementos
		 *  $tp_endereco = new Zend_Form_Element_Select("tp_endereco");
		  $tp_endereco->setLabel(utf8_encode('Tipo de Endere�o'))
		              ->setMultiOptions($tiposenderecos)
		              ->addErrorMessage(utf8_encode("Informe o Tipo de Endere�o"))
		              ->setRequired(true)->addValidator('NotEmpty', true)->setDecorators(array('Leader15'));
		  $fields[] = $tp_endereco;
		*********************/
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$id_empresa = new Zend_Form_Element_Select('id_empresa');
		$id_empresa->setLabel("Empresa:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Empresas::renderCombo())
		->addErrorMessage("Selecione a Empresa responsável pelo cadastro");
		$this->addElement($id_empresa);
		$this->configurandoTamanho('id_empresa','span3');
		
		$tipopessoa = new Zend_Form_Element_Select("tipopessoa");
		$tipopessoa->setLabel('Tipo de Pessoa:')->setRequired(true)
		->addErrorMessage("Informe o tipo de pessoa")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('1' => 'Jurídica','2' => 'Física'));
		$this->addElement($tipopessoa);
		$this->configurandoTamanho('tipopessoa','span3');
		
				
		
		$nome = new Zend_Form_Element_Text('razaosocial');
		$nome->setLabel('Razão Social: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe o nome")
		->addErrorMessage("Informe a Razão Social");
		$this->addElement($nome);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('razaosocial','span3');
		//definindo o grupo que o elemento pertencerá
		
		$cpf = new Zend_Form_Element_Text('cnpj');
		$cpf->setLabel('CNPJ/CPF:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o CNPJ/CPF');
		$this->addElement($cpf);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('cpf','span3');
		//definindo o grupo que os elementos pertencerão
		
		$this->montandoGrupo(array('id_empresa','tipopessoa','razaosocial','cnpj'), 'grupo1',array('legend'=>'PORRA'));
		
		$nomefantasia = new Zend_Form_Element_Text('nomefantasia');
		$nomefantasia->setLabel('Nome Fantasia:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o sobrenome');
		$this->addElement($nomefantasia);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('nomefantasia','span3');
		
		$rg = new Zend_Form_Element_Text('inscestadual');
		$rg->setLabel('Inscrição Estadual / RG:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o rg');
		$this->addElement($rg);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('inscestadual','span3');
		
		$inscmunicipal = new Zend_Form_Element_Text('inscmunicipal');
		$inscmunicipal->setLabel('Inscrição Municipal:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o rg');
		$this->addElement($inscmunicipal);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('inscmunicipal','span3');
		
		$dataabertura = new Zend_Form_Element_Text('dataabertura');
		$dataabertura->setLabel('Data de Abertura:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o rg');
		$this->addElement($dataabertura);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('dataabertura','span3');
		
		$this->montandoGrupo(array('nomefantasia','inscestadual','inscmunicipal','dataabertura'), 'grupo2',array('legend'=>'PORRA'));
		
		
		//$this->addDisplayGroup( array('razaosocial', 'sobrenome','rg','cpf'),     'dados');
		
		
		$categoria = new Zend_Form_Element_Select('categoria');
		$categoria->setLabel("Categoria:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Categoriapessoas::renderCombo())
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($categoria);
		$this->configurandoTamanho('categoria','span3');
		
		
		$tags = new Zend_Form_Element_Text('tags');
		$tags->setLabel('Tags')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o rg')
		->setAttrib("style", "width:100%")
		->setAttrib('placeholder', 'Tags são Palavras chave para ajuda-lo a localizar o cadastro em uma busca');
		$this->addElement($tags);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('tags','span8');
		$this->montandoGrupo(array('categoria',"tags"), 'grupo3',array('legend'=>'PORRA'));
		
		$observacoes = new Zend_Form_Element_Textarea('observacoes');
		$observacoes->setLabel('Observações')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($observacoes);
		$this->configurandoTamanho('observacoes','span11');
		$this->montandoGrupo(array("observacoes"), 'grupo4',array('legend'=>'PORRA'));
	
		
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
	
	
	public function enderecos(){
		$this->setName('clientesenderecos');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		$this->setAction("/cadastros/clientes/salvaendereco");
	
		
	
		$id_registro = new Zend_Form_Element_Hidden('id_registro_end');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa_end');
		$this->addElement($id_pessoa);
	
			
		$tipoendereco = new Zend_Form_Element_Select("tipoendereco");
		$tipoendereco->setLabel('Tipo de Endereço:')->setRequired(true)
		->addErrorMessage("Informe o tipo de endereço")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Tiposenderecos::renderCombo());
		$this->addElement($tipoendereco);
		$this->configurandoTamanho('tipoendereco','span3');
	
	
		$cep = new Zend_Form_Element_Text('cep');
		$cep->setLabel('CEP:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Cep')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","00000000")
		->addErrorMessage("Informe o Cep");
		$this->addElement($cep);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('cep','span3');
		//definindo o grupo que o elemento pertencerá
	
		$logradouro = new Zend_Form_Element_Text('logradouro');
		$logradouro->setLabel('Logradouro:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","Rua x ")
		->setAttrib('class', 'required')
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($logradouro);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('logradouro','span3');
		//definindo o grupo que os elementos pertencerão
		
		$numero = new Zend_Form_Element_Text('numero');
		$numero->setLabel('Numero:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Numero')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","00000000")
		->addErrorMessage("Informe o Numero");
		$this->addElement($numero);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('numero','span3');
		
		$this->montandoGrupo(array('tipoendereco','cep','logradouro','numero'), 'grupo1',array('legend'=>'PORRA'));
		
		$complemento = new Zend_Form_Element_Text('complemento');
		$complemento->setLabel('Complemento:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Complemento')
		->setAttrib("placeholder","Complemento")
		->addErrorMessage("Informe o Complemento");
		$this->addElement($complemento);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('complemento','span3');
	
	
		$bairro = new Zend_Form_Element_Text('bairro');
		$bairro->setLabel('Bairro:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('class', 'required')
		->setAttrib('title', 'Informe o Bairro');
		$this->addElement($bairro);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('bairro','span3');
	
		$cidade = new Zend_Form_Element_Text('cidade');
		$cidade->setLabel('Cidade:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('class', 'required')
		->setAttrib('title', 'Informe a cidade');
		$this->addElement($cidade);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('cidade','span3');
	
		$estado = new Zend_Form_Element_Text('estado');
		$estado->setLabel('Estado:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('class', 'required')
		->setAttrib('title', 'Informe o estado');
		$this->addElement($estado);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('estado','span1');
	
		$this->montandoGrupo(array('complemento','bairro','cidade','estado','codpais','codibge'), 'grupo2',array('legend'=>'PORRA'));
		
		$codpais = new Zend_Form_Element_Text('codpais');
		$codpais->setLabel('Código Pais:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('class', 'required')
		->setDescription("Código do pais segundo a tabela do IBGE")
		->setAttrib('title', 'Informe o Pais');
		
		$this->addElement($codpais);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('codpais','span3');
		
		$codibge = new Zend_Form_Element_Text('codibge');
		$codibge->setLabel('Código IBGE:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('class', 'required')
		->setDescription("Código da cidade segundo a tabela do IBGE")
		->setAttrib('title', 'Informe o Codigo IBGE');
		$this->addElement($codibge);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('codibge','span3');
	
		
		$this->montandoGrupo(array('codpais','codibge'), 'grupo3',array('legend'=>'PORRA'));
		
	
		$submit = new Zend_Form_Element_Submit('submitendereco');
		$submit->setLabel('Salvar')
		->setAttrib('class', 'btn btn-large btn-primary')
		->setIgnore(true);
		$this->addElement($submit);
	
		$limpar = new Zend_Form_Element_Reset('limparendereco');
		$limpar->setLabel('Limpar')
		->setAttrib('class', 'btn btn-large btn-warning')
		->setIgnore(true);
		$this->addElement($limpar);
	
	
		//os botões por padrão ficarão sempre centralizados desde que façam parte do grupo botoes
		$this->montandoGrupo(array('limparendereco','submitendereco'), 'botoes');
	
	
	}
	
	public function contatos(){
		
		$this->setName('clientescontatos');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		$this->setAction("/cadastros/clientes/salvacontatos");
		
		
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro_contato');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa_contato');
		$this->addElement($id_pessoa);
		
		$nomecontato = new Zend_Form_Element_Text('nomecontato');
		$nomecontato->setLabel('Nome:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Cep')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","")
		->addErrorMessage("Informe o nome do contato");
		$this->addElement($nomecontato);
	
		
		$contato = new Zend_Form_Element_Text('telcomercial');
		$contato->setLabel('Tel Comercial:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","(xx)xxxx-xxxx")
		->setAttrib('class', 'span12 required')
		->setAttrib('title', 'Informe o Telefone Comercial');
		$this->addElement($contato);
		
		
		$ramal = new Zend_Form_Element_Text('ramal');
		$ramal->setLabel('Ramal:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('class', 'span12')
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($ramal);
	
		
		$telresidencial = new Zend_Form_Element_Text('telresidencial');
		$telresidencial->setLabel('Tel Residencia')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","(xx)xxxx-xxxx")
		->setAttrib('class', 'span12')
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($telresidencial);
		
		//definindo o grupo que os elementos pertencerão
		
		$celular = new Zend_Form_Element_Text('celular');
		$celular->setLabel('Celular')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('class', 'span12')
		->setAttrib('title', '(xx)xxxx-xxxx');
		$this->addElement($celular);
	
		
		$email = new Zend_Form_Element_Text('email');
		$email->setLabel('Enmail:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","xxxxxx@xxxxx.xxx")
		->setAttrib('class', 'span12 required')
		->setAttrib('title', 'Informe o Email');
		$this->addElement($email);
		//definindo a posição do elemento no formulário
		
		
		
	
	}
	
	public function outros(){
		
		$this->setName('clientesoutros');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		$this->setAction("/cadastros/clientes/outrosdados");
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro_outros');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa_outros');
		$this->addElement($id_pessoa);
		
		
		$tipocadastro = new Zend_Form_Element_Select("tipocadastro");
		$tipocadastro->setLabel('Tipo de Cadastro:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Tipopessoas::renderCombo());
		$this->addElement($tipocadastro);
		$this->configurandoTamanho('tipocadastro','span3');
		
		$inscestsub = new Zend_Form_Element_Text('inscestsub');
		$inscestsub->setLabel('Insc Est. Subst Trib:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($inscestsub);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('inscestsub','span3');
		
		$inscsuframa = new Zend_Form_Element_Text('inscsuframa');
		$inscsuframa->setLabel('Inscrição Suframa:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($inscsuframa);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('inscsuframa','span3');
		
		
		$cnpjentrega = new Zend_Form_Element_Text('cnpjentrega');
		$cnpjentrega->setLabel('CNPJ de Entrega:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($cnpjentrega);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('cnpjentrega','span3');
		$this->montandoGrupo(array('tipocadastro','inscestsub','inscsuframa','cnpjentrega'), 'grupo1',array('legend'=>'PORRA'));
		
		$centrodecustos = new Zend_Form_Element_Select("centrodecustos");
		$centrodecustos->setLabel('Centro de Custos:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Financeiro_CategoriasLancamentos::getCombo());
		$this->addElement($centrodecustos);
		$this->configurandoTamanho('centrodecustos','span3');
		
		$planodecontas = new Zend_Form_Element_Select("planodecontas");
		$planodecontas->setLabel('Plano de Contas:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Financeiro_TiposLancamentos::renderComboGroup());
		$this->addElement($planodecontas);
		$this->configurandoTamanho('planodecontas','span3');
		
		$tabeladeprecos = new Zend_Form_Element_Select("tabeladeprecos");
		$tabeladeprecos->setLabel('Tabela de Preços:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Tabeladeprecos::renderCombo());
		$this->addElement($tabeladeprecos);
		$this->configurandoTamanho('tabeladeprecos','span3');
		
		$modalidadefrete = new Zend_Form_Element_Select("modalidadefrete");
		$modalidadefrete->setLabel('Modalidade de Frete:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'- Selecione -','0'=>'Emitente','1'=>'Det/Rem','2'=>'Terceiros','9'=>'Sem Frete'));
		$this->addElement($modalidadefrete);
		$this->configurandoTamanho('modalidadefrete','span3');
		
		$this->montandoGrupo(array('centrodecustos','planodecontas','tabeladeprecos','modalidadefrete'), 'grupo2',array('legend'=>'PORRA'));
		
		$vendedorpadrao = new Zend_Form_Element_Select("vendedorpadrao");
		$vendedorpadrao->setLabel('Vendedor Padrão:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->setMultiOptions(Cadastros_Model_Pessoas::renderCombo('4'));
		$this->addElement($vendedorpadrao);
		$this->configurandoTamanho('vendedorpadrao','span3');
		
		$comissao = new Zend_Form_Element_Text('comissao');
		$comissao->setLabel('Comissao:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($comissao);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('comissao','span3');
		
		$transportadorpadrao = new Zend_Form_Element_Select("transportadorpadrao");
		$transportadorpadrao->setLabel('Transportadora Padrão:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->setMultiOptions(Cadastros_Model_Pessoas::renderCombo('3'));
		$this->addElement($transportadorpadrao);
		$this->configurandoTamanho('transportadorpadrao','span3');
		
		
		$acessosistemas = new Zend_Form_Element_Select("acessosistemas");
		$acessosistemas->setLabel('Acesso ao Sistema:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('0'=>'Não','1'=>'Sim'));
		$this->addElement($acessosistemas);
		$this->configurandoTamanho('acessosistemas','span3');
		
		$this->montandoGrupo(array('vendedorpadrao','comissao','transportadorpadrao','acessosistemas'), 'grupo3',array('legend'=>'PORRA'));
		
		
		$acessosoap = new Zend_Form_Element_Select("acessosoap");
		$acessosoap->setLabel('Acesso ao SOAP:')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('0'=>'Não','1'=>'Sim'));
		$this->addElement($acessosoap);
		$this->configurandoTamanho('acessosoap','span3');
		
		$chavesoap = new Zend_Form_Element_Text('chavesoap');
		$chavesoap->setLabel('Chave Soap:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("placeholder","")
		->setAttrib('title', 'Informe o Logradouro');
		$this->addElement($chavesoap);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('chavesoap','span3');
		
		$envionewsletter = new Zend_Form_Element_Select("envionewsletter");
		$envionewsletter->setLabel('Envia NewsLetter?')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('1'=>'Sim','0'=>'Não'));
		$this->addElement($envionewsletter);
		$this->configurandoTamanho('envionewsletter','span3');
		
		$enviosms = new Zend_Form_Element_Select("enviosms");
		$enviosms->setLabel('Envio de SMS?')->setRequired(true)
		->addErrorMessage("Informe o tipo de contato")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('1'=>'Sim','0'=>'Não'));
		$this->addElement($enviosms);
		$this->configurandoTamanho('enviosms','span3');
		
		
		$this->montandoGrupo(array('acessosoap','chavesoap','envionewsletter','enviosms'), 'grupo4',array('legend'=>'PORRA'));
		
		
		$observacoes = new Zend_Form_Element_Textarea('obsnf');
		$observacoes->setLabel('Observações para Nota Fiscal:')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($observacoes);
		$this->configurandoTamanho('obsnf','span11');
		$this->montandoGrupo(array("obsnf"), 'grupo20',array('legend'=>'PORRA'));
		
		$submit = new Zend_Form_Element_Submit('submitoutros');
		$submit->setLabel('Salvar')
		->setAttrib('class', 'btn btn-large btn-primary')
		->setIgnore(true);
		$this->addElement($submit);
		
		$limpar = new Zend_Form_Element_Reset('limparoutros');
		$limpar->setLabel('Limpar')
		->setAttrib('class', 'btn btn-large btn-warning')
		->setIgnore(true);
		$this->addElement($limpar);
		
		
		//os botões por padrão ficarão sempre centralizados desde que façam parte do grupo botoes
		$this->montandoGrupo(array('limparoutros','submitoutros'), 'botoes');
		
		
		
	}
	
	
	public function files(){
		
		$this->setName('clientesfiles');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		$this->setAction("/cadastros/clientes/save-file");
		
		$id_registro = new Zend_Form_Element_Hidden('tipofile');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('idreg');
		$this->addElement($id_pessoa);
		
		
		$file = new Zend_Form_Element_File("arquivo");
		$file->setLabel('Selecione o Documento:');
		$this->addElement($file);
		//$this->configurandoTamanho('arquivo','span11');
		$this->montandoGrupo(array("arquivo"), 'grupo1',array('legend'=>'PORRA'));
		
		
		$observacoes = new Zend_Form_Element_Textarea('obsfile');
		$observacoes->setLabel('Observações do Arquivo:')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($observacoes);
		$this->configurandoTamanho('obsfile','span11');
		$this->montandoGrupo(array("obsfile"), 'grupo20',array('legend'=>'PORRA'));
		
		$submit = new Zend_Form_Element_Submit('submitoutros');
		$submit->setLabel('Salvar')
		->setAttrib('class', 'btn btn-large btn-primary')
		->setIgnore(true);
		$this->addElement($submit);
		
		$limpar = new Zend_Form_Element_Reset('limparoutros');
		$limpar->setLabel('Limpar')
		->setAttrib('class', 'btn btn-large btn-warning')
		->setIgnore(true);
		$this->addElement($limpar);
		
		
		//os botões por padrão ficarão sempre centralizados desde que façam parte do grupo botoes
		$this->montandoGrupo(array('limparoutros','submitoutros'), 'botoes');
		
	}
	


}