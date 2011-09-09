<?php
/**
 * Model Usuários
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class Application_Model_Usuario extends AppModel {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	public
	 */
	protected $_name 	= 'usuarios';

	/**
	 * Chave primária
	 * 
	 * @var		string
	 * @access	public
	 */
	protected $_primary	= 'id';

	/**
	 * Ordem padrão
	 * 
	 * @var		string
	 * @access	protected
	 */
	public $_order	= 'nome';
}
?>
