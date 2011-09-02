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
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->Permissao = new Application_Model_Permissao();
		
		// atualizando a camada de visão com base na action solicitada
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('controlador','acao','modificado','criado');
				$select = $this->Permissao->select()->order('controlador ASC')->limit(20);
				$this->view->data = $this->Permissao->fetchAll($select)->toArray();
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
