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
	 * O conteúdo da paginação é salvo no cache, (memcache), caso o cadastro sofra alguma alteração o mesmo deve ser refeito.
	 * 
	 * @return void
	 */
	public function init()
	{
		// sem model, nem a pau juvenal
		if (!isset($this->model)) die('O nome do model é obrigatório !!!');

		// configuração padrão para a camada de visão (em segundos)
		$this->view->tempoOn = isset($this->view->tempoOn) ? $this->view->tempoOn : 300;

		// instanciando sessão e configurando alguns itens
		$this->Sessao	= new Zend_Session_Namespace(SISTEMA);
		$this->Sessao->setExpirationSeconds($this->view->tempoOn);

		// instanciando cache, caso ele esteja instaldo
		if( class_exists('Memcache') )
		{
			$frontendOpts	= array('caching' => true, 'lifetime' => 1800, 'automatic_serialization' => true);
			$backendOpts	= array('servers' =>array(array('host' => '127.0.0.1','port' => 11211)),'compression' => false);
			$this->Cache	= Zend_Cache::factory('Core', 'Memcached', $frontendOpts, $backendOpts);
		}

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
		$this->view->menuModulos['Sistema']	= 'cidades';

		// configurando as opções de menu para o módulo sistema
		if (in_array($this->view->controllerName,array('cidades','estados','usuarios','perfis','permissoes')) && in_array($this->view->actionName,array('editar','novo','exclur','listar')))
		{
			$this->view->subMenuModulos = array();
			$this->view->subMenuModulos['Cidades']	 = 'cidades';
			$this->view->subMenuModulos['Estados']	 = 'estados';
			$this->view->subMenuModulos['Perfis']	 = 'perfis';
			$this->view->subMenuModulos['Permissões']= 'permissoes';
			$this->view->subMenuModulos['Usuários']	 = 'usuarios';
		}

		// configurando as propriedades de cada campo que será usada na view
		$this->view->campos	= isset($this->view->campos) ? $this->view->campos : array();
		$this->view->campos['nome']['label'] 			= 'Nome';
		$this->view->campos['nome']['th']['width']		= '300px';

		$this->view->campos['ultimo_acesso']['label']	= 'Último Acesso';
		$this->view->campos['ultimo_acesso']['mascara']	= '99/99/9999 99:99:99';

		$this->view->campos['estado']['label']			= 'Estado';
		$this->view->campos['estado']['th']['width']	= '230px';

		$this->view->campos['login']['label']			= 'Login';
		$this->view->campos['login']['th']['width']		= '100px';

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
	 * Redireciona para a tela de lista
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
	 * @param	integer	$num	Número da Página
	 * @param	string	$ord	Campo de ordem
	 * @param	string	$dir	Ordenação, ascendente ou descendente
	 * @return	void
	 */
	public function listarAction()
	{
		if (!isset($this->view->listaCampos)) die('Os campos para a lista não foram definidos !!!');
		$model = $this->model;
		$this->view->totPag = isset($this->view->totPag) ? $this->view->totPag : 20;

		// configurando os parâmetros da paginação
		$arrPams = $this->getRequest()->getParams();
		$param['num'] = isset($arrPams['num']) ? $arrPams['num'] : 1;
		$param['ord'] = isset($arrPams['ord']) ? $arrPams['ord'] : '';
		$param['dir'] = isset($arrPams['dir']) ? $arrPams['dir'] : 'asc';
		if (empty($param['ord'])) if (isset($this->$model->_order)) $param['ord'] = $this->$model->_order;
		if (empty($param['ord'])) die('O campo de ordenação não foi definido !!!');

		// configurando alguns parâmetros da visão listar
		$this->view->param				= $param;
		$this->view->listaFerramentas 	= (isset($this->view->listaFerramentas)) 	? $this->view->listaFerramentas : array();
		$this->view->listaBotoes		= (isset($this->view->listaBotoes)) 		? $this->view->listaBotoes		: array();
		$this->view->titulo				= (isset($this->view->titulo))				? $this->view->titulo			: 'Lista';
		$this->view->listaBotoes['Novo']= (isset($this->view->listaBotoes['Novo']))	? $this->view->listaBotoes['Novo'] : URL . strtolower($this->view->controllerName) . '/novo';

		// se o memcache está instalado
		if( class_exists('Memcache') )
		{
			// descobrindo o total da consulta sem a paginação e calculando a última página, primeiro pesquisa no cache
			if (!$this->view->totReg = $this->Cache->load('totReg_'.$this->$model->getName()))
			{
				$this->view->totReg = count($this->$model->fetchAll('select count(*) FROM '.$this->$model->getName())->toArray());
				$this->Cache->save($this->view->totReg, 'totReg_'.$this->$model->getName());
				$this->view->msg = 'Salvei o total no cache ...';
			} else
			{
				$this->view->msg = 'Recuperei o total no cache ...';
			}
		} else
		{
			$this->view->totReg = count($this->$model->fetchAll('select count(*) FROM '.$this->$model->getName())->toArray());
			$this->view->msg = 'O cache não está instalado !!!';
		}
		
		$this->view->ultPag = round($this->view->totReg/$this->view->totPag)+1;

		// configurando a ordem e o limite da paginação
		$this->select->order($param['ord'].' '.$param['dir']);
		$this->select->limit($this->view->totPag,($param['num']*$this->view->totPag)-$this->view->totPag);

		// se o memcache está instalado
		if( class_exists('Memcache') )
		{
			// recuperando o a página do banco, primeiro pesquisa no cache
			if (!$data = $this->Cache->load('pag_'.$param['num'].'_'.$this->$model->getName()))
			{
				$data = $this->$model->fetchAll($this->select)->toArray();
				$this->Cache->save($data, 'pag_'.$param['num'].'_'.$this->$model->getName());
				$this->view->msg = '<span style="color: blue; font-bold: weight;">Salvei a página '.$param['num'].' no cache ...</span>';
			} else
			{
				$this->view->msg = '<span style="color: green; font-bold: weight;">Recuperei a página '.$param['num'].' no cache ...</span>';
			}
		} else
		{
			$data = $this->$model->fetchAll($this->select)->toArray();
			$this->view->msg = 'O cache não está instalado !!!';
		}
		$this->view->data = $data;

		if (!isset($this->view->listaFerramentas['editar'])) 	// botão padrão editar
		{
			$this->view->listaFerramentas['editar']['img']  = URL . '/img/bt_editar.png';
			$this->view->listaFerramentas['editar']['link'] = URL . strtolower($this->view->controllerName) . '/editar/id/{id}';
		}
		if (!isset($this->view->listaFerramentas['excluir']))			// botão padrão excluir
		{
			$this->view->listaFerramentas['excluir']['img']  = URL . '/img/bt_excluir.png';
			$this->view->listaFerramentas['excluir']['link'] = URL . strtolower($this->view->controllerName) . '/excluir/id/{id}';
		}
		if (!isset($this->view->listaFerramentas['imprimir']))	// botão padrão imprimir
		{
			$this->view->listaFerramentas['imprimir']['img']  = URL . '/img/bt_imprimir.png';
			$this->view->listaFerramentas['imprimir']['link'] = URL . strtolower($this->view->controllerName) . '/imprimir/id/{id}';
		}

		$this->renderScript('app/listar.phtml');
	}

	/**
	 * Exibe a tela de edição do cadastro
	 * 
	 * @param	integer	$id Id a ser editado
	 * @return	void
	 */
	public function editarAction()
	{
		$id = $this->getId();

		$this->view->titulo = (isset($this->view->titulo)) ? $this->view->titulo : 'Edição';

		$model = $this->model;
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
	 * @param	integer	$id	Id do registro a ser recuperado
	 * @return	void
	 */
	public function excluirAction()
	{
		$id = $this->getId();

		$this->view->titulo = (isset($this->view->titulo)) ? $this->view->titulo : 'Edição';

		$model = $this->model;
		$this->view->data = $this->$model->fetchRow($this->$model->select()->where('id='.$id));
		$this->view->excluir = true;
		$this->renderScript('app/editar.phtml');
	}

	/**
	 * Exibe a tela de impressão do cadastro
	 * 
	 * @return	void
	 */
	public function imprimirAction()
	{
		$id = $this->getId();

		$this->view->titulo = (isset($this->view->titulo)) ? $this->view->titulo : 'Impressão';

		$model = $this->model;
		$this->view->data = $this->$model->fetchRow($this->$model->select()->where('id='.$id));
		$this->renderScript('app/editar.phtml');
	}

	/**
	 * Executa a exclusão do registro passado no parâmetro
	 * 
	 * @param	integer	$id	Id do registro a ser recuperado no banco de dados
	 * @return	void
	 */
	public function deleteAction()
	{
		$model = $this->model;
		//if ($this->$model->delete($id))
		$this->_redirect('listar');
	}

	/**
	 * Retorna o id passado no parâmetro
	 * 
	 * @return	integer $id Número do id
	 */
	private function getId()
	{
		$id = $this->getRequest()->getParam('id');
		return $id;
	}
}
?>
