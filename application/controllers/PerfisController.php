<?php
/**
 * Controller para o cadastro de estados
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class PerfisController extends AppController {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $model	= 'Perfil';

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
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('nome','modificado','criado');
				$this->select				= $this->Perfil->select()->order('nome ASC')->limit(20);
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
