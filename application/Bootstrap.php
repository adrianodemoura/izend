<?php
/**
 * Bootstrap
 * 
 * @package		izend
 * @subpacakge	izend.config
 */
/*
 * @package		izend
 * @subpacakge	izend.config
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
	/**
	 * Antes de tudo
	 * 
	 * @return void
	 */
	public function init()
	{
		echo 'oi bootstrap ...';
		$this->sessao = new Zend_Session_Namespace(SISTEMA);
	}
}

