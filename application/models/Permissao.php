<?php
/**
 * Model Permissão
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class Application_Model_Permissao extends AppModel {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $_name 	= 'permissoes';

	/**
	 * Chave primária
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $_primary	= 'id';

	/**
	 * Ordem padrão
	 * 
	 * @var		string
	 * @access	public
	 */
	public $_order	= 'controlador';
}

?>
