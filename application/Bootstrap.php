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
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap {
	/**
	 * Configuração para a visão
	 * 
	 * @return	array	$view	Objeto View
	 */
	protected function _initViews()
	{
		/*$request = new Zend_Controller_Request_Http();
		$modulo	= $request->getModuleName();
		$this->bootstrap('view');
		$view = $this->getResource('view');
		$view->modulo = $modulo;
		return $view;*/
	}
}

