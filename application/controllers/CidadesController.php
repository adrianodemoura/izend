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
	public function listarAction($num=1, $ord='nome', $dir='asc')
	{
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

		parent::listarAction($num, $ord, $dir);
	}

	/**
	 * Exibe a tela de edição
	 * 
	 * @param	integer	$id	Id do registro a ser editado
	 * @return	void
	 */
	public function editarAction($id=0)
	{
		$this->view->edicaoCampos = array('nome','modificado','criado');
		parent::editarAction($id);
	}
}
?>
