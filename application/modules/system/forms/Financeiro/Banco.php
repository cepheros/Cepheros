
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
 
class System_Form_Financeiro_Banco extends System_Form_Formdecorator{

	public $tipocatlanc;

	public function init()
	{
		$this->setName('LocaisEstoques');
		$this->setAttrib( 'class', 'form-horizontal' );
		$this->setMethod('POST');
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$numerobanco = new Zend_Form_Element_Text('numerobanco');
		$numerobanco->setLabel('Número Banco: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o numero do banco')
		->setAttrib('class', 'span12 required ')
		->setAttrib("placeholder","000")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($numerobanco);
		
		$nomebanco = new Zend_Form_Element_Text('nomebanco');
		$nomebanco->setLabel('Nome Banco: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome do banco')
		->setAttrib('class', 'span12 required ')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($nomebanco);
		
		$agencia = new Zend_Form_Element_Text('agencia');
		$agencia->setLabel('Número Agência: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o numero da agencia')
		->setAttrib('class', 'span12 required ')
		->setAttrib("placeholder","000")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($agencia);
		
		$conta = new Zend_Form_Element_Text('conta');
		$conta->setLabel('Número Conta: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o numero da conta')
		->setAttrib('class', 'span12 required ')
		->setAttrib("placeholder","000")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($conta);
		
		$carteirapadrao = new Zend_Form_Element_Text('carteirapadrao');
		$carteirapadrao->setLabel('Carteira: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($carteirapadrao);
		
		$valoremissaoboleto = new Zend_Form_Element_Text('valoremissaoboleto');
		$valoremissaoboleto->setLabel('Valor de Emissão boleto')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($valoremissaoboleto);
		
		$msgboleto_l1 = new Zend_Form_Element_Text('msgboleto_l1');
		$msgboleto_l1->setLabel('Mensagem Boleto (Linha 1):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($msgboleto_l1);
		
		$msgboleto_l2 = new Zend_Form_Element_Text('msgboleto_l2');
		$msgboleto_l2->setLabel('Mensagem Boleto (Linha 2):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($msgboleto_l2);
		
		$msgboleto_l3 = new Zend_Form_Element_Text('msgboleto_l3');
		$msgboleto_l3->setLabel('Mensagem Boleto (Linha 3):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12 ')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($msgboleto_l3);
		
		$msgboleto_l4 = new Zend_Form_Element_Text('msgboleto_l4');
		$msgboleto_l4->setLabel('Mensagem Boleto (Linha 4):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($msgboleto_l4);
		
		$convenio = new Zend_Form_Element_Text('convenio');
		$convenio->setLabel('Convenio: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12 ')
		
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($convenio);
		
		$variacaocarteira = new Zend_Form_Element_Text('variacaocarteira');
		$variacaocarteira->setLabel('Variação Carteira:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($variacaocarteira);
		
		$codigocliente = new Zend_Form_Element_Text('codigocliente');
		$codigocliente->setLabel('Código Cliente:')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($codigocliente);
		
		$numerobanco = new Zend_Form_Element_Text('localpagamento_l1');
		$numerobanco->setLabel('Local Pagamento (Linha 1):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($numerobanco);
		
		$localpagamento_l2 = new Zend_Form_Element_Text('localpagamento_l2');
		$localpagamento_l2->setLabel('Local Pagamento (Linha 2):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($localpagamento_l2);
		
		$localpagamento_l3 = new Zend_Form_Element_Text('localpagamento_l3');
		$localpagamento_l3->setLabel('Local Pagamento (Linha 3):')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a descrição do Local de estoque')
		->setAttrib('class', 'span12')
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($localpagamento_l3);
		
		
	}
	
}