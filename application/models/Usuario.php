<?php
/**
 * Model Usuário
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class Application_Model_Usuario extends Zend_Db_Table_Abstract {
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
}
?>
