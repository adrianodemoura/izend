<?php
/**
 * Controlador para cadastro de usuários
 * 
 * Usuário administrador não pode ser excluído
 * 
 * @package		admin
 * @subpackage	admin.controller
 */
/**
 * @package		admin
 * @subpackage	admin.controller
 */
class Admin_UsuariosController extends AppController {
	/**
	 * Nome do model
	 * 
	 * @var		private
	 * @access	protected
	 */
	protected $model	= 'Usuario';

	/**
	 * Exibe a tela de listagem
	 * 
	 * @param	integer	$pag	Número da página a ser listada
	 * @return	void
	 */
	public function listarAction()
	{
		$this->view->posicao 		= 'Usuários | Listar';
		$this->view->campos['login']['td']['align']	= 'center';
		$this->view->listaCampos	= array('login','nome','modificado','criado');
		$this->view->listaFerramentas = array();
		$this->view->listaFerramentas['excluir']['img'] = $this->getRequest()->getHttpHost() . '/img/bt_excluir_off.png';
		$this->view->listaFerramentas['excluir'][1] 	= true;
		$this->view->listaPesquisa	 				= array();
		$this->view->listaPesquisa['login']			= 'Login';
		$this->view->listaPesquisa['email']			= 'e-mail';
		$this->view->listaPesquisa['nome']			= 'Nome';
		

		$this->select = $this->Usuario->select();
		$this->select->setIntegrityCheck(false);
		$this->select->from(array('u'=>'usuarios'), array('u.id','u.login','u.nome','u.modificado','u.criado'));
		parent::listarAction();
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
		Zend_Session::destroy();
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

	/**
	 * Exibe a tela de expiração
	 * 
	 * Quando a sessão expira, o usuário é direcionado para esta tela.
	 * 
	 * @retun	void
	 */
	public function expiradoAction()
	{
		Zend_Session::destroy();
	}
}

