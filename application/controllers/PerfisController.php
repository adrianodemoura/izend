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
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->Perfil = new Application_Model_Perfil();
		
		// atualizando a camada de visão com base na action solicitada
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('nome','modified','created');
				$select = $this->Perfil->select()->order('nome ASC')->limit(20);
				$this->view->data = $this->Perfil->fetchAll($select)->toArray();
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
