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
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $model	= 'Permissao';

	/**
	 * Exibe a tela de paginação
	 * 
	 * @return	void
	 */
	public function listarAction($pag=1, $ord='controlador', $dir='asc')
	{
		$this->view->listaMenu		= 'menu_sistema';
		$this->view->listaCampos	= array('controlador','acao','modificado','criado');
		
		$this->select = $this->Permissao->select();
		$this->select->setIntegrityCheck(false);
		$this->select->from(array('p'=>'permissoes'), array('p.id','p.controlador','p.acao','p.modificado','p.criado'));
		parent::listarAction($pag, $ord, $dir);
	}
}
?>
