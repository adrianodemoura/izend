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
			$Usuario = new Application_Model_Usuario();
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
								// instala módulo sistema
								if ($this->getInstalaSistema()) 
								{
									// inclui o usuário administrador
									$bd   = Zend_Db_Table_Abstract::getDefaultAdapter();
									$sql  = 'INSERT INTO usuarios (login,senha,nome,email,ativo,acessos,ultimo_acesso,criado,modificado) ';
									$sql .= 'VALUES ("'.$dataForm['login'].'",sha1("'.$dataForm['login'].'"),"'.mb_strtoupper($dataForm['nome']).'","'.mb_strtolower($dataForm['email']).'",1,1,sysdate(),sysdate(),sysdate())';
									try
									{
										$bd->query($sql);
									} catch (Exception $e)
									{
										echo '<pre>'.$sql.'</pre>';
										exit($e->getMessage());
									}
									$sessao = new Zend_Session_Namespace(SISTEMA);
									$sessao->msg = 'A instalação foi executada com sucesso!!!';
									$this->_redirect('usuarios/login');
								} else $this->view->message = 'Erro ao tentar instalar módulo sistema!!!';
							}
						}
					}
					$this->view->on_read .= '$("#nome").focus();';
					$this->view->form = $form;
					break;
			}
		}
	}

	/**
	 * Executa a instalação das tabelas dos módulos nativos do sistema.
	 * 
	 * Este módulo executa a sql, que deve estar em docs/sql/izend.sql, este arquivo deve conter o comando sql para criar as tabelas.
	 * Cada tabela pode ser populada, basta criar o arquivo CSV com o mesmo nome, a primeira linha deve conter o nome dos campos.
	 * 
	 * Em caso de sucesso o sistema será redirecionado à página de login.
	 * 
	 * @return	boolean
	 */
	private function getInstalaSistema()
	{
		// csv a importar
		$arrCsv = array('estados','cidades','perfis','usuarios_perfis');

		// instanciando o banco de dados
		$bd = Zend_Db_Table_Abstract::getDefaultAdapter();

		// instala todas as tabelas do sistema
		$arq = '../docs/sql/'.mb_strtolower(SISTEMA).'.sql';
		if (!file_exists($arq)) exit('não foi possível localizar o arquivo '.$arq);
		$handle  = fopen($arq,"r");
		$texto   = fread($handle, filesize($arq));
		$sqls	 = explode(";",$texto);
		fclose($handle);
		foreach($sqls as $sql) // executando sql a sql
		{
			if (trim($sql))
			{
				try
				{
					$bd->query($sql);
				} catch(Exception $e)
				{
					exit($e->getMessage());
				}
			}
		}

		// populando tabelas via csv
		foreach($arrCsv as $tabela)
		{
			$arq = '../docs/sql/'.$tabela.'.csv';

			// mandando bala se o csv existe
			if (file_exists($arq))
			{
				$handle  	= fopen($arq,"r");
				$l 			= 0;
				$campos 	= '';
				$cmps	 	= array();
				$valores 	= '';

				// executando linha a linha
				while ($linha = fgetcsv($handle, 2048, ";"))
				{
					if (!$l)
					{
						$i = 0;
						$t = count($linha);
						foreach($linha as $campo)
						{
							$campos .= $campo;
							$i++;
							if ($i!=$t) $campos .= ',';
						}
						// montand os campos da tabela
						$arr_campos = explode(',',$campos);
					} else
					{
						$valores  = '';
						$i = 0;
						$t = count($linha);
						foreach($linha as $valor)
						{
							if ($arr_campos[$i]=='criado' || $arr_campos[$i]=='modificado') $valor = date("Y-m-d H:i:s");
							$valores .= "'".str_replace("'","\'",$valor)."'";
							$i++;
							if ($i!=$t) $valores .= ',';
						}
						$sql = 'INSERT INTO '.$tabela.' ('.$campos.') VALUES ('.$valores.')';
						try
						{
							$bd->query($sql);
						} catch(Exception $e)
						{
							exit($e->getMessage());
						}
					}
					$l++;
				}
				fclose($handle);

				// verificando se a tabela possui os campos criado e modificado, se sim atualiza-os
				$res = $bd->fetchAll('SHOW FULL COLUMNS FROM '.$tabela);
				foreach($res as $_linha => $_arrCmp)
				{
					if ($_arrCmp['Field']=='modificado')	array_push($cmps,'modificado');
					if ($_arrCmp['Field']=='criado')	array_push($cmps,'criado');
				}
				if (count($cmps))
				{
					$sql = '';
					foreach($cmps as $_campo) $sql .= "$_campo='".date("Y-m-d H:i:s")."', ";
					$sql = substr($sql,0,strlen($sql)-2);
					$sql = 'UPDATE '.$tabela.' SET '.$sql;
					try
					{
						$bd->query($sql);
					} catch(Exception $e)
					{
						exit($e->getMessage());
					}
				}
			} else exit('não foi possivel localizar '.$arq);
		}

		return true;
	}
}

?>
