
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
 
class System_Form_Faturamento_Perfil extends System_Form_Formdecorator{
	
	public function basicos(){
		$id_registro = new Zend_Form_Element_Hidden('id_registro');
		$this->addElement($id_registro);
		
		$nomedoperfil = new Zend_Form_Element_Text('nomedoperfil');
		$nomedoperfil->setLabel('Nome do Perfil: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o nome do perfil')
		->setAttrib('class', 'span12 required ')
		->setAttrib("placeholder","Informe um nome de fácil localização")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($nomedoperfil);
		
		$observacoesfisco = new Zend_Form_Element_Textarea('observacoesfisco');
		$observacoesfisco->setLabel('Observações para o FISCO')
		->setAttrib('rows', '5')
		->setAttrib("style", "width:100%")
		->addFilter('StripTags');
		$this->addElement($observacoesfisco);
		
		$cfop = new Zend_Form_Element_Text('cfop');
		$cfop->setLabel('Código CFOP: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe o CFOP')
		->setAttrib('class', 'span12 required ')
		->setAttrib("placeholder","0000")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($cfop);
		
		$naturezaoperacao = new Zend_Form_Element_Text('naturezaoperacao');
		$naturezaoperacao->setLabel('Natureza da Operação: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a Natureza da Operação conforme o CFOP')
		->setAttrib('class', 'span12 required ')
		->setAttrib("placeholder","Conforme o CFOP")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($naturezaoperacao);
		
		$suframa = new Zend_Form_Element_Checkbox("suframa");
		$suframa->setLabel("É Suframa?")
		->setRequired(TRUE)
		->setValue('1');
		$this->addElement($suframa);
		
		$finalidadeemissao = new Zend_Form_Element_Select('finalidadeemissao');
		$finalidadeemissao->setLabel("Finalidade da Emissão:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('1'=>"NFe Normal",'2'=>"NFe Complementar",'3'=>"NFe de Ajuste"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($finalidadeemissao);
		
		$tipoperfil = new Zend_Form_Element_Select('tipoperfil');
		$tipoperfil->setLabel("Tipo de NFe:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array('0'=>"Entrada",'1'=>"Saída"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($tipoperfil);
		
		$incluirpedcompra = new Zend_Form_Element_Checkbox("incluirpedcompra");
		$incluirpedcompra->setLabel("Incluir Cód do Ped de Compra?")
		->setRequired(TRUE)
		->setValue('1');
		$this->addElement($incluirpedcompra);
		
		
		$valorprodcompetotal = new Zend_Form_Element_Checkbox("valorprodcompetotal");
		$valorprodcompetotal->setLabel("Os Prod compõe Total da NFe?")
		->setRequired(TRUE)
		->setValue('1')
		  ->addDecorator('HtmlTag',array('tag' => 'div', 'class' =>'switch','placement' => 'WRAP')); 
		$this->addElement($valorprodcompetotal);
		
		$incluirpedvenda = new Zend_Form_Element_Checkbox("incluirpedvenda");
		$incluirpedvenda->setLabel("Incluir Cód do Ped de Venda?")
		->setRequired(TRUE)
		->setValue('1');
		$this->addElement($incluirpedvenda);
		
		
	}
	
	public function icms(){
		
		$sittributaria = new Zend_Form_Element_Select('sittributaria');
		$sittributaria->setLabel("Situação Tributária:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>"-Selecione-",'Tributação Normal'=> array('00'=>"00 - Tributada Integralmente",'10'=>"10 - Tributada com cobrança do ICMS por ST (Com Partilha)",
				'20'=>"20 - Com redução da base de cálculo",'30'=>'30 - Isenta ou não tributada e com cobrança do ICMS por ST',
		'40'=>'40 - Isenta','41'=>'41 - Não Tributada','41'=>'41 - Não tributada (ICMSST Devido para a UF de destino',"50"=>"50 - Suspenção"),'Simpes Nacional'=>array('101'=>'101 - Tributada com permissão de crédito',
		'102'=>'102 - Tributada sem permissão de crédito','103'=>'103 - Isenção de ICMS para a faixa de receita bruta','201'=>'201 - Tributada com permissão de crédito e com cobrança do ICMS por ST',
		'202'=>'202 - Tributada sem permissão de crédito e com cobrança do ICMS por ST', '203'=>'203 - Isenção do ICMS por faixa de receita bruta e com cobrança de ICMS por ST',
				'300'=>'300 - Imune','400'=>'400 - Não Tributada'
		)
	))
		->addErrorMessage("Selecione a Situação Tributária");
		$this->addElement($sittributaria);
		
		$moddeterbcicms = new Zend_Form_Element_Select('moddeterbcicms');
		$moddeterbcicms->setLabel("Modalidade de determinação da BC do ICMS:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','0'=>"Margem Valor Agregado (%)",'1'=>"Pauta (Valor)",'2'=>"Preço Tabelado Máx (valor)",'3'=>'Valor da Operação'))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($moddeterbcicms);
		
		$moddeterbcicmsst = new Zend_Form_Element_Select('moddeterbcicmsst');
		$moddeterbcicmsst->setLabel("Modalidade de determinação da BC do ICMS ST:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','0'=>"Preço tabelado ou máximo sugerido",'1'=>"Lista Negativa (Valor)",'2'=>"Lista Positiva (valor)",'3'=>'Lista Neutra (valor)','4'=>'Margem Valor Agregado (%)','5'=>'Pauta (valor)'))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($moddeterbcicmsst);
		
		$aliqicms = new Zend_Form_Element_Text('aliqicms');
		$aliqicms->setLabel('Alíquota do Imposto: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqicms);
		
		$aliqaplicavelcalculocredito = new Zend_Form_Element_Text('aliqaplicavelcalculocredito');
		$aliqaplicavelcalculocredito->setLabel('Alíquota do Imposto Para Cálculo de Crédito (Simples): *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqaplicavelcalculocredito);
		
		
		$aliqicmsst = new Zend_Form_Element_Text('aliqicmsst');
		$aliqicmsst->setLabel('Alíquota do ICMS ST: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqicmsst);
		
		
		$redutorbcicms = new Zend_Form_Element_Text('redutorbcicms');
		$redutorbcicms->setLabel('Redutor Base de Cálculo ICMS: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($redutorbcicms);
		
		
		$redutorbcicmsst = new Zend_Form_Element_Text('redutorbcicmsst');
		$redutorbcicmsst->setLabel('Redutor Base de Cálculo ICMS ST: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($redutorbcicmsst);
		
		
		$bcicmsstretironaufremetente = new Zend_Form_Element_Text('bcicmsstretironaufremetente');
		$bcicmsstretironaufremetente->setLabel('BC ICMS Retido na UF Remetente *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($bcicmsstretironaufremetente);
		
		$bcicmsstufdestino = new Zend_Form_Element_Text('bcicmsstufdestino');
		$bcicmsstufdestino->setLabel('BC ICMS Retido na UF Destino *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($bcicmsstufdestino);
		
		$bcoperacaopropria = new Zend_Form_Element_Text('bcoperacaopropria');
		$bcoperacaopropria->setLabel('% BC da Operação Própria *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($bcoperacaopropria);
		
		$uficmsstdevop = new Zend_Form_Element_Text('uficmsstdevop');
		$uficmsstdevop->setLabel('UF do ICMS ST devido na Operação *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","AA")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($uficmsstdevop);
		
		$uficmsstdevop = new Zend_Form_Element_Text('uficmsstdevop');
		$uficmsstdevop->setLabel('UF do ICMS ST devido na Operação *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","AA")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($uficmsstdevop);
		
		$margemvladicicmsst = new Zend_Form_Element_Text('margemvladicicmsst');
		$margemvladicicmsst->setLabel('% Margem Valor adic. ICMS ST *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($margemvladicicmsst);
		
		
		
		
		$motivodadesoneracao = new Zend_Form_Element_Select('motivodadesoneracao');
		$motivodadesoneracao->setLabel("Motivo da Desoneração do ICMS:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','1'=>"Taxi",'2'=>"Deficiente Físico",
				'3'=>"Produtor Agropecuário",'4'=>'Frotista / Locadora',
				'5'=>'Diplomático / Consultar','6'=>'Utilitários e Motocicletas da Amazonia Ocidental e Áreas de Livre Comércio',
		'7'=>'SUFRAMA','8'=>"Venda a órgãos públicos","9"=>"Outros (NT 2011/004)"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($motivodadesoneracao);
	}
	
	public function ipi(){
		$sittributaria_ipi = new Zend_Form_Element_Select('sittributaria_ipi');
		$sittributaria_ipi->setLabel("Situação Tributária:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','00'=>"00 - Entrada com Recuperação de crédito",'01'=>"01 - Entrada Tributada com Aliquota zero",
				'02'=>"02 - Entrada Isenta",'03'=>'03 - Entrada não tributada',
				'04'=>'04 - Entrada Imune','05'=>'05 - Entrada com Suspenção',
				'49'=>'49 - Outras Entradas','50'=>"50 - Saída Tributada",'51'=>"51 - Saída tributada com aliquota zero",
		'52'=>"52 - Saída Isenta",'53'=>'53 - Saída não tributada','54'=>'54 - Saída Imune','55'=>'55 - Saída com Suspensão','99'=>'99 - Outras Saídas'))
				->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($sittributaria_ipi);
		
		
		$classedeenquadramento_ipi = new Zend_Form_Element_Text('classedeenquadramento_ipi');
		$classedeenquadramento_ipi->setLabel('Classe de enquadramento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($classedeenquadramento_ipi);
		
		$codenquadramento_ipi = new Zend_Form_Element_Text('codenquadramento_ipi');
		$codenquadramento_ipi->setLabel('Código de enquadramento: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($codenquadramento_ipi);
		
		
		$cnpjprodutor_ipi = new Zend_Form_Element_Text('cnpjprodutor_ipi');
		$cnpjprodutor_ipi->setLabel('CNPJ do Produtor: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($cnpjprodutor_ipi);
		
		$codselocontrole_ipi = new Zend_Form_Element_Text('codselocontrole_ipi');
		$codselocontrole_ipi->setLabel('Código do selo de controle: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($codselocontrole_ipi);
		
		
		$tipocalculo_ipi = new Zend_Form_Element_Select('tipocalculo_ipi');
		$tipocalculo_ipi->setLabel("Tipo De cálculo IPI:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','1'=>"Percentual (%)",'2'=>"Valor Fixo"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($tipocalculo_ipi);
		
		$alipi = new Zend_Form_Element_Text('alipi');
		$alipi->setLabel('Alíquiota IPI: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($alipi);
		
		$vlfixo_ipi = new Zend_Form_Element_Text('vlfixo_ipi');
		$vlfixo_ipi->setLabel('Valor fixo por unidade: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($vlfixo_ipi);
		
		
		
	}
	
	public function pis(){
		$sittrib_pis = new Zend_Form_Element_Select('sittrib_pis');
		$sittrib_pis->setLabel("Situação Tributária:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','01'=>"01 - Operação Tributavel (Cumulativo/Não Cumulativo)",'02'=>"02 - Operação Tributabel (Alíquota Diferenciada)",
				'03'=>"03 - Operação Tributabel - Base de Cálculo = Quantidade Vendixa x Alíquota por Unidade de Produto",'04'=>'04 - Operação Tributavel - Tributação Monofásica (Alíquota Zero)',
				'06'=>'06 - Operação Tributavel - Alíquota Zero',
				'07'=>'07 - Operação Isenta da Contribuição',
				'08'=>'08 - Operação sem incidência de contribuição',
				'09'=>"09 - Operação com suspenção da contribuição"))
				->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($sittrib_pis);
		
		$tipocalculo_pis = new Zend_Form_Element_Select('tipocalculo_pis');
		$tipocalculo_pis->setLabel("Tipo de cálculo:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','1'=>"Percentual (%)",'2'=>"Valor Fixo"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($tipocalculo_pis);
		
		$tipocalculost_pis = new Zend_Form_Element_Select('tipocalculost_pis');
		$tipocalculost_pis->setLabel("Tipo de cálculo ST:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','1'=>"Percentual (%)",'2'=>"Valor Fixo"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($tipocalculost_pis);
		
		$aliqpis_pis = new Zend_Form_Element_Text('aliqpis_pis');
		$aliqpis_pis->setLabel('Alíquota PIS: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqpis_pis);
		
		$aliqpis_st_pis = new Zend_Form_Element_Text('aliqpis_st_pis');
		$aliqpis_st_pis->setLabel('Alíquota PIS ST: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqpis_st_pis);
		
		$aliqreal__pis = new Zend_Form_Element_Text('aliqreal__pis');
		$aliqreal__pis->setLabel('Alíquota  Em Reais PIS : *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqreal__pis);
		
		$aliqrealst__pis = new Zend_Form_Element_Text('aliqrealst__pis');
		$aliqrealst__pis->setLabel('Alíquota  Em Reais PIS ST: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqrealst__pis);
		
		
		
		
	}
	
	public function cofins(){
		
		$sittrib_cofins = new Zend_Form_Element_Select('sittrib_cofins');
		$sittrib_cofins->setLabel("Situação Tributária:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','01'=>"01 - Operação Tributavel (Cumulativo/Não Cumulativo)",'02'=>"02 - Operação Tributabel (Alíquota Diferenciada)",
				'03'=>"03 - Operação Tributabel - Base de Cálculo = Quantidade Vendixa x Alíquota por Unidade de Produto",'04'=>'04 - Operação Tributavel - Tributação Monofásica (Alíquota Zero)',
				'06'=>'06 - Operação Tributavel - Alíquota Zero',
				'07'=>'07 - Operação Isenta da Contribuição',
				'08'=>'08 - Operação sem incidência de contribuição',
				'09'=>"09 - Operação com suspenção da contribuição"))
				->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($sittrib_cofins);
		
		$tipocalculo_cofins = new Zend_Form_Element_Select('tipocalculo_cofins');
		$tipocalculo_cofins->setLabel("Tipo de cálculo:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','1'=>"Percentual (%)",'2'=>"Valor Fixo"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($tipocalculo_cofins);
		
		$tipocalculost_cofins = new Zend_Form_Element_Select('tipocalculost_cofins');
		$tipocalculost_cofins->setLabel("Tipo de cálculo ST:")
		->setRequired(true)
		->addValidator("NotEmpty")
		->setAttrib('class', 'required')
		->setMultiOptions(array(''=>'-Selecione-','1'=>"Percentual (%)",'2'=>"Valor Fixo"))
		->addErrorMessage("Selecione a categoria do cadatro");
		$this->addElement($tipocalculost_cofins);
		
		$aliqcofins_cofins = new Zend_Form_Element_Text('aliqcofins_cofins');
		$aliqcofins_cofins->setLabel('Alíquota COFINS: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqcofins_cofins);
		
		$aliqcofins_st_cofins = new Zend_Form_Element_Text('aliqcofins_st_cofins');
		$aliqcofins_st_cofins->setLabel('Alíquota COFINS ST: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqcofins_st_cofins);
		
		$aliqreal__cofins = new Zend_Form_Element_Text('aliqreal__cofins');
		$aliqreal__cofins->setLabel('Alíquota  Em Reais COFINS : *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqreal__cofins);
		
		$aliqrealst__cofins = new Zend_Form_Element_Text('aliqrealst__cofins');
		$aliqrealst__cofins->setLabel('Alíquota  Em Reais COFINS ST: *')
		->setRequired(true)
		->addFilter('StripTags')
		->addFilter('StringTrim')
		->addValidator('NotEmpty')
		->setAttrib('title', 'Informe a alíquiota do ICMS')
		->setAttrib('class', 'span9 required ')
		->setAttrib("placeholder","00,00")
		->addErrorMessage("Deve ser informado uma pessoa");
		$this->addElement($aliqrealst__cofins);
		
	}
	
	
}