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
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		// atualizando a view
		$this->view->controllerName = $this->getRequest()->getControllerName();
		$this->view->actionName 	= $this->getRequest()->getActionName();

		// instanciando alguns objetos internos para usuário
		$this->Sessao	= new Zend_Session_Namespace(SISTEMA);
		$this->Usuario 	= new Application_Model_Usuario();
		if (isset($this->Sessao->msg))
		{
			$this->view->msg = $this->Sessao->msg;
			unset($this->Sessao->msg);
		}
	}

	/**
	 * Redireciona para a tela de login ou para tela de informação caso o usuário já esteja logado
	 * 
	 * @return void
	 */
	public function indexAction()
	{
		if (isset($this->Sessao->usuario)) $this->_redirect('usuarios/login'); else $this->_redirect('usuarios/info');
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
		if (isset($this->Sessao->usuario)) $this->_helper->redirector('info');

		$this->view->titulo 	= 'Login';
        $this->view->posicao	= 'Usuários | Login';
        $this->view->on_read	= '$("#ed_login").focus()';

        if ($this->getRequest()->isPost())
        {
			$dataForm	= $this->getRequest()->getPost();
			try
			{
				$filtro		= 'login="'.$dataForm['ed_login'].'" AND senha=sha1("'.$dataForm['ed_senha'].'")';
				$dataUsuario= $this->Usuario->fetchRow($this->Usuario->select()->where($filtro));
				if (!empty($dataUsuario))
				{
					$dataUs = $dataUsuario->toArray();
					$this->Sessao->usuario['login'] 		= $dataUs['login'];
					$this->Sessao->usuario['nome']			= $dataUs['nome'];
					$this->Sessao->usuario['email']			= $dataUs['email'];
					$this->Sessao->usuario['acessos']		= $dataUs['acessos'];
					$this->Sessao->usuario['ultimo_acesso']	= $dataUs['ultimo_acesso'];					
					$this->Sessao->msg = 'Usuário autenticado com sucesso !!!';

					$this->Usuario->update(array('acessos'=>$dataUs['acessos']+1, 'ultimo_acesso'=>date('Y/m/d h:i:s')), 'id='.$dataUs['id']);
					
					$this->_helper->redirector('info');
				} else
				{
					$this->view->msg = 'Usuário inválido !!!';
				}
				
			} catch (Exception $e)
			{
				switch($e->getCode())
				{
					case 1045:
					case 1049:
					case 42:
						echo $e->getMessage();
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
		unset($this->Sessao->usuario);
		unset($this->Sessao->perfis);
		$this->_helper->redirector('index', 'Index');
	}

	/**
	 * Exibe a tela de informação do usuário logado
	 * 
	 * @return	void
	 */
	public function infoAction()
	{
		$this->view->usuario = isset($this->Sessao->usuario) ? $this->Sessao->usuario : array();
		$this->view->perfis  = isset($this->Sessao->perfis)  ? $this->Sessao->perfis  : array();
		$this->view->titulo  = 'Informações do Usuário';
		$this->view->posicao = 'Usuários | Informações';
	}
}

