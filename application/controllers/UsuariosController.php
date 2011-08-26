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
include_once('AppController.php');
class UsuariosController extends AppController {
	/**
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->Usuario 	= new Application_Model_Usuario();

		// definindo o link posição
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->posicao = 'Usuários | Listar';
				break;
			case 'editar':
				$this->view->posicao = 'Usuários | Edição';
				break;
			case 'novo':
				$this->view->posicao = 'Usuários | Inclusão';
				break;
		}

		// atualizando a view
		if (in_array($this->getRequest()->getActionName(),array('editar','novo','excluir','listar','info','login')))
		{
			$this->view->campos['login']['label'] 			= 'Login';
			$this->view->campos['nome']['label'] 			= 'Nome';
			$this->view->campos['email']['label'] 			= 'e-mail';
			$this->view->campos['acessos']['label'] 		= 'Acessos';
			$this->view->campos['ultimo_acesso']['label'] 	= 'Último Acesso';
			$this->view->campos['ultimo_acesso']['mascara']	= '99/99/9999 99:99:99';
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
		$this->_redirect('/');
	}

	/**
	 * Exibe a tela de informação do usuário logado
	 * 
	 * @return	void
	 */
	public function infoAction()
	{
		$this->view->titulo  = 'Informações do Usuário';
		$this->view->posicao = 'Usuários | Informações';
	}
}

