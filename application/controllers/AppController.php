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
	 * Inicia o model do controlador filho
	 * Verifica se o usuário está logado, caso não esteja, redireciona para tela de login.
	 * Atualiza a camada de visão com alguns itens importantes de cada controlador.
	 * Verifica a permissão do usuário com o método solicitado, caso o mesmo não tenha 
	 * permissão, redireciona para a tela de erro de permissão.
	 * Exibe as telas de: paginação, edição, inclusão e exclusão
	 * 
	 * @return void
	 */
	public function init()
	{
		// sem model, nem a pau juvenal
		if (!isset($this->model)) die('O nome do model é obrigatório !!!');

		// configuração padrão para a camada de visão (em segundos)
		$this->view->tempoOn = 300;

		// instanciando sessão e configurando alguns itens
		$this->Sessao	= new Zend_Session_Namespace(SISTEMA);
		$this->Sessao->setExpirationSeconds($this->view->tempoOn);

		// instanciando o model
		$model			= $this->model;
		$tmpModel		= 'Application_Model_'.$model;
		$this->$model	= new $tmpModel();

		// jogando a mensagem para a visão
		if (isset($this->Sessao->msg))
		{
			$this->view->msg = $this->Sessao->msg;
			unset($this->Sessao->msg);
		}

		// se não está logado é redirecionado para a tela de login
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
		$this->view->posicao 		= ucfirst(mb_strtolower($this->view->controllerName)).' | '.ucfirst(mb_strtolower($this->view->actionName));

		// configurando as opções de menu de módulo
		$this->view->menuModulos	= array();
		$this->view->menuModulos['Sistema']	= 'cidades/listar/'.$this->getPag('cidades','num').'/'.$this->getPag('cidades','ord').'/'.$this->getPag('cidades','dir');

		// configurando as opções de menu para o módulo sistema
		if (in_array($this->view->controllerName,array('cidades','estados','usuarios','perfis','permissoes')) && in_array($this->view->actionName,array('editar','novo','exclur','listar')))
		{
			$this->view->subMenuModulos = array();
			$this->view->subMenuModulos['Cidades']	 = 'cidades/listar/'	.$this->getPag('cidades','num')		.'/'.$this->getPag('cidades','ord')		.'/'.$this->getPag('cidades','dir');
			$this->view->subMenuModulos['Estados']	 = 'estados/listar/'	.$this->getPag('estados','num')		.'/'.$this->getPag('estados','ord')		.'/'.$this->getPag('estados','dir');
			$this->view->subMenuModulos['Perfis']	 = 'perfis/listar/'		.$this->getPag('perfis','num')		.'/'.$this->getPag('perfis','ord')		.'/'.$this->getPag('perfis','dir');
			$this->view->subMenuModulos['Permissões']= 'permissoes/listar/'	.$this->getPag('permissoes','num')	.'/'.$this->getPag('permissoes','ord')	.'/'.$this->getPag('permissoes','dir');
			$this->view->subMenuModulos['Usuários']	 = 'usuarios/listar/'	.$this->getPag('usuarios','num')	.'/'.$this->getPag('usuarios','ord')	.'/'.$this->getPag('usuarios','dir');
		}

		// configurando as propriedades de cada campo que será usada na view
		$this->view->campos	= isset($this->view->campos) ? $this->view->campos : array();
		$this->view->campos['nome']['label'] 			= 'Nome';
		$this->view->campos['nome']['td']['width']		= '300px';

		$this->view->campos['ultimo_acesso']['label']	= 'Último Acesso';
		$this->view->campos['ultimo_acesso']['mascara']	= '99/99/9999 99:99:99';

		$this->view->campos['estado']['label']			= 'Estado';
		$this->view->campos['estado']['td']['width']	= '230px';

		$this->view->campos['login']['label']			= 'Login';

		$this->view->campos['uf']['label']				= 'Uf';
		$this->view->campos['uf']['td']['width']		= '50px';
		$this->view->campos['uf']['td']['align']		= 'center';

		$this->view->campos['controlador']['label']		= 'Cadastro';

		$this->view->campos['acao']['label']			= 'Ação';

		$this->view->campos['criado']['label']			= 'Criado';
		$this->view->campos['criado']['td']['width']	= '130px';
		$this->view->campos['criado']['td']['align']	= 'center';
		$this->view->campos['criado']['mascara']		= '99/99/9999 99:99:99';
		$this->view->campos['modificado']['label']		= 'Modificado';
		$this->view->campos['modificado']['mascara']	= '99/99/9999 99:99:99';
		$this->view->campos['modificado']['td']['width']= '130px';
		$this->view->campos['modificado']['td']['align']= 'center';
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
	public function listarAction($num=1, $ord='', $dir='asc')
	{
		if (!isset($this->view->listaCampos)) die('Os campos para a lista não foram definidos !!!');
		$model = $this->model;
		$toPag = 20;

		$this->setPag($num,$ord,$dir);
		$num = $this->getPag($this->view->controllerName,'num');
		$ord = $this->getPag($this->view->controllerName,'ord');
		$dir = $this->getPag($this->view->controllerName,'dir');

		$this->view->listaFerramentas 	= (isset($this->view->listaFerramentas)) 	? $this->view->listaFerramentas : array();
		$this->view->listaBotoes		= (isset($this->view->listaBotoes)) 		? $this->view->listaBotoes		: array();
		if (!isset($this->view->titulo)) $this->view->titulo  = 'Lista';
		if (!isset($this->view->listaBotoes['Novo'])) $this->view->listaBotoes['Novo'] = URL . strtolower($this->view->controllerName) . '/novo';

		// configurando a SQL a ser executada
		$this->select->limit($toPag,($num*$toPag)-$toPag);
		$this->select->order($ord.' '.$dir);

		$data = $this->$model->fetchAll($this->select)->toArray();
		$this->view->data = $data;
		foreach($data as $_linha => $_arrCampos)
		{
			if (!isset($this->view->listaFerramentas['editar'])) 	// botão padrão editar
			{
				$this->view->listaFerramentas['editar']['img']  = URL . '/img/bt_editar.png';
				//$this->view->listaFerramentas['editar']['link'] = URL . strtolower($this->view->controllerName) . '/editar/'.$_arrCampos['id'];
				$this->view->listaFerramentas['editar']['link'] = URL . strtolower($this->view->controllerName) . '/editar/{id}';
			}
			if (!isset($this->view->listaFerramentas['excluir']))			// botão padrão excluir
			{
				$this->view->listaFerramentas['excluir']['img']  = URL . '/img/bt_excluir.png';
				$this->view->listaFerramentas['excluir']['link'] = URL . strtolower($this->view->controllerName) . '/excluir/{id}';
			}
			if (!isset($this->view->listaFerramentas['imprimir']))	// botão padrão imprimir
			{
				$this->view->listaFerramentas['imprimir']['img']  = URL . '/img/bt_imprimir.png';
				$this->view->listaFerramentas['imprimir']['link'] = URL . strtolower($this->view->controllerName) . '/imprimir/{id}';
			}
		}
		/*
		//
		$paginator = Zend_Paginator::factory($res);
		$paginator->setItemCountPerPage(2);
		$paginator->setCurrentPageNumber($this->_request->getParam('page'));

		$this->view->paginator = $paginator;
		*/
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
		$model 	= $this->model;
		$this->view->data = $this->$model->fetchRow($this->$model->select()->where('id='.$id));
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
		$model 	= $this->model;
		$this->view->data = $this->$model->fetchRow($this->$model->select()->where('id='.$id));
		$this->view->excluir = true;
		$this->renderScript('app/editar.phtml');
	}

	/**
	 * Exibe a tela de impressão do cadastro
	 * 
	 * @param	integer	$id	ID do registro a ser editado
	 * @return	void
	 */
	public function imprimirAction($id=0)
	{
		if (!isset($this->view->titulo)) $this->view->titulo  = 'Impressão';
		$model 	= $this->model;
		$this->view->data = $this->$model->fetchRow($this->$model->select()->where('id='.$id));
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
		$model 	= $this->model;
		//if ($this->$model->delete($id))
		$this->_redirect('listar');
	}

	/**
	 * Configura os parâmetros da página do cadastro corrente
	 * 
	 * @param	integer		$num	Número da página
	 * @param	string		$ord	Ordenação
	 * @param	string		$dir	Direção ASC|DESC
	 */
	private function setPag($num=1, $ord='', $dir='asc')
	{
		$controlador = $this->getRequest()->getControllerName();
		$this->Sessao->$controlador->pag['num'] = $num;
		$this->Sessao->$controlador->pag['ord'] = $ord;
		$this->Sessao->$controlador->pag['dir'] = $dir;
	}

	/**
	 * Retorna o parâmetro da paginação.
	 * 
	 * @param	string	$controlador	Nome do controlador, importante pra identificar na sessão.
	 * @param	string	$param			Nome do campo a ser retornado, pagina, ordem ou direção
	 * @return	string	$campo			Valor do parâmetro solicitado
	 */
	public function getPag($controlador=null, $param=null)
	{
		$this->Sessao->$controlador->pag['num'] = isset($this->Sessao->$controlador->pag['num']) ? $this->Sessao->$controlador->pag['num'] : 1;
		$this->Sessao->$controlador->pag['ord'] = isset($this->Sessao->$controlador->pag['ord']) ? $this->Sessao->$controlador->pag['ord'] : '';
		$this->Sessao->$controlador->pag['dir'] = isset($this->Sessao->$controlador->pag['dir']) ? $this->Sessao->$controlador->pag['dir'] : 'asc';
		return $this->Sessao->$controlador->pag[$param];
	}
}
?>
