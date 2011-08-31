<?php
/**
 * Controller para o cadastro de Clientes
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class ClientesController extends AppController {
	/**
	 * Método de inicialização do controlador
	 * 
	 * @return void
	 */
	public function init()
	{
		parent::init();
		$this->Cliente = new Application_Model_Cliente();

		// atualizando a camada de visão com base na action solicitada
		switch($this->getRequest()->getActionName())
		{
			case 'listar':
				$this->view->posicao 		= 'Clientes | Listar';
				$this->view->listaCampos 	= array('login','nome','ativo','ultimo_acesso');
				$select = $this->Cliente->select()
					->setIntegrityCheck(false)
					->from(array('l'=>'clientes'), array())
					->join(array('c'=>'cidades'), 'c.id = l.cidade_id', array())
					->join(array('e'=>'estados'), 'e.id = l.estado_id', array())
					->order('l.nome ASC')
					->limit(10);
				$this->view->data = $this->Cliente->fetchAll($select)->toArray();
				break;
			case 'editar':
				break;
			case 'novo':
				break;
		}
	}
}
?>
