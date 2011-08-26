<?php
/**
 * Helper para Máscaras
 *
 * @package		izend
 * @subpackage	izend.view.helper
 */
/**
 * @package		izend
 * @subpackage	izend.view.helper
 */
class Zend_View_Helper_Mascara extends Zend_View_Helper_Abstract {
	/**
	 * Retorno o valor do campo mascarado
	 * 
	 * @parameter	string		$valor		Valor do campo
	 * @param		string		$mascara	Máscara a ser aplicada no valor
	 * @param		array		$opcoes		Opções para exibição, exemplo: 1=>Sim, 0=>Não
	 * @return		string		$mascarado	Campo mascarado
	 */
	public function Mascara($valor='', $mascara='', $opcoes=array())
	{
		$mascarado = $valor;
		switch($mascara)
		{
			case 'datahora':
			case '99/99/9999 99:99:99':
				$mascarado = date('d/m/Y H:i:s',strtotime($valor));
				if ($valor=='0000-00-00 00:00:00') $mascarado = '';
				break;
			case 'data':
			case '99/99/9999':
				$mascarado = date('d/m/Y',strtotime($valor));
				if ($valor=='0000-00-00') $mascarado = '';
				break;
			case 'hora':
			case '99:99:99':
				$mascarado = $Time->format('H:i:s',strtotime($valor));
				if ($valor=='00:00:00') $mascarado = '';
				break;
			case 'cpf':
			case '999.999.999-99':
				$mascarado = substr($valor,0,3).'.'.substr($valor,3,3).'.'.substr($valor,6,3).'-'.substr($valor,9,2);
				break;
			case 'cnpj':
			case '99.999.999/9999-99':
				$mascarado = substr($valor,0,2).'.'.substr($valor,2,3).'.'.substr($valor,5,3).'/'.substr($valor,8,4).'-'.substr($valor,12,2);
				break;
			case 'aniversario':
			case '99/99':
				$mascarado = substr($valor,0,2).'/'.substr($valor,2,2);
				break;
			case 'cep':
			case '99.999-999':
				$mascarado = substr($valor,0,2).'.'.substr($valor,2,3).'-'.substr($valor,5,3);
				break;
			case 'telefone':
			case '99 9999-9999':
				$mascarado = substr($valor,0,2).' '.substr($valor,2,4).'-'.substr($valor,6,4);
				if ($valor=='') $mascarado = '';
				break;
		}
		if (count($opcoes)>0)
		{
			foreach($opcoes as $_val1 => $_val2)
			{
				if ($valor==$_val1) $mascarado = $_val2;
			}
		}

		return $mascarado;
	}
}
?>
