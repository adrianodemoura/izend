<?php
/**
 * Controlador para página inicial
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class IndexController extends Zend_Controller_Action
{
	/**
	 * Antes de tudo
	 * 
	 * @return void
	 */
	public function init()
	{
		$sessao = new Zend_Session_Namespace(SISTEMA);
		$this->view->controllerName = $this->getRequest()->getControllerName();
		$this->view->usuario		= isset($sessao->usuario) ? $sessao->usuario : array();
		$this->view->perfis			= isset($sessao->perfis)  ? $sessao->perfis : array();
	}

	/**
	 * Exibe a tela principal
	 * 
	 * @return void
	 */
    public function indexAction()
    {
        $this->view->titulo = 'iZend - Aplicação Exemplo';
    }

	/**
	 * 
	 */
	public function erropermissaoAction()
	{
	}
}

