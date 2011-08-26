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
		if (!isset($this->Sessao->usuario) && $this->getRequest()->getPathInfo() != '/usuarios/login')
		{
			$this->Sessao->msg = 'Autenticação necessária !!!';
			$this->_redirect('usuarios/login');
		}

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
	 * @param	integer	$pag	Número da Página
	 * @return	void
	 */
	public function listarAction($pag=1)
	{
		$this->view->titulo  = 'Lista';
		$this->renderScript('index/listar.phtml');
	}

	/**
	 * Exibe a tela de edição do cadastro
	 * 
	 * @param	integer	$id	ID do registro a ser editado
	 * @return	void
	 */
	public function editarAction($id=0)
	{
		$this->view->titulo  = 'Edição';
		$this->renderScript('index/editar.phtml');
	}

	/**
	 * Exibe a tela de exclusão do cadastro
	 * 
	 * @return	void
	 */
	public function novoAction()
	{
		$this->view->titulo  = 'Inclusão';
		$this->renderScript('index/editar.phtml');
	}

	/**
	 * Exibe a tela de exclusão do cadastro
	 * 
	 * @param	integer	$id	ID do registro a ser editado
	 * @return	void
	 */
	public function excluirAction($id=0)
	{
		$this->view->titulo  = 'Exclusão';
		$this->view->excluir = true;
		$this->renderScript('index/editar.phtml');
	}

	/**
	 * Executa a exclusão do registro passado no parâmetro
	 * 
	 * @param	integer	$id	ID a ser excluido
	 * @return	void
	 */
	public function deleteAction($id=0)
	{
		$this->_redirect('listar');
	}
}

?>
