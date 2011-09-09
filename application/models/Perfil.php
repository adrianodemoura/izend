<?php
/**
 * Model Perfil
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class Application_Model_Perfil extends AppModel {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $_name 	= 'perfis';

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
	public $_order		= 'nome';
}

?>
