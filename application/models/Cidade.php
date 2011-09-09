<?php
/**
 * Model Cidades
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class Application_Model_Cidade extends AppModel {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $_name 	= 'cidades';

	/**
	 * Chave primária
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $_primary	= 'id';

	/**
	 * Relacionamentos 1 para N
	 * 
	 * @var		array
	 * @access	protected
	 */
    protected $_dependentTables = array('Estado');
}

?>
