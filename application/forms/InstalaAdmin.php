<?php
/**
 * Formulário de instalação do administrador do sistema
 * 
 * @package		izend
 * @subpackage	izend.form
 */
/**
 * @package		izend
 * @subpackage	izend.form
 */
class Application_Form_InstalaAdmin extends Zend_Form {
	/**
	 * Método de inicialização
	 */
	public function init()
	{
		$this->setMethod('post');
		$this->addElement('text'  	, 'nome'  , array('label'   => 'Nome'	, 'size'=>50, 'filters' => array('StringToUpper'), 'required'   => true ));
		$this->addElement('text'  	, 'email' , array('label'   => 'e-mail'	, 'size'=>50, 'filters' => array('StringToLower'), 'required'   => true, 'validators' => array('EmailAddress',) ));
		$this->addElement('text'  	, 'login' , array('label'   => 'login'	, 'required'   => true, 'value'=>'admin'));
		$this->addElement('password', 'senha' , array('label'   => 'Senha'  , 'required'   => true,));
		$this->addElement('password', 'senha2', array('label'   => 'Confirmar Senha', 'required'   => true,));
        $this->addElement('submit'	, 'enviar', array('ignore'	=> true,'label' => 'Enviar', 'class' => 'btEnviar'));
		$this->addElement('hash'	, 'csrf'  , array('ignore'  => true,));
	}
}
?>
