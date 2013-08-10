
<?php
/**
 * Este arquivo é parte do projeto SysAdmin - ERP em PHP
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou 
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da 
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>. 
 *
 * @package   SysAdmin
 * @name      
 * @version   1.0.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; SysAdmin
 * @link      http://www.cepheros.com.br
 * @author    Daniel R. Chaves <dchaves at 32dll dot com dot br>
 *
 */
 
class Erp_Form_Financeiro extends System_Form_Formdecorator{
	
	public $tipocatlanc;
	
	public function init()
	{
		$this->setName('LocaisEstoques');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
	}
	
	
	public function fluxoCaixa(){
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$categoria = new Zend_Form_Element_Select("categoria");
		$categoria->setLabel('Categoria Conta:')->setRequired(true)
		->addErrorMessage("Informe a Categoria")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', ' required ')
		->setMultiOptions(Erp_Model_Financeiro_TiposLancamentos::renderComboGroup($this->tipocatlanc));
		$this->addElement($categoria);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa');
		$this->addElement($id_pessoa);
		
		$pessoapagamento = new Zend_Form_Element_Text('nomelancamento');
		$pessoapagamento->setLabel('Pessoa: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12 required input-append')
		->setAttrib("placeholder","Digite para localizar")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($pessoapagamento);
		
		$contapagamento = new Zend_Form_Element_Select("id_conta");
		$contapagamento->setLabel('Conta Corrente:')->setRequired(true)
		->addErrorMessage("Informe a Conta")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Financeiro_ContaCorrente::getCombo());
		$this->addElement($contapagamento);
		
