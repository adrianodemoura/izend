<?php
/**
 * Controlador para página inicial
 * 
 * @package		admin
 * @subpackage	admin.controller
 */
/**
 * @package		admin
 * @subpackage	admin.controller
 */
class Admin_IndexController extends Zend_Controller_Action {
	/**
	 * Exibe a tela principal
	 * 
	 * @return void
	 */
    public function indexAction()
    {
        $this->view->titulo = 'iZend - Admin';
    }
}

