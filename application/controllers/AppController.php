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
	 * 
	 * Inicia a sessão para todos os controladores.
	 * Verifica se o usuário está logado, caso não esteja, redireciona para tela de login.
	 * Atualiza a camada de visão com alguns itens importantes de cada controlador.
	 * Verifica a permissão do usuário com o método solicitado, caso o mesmo não tenha 
	 * permissão, redireciona para a tela de erro de permissão.
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
		
		// área administrativa só logado
		if (!isset($this->Sessao->usuario) && $this->getRequest()->getPathInfo() != '/usuarios/login')
		{
			$this->Sessao->msg = 'Autenticação necessária !!!';
			$this->_redirect('usuarios/login');
		}

		// verifica permissão
		if ($this->getRequest()->getPathInfo() != '/usuarios/sair')
		{
			if (!$this->getPermissao()) $this->_redirect('index/erro_permissao');
		}

		// atualizando a view com algumas propriedades genéricas a todas as telas
		$this->view->url			= URL;
		$this->view->controllerName = $this->getRequest()->getControllerName();
		$this->view->actionName 	= $this->getRequest()->getActionName();
		$this->view->usuario 		= isset($this->Sessao->usuario)  ? $this->Sessao->usuario   : array();
		$this->view->perfis  		= isset($this->Sessao->perfis)   ? $this->Sessao->perfis    : array();
		$this->view->permissao 		= isset($this->Sessao->permissao)? $this->Sessao->permissao : array();
		$this->view->campos			= array();
		$this->view->posicao 		= $this->view->controllerName.' | '.$this->view->actionName;
		//$url						= rtrim(Zend_Controller_Front::getInstance()->getBaseUrl(),'/').'/';

		// configurando as opções de menu de módulo
		$this->view->menuModulos	= array();
		$this->view->menuModulos['Clientes']= 'clientes';
		$this->view->menuModulos['Sistema']	= 'cidades';

		// configurando as opções de menu de cada módulo
		if (in_array($this->view->controllerName,array('cidades','estados','usuarios','perfis','permissoes')))
		{
			$this->view->subMenuModulos = array();
			$this->view->subMenuModulos['Cidades']	 = 'cidades';
			$this->view->subMenuModulos['Estados']	 = 'estados';
			$this->view->subMenuModulos['Perfis']	 = 'perfis';
			$this->view->subMenuModulos['Permissões']= 'permissoes';
			$this->view->subMenuModulos['Usuários']	 = 'usuarios';
		}
		
		// configurando as propriedades de cada campo que será usada na view
		$this->view->campos			= array();
		$this->view->campos['nome']['label'] = 'Nome';
		$this->view->campos['ultimo_acesso']['label']	= 'Último Acesso';
		$this->view->campos['ultimo_acesso']['mascara']	= '99/99/9999 99:99:99';
		$this->view->campos['created']['label']			= 'Criado';
		$this->view->campos['created']['mascara']		= '99/99/9999 99:99:99';
		$this->view->campos['modified']['label']		= 'Modificado';
		$this->view->campos['modified']['mascara']		= '99/99/9999 99:99:99';
	}

	/**
	 * Verifia a permissão do usuário logado
	 * 
	 * @return	boolean	Verdadeir|Falso
	 */
	public function getPermissao()
	{
		$controlador = strtolower($this->getRequest()->getControllerName());
		$acao		 = strtolower($this->getRequest()->getActionName());
		$permissoes	 = $this->Sessao->permissao;
		if (count($permissoes))
		{
			foreach($permissoes as $_controlador => $_arrAcao)
			{
				if ($_controlador == $controlador && in_array($acao,$_arrAcao) ) return false;
			}
		}
		return true;
	}

	/**
	 * Redireciona para a tela de login ou para tela de informação caso o usuário já esteja logado
	 * 
	 * @return void
	 */
	public function indexAction()
	{
		$this->_helper->redirector('listar');
	}

	/**
	 * Exibe a lista do cadastro
	 * 
	 * @param	integer	$pag	Número da Página
	 * @return	void
	 */
	public function listarAction($pag=1)
	{
		if (!isset($this->view->titulo)) $this->view->titulo  = 'Lista';
		$this->renderScript('app/listar.phtml');
	}

	/**
	 * Exibe a tela de edição do cadastro
	 * 
	 * @param	integer	$id	ID do registro a ser editado
	 * @return	void
	 */
	public function editarAction($id=0)
	{
		if (!isset($this->view->titulo)) $this->view->titulo  = 'Edição';
		$this->renderScript('app/editar.phtml');
	}

	/**
	 * Exibe a tela de exclusão do cadastro
	 * 
	 * @return	void
	 */
	public function novoAction()
	{
		if (!isset($this->view->titulo)) $this->view->titulo  = 'Inclusão';
		$this->renderScript('app/editar.phtml');
	}

	/**
	 * Exibe a tela de exclusão do cadastro
	 * 
	 * @param	integer	$id	ID do registro a ser editado
	 * @return	void
	 */
	public function excluirAction($id=0)
	{
		if (!isset($this->view->titulo)) $this->view->titulo  = 'Exclusão';
		$this->view->excluir = true;
		$this->renderScript('app/editar.phtml');
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
