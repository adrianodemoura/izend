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
class EstadosController extends AppController {
	/**
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->Estado = new Application_Model_Estado();
		
		// atualizando a camada de visão com base na action solicitada
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('nome','uf');
				$select = $this->Estado->select()
					->order('nome ASC')
					->limit(20);
				$this->view->data = $this->Estado->fetchAll($select)->toArray();
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
