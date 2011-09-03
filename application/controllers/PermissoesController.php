<?php
/**
 * Controller para o cadastro de permissões
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class PermissoesController extends AppController {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $model	= 'Permissao';

	/**
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();

		// atualizando a camada de visão com base na action solicitada
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->posicao		= 'Permissões | Listar';
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('controlador','acao','modificado','criado');
				$this->select				= $this->Permissao->select()->order('controlador ASC')->limit(20);
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
