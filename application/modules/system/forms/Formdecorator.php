<?php
class System_Form_Formdecorator extends Zend_Form
{
	protected $_formDecorator = array( 'FormElements', 'Fieldset', 'Form' );

	protected $_elementDecorator = array(
			'viewHelper',
			'label',
			array(
					array('row'=>'HtmlTag'),array('tag'=>'div', 'class' => 'row-fluid')
			));

	protected $_submitDecorator = array();

	public function __construct( $options = null ) {
		parent::__construct( $options );

		$this->setDecorators( $this->_formDecorator );
	}
	
	


	public function addDisplayGroup(array $elements, $name, $options = null) {

		parent::addDisplayGroup($elements, $name, $options);

		if( $name == 'botoes') {
			$this->getDisplayGroup($name)
			->addDecorators( array(
					'FormElements',
					array(
							'HtmlTag',
							array('tag'=>'div', 'style' => 'text-align:right')
					)
			) );
		} else {
			$this->getDisplayGroup($name)
			->addDecorators( array(
					'FormElements',
					array(
							
							'HtmlTag',
							array('tag'=>'div', 'class' => 'row-fluid formSep')
					)
			) )
			->removeDecorator('DtDdWrapper');
		}

	}


	public function addElement($element, $name = null, $options = null) {

		if( !is_string($element) ) {
			$element->setDisableLoadDefaultDecorators(true);

			if( ( $element instanceof Zend_Form_Element_Submit )
					|| ( $element instanceof Zend_Form_Element_Button )
					|| ( $element instanceof Zend_Form_Element_Reset ) ) {
				$element->addDecorators( $this->_submitDecorator )
				->removeDecorator('DtDdWrapper');
			} else {
				$element->addDecorators( $this->_elementDecorator );

			}
		}

		parent::addElement($element, $name, $options);

		return $this;
	}

	//função para montagem dos grupos (fieldsets)
	function  montandoGrupo( array $nome, $valor ){

		$this->addDisplayGroup($nome, $valor);

	}
	//função para definir a posição de cada elemento do formulário
	function configurandoTamanho( $nome, $valor ){
		$this->setElementDecorators(array(
				'viewHelper',
				'label',
				array(array('row'=>'HtmlTag'), array('tag'=>'div', 'class' => $valor)
				)),array($nome)
				);
	}
}