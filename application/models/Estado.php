<?php
/**
 * Model Estado
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class Application_Model_Estado extends AppModel {
	/**
	 * Nome do model
	 * 
	 * @var		string
	 * @access	protected
	 */
	protected $_name 	= 'estados';

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
	 * Referências
	 * 
	 * @var		array
	 * @access	protected
	 */
	protected $_referenceMap = array
	(
		array
		(
			'refTableClass'	=> 'Cidade',
			'refColumns'	=> 'estado_id',
			'coluns'		=> 'id, nome'
		),
	);
}

?>
