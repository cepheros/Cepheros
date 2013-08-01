
<?php
/**
 * Este arquivo é parte do projeto SysAdmin - ERP em PHP
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou 
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da 
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>. 
 *
 * @package   SysAdmin
 * @name      
 * @version   1.0.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; SysAdmin
 * @link      http://www.cepheros.com.br
 * @author    Daniel R. Chaves <dchaves at 32dll dot com dot br>
 * 
 * Classe que transforma valores monetários por extendo
 * exemplo de como utilizar
 * echo Functions_Monetary::numberToExt(4526.89);
 * o exemplo acima ira imprimir: quatro mil quinhentos e vinte e seis reais e oitenta e nove centavos
 *
 */
 
class Functions_Monetary {
	private static $unidades = array("um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze",
			"treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
	private static $dezenas = array("dez", "vinte", "trinta", "quarenta","cinqüenta", "sessenta", "setenta", "oitenta", "noventa");
	private static $centenas = array("cem", "duzentos", "trezentos", "quatrocentos", "quinhentos",
			"seiscentos", "setecentos", "oitocentos", "novecentos");
	private static $milhares = array(
			array("text" => "mil", "start" => 1000, "end" => 999999, "div" => 1000),
			array("text" => "milhão", "start" =>  1000000, "end" => 1999999, "div" => 1000000),
			array("text" => "milhões", "start" => 2000000, "end" => 999999999, "div" => 1000000),
			array("text" => "bilhão", "start" => 1000000000, "end" => 1999999999, "div" => 1000000000),
			array("text" => "bilhões", "start" => 2000000000, "end" => 2147483647, "div" => 1000000000)
	);
	const MIN = 0.01;
	const MAX = 2147483647.99;
	const MOEDA = " real ";
	const MOEDAS = " reais ";
	const CENTAVO = " centavo ";
	const CENTAVOS = " centavos ";
	 
	static function numberToExt($number, $moeda = true) {
		if ($number >= self::MIN && $number <= self::MAX) {
			$value = self::conversionR((int)$number);
			if ($moeda) {
				if (floor($number) == 1) {
					$value .= self::MOEDA;
				}
				else if (floor($number) > 1) $value .= self::MOEDAS;
			}

			$decimals = self::extractDecimals($number);
			if ($decimals > 0.00) {
				$decimals = round($decimals * 100);
				$value .= " e ".self::conversionR($decimals);
				if ($moeda) {
					if ($decimals == 1) {
						$value .= self::CENTAVO;
					}
					else if ($decimals > 1) $value .= self::CENTAVOS;
				}
			}
		}
		return trim($value);
	}
	 
	private static function extractDecimals($number) {
		return $number - floor($number);
	}
	 
	static function conversionR($number) {
		if (in_array($number, range(1, 19))) {
			$value = self::$unidades[$number-1];
		}
		else if (in_array($number, range(20, 90, 10))) {
			$value = self::$dezenas[floor($number / 10)-1]." ";
		}
		else if (in_array($number, range(21, 99))) {
			$value = self::$dezenas[floor($number / 10)-1]." e ".self::conversionR($number % 10);
		}
		else if (in_array($number, range(100, 900, 100))) {
			$value = self::$centenas[floor($number / 100)-1]." ";
		}
		else if (in_array($number, range(101, 199))) {
			$value = ' cento e '.self::conversionR($number % 100);
		}
		else if (in_array($number, range(201, 999))) {
			$value = self::$centenas[floor($number / 100)-1]." e ".self::conversionR($number % 100);
		}
		else {
			foreach (self::$milhares as $item) {
				if ($number >= $item['start'] && $number <= $item['end']) {
					$value = self::conversionR(floor($number / $item['div']))." ".$item['text']." ".self::conversionR($number % $item['div']);
					break;
				}
			}
		}
		return $value;
	}
}