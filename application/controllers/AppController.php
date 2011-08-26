<?php
/**
 * Controlador pai de todos
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class AppController extends Zend_Controller_Action {
	/**
	 * Método de inicialização do controlador.
	 * Verifica se o usuário está logado, castro contrário redireciona para tela de login
	 * Atualiza a camada de visão com alguns itens importantes a todo o sittema.
	 * 
	 * @return void
	 */
	public function init()
	{
		// instanciando sessão
		$this->Sessao	= new Zend_Session_Namespace(SISTEMA);
		if (isset($this->Sessao->msg))
		{
			$this->view->msg = $this->Sessao->msg;
			unset($this->Sessao->msg);
		}
		if (!isset($this->Sessao->usuario) && $this->getRequest()->getPathInfo() != '/usuarios/login') $this->_redirect('usuarios/login');

		// atualizando a view
		$this->view->controllerName = $this->getRequest()->getControllerName();
		$this->view->actionName 	= $this->getRequest()->getActionName();
		$this->view->usuario 		= isset($this->Sessao->usuario) ? $this->Sessao->usuario : array();
		$this->view->perfis  		= isset($this->Sessao->perfis)  ? $this->Sessao->perfis  : array();
		$this->view->campos			= array();
		$this->view->posicao 		= $this->view->controllerName.' | '.$this->view->actionName;
	}

	/**
	 * Exibe a lista do cadastro
	 * 
	 * @return	void
	 */
	public function listarAction()
	{
		$this->view->titulo  = 'Lista';
	}

	/**
	 * Exibe a tela de edição lista do cadastro
	 * 
	 * @return	void
	 */
	public function editarAction()
	{
		$this->view->titulo  = 'Edição';
	}
}

