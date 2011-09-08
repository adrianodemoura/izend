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
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $model	= 'Perfil';

	public function listarAction($pag=1, $ord='nome', $dir='asc')
	{
		$this->view->listaCampos					= array('nome','modificado','criado');
		$this->select = $this->Perfil->select();
		$this->select->setIntegrityCheck(false);
		$this->select->from(array('p'=>'perfis'), array('p.id','p.nome','p.modificado','p.criado'));
		parent::listarAction($pag, $ord, $dir);
	}
}
?>
