
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
 
class ProducaoController extends Zend_Controller_Action{
	
	public function init(){
	
	
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
		$this->view->parameters = $this->_request->getParams();
	
		$this->DocsPath = $this->configs->SYS->DocsPath;
	
	
		$this->_helper->layout()->setLayout('outside');
	
	}
	
	
	public function lancarAction(){
		
		//$this->_helper->layout->disableLayout();
		$id = $this->_getParam('cod');
		$this->view->idOP = $id;
		
		$ops = new Erp_Model_Producao_Basicos();
		$dataop = $ops->fetchRow("accesscode = '$id'");
		$this->view->dataop = $dataop;
		
		$form = new Erp_Form_Producao;
		$form->lancamentoext();
		$form->populate(array('id_producao'=>$dataop['id_registro']));
		$this->view->form = $form;
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			try{
					
				$datamov = new Erp_Model_Producao_Basicos();
				$dataMov = $datamov->fetchRow("id_registro = '{$formdata['id_producao']}'");
					
				$prod = new Erp_Model_Vendas_Produtos();
				$ProdData = $prod->fetchRow("id_registro = '{$dataMov['id_prod_venda']}'");
					
				$db = new Erp_Model_Producao_Registros();
				$data = array('id_producao'=>$formdata['id_producao'],
						'etapa'=>$formdata['etapa'],
						'quantidade'=>str_replace(",", ".",$formdata['quantidade']),
						'usuario'=>$formdata['usuario'],
						'datalancamento'=>date('Y-m-d H:i:s')
				);
					
				$db->insert($data);
				System_Model_SysConfigs::getConfig("ProducaoEstoqueProdutos");
					
				if($formdata['etapa'] == System_Model_SysConfigs::getConfig("ProducaoEtapaFinal")){
					$est = new Erp_Model_Estoque_Movimento();
					$datamov = array('id_produto'=>$ProdData['id_produto'],
							'id_estoque'=>System_Model_SysConfigs::getConfig("ProducaoEstoqueProdutos"),
							'quantidade'=>str_replace(",", ".",$formdata['quantidade']),
							'historico'=> "Movimento da Ordem de Produção N {$formdata['id_producao']}",
							'usuario'=>$this->userInfo->id_registro,
							'data'=>date('Y-m-d H:i:s')
					);
					$est->insert($datamov);
					$somaOps = Erp_Model_Producao_Registros::GetSomaOP($formdata['id_producao']);
					$totalAtual = Erp_Model_Estoque_Movimento::estoqueAtual($ProdData['id_produto'],System_Model_SysConfigs::getConfig("ProducaoEstoqueProdutos" ),0);
					if($somaOps >= $totalAtual){
						$statusfinal = System_Model_SysConfigs::getConfig("ProducaoStatusFinalizado");
						$prod->update(array("statusproducao = '$statusfinal'"), "id_registro = '{$dataMov['id_prod_venda']}'");
					}else{
						$statusfinal = System_Model_SysConfigs::getConfig("ProducaoEtapaAndamento");
						$prod->update(array("statusproducao = '$statusfinal'"), "id_registro = '{$dataMov['id_prod_venda']}'");
					}
		
				}
				$statusfinal = System_Model_SysConfigs::getConfig("ProducaoEtapaAndamento");
				$otherdata = $prod->fetchRow("id_venda = '{$ProdData['id_venda']}' and statusproducao <> '$statusfinal' ");
				if(!$otherdata->id_registro){
					$vendad = new Erp_Model_Vendas_Basicos();
					$datav = array('pedidoemproducao'=>'2');
					$vendad->update($datav,"id_registro = '{$ProdData['id_venda']}'");
					 
		
				}
					
					
					
				echo "OK";
					
			}catch (Exception $e){
				echo "ERRO"; echo $e->getMessage();
			}
		}
		
	}
	
	
	
}
