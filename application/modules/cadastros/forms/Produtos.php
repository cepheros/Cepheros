<?php
class Cadastros_Form_Produtos extends System_Form_Formdecorator{
	public function init()
	{

	}

	public function novo(){
		$this->setName('clientes');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');

		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);

		$nomeproduto = new Zend_Form_Element_Text('nomeproduto');
		$nomeproduto->setLabel('Nome do Produto: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome do produto')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe o nome do produto")
		->addErrorMessage("Informe o nome do produto");
		$this->addElement($nomeproduto);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('nomeproduto','span12');

		$referenciaproduto = new Zend_Form_Element_Text('referenciaproduto');
		$referenciaproduto->setLabel('Referência: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a referencia do produto')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe a Referência do Produto")
		->addErrorMessage("Informe a referencia do produto");
		$this->addElement($referenciaproduto);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('referenciaproduto','span12');

		$codigointerno = new Zend_Form_Element_Text('codigointerno');
		$codigointerno->setLabel('Código Interno: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o código de localização interna')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Código Interno")
		->addErrorMessage("Informe o código de localização interna");
		$this->addElement($codigointerno);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('codigointerno','span12');


		$codigonfe = new Zend_Form_Element_Text('codigonfe');
		$codigonfe->setLabel('Código NFe: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe um código para NFe"')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe um código para NFe")
		->addErrorMessage("Informe um código para NFe");
		$this->addElement($codigonfe);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('codigonfe','span12');


