<?php
/**
 * Model Pai de todos
 * 
 * @package		izend
 * @subpackage	izend.model
 */
/**
 * @package		izend
 * @subpackage	izend.model 
 */
class AppModel extends Zend_Db_Table_Abstract {

	/**
	 * Retorna o nome do model
	 * 
	 * @return	string
	 */
	public function getName()
	{
		return $this->_name;
	}
}

?>
