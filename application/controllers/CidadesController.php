<?php
/**
 * Controller para o cadastro de Cidades
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class CidadesController extends AppController {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $model	= 'Cidade';

	/**
	 * Exibe a tela de paginação
	 * 
	 * @return	void
	 */
	public function listarAction($pag=1, $ord='', $dir='asc')
	{
		$this->view->posicao						= 'Cidades | Listar';
		$this->view->listaMenu						= 'menu_sistema';
		$this->view->listaCampos					= array('nome','estado','modificado','criado');
		$this->view->listaFerramentas 				= array();
		$this->view->listaFerramentas['excluir'] 	= false;
		
		$this->select = $this->Cidade->select();
		$this->select->setIntegrityCheck(false);
		$this->select->from(array('c'=>'cidades'), array('c.id','c.nome','e.nome as estado','c.modificado','c.criado'));
		$this->select->join(array('e'=>'estados'), 'e.id = c.estado_id', array());
		$this->select->order('c.nome asc');

		parent::listarAction($pag, $ord, $dir);
	}
}
?>
