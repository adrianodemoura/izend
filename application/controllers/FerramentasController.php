<?php
/**
 * Controlador para cadastro de usuários
 * 
 * @package		izend
 * @subpackage	izend.controller
 */
/**
 * @package		izend
 * @subpackage	izend.controller
 */
class FerramentasController extends Zend_Controller_Action {
	/**
	 * Antes de tudo.
	 * 
	 * @return void
	 */
	public function init()
	{
		$this->view->controllerName = $this->getRequest()->getControllerName();
		$this->view->actionName 	= $this->getRequest()->getActionName();
	}

	/**
	 * Exibe a tela instalação do banco de dados. Antes de exibir a tela 
	 * testa novamente a conexão, em caso de sucesso redireciona para a tela 
	 * de instalação dos módulos nativos do sistema.
	 * 
	 * @return	void
	 */
	public function instalardbAction()
	{
		try
		{
			$Usuario = new Application_Model_Usuario_Table();
			$dataUsuario= $Usuario->fetchAll();
			$this->_redirect('usuarios/login');
		} catch (Exception $e)
		{
			$this->view->on_read 	= '$("#menu_usuario").fadeOut();';
			$this->view->code		= $e->getCode();
			$this->view->message	= $e->getMessage();
			switch($e->getCode())
			{
				case 1045:
				case 1049:
					$config = new Zend_Config_Ini('../application/configs/application.ini', APPLICATION_ENV);
					$this->view->configDB= $config->resources->db->params;
					break;
				case 42:
					$form 		= new Application_Form_InstalaAdmin();
					$request 	= $this->getRequest();
					if ($this->getRequest()->isPost())
					{
						if ($form->isValid($request->getPost())) 
						{
							$dataForm	= $this->getRequest()->getPost();
							if ($dataForm['senha'] != $dataForm['senha2'])
							{
								$this->view->message = 'As senhas não estão iguais !!!';
							} else
							{
								// instala módulos nativo
								if ($this->getInstalaModulos()) $this->view->message = 'A instalação';
								
								// insere usuários administrador
								
								// populas tabelas
							}
						}
					}
					$this->view->on_read .= '$("#nome").focus();';
					$this->view->form = $form;
					break;
			}
			
		}
	}
}

?>
