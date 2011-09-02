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
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->posicao 		= 'Usuários | Listar';
				$this->view->listaCampos	= array('login','nome','modificado','criado');
				$select = $this->Usuario->select()
					->from(array('u'=>'usuarios'), array())
					->order('login ASC')
					->limit(10);
				$this->view->data = $this->Usuario->fetchAll($select)->toArray();
				break;
			case 'editar':
				$this->view->posicao = 'Usuários | Edição';
				break;
			case 'novo':
				$this->view->posicao = 'Usuários | Inclusão';
				break;
		}
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

					// recuperando dados do usuário
					$this->Sessao->usuario['login'] 		= $dataUs['login'];
					$this->Sessao->usuario['nome']			= $dataUs['nome'];
					$this->Sessao->usuario['email']			= $dataUs['email'];
					$this->Sessao->usuario['acessos']		= $dataUs['acessos'];
					$this->Sessao->usuario['ultimo_acesso']	= $dataUs['ultimo_acesso'];
	
					// jogando na sessão os perfis do usuário
					
					// jogando na sessão as permissão do usuário
					//$this->Sessao->permissao['usuarios'] = array('editar');

					// msg de saudação
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
		unset($this->Sessao->permissao);
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

