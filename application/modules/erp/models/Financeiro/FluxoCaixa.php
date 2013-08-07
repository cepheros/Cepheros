
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
 */
 
class Erp_Model_Financeiro_FluxoCaixa extends Zend_Db_Table_Abstract{

	protected $_name = 'tblmovimentobancario';
	protected $_primary = 'id_registro';
	
	
	public static function saldoAtual($conta, $format = 2){
		$dados = new Erp_Model_Financeiro_FluxoCaixa();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblmovimentobancario'),array('SUM(a.valorregistro) as valor'))
		->where("id_conta = '$conta'");
		
	
		$rs = $return->query();
	
		$dados = $rs->fetchAll();
	
		$estoqueatual = $dados[0]['valor'];
		if(!$estoqueatual){
			$estoqueatual = '0';
		}
	
		if($format > 0){
			$estoqueatual = number_format($estoqueatual,$format,',','');
		}
	
		return $estoqueatual;
	}
	
	
	public static function saldoAtualComEste($lanc, $conta, $format = 2){
		$lanc = $lanc+1;
		$dados = new Erp_Model_Financeiro_FluxoCaixa();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblmovimentobancario'),array('SUM(a.valorregistro) as valor'))
		->where("id_conta = '$conta' and id_registro < '$lanc' ");
	
	
		$rs = $return->query();
	
		$dados = $rs->fetchAll();
	
		$estoqueatual = $dados[0]['valor'];
		if(!$estoqueatual){
			$estoqueatual = '0';
		}
	
		if($format > 0){
			$estoqueatual = number_format($estoqueatual,$format,',','');
		}
	
		return $estoqueatual;
	}
	
	


}