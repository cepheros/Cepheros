
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
 
class Erp_Form_Producao extends System_Form_Formdecorator{



	public function init()
	{
		
	}

	public function lancamento(){
		
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$id_producao = new Zend_Form_Element_Hidden('id_producao');
		$this->addElement($id_producao);
		
		$quantidade = new Zend_Form_Element_Text('quantidade');
		$quantidade->setLabel('Quantidade: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a quantidade')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Quantidade deve ser informada");
		$this->addElement($quantidade);
		
		$etapa = new Zend_Form_Element_Select("etapa");
		$etapa->setLabel('Etapa:')->setRequired(true)
		->addErrorMessage("Informe o tipo de estoque")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Producao_Etapas::renderCombo());
		$this->addElement($etapa);
	}
	
	public function lancamentoext(){
	
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
	
		$id_producao = new Zend_Form_Element_Hidden('id_producao');
		$this->addElement($id_producao);
	
		$quantidade = new Zend_Form_Element_Text('quantidade');
		$quantidade->setLabel('Quantidade: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a quantidade')
		->setAttrib('class', 'span12 required')
		->setAttrib("placeholder","0,00")
		->addErrorMessage("Quantidade deve ser informada");
		$this->addElement($quantidade);
	
		$etapa = new Zend_Form_Element_Select("etapa");
		$etapa->setLabel('Etapa:')->setRequired(true)
		->addErrorMessage("Informe o tipo de estoque")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(Erp_Model_Producao_Etapas::renderCombo());
		$this->addElement($etapa);
		
		$usuario = new Zend_Form_Element_Select("usuario");
		$usuario->setLabel('Responsável:')->setRequired(true)
		->addErrorMessage("Informe o tipo de estoque")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(System_Model_Users::renderCombo());
		$this->addElement($usuario);
	}
}