
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
 
class Erp_Model_Financeiro_Perfil extends Zend_Db_Table_Abstract{
	
	protected $_name = 'tblfinanceiro_perfilpagamento';
	protected $_primary = 'id_registro';
	
	public static function getCombo(){
		$db = new Erp_Model_Financeiro_Perfil();
		$dados = $db->fetchAll()->toArray();
		$rdepto[''] = '- Selecione -';
		foreach($dados as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomeperfil'];
		}
		return $rdepto;
	}
		
	
	public static function renderComboGroup($tipo = null){
		$db1 = new Erp_Model_Financeiro_CategoriasLancamentos();
		if($tipo){
			$data = $db1->fetchAll("tipocategoria = '$tipo'");
		}else{
			$data = $db1->fetchAll();
		}
		
		$dataarray[] = '-Selecione-';
		foreach($data as $cat){
			$dataarray[$cat->nomecategoria] = Erp_Model_Financeiro_TiposLancamentos::getDataCategora($cat->id_registro);
		}
		
		return $dataarray;
		
		
	}
	
	public static function getPerfil($id){
		$db = new Erp_Model_Financeiro_Perfil();
		$dados = $db->fetchRow("id_registro = '$id'");
		return $dados;
	}
	
	
	
}