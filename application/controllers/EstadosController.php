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
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $model	= 'Estado';

	/**
	 * Exibe a tela de listagem
	 * 
	 * @param	integer	$pag	Número da página a ser listada
	 * @return	void
	 */
	public function listarAction($pag=1, $ord='', $dir='asc')
	{
		$this->view->listaMenu			= 'menu_sistema';
		$this->view->listaFerramentas 	= array();
		$this->view->listaFerramentas['excluir'] = false;
		$this->view->listaCampos		= array('nome','uf','modificado','criado');
		$this->select 					= $this->Estado->select()->order('nome ASC')->limit(20);
		parent::listarAction($pag, $ord, $dir);
	}
}
?>
