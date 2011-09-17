<?php
/**
 * Model Cidades
 * 
 * @package		admin
 * @subpackage	admin.model
 */
/**
 * @package		admin
 * @subpackage	admin.model 
 */
class Admin_Model_Cidade extends AppModel {
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
	 * Ordem padrão
	 * 
	 * @var		string
	 * @access	public
	 */
	public $_order		= 'nome';

	/**
	 * Relacionamentos 1 para N
	 * 
	 * @var		array
	 * @access	protected
	 */
    protected $_dependentTables = array('Estado');
}

?>