		$contapagamento = new Zend_Form_Element_Select("tipolancamento");
		$contapagamento->setLabel('Tipo de Operação:')->setRequired(true)
		->addErrorMessage("Informe a Conta")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('1'=>'Crédito','2'=>'Débito','3'=>"Transferência"));
		$this->addElement($contapagamento);
		
		
		$totalgeral = new Zend_Form_Element_Text('valorregistro');
		$totalgeral->setLabel('Total Geral: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($totalgeral);
		
		
		$datalancamento = new Zend_Form_Element_Text('datalancamento');
		$datalancamento->setLabel('Data: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a Data')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","dd/mm/yyyy")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($datalancamento);
		
		$informacoes = new Zend_Form_Element_Textarea('observacoes');
		$informacoes->setLabel("Observações e Informações Importantes:")
		->setRequired(false)
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($informacoes);
		
			
		$contatransferencia = new Zend_Form_Element_Select("contatransferencia");
		$contatransferencia->setLabel('Conta Destino:')->setRequired(true)
		->addErrorMessage("Informe a Conta")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_ContaCorrente::getCombo());
		$this->addElement($contatransferencia);
		
		
		
		
	}
	
	public function lancamento(){
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$id_pessoa = new Zend_Form_Element_Hidden('id_pessoa');
		$this->addElement($id_pessoa);
		
		$id_empresa = new Zend_Form_Element_Select("id_empresa");
		$id_empresa->setLabel('Empresa:')->setRequired(true)
		->addErrorMessage("Informe a Empresa")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(System_Model_Empresas::renderCombo());
		$this->addElement($id_empresa);
		
		$pessoapagamento = new Zend_Form_Element_Text('pessoapagamento');
		$pessoapagamento->setLabel('Pessoa: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12 required input-append')
		->setAttrib("placeholder","Digite para localizar")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($pessoapagamento);
		
		$nomelancamento = new Zend_Form_Element_Text('nomelancamento');
		$nomelancamento->setLabel('Descrição Lançamento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição para o lançamento')
		->setAttrib('class', 'span12 required input-append')
		->setAttrib("placeholder","Informe uma descrição para este lançamento")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($nomelancamento);
		
		
		$totalgeral = new Zend_Form_Element_Text('totalgeral');
		$totalgeral->setLabel('Total Geral: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento (somando as parcelas)')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($totalgeral);
		
		
		$primeirovencimento = new Zend_Form_Element_Text('primeirovencimento');
		$primeirovencimento->setLabel('Primeiro Vencimento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento (somando as parcelas)')
		->setAttrib('class', 'span12 required datepicker')
		->setAttrib("placeholder","dd/mm/aaaa")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($primeirovencimento);
		
		
		$numeroparcelas = new Zend_Form_Element_Text('numeroparcelas');
		$numeroparcelas->setLabel('Parcelas: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Quantas Parcelas?')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($numeroparcelas);
		
		
		$intervaloparcelas = new Zend_Form_Element_Select("intervaloparcelas");
		$intervaloparcelas->setLabel('Intervalo:')->setRequired(true)
		->addErrorMessage("Informe o tipo de intervalo")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(array('30'=>'Mensal',"15"=>"Quinzenal","7"=>'Semanal','45'=>'Trimestral','60'=>'Bimestral','180'=>'Semestral','365'=>'Anual','1'=>'Diário'));
		$this->addElement($intervaloparcelas);
		
		
		$contapadrao = new Zend_Form_Element_Select("contapadrao");
		$contapadrao->setLabel('Conta Corrente:')->setRequired(true)
		->addErrorMessage("Informe a Conta")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_ContaCorrente::getCombo());
		$this->addElement($contapadrao);
		
		
		$categoria = new Zend_Form_Element_Select("categoria");
		$categoria->setLabel('Categoria Conta:')->setRequired(true)
		->addErrorMessage("Informe a Categoria")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_TiposLancamentos::renderComboGroup($this->tipocatlanc));
		$this->addElement($categoria);
		
		$tipodocumento = new Zend_Form_Element_Select("tipodocumento");
		$tipodocumento->setLabel('Tipo Documento:')->setRequired(true)
		->addErrorMessage("Informe o tipo de documento")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_TiposDocumentos::renderCombo());
		$this->addElement($tipodocumento);
		
		$numerodocumento = new Zend_Form_Element_Text('numerodocumento');
		$numerodocumento->setLabel('Numero Documento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Qual o numero do documento?')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($numerodocumento);
				
	}
	
	public function baixa(){
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$nomelancamento = new Zend_Form_Element_Text('nomelancamento');
		$nomelancamento->setLabel('Descrição Lançamento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição para o lançamento')
		->setAttrib('class', 'span12 required input-append')
		->setAttrib("placeholder","Informe uma descrição para este lançamento")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($nomelancamento);
		
		$datapagamento = new Zend_Form_Element_Text('datapagamento');
		$datapagamento->setLabel('Data Pagamento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a data de pagamento')
		->setAttrib('class', 'span12 required datepicker')
		->setAttrib("placeholder","dd/mm/aaaa")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($datapagamento);
		
		$pessoapagamento = new Zend_Form_Element_Text('pessoapagamento');
		$pessoapagamento->setLabel('Pessoa: *')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12 input-append')
		->setAttrib("placeholder","Digite para localizar")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($pessoapagamento);
		
		$linhadigitavel = new Zend_Form_Element_Text('linhadigitavel');
		$linhadigitavel->setLabel('Linha Digitavel: *')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento (somando as parcelas)')
		->setAttrib('class', 'span12')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($linhadigitavel);
		
		$tipopagamento = new Zend_Form_Element_Select("tipopagamento");
		$tipopagamento->setLabel('Tipo de Pagamento:')->setRequired(true)
		->addErrorMessage("Informe o tipo de documento")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_TiposDocumentos::renderCombo());
		$this->addElement($tipopagamento);
		
		$categoria = new Zend_Form_Element_Select("categoria");
		$categoria->setLabel('Categoria Conta:')->setRequired(true)
		->addErrorMessage("Informe a Categoria")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_TiposLancamentos::renderComboGroup($this->tipocatlanc));
		$this->addElement($categoria);
		
		$numerodocumento = new Zend_Form_Element_Text('numerodocumento');
		$numerodocumento->setLabel('Numero Documento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Qual o numero do documento?')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($numerodocumento);
		
		$contapagamento = new Zend_Form_Element_Select("contapagamento");
		$contapagamento->setLabel('Conta Corrente:')->setRequired(true)
		->addErrorMessage("Informe a Conta")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'span12 required combobox')
		->setMultiOptions(Erp_Model_Financeiro_ContaCorrente::getCombo());
		$this->addElement($contapagamento);
		
		$valorbaixa = new Zend_Form_Element_Text('valorbaixa');
		$valorbaixa->setLabel('Valor Lançamento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Valor original deve ser preservado')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($valorbaixa);
		
		$valorjuros = new Zend_Form_Element_Text('valorjuros');
		$valorjuros->setLabel('Juros (+): *')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento (somando as parcelas)')
		->setAttrib('class', 'span12')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($valorjuros);
		
		$valormultas = new Zend_Form_Element_Text('valormultas');
		$valormultas->setLabel('Multas (+): *')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento (somando as parcelas)')
		->setAttrib('class', 'span12')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($valormultas);
		
		$valordescontos = new Zend_Form_Element_Text('valordescontos');
		$valordescontos->setLabel('Descontos (-): *')
		->setRequired(false)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento (somando as parcelas)')
		->setAttrib('class', 'span12')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($valordescontos);
		
		$valorpago = new Zend_Form_Element_Text('valorpago');
		$valorpago->setLabel('Valor Baixa: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o Total do pagamento')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Um Valor deve ser informado");
		$this->addElement($valorpago);
		
	}
}