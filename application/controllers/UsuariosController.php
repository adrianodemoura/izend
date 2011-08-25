<?php
/**
 * Controlador para cadastro de usuários
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class UsuariosController extends Zend_Controller_Action {
	/**
	 * Antes de tudo
	 * 
	 * @return void
	 */
	public function init()
	{
		$this->view->controllerName = $this->getRequest()->getControllerName();
		$this->view->actionName 	= $this->getRequest()->getActionName();
		$this->sessao = new Zend_Session_Namespace(SISTEMA);
	}

	/**
	 * Redireciona para a tela de login ou para tela de informação caso o usuário já esteja logado
	 * 
	 * @return void
	 */
	public function indexAction()
	{
		if (isset($this->sessaon->usuario)) $this->_redirect('usuarios/login'); else $this->_redirect('usuarios/info');
	}

	/**
	 * Exibe a tela principal
	 * 
	 * Caso o usuário já esteja autenticado redireciona para a tela de informações.
	 * 
	 * @return void
	 */
    public function loginAction()
    {
		if (isset($this->sessao->usuario)) $this->_helper->redirector('info');

		$this->view->titulo 	= 'iZend - login';
        $this->view->posicao	= array(1=>'Usuários', 2=>'Login');
        $this->view->on_read	= '$("#ed_login").focus()';

        if ($this->getRequest()->isPost())
        {
			$dataForm	= $this->getRequest()->getPost();
			$Usuario 	= new Application_Model_Usuario_Table();
			try
			{
				$dataUsuario= $Usuario->fetchAll();
				echo '<pre>'.print_r($dataUsuario).'</pre>';
				/*$this->sessao->usuario = array('id'=>2, 'login'=>'adrianoc', 'nome'=>'Adriano C. de Moura', 'acessos'=>49);
				$this->view->usuario = $sessao->usuario;
				$this->_helper->redirector('info', 'usuarios');
				*/
			} catch (Exception $e)
			{
				switch($e->getCode())
				{
					case 1045:
					case 1049:
					case 42:
						$this->_redirect('ferramentas/instalardb');
						break;
				}
			}
		}
    }

	/**
	 * Executa logoff do sistema
	 * 
	 * @return	void
	 */
	public function sairAction()
	{
		unset($this->sessao->usuario);
		unset($this->sessao->perfis);
		$this->_helper->redirector('index', 'Index');
	}

	/**
	 * Exibe a tela de informação do usuário logado
	 * 
	 * @return	void
	 */
	public function infoAction()
	{
		$this->view->usuario = isset($this->sessao->usuario) ? $this->sessao->usuario : array();
		$this->view->perfis  = isset($this->sessao->perfis)  ? $this->sessao->perfis  : array();
	}
}

