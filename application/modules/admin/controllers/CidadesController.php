<?php
/**
 * Controller para o cadastro de Cidades
 * 
 * @package		admin
 * @subpackage	admin.controller
 */
/**
 * @package		admin
 * @subpackage	admin.controller
 */
class Admin_CidadesController extends AppController {
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
	public function listarAction()
	{
		// configurando alguns parâmetros da visão
		$this->view->posicao						= 'Cidades | Listar';
		$this->view->listaMenu						= 'menu_sistema';
		$this->view->listaCampos					= array('nome','estado','modificado','criado');
		$this->view->listaFerramentas 				= array();
		$this->view->listaFerramentas['excluir'] 	= false;

		// configurando a sql
		$this->select = $this->Cidade->select();
		$this->select->from(array('Cidade'=>'cidades'), array('id','nome','Estado.nome as estado','modificado','criado'));
		$this->select->join(array('Estado'=>'estados'), 'Estado.id = Cidade.estado_id', array());
		$this->select->setIntegrityCheck(false);

		parent::listarAction();
	}

	/**
	 * Exibe a tela de edição
	 * 
	 * @return	void
	 */
	public function editarAction()
	{
		$this->view->edicaoCampos = array('nome','modificado','criado');
		parent::editarAction();
	}
}
?>
