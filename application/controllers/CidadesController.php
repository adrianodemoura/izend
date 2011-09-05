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
	 * Exibe a tela de listagem
	 * 
	 * @param	integer	$pag	Número da página a ser listada
	 * @return	void
	 */
	public function listarAction($pag=1)
	{
		$this->view->posicao		= 'Cidades | Listar';
		$this->view->listaMenu		= 'menu_sistema';
		$this->view->listaCampos	= array('nome','estado','modificado','criado');
		$this->view->listaFerramentas 	= array();
		$this->view->listaFerramentas['excluir'] = false;
		
		$this->select = $this->Cidade->select()
			->setIntegrityCheck(false)
			->from(array('c'=>'cidades'), array('c.id','c.nome','e.nome as estado','c.modificado','c.criado'))
			->join(array('e'=>'estados'), 'e.id = c.estado_id', array())
			->order('c.nome ASC')
			->limit(20);
		parent::listarAction($pag);
	}
}
?>
