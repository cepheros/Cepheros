
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
 
class System_DatatablesFinanceiroController extends Zend_Controller_Action{
	
	public function lancamentosReceberAction(){
	
		$tipo = $this->_getParam('tipo');
		switch($tipo){
			case 'abertos':
				$status = '1';
				break;
			case 'liberados':
				$status = '2';
				break;
			case 'baixados':
				$status = '3';
				break;
			case 'cancelados':
				$status = '4';
				break;
		}
			
		$aColumns = array("a.id_lancamento","c.nomefantasia",'d.razaosocial','g.nomesubcategoria','a.numeroparcela','a.valororiginal','a.datavencimento','b.numerodocumento');
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	
		$db = new Erp_Model_Financeiro_LancamentosRecebimentos();
	
		$iTotal = count($db->fetchAll("statuslancamento in ('$status')"));
	
		$dados = new Erp_Model_Financeiro_LancamentosRecebimentos();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblrecebimentos_lancamentos'),array('a.id_registro',
					'a.id_lancamento',
					'a.datavencimento',
					'a.valororiginal',
					'a.numeroparcela',
					'a.quantidadeparcelas', 
					'a.tipodocumento',
					'a.data_sysbaixa',
					'a.user_sysbaixa',
					'a.databaixa',
					'a.valorbaixa',
					'a.valorjuros',
					'a.valormultas',
					'a.valordescontos',
					'a.valorpago',
					'a.tipopagamento',
					'a.contapagamento',
					'a.id_registro_fluxo',
					'a.id_banco',
					'a.numerodocumento',
					'a.statuslancamento',
					'a.user_liberacao',
					'a.datalibera',
					'b.tiporegistro',
					'b.datacadastro',
					'b.totalgeral',
					'b.id_pessoa',
					'b.id_empresa',
					'b.nomelancamento',
					'b.numerodocumento',
					'c.nomefantasia as empresa',					 
					'd.razaosocial',
					'e.nomedocumento',
					'f.nomebanco',
					'g.nomesubcategoria'))
			->join(array('b'=>'tblrecebimentos_dados'),'b.id_registro = a.id_lancamento',array())
			->join(array('c'=>'tblsystem_empresas'), 'c.id_registro = b.id_empresa',array())
			->join(array('d'=>'tblpessoas_basicos'),'d.id_registro = b.id_pessoa',array())
			->join(array('e'=>'tblapoio_tiposdocumentos'),'e.id_registro = b.tipodocumento',array())
			->join(array('f'=>'tblcontasbancarias'),'f.id_registro = b.contapadrao',array())
			->join(array('g'=>'tblfinanceiro_subcategorias'),'g.id_registro = b.categorialanc',array())
			->where("a.statuslancamento in ('$status')");
					
			//echo $return;
				if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
				{
					for ( $i=0 ; $i<count($aColumns) ; $i++ )
					{
						
					if($aColumns[$i] == 'a.datavencimento'){
						$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch']) ." 00:00:00' and {$aColumns[$i]} IS NULL";
					}else{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
					}
						
						if($i==0){
							$return->where($sWhere);
						}else{
							$return->orWhere($sWhere);
						}
					}
			
				}
				if ( isset( $_GET['iSortCol_0'] ) )
				{
			
					for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
					{
						if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
						{
							$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
							$type =  $_GET['sSortDir_'.$i];
							$return->order("$collum $type");
			
			
						}
					}
			
			
			
			
				}else{
					$return->order("id_registro DESC");
				}
			
				if($_GET['iDisplayLength']){
					$tamanho = $_GET['iDisplayLength'];
				}else{
					$tamanho = 10;
				}
				if($_GET['iDisplayStart']){
					$inicio = $_GET['iDisplayStart'];
				}else{
					$inicio = 0;
				}
			
				$return->limit($tamanho,$inicio);
			
			
			
			
			
				$rs = $return->query();
				$dados = $rs->fetchAll();
			
				$iFilteredTotal = count($dados);
			
			
				$output = array(
						"sEcho" => $_GET['sEcho'],
						"iTotalRecords" => $iTotal,
						"iTotalDisplayRecords" => $iTotal,
						"aaData" => array()
				);
			foreach($dados as $data){
														
							switch($tipo){
								
								case 'abertos':
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/baixa-recebimento/id/{$data['id_registro']}\" data-target=\"#AllModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_download\" title=\"Baixar esta parcela\"></i></a>";
									
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/edita-parcela-receber/id/{$data['id_registro']}\" data-target=\"#EditParcModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_edit\" title=\"Editar esta parcela\"></i></a>";
										
									if($data['quantidadeparcelas'] > 1){
										$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/edita-lancamento-receber/id/{$data['id_lancamento']}\" data-target=\"#EditLancModal\"><i style=\"cursor:pointer\" class=\"splashy-documents_edit\" title=\"Editar todas as parcelas\"></i></a>";
									};
									
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/confirm-cancela-receber/tipo/parcela/id/{$data['id_registro']}\" data-target=\"#CancelModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_remove\" title=\"Cancelar esta parcela\"></i></a>";
									if($data['quantidadeparcelas'] > 1){
										$icons .= "<a data-toggle=\"modal\" href=\"/erp/financeiro/confirm-cancela-receber/tipo/lancamento/id/{$data['id_registro']}\" data-target=\"#CancelModal\"><i style=\"cursor:pointer\" class=\"splashy-documents_remove\" title=\"Cancelar todas as parcelas\"></i></a>";
									}
									  
								break;
								case 'liberados':
									$icons.= "<i style=\"cursor:pointer\" class=\"splashy-document_a4_okay liberaparcela\" id_parcela=\"{$data['id_registro']}\" title=\"Liberar esta parcela\"></i>";
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/confirm-cancela-receber/tipo/parcela/id/{$data['id_registro']}\" data-target=\"#CancelModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_remove\" title=\"Cancelar esta parcela\"></i></a>";
								break;
							case 'baixados':
								$icons.="<i class=\"splashy-error_do_not reincluirlancado\" id_parcela=\"{$data['id_registro']}\" title=\"Cancelar Recebimento\"></i>";
							break;
								case 'cancelados':								
									$icons.="<i style=\"cursor:pointer\" class=\"splashy-refresh_backwards reincluir\" id_parcela=\"{$data['id_registro']}\" title=\"Re-incluir Parcela\"></i>";
								break;
								
							}

							$icons.="<i style=\"cursor:pointer\" class=\"splashy-printer printerbutton\" id_parcela=\"{$data['id_registro']}\" title=\"Imprimir Recibo\"></i>";
							
							$row = array();
							$row[] = $data['id_lancamento'];
							$row[] = $data['empresa'];
							$row[] = "{$data['nomelancamento']} ({$data['razaosocial']})";
							$row[] = $data['nomesubcategoria'];
							$row[] = $data['numeroparcela'].'/'.$data['quantidadeparcelas'];
							$row[] = number_format($data['valororiginal'],2,',','');
							$row[] = Functions_Datas::MyDateTime($data['datavencimento']);	
							$row[] = $data['nomedocumento']." - ".$data['numerodocumento'];
							$row[] = $icons;
							
							
							
							$output['aaData'][] = $row;
							$row = null;
							$icons = null;
				}
			
			
			
			
				echo json_encode( $output );
			}
			
		

			public function lancamentosPagarAction(){
			
				$tipo = $this->_getParam('tipo');
				switch($tipo){
					case 'abertos':
						$status = '1';
						break;
					case 'liberados':
						$status = '2';
						break;
					case 'baixados':
						$status = '3';
						break;
					case 'cancelados':
						$status = '4';
						break;
				}
					
				$aColumns = array("a.id_lancamento","empresa",'d.razaosocial','g.nomesubcategoria','a.numeroparcela','a.valororiginal','a.datavencimento','b.numerodocumento');
			
				$this->_helper->layout->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
			
				$db = new Erp_Model_Financeiro_LancamentosPagamentos();
			
				$iTotal = count($db->fetchAll("statuslancamento in ('$status')"));
			
				$dados = new Erp_Model_Financeiro_LancamentosPagamentos();
				$return = $dados->getAdapter()->select()
				->from(array('a'=>'tblpagamentos_lancamentos'),array('a.id_registro',
						'a.id_lancamento',
						'a.datavencimento',
						'a.valororiginal',
						'a.numeroparcela',
						'a.quantidadeparcelas',
						'a.tipodocumento',
						'a.data_sysbaixa',
						'a.user_sysbaixa',
						'a.databaixa',
						'a.valorbaixa',
						'a.valorjuros',
						'a.valormultas',
						'a.valordescontos',
						'a.valorpago',
						'a.tipopagamento',
						'a.contapagamento',
						'a.id_registro_fluxo',
						'a.id_banco',
						'a.numerodocumento',
						'a.statuslancamento',
						'a.user_liberacao',
						'a.datalibera',
						'b.tiporegistro',
						'b.nomelancamento',
						'b.datacadastro',
						'b.totalgeral',
						'b.id_pessoa',
						'b.id_empresa',
						'b.numerodocumento',
						'c.nomefantasia as empresa',
						'd.razaosocial',
						'e.nomedocumento',
						'f.nomebanco',
						'g.nomesubcategoria'))
						->join(array('b'=>'tblpagamentos_dados'),'b.id_registro = a.id_lancamento',array())
						->join(array('c'=>'tblsystem_empresas'), 'c.id_registro = b.id_empresa',array())
						->join(array('d'=>'tblpessoas_basicos'),'d.id_registro = b.id_pessoa',array())
						->join(array('e'=>'tblapoio_tiposdocumentos'),'e.id_registro = b.tipodocumento',array())
						->join(array('f'=>'tblcontasbancarias'),'f.id_registro = b.contapadrao',array())
						->join(array('g'=>'tblfinanceiro_subcategorias'),'g.id_registro = b.categorialanc',array())
						->where("a.statuslancamento in ('$status')");
							
					
						if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
						{
							for ( $i=0 ; $i<count($aColumns) ; $i++ )
							{
							if($aColumns[$i] == 'a.datavencimento'){
									$sWhere= $aColumns[$i]." >= '". Functions_Datas::inverteData($_GET['sSearch'])."' and {$aColumns[$i]} IS NULL";
								}else{
									$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
								}
								if($i==0){
									$return->where($sWhere);
								}else{
									$return->orWhere($sWhere);
								}
							}
					
						}
						if ( isset( $_GET['iSortCol_0'] ) )
						{
					
							for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
							{
								if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
								{
									$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
									$type =  $_GET['sSortDir_'.$i];
									$return->order("$collum $type");
					
					
								}
							}
					
					
					
					
						}else{
							$return->order("id_registro DESC");
						}
					
						if($_GET['iDisplayLength']){
							$tamanho = $_GET['iDisplayLength'];
						}else{
							$tamanho = 10;
						}
						if($_GET['iDisplayStart']){
							$inicio = $_GET['iDisplayStart'];
						}else{
							$inicio = 0;
						}
					
						$return->limit($tamanho,$inicio);
					
					
					
					//	echo $return;	
					
						$rs = $return->query();
						$dados = $rs->fetchAll();
					
						$iFilteredTotal = count($dados);
					
					
						$output = array(
								"sEcho" => $_GET['sEcho'],
								"iTotalRecords" => $iTotal,
								"iTotalDisplayRecords" => $iTotal,
								"aaData" => array()
						);
						//echo $return;
						$valorteto = number_format(System_Model_SysConfigs::getConfig("FinanValorMaxSemLib"),2);
						foreach($dados as $data){
														
							switch($tipo){
								
								case 'abertos':
									if(number_format($data['valororiginal'],2) <= $valorteto || $data['user_liberacao'] <> '0'){
										$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/baixa-pagamento/id/{$data['id_registro']}\" data-target=\"#AllModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_download\" title=\"Baixar esta parcela\"></i></a>";
									}
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/edita-parcela-pagar/id/{$data['id_registro']}\" data-target=\"#EditParcModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_edit\" title=\"Editar esta parcela\"></i></a>";
										
									if($data['quantidadeparcelas'] > 1){
										$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/edita-lancamento-pagar/id/{$data['id_lancamento']}\" data-target=\"#EditLancModal\"><i style=\"cursor:pointer\" class=\"splashy-documents_edit\" title=\"Editar todas as parcelas\"></i></a>";
									};
									if($data['valororiginal'] >= System_Model_SysConfigs::getConfig("FinanValorMaxSemLib") && $data['user_liberacao'] == '0' && $tipo <> 'cancelados'){
										$icons.= "<i style=\"cursor:pointer\" class=\"splashy-document_a4_okay liberaparcela\" id_parcela=\"{$data['id_registro']}\" title=\"Liberar esta parcela\"></i>";
									}
										
									if($data['valororiginal'] >= System_Model_SysConfigs::getConfig("FinanValorMaxSemLib")  && $data['user_liberacao'] == '0' && $data['quantidadeparcelas'] > 1 && $tipo <> 'cancelados'){
										$icons.= "<i style=\"cursor:pointer\" class=\"splashy-documents_okay liberalancamento\" id_lancamento=\"{$data['id_lancamento']}\" title=\"Liberar todas as Parcelas\"></i>";
									}
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/confirm-cancela-pagar/tipo/parcela/id/{$data['id_registro']}\" data-target=\"#CancelModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_remove\" title=\"Cancelar esta parcela\"></i></a>";
									if($data['quantidadeparcelas'] > 1){
										$icons .= "<a data-toggle=\"modal\" href=\"/erp/financeiro/confirm-cancela-pagar/tipo/lancamento/id/{$data['id_registro']}\" data-target=\"#CancelModal\"><i style=\"cursor:pointer\" class=\"splashy-documents_remove\" title=\"Cancelar todas as parcelas\"></i></a>";
									}
									  
								break;
								case 'liberados':
									$icons.= "<i style=\"cursor:pointer\" class=\"splashy-document_a4_okay liberaparcela\" id_parcela=\"{$data['id_registro']}\" title=\"Liberar esta parcela\"></i>";
									$icons.= "<a data-toggle=\"modal\" href=\"/erp/financeiro/confirm-cancela-pagar/tipo/parcela/id/{$data['id_registro']}\" data-target=\"#CancelModal\"><i style=\"cursor:pointer\" class=\"splashy-document_a4_remove\" title=\"Cancelar esta parcela\"></i></a>";
								break;
							case 'baixados':
								$icons.="<i class=\"splashy-error_do_not reincluirlancado\" id_parcela=\"{$data['id_registro']}\" title=\"Cancelar Pagamento\"></i>";
							break;
								case 'cancelados':								
									$icons.="<i style=\"cursor:pointer\" class=\"splashy-refresh_backwards reincluir\" id_parcela=\"{$data['id_registro']}\" title=\"Re-incluir Parcela\"></i>";
								break;
								
							}

							$icons.="<i style=\"cursor:pointer\" class=\"splashy-printer printerbutton\" id_parcela=\"{$data['id_registro']}\" title=\"Imprimir Recibo\"></i>";
							
							$row = array();
							$row[] = $data['id_lancamento'];
							$row[] = $data['empresa'];
							$row[] = "{$data['nomelancamento']} ({$data['razaosocial']})";
							$row[] = $data['nomesubcategoria'];
							$row[] = $data['numeroparcela'].'/'.$data['quantidadeparcelas'];
							$row[] = number_format($data['valororiginal'],2,',','');
							$row[] = Functions_Datas::MyDateTime($data['datavencimento']);	
							$row[] = $data['nomedocumento']." - ".$data['numerodocumento'];
							$row[] = $icons;
							
							
							
							$output['aaData'][] = $row;
							$row = null;
							$icons = null;
						}
					
					
					
					
						echo json_encode( $output );
					}
					
					
					
					
					
	public function fluxoCaixaAction(){

		
		$aColumns = array("a.id_registro","b.nomebanco",'e.nomesubcategoria','a.datalancamento','c.razaosocial','a.valorregistro','a.saldocomesse','a.observacoes');
			
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
			
		$db = new Erp_Model_Financeiro_FluxoCaixa();
			
		$iTotal = count($db->fetchAll());
			
		$dados = new Erp_Model_Financeiro_FluxoCaixa();
		$return = $dados->getAdapter()->select()
		->from(array('a'=>'tblmovimentobancario'),array('a.id_registro',
				'a.id_conta',
				'a.datalancamento',
				'a.valorregistro',
				'a.categoria',
				'a.situacao',
				'a.id_pessoa',
				'a.tipolancamento',
				'a.saldocomesse',
				'a.observacoes',
				'a.nomelancamento',
				'a.dataregistro',
				'a.id_user',
				'b.nomebanco',
				'c.razaosocial',
				'd.nomecompleto',
				'e.nomesubcategoria',
				'f.nomecategoria'
				))
				->join(array('b'=>'tblcontasbancarias'),'b.id_registro = a.id_conta',array())
				->join(array('c'=>'tblpessoas_basicos'), 'c.id_registro = a.id_pessoa',array())
				->join(array('d'=>'tblsystem_users'),'d.id_registro = a.id_user',array())
				->join(array('e'=>'tblfinanceiro_subcategorias'),'e.id_registro = a.categoria',array())
				->join(array('f'=>'tblfinanceiro_categorias'),'f.id_registro = e.id_categoria',array());
							
					
				if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
				{
					for ( $i=0 ; $i<count($aColumns) ; $i++ )
					{
						$sWhere= $aColumns[$i]." LIKE '%". $_GET['sSearch'] ."%'";
						
						if($i==0){
							$return->where($sWhere);
						}else{
							$return->orWhere($sWhere);
						}
					}
						
				}
				
				if(isset($_GET['datainicial']) && $_GET['datainicial'] != '' ){
					$datainicial = Functions_Datas::inverteData($_GET['datainicial']);
					$return->where("a.datalancamento >= '$datainicial'");
				}
				
				if(isset($_GET['datafinal'])&& $_GET['datafinal'] != '' ){
					$datafinal = Functions_Datas::inverteData($_GET['datafinal']);
					$return->where("a.datalancamento <='$datafinal'");
				}
				
				if(isset($_GET['conta'])&& $_GET['conta'] != ''){
					$banco = $_GET['conta'];
					$return->where("a.id_conta = '$banco'");
				}
				
				if(isset($_GET['conciliados'])&& $_GET['conciliados'] != ''){
					$conciliados = $_GET['conciliados'];
					$return->where("a.situacao = '$conciliados'");
				}
				
				if ( isset( $_GET['iSortCol_0'] ) )
				{
						
					for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
					{
						if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
						{
							$collum = $aColumns[ intval( $_GET['iSortCol_'.$i] ) ];
							$type =  $_GET['sSortDir_'.$i];
							$return->order("$collum $type");
								
								
						}
					}
						
						
						
						
				}else{
					$return->order("id_registro ASC");
				}
					
				if($_GET['iDisplayLength']){
					$tamanho = $_GET['iDisplayLength'];
				}else{
					$tamanho = 10;
				}
				if($_GET['iDisplayStart']){
					$inicio = $_GET['iDisplayStart'];
				}else{
					$inicio = 0;
				}
					
				$return->limit($tamanho,$inicio);
					
					
					
				//	echo $return;
					
				$rs = $return->query();
				$dados = $rs->fetchAll();
					
				$iFilteredTotal = count($dados);
					
					
				$output = array(
						"sEcho" => $_GET['sEcho'],
						"iTotalRecords" => $iTotal,
						"iTotalDisplayRecords" => $iTotal,
						"aaData" => array()
				);
				//echo $return;
			
				foreach($dados as $data){
				
					if($data['valorregistro'] < 0){
						$valor = number_format($data['valorregistro'],2,',','');
						$valorregistro = "<span class=\"label label label-important\">$valor</span>";
					}else{
						$valor = number_format($data['valorregistro'],2,',','');
						$valorregistro = "<span class=\"label label label-success\">$valor</span>";
						
					}
					
					if(Erp_Model_Financeiro_FluxoCaixa::saldoAtualComEste($data['id_registro'], $data['id_conta'],0) < 0){
						$valor = Erp_Model_Financeiro_FluxoCaixa::saldoAtualComEste($data['id_registro'], $data['id_conta']);
						$saldoconta = "<span class=\"label label label-important\">$valor</span>";
					}else{
						$valor = Erp_Model_Financeiro_FluxoCaixa::saldoAtualComEste($data['id_registro'], $data['id_conta']);
						$saldoconta = "<span class=\"label label label-success\">$valor</span>";
					}
					
					
					
					if($data['situacao'] == 0){
						$icons.="<a data-toggle=\"modal\" href=\"/erp/financeiro/novo-lancamento-caixa/id/{$data['id_registro']}\" data-target=\"#NovoLancamentoModal\"><i style=\"cursor:pointer\" class=\"splashy-tag_edit editlanc\" idlanc=\"{$data['id_registro']}\" title=\"Editar Lançamento\"></i></a>  ";
						$icons.="<i style=\"cursor:pointer\" class=\"splashy-star_boxed_empty validarlanc\" idlanc=\"{$data['id_registro']}\" title=\"Conciliar Lançamento\"></i>";
					}else{
						$icons.="<i class=\"splashy-star_boxed_full\" title=\"Lançamento Conciliado\"></i>";
					}
						
					$row = array();
					$row[] = $data['id_registro'];
					$row[] = $data['nomebanco'];
					$row[] = Functions_Datas::MyDateTime($data['datalancamento']);
					$row[] = $data['nomesubcategoria'];
					$row[] = $data['razaosocial'];
					$row[] = $valorregistro;
					$row[] = $saldoconta;
					$row[] = $data['observacoes'];
					$row[] = $icons;
						
						
						
					$output['aaData'][] = $row;
					$row = null;
					$icons = null;
				}
					
					
					
					
				echo json_encode( $output );
		
		
		
	}				
						
	
}