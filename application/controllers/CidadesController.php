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
				$this->view->posicao		= 'Cidades | Listar';
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('nome','estado','modificado','criado');
				$this->listaFerramentas = array();
				$this->listaFerramentas['excluir'] = false;
				
				$this->select = $this->Cidade->select()
					->setIntegrityCheck(false)
					->from(array('c'=>'cidades'), array('c.id','c.nome','e.nome as estado','c.modificado','c.criado'))
					->join(array('e'=>'estados'), 'e.id = c.estado_id', array())
					->order('c.nome ASC')
					->limit(20);
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
