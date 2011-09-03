<?php
/**
 * Helper para input
 *
 * @package		izend
 * @subpackage	izend.view.helper
 */
/**
 * @package		izend
 * @subpackage	izend.view.helper
 */
class Zend_View_Helper_Input extends Zend_View_Helper_Abstract {
	/**
	 * Returna o campo input formatado
	 * 
	 * @param	string	$campo		Nome do campo
	 * @param	string	$valor		Valor do campo
	 * @param	array	$arrProp	Matriz com todas as propriedades do campo
	 */
	public function Input($campo='', $valor='', $arrProp=array())
	{
		$input	= '';
		$tipo	= isset($arrProp['type']) $arrProp['type'] : 'text';
		switch($tipo)
		{
			default:
				$input .= "<div id='".ucfirst(strtolower($campo))."' class='divEditarCampo'>";
				$input .= "<label class='labelCampo'></label><input type='text' class='inputCampo' id='in".$campo."' value='".$valor."' />";
				$input .= "</div>";
				break;
		}
		return $input;
	}
}
?>