		$categoria = new Zend_Form_Element_Select('categoriaproduto');
		$categoria->setLabel("Categoria:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Categoriaprodutos::renderCombo())
		->addErrorMessage("Selecione a categoria do produto");
		$this->addElement($categoria);
		$this->configurandoTamanho('categoriaproduto','span3');


		$subcategoria = new Zend_Form_Element_Select('subcategoriaproduto');
		$subcategoria->setLabel("Sub-Categoria:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Subcategoriaprodutos::renderCombo())
		->addErrorMessage("Selecione a sub-categoria do cadatro");
		$this->addElement($subcategoria);
		$this->configurandoTamanho('subcategoriaproduto','span3');


		$eangtin = new Zend_Form_Element_Text('eangtin');
		$eangtin->setLabel('EAN/GTIN:')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Código EAN')
		->setAttrib("placeholder","Informe o Código EAN")
		->addErrorMessage("Informe o nome do produto");
		$this->addElement($eangtin);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('eangtin','span12');


		$ncmproduto = new Zend_Form_Element_Text('ncmproduto');
		$ncmproduto->setLabel('Código NCN *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Código NCM')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe o Código NCM")
		->addErrorMessage("Informe o Código NCM");
		$this->addElement($ncmproduto);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('ncmproduto','span12');


		$origemproduto = new Zend_Form_Element_Select('origemproduto');
		$origemproduto->setLabel("Origem:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>"- Selecione -",'0'=>"(0) Nacional","1"=>"(1) Estrangeira (Impor. Direta)","2"=>"(2) Estrangeira (Adiq. Merc. Interno"))
		->addErrorMessage("Qual a origem deste produto?");
		$this->addElement($origemproduto);
		$this->configurandoTamanho('origemproduto','span3');


		$pesoproduto = new Zend_Form_Element_Text('pesoproduto');
		$pesoproduto->setLabel('Peso Unitário *:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome do produto')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Informe o nome do produto");
		$this->addElement($pesoproduto);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('pesoproduto','span12');



		$contaestoque = new Zend_Form_Element_Radio("contaestoque");
		$contaestoque->setLabel("Controle de Estoque?")
		->addMultiOption('1','Sim')
		->addMultiOption('0','Não')
		->setSeparator(" ");
		$this->addElement($contaestoque);
		$this->configurandoTamanho('contaestoque','span10');


		$localestoque = new Zend_Form_Element_Select('localestoque');
		$localestoque->setLabel("Localização:")
		->setRequired(false)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Estoque_Locais::getCombo())
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($localestoque);
		$this->configurandoTamanho('localestoque','span3');


		$estoqueminimo = new Zend_Form_Element_Text('estoqueminimo');
		$estoqueminimo->setLabel('Estoque Minimo: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Qual o estoque minimo? coloque 0 para não controlar')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe 0 para não controlar")
		->addErrorMessage("Qual o estoque minimo? coloque 0 para não controlar");
		$this->addElement($estoqueminimo);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('estoqueminimo','span12');

		$estoquemaximo = new Zend_Form_Element_Text('estoquemaximo');
		$estoquemaximo->setLabel('Estoque Máximo: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Qual o estoque máximo? coloque 0 para não controlar')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Informe 0 para não controlar")
		->addErrorMessage("Qual o estoque minimo? coloque 0 para não controlar");
		$this->addElement($estoquemaximo);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('estoquemaximo','span12');

		$estoqueatual = new Zend_Form_Element_Text('estoqueatual');
		$estoqueatual->setLabel('Estoque Atual: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Qual o estoque atual deste produto?')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","Qual o estoque atual deste produto?")
		->addErrorMessage("Qual o estoque atual deste produto?");
		$this->addElement($estoqueatual);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('estoqueatual','span12');

		$avisarestoque = new Zend_Form_Element_Radio("avisarestoque");
		$avisarestoque->setLabel("Alerta de Estoque?")
		->addMultiOption('1','Sim')
		->addMultiOption('0','Não')
		->setSeparator(" ");
		$this->addElement($avisarestoque);
		$this->configurandoTamanho('avisarestoque','span12');

		$orcarautomatico = new Zend_Form_Element_Radio("orcarautomatico");
		$orcarautomatico->setLabel("Orçamento Automático?")
		->addMultiOption('1','Sim')
		->addMultiOption('0','Não')
		->setSeparator(" ");
		$this->addElement($orcarautomatico);
		$this->configurandoTamanho('orcarautomatico','span12');

		$unidadedemedida = new Zend_Form_Element_Select('unidadedemedida');
		$unidadedemedida->setLabel("UN Medida:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Unidadesdemedida::renderCombo())
		->addErrorMessage("Informe a unidade de medida");
		$this->addElement($unidadedemedida);
		$this->configurandoTamanho('unidadedemedida','span8');


		$precocusto = new Zend_Form_Element_Text('precocusto');
		$precocusto->setLabel('Preço de Custo: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o preço de custo do produto ou 0,00 para não controlar')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Informe o preço de custo do produto ou 0,00 para não controlar");
		$this->addElement($precocusto);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('precocusto','span12');


		$precovenda = new Zend_Form_Element_Text('precovenda');
		$precovenda->setLabel('Preço de Venda: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o preço de venda do produto ou 0,00 para não controlar')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Informe o preço de venda do produto ou 0,00 para não controlar");
		$this->addElement($precovenda);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('precovenda','span12');


		$margemlucro = new Zend_Form_Element_Text('margemlucro');
		$margemlucro->setLabel('Margem de Lucro: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a margem de lucro do produto ou 0,00 para não controlar')
		->setAttrib('class', 'required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Informe o nome do produto");
		$this->addElement($margemlucro);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('margemlucro','span12');


		$obsinternas = new Zend_Form_Element_Textarea('obsinternas');
		$obsinternas->setLabel('Observações Internas')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($obsinternas);
		$this->configurandoTamanho('obsinternas','span12');

		$infadicionaisnfe = new Zend_Form_Element_Text('infadicionaisnfe');
		$infadicionaisnfe->setLabel('Informações adicionais para NFe: *')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib("style", "width:100%")
		->setAttrib("placeholder","Esses dados serão inseridos na NFe");
		$this->addElement($infadicionaisnfe);
		//definindo a posição do elemento no formulário
		$this->configurandoTamanho('infadicionaisnfe','span12');


















	}
}