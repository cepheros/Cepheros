
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
 
class Erp_Model_Financeiro_LancamentosPagamentos extends Zend_Db_Table_Abstract{

	protected $_name = 'tblpagamentos_lancamentos';
	protected $_primary = 'id_registro';
	
	public static function getAllDataLanc($id){
	
		$dados = new Erp_Model_Financeiro_LancamentosPagamentos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblpagamentos_lancamentos'),array('a.id_registro','a.id_lancamento','a.datavencimento','a.valororiginal','a.numeroparcela','a.quantidadeparcelas','a.tipodocumento','a.data_sysbaixa','a.user_sysbaixa','a.databaixa',
				'a.valorbaixa','a.valorjuros','a.valormultas','a.valordescontos','a.valorpago','a.tipopagamento','a.contapagamento','a.id_registro_fluxo','a.id_banco',
				'a.numerodocumento','a.statuslancamento','a.user_liberacao','a.datalibera','b.tiporegistro','b.categorialanc','b.datacadastro','b.totalgeral',
				'b.id_pessoa','b.id_empresa','b.nomelancamento','b.numerodocumento','b.categorialanc','c.nomefantasia as empresa','d.razaosocial','e.nomedocumento','f.nomebanco','g.nomesubcategoria'))
				->join(array('b'=>'tblpagamentos_dados'),'b.id_registro = a.id_lancamento',array())
				->join(array('c'=>'tblsystem_empresas'), 'c.id_registro = b.id_empresa',array())
				->join(array('d'=>'tblpessoas_basicos'),'d.id_registro = b.id_pessoa',array())
				->join(array('e'=>'tblapoio_tiposdocumentos'),'e.id_registro = b.tipodocumento',array())
				->join(array('f'=>'tblcontasbancarias'),'f.id_registro = b.contapadrao',array())
				->join(array('g'=>'tblfinanceiro_subcategorias'),'g.id_registro = b.categorialanc',array())
				->where("a.id_registro = '$id'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
	
		return $dados[0];
	
	}
	
	public static function getValoresPagamentoMes($mes,$ano,$statusl){
		$dados = new Erp_Model_Financeiro_LancamentosRecebimentos();
	
		if($mes <> '' && $ano <> '' && $statusl <> ''){
			$return = $dados->getAdapter()->query("
					SELECT
					EXTRACT(MONTH FROM datavencimento) as month,
					EXTRACT(YEAR FROM datavencimento) as year,
					SUM(valororiginal) as total
					FROM
					tblpagamentos_lancamentos
					WHERE
					month(datavencimento) = '$mes' AND
					year(datavencimento) = '$ano' AND
					statuslancamento in ($statusl)
					GROUP BY
					month,
					year
					ORDER BY
					year,
					month");
	
					$rs = $return->fetchAll();
	}
			if($rs[0]['total']){
			return $rs[0]['total'];
			}else{
			return 0;
	}
	
	
	}
	
	public static function getValoresPagamentoMesRealizado($mes,$ano,$statusl){
		$dados = new Erp_Model_Financeiro_LancamentosRecebimentos();
	
		if($mes <> '' && $ano <> '' && $statusl <> ''){
			$return = $dados->getAdapter()->query("
					SELECT
					EXTRACT(MONTH FROM datavencimento) as month,
					EXTRACT(YEAR FROM datavencimento) as year,
					SUM(valororiginal) as total
					FROM
					tblpagamentos_lancamentos
					WHERE
					month(databaixa) = '$mes' AND
					year(databaixa) = '$ano' AND
					statuslancamento in ($statusl)
					GROUP BY
					month,
					year
					ORDER BY
					year,
					month");
	
					$rs = $return->fetchAll();
		}
		if($rs[0]['total']){
		return $rs[0]['total'];
		}else{
		return 0;
		}
	
	
		}
	
	
}
