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
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->Cidade = new Application_Model_Cidade();
		
		// atualizando a camada de visão com base na action solicitada
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->posicao		= 'Cidades | Listar';
				$this->view->listaMenu		= 'menu_sistema';
				$this->view->listaCampos	= array('nome','estado');
				$select = $this->Cidade->select()
					->setIntegrityCheck(false)
					->from(array('c'=>'cidades'), array('c.id','c.nome','e.nome as estado','c.modified','c.created'))
					->join(array('e'=>'estados'), 'e.id = c.estado_id', array())
					->order('c.nome ASC')
					->limit(10);
				$this->view->data = $this->Cidade->fetchAll($select)->toArray();
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
