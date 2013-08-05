<?php
class RelatoriosController extends Zend_Controller_Action{

	public function init(){


		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');

		$this->view->parameters = $this->_request->getParams();

		$this->DocsPath = $this->configs->SYS->DocsPath;


		$this->_helper->layout()->setLayout('outside');

	}
	
	public function ordemProducaoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$id = $this->_getParam('id');
		$dbop = new Erp_Model_Producao_Basicos();
		$dadosop = $dbop->fetchRow("id_registro = '$id'");
		$this->view->dadosOP = $dadosop;
	
	
		$db = new Erp_Model_Vendas_Produtos();
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblvendas_produtos'),array('a.id_registro','a.id_venda','a.id_produto', 'a.quantidade','a.vl_unitario','a.vl_total','a.adicional_1','a.adicional_2', 'a.adicional_3','a.qtd_faturada','a.qtd_afaturar','a.comissao','b.nomeproduto','b.referenciaproduto','b.codigointerno', 'c.nomecategoria as nomecategoria','d.nomesubcategoria as nomesubcategoria'))
		->join(array('b'=>'tblprodutos_basicos'),'b.id_registro = a.id_produto',array())
		->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
		->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array());
		$return->where("a.id_registro = '{$dadosop['id_prod_venda']}'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$dados = $dados[0];
		$this->view->dadosProduto = $dados;
	
		$dbprod = new Cadastros_Model_Produtos();
		$dataprod = $dbprod->fetchRow("id_registro = '{$dados['id_produto']}'");
	
		$this->view->DataProd = $dataprod;
	
		$db2 = new Erp_Model_Vendas_Basicos();
		$datavenda = $db2->fetchRow("id_registro = '{$dados['id_venda']}'");
		$this->view->dadosVenda = $datavenda;
	
		$dbconp = new Cadastros_Model_ProdutosCompostos();
		$datacompostos = $dbconp->fetchAll("id_produto = '{$dados['id_produto']}'");
	
		$produto = array();
		foreach ($datacompostos as $composto){
			$nomeproduto = Cadastros_Model_Produtos::getNomeProduto($composto->id_composto);
			$quantidadenecessaria = number_format($composto->quantidadecomposto) * number_format($dados['quantidade']);
			$estoqueatual = Erp_Model_Estoque_Movimento::estoqueAtual($composto->id_composto,FALSE,0);
	
			if(number_format($quantidadenecessaria) > number_format($estoqueatual)){
				$statusprod = 'OK';
			}else{
				$statusprod = 'FALTA';
			}
	
			$produto[] = array('produto'=>$nomeproduto,
					'quantidade'=>$quantidadenecessaria,
					'estoqueatual'=>$estoqueatual,
					'statusprod'=>$statusprod,
					'id_produto'=>$composto->id_composto
			);
		}
	
		$etdb = new Erp_Model_Producao_Etapas();
		$etapas = $etdb->fetchAll();
		$this->view->etapasProd = $etapas;
		$this->view->compostos = $produto;
	
		if(System_Model_SysConfigs::getConfig('ProducaoTinyUrl') == 1){
			$montaurl= Functions_Auxilio::getSysUrl().'/default/producao/lancar/cod/'.$dadosop['accesscode'];
			$tinyurl = new Zend_Service_ShortUrl_TinyUrlCom();
			$url = $tinyurl->shorten($montaurl);
		}else{
			$url= Functions_Auxilio::getSysUrl().'/default/producao/lancar/cod/'.$dadosop['accesscode'];
		}
	
		$this->code_params = array('text'=> $url,
				'moduleSize' => 8);
		$type = 'image';
		$renderer_params['imageType'] = 'png';
		$renderer_params['sendResult'] = false;
		$extension = 'png';
		if(is_file( APPLICATION_PATH . '/data/temp'. DIRECTORY_SEPARATOR . "op_$id.png")){
			unlink( APPLICATION_PATH . '/data/temp'. DIRECTORY_SEPARATOR . "op_$id.png");
		}
		$res =  Zend_Matrixcode::render('qrcode', $this->code_params, 'image', $renderer_params);
		$img = imagepng($res, APPLICATION_PATH . '/data/temp'. DIRECTORY_SEPARATOR . "op_$id.png");
	
	
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$OP = $dom->createElement("OP");
		$DadosOp = $dom->createElement("DadosOP");
	
		$Logo = $dom->createElement("LOGOTIPO", Functions_Auxilio::getSysUrl() . "/default/arquivos/get-logo-report");
		$DadosOp->appendChild($Logo);
	
		$QRCODE = $dom->createElement("QRCODE", Functions_Auxilio::getSysUrl(). "/default/arquivos/render-op-code/id/". $dadosop->id_registro);
		$DadosOp->appendChild($QRCODE);
	
	
		$nomeEmpresa = $dom->createElement("EMPRESA", System_Model_Empresas::getNomeFantasiaEmpresa(System_Model_Empresas::getEmpresaPadrao()));
		$DadosOp->appendChild($nomeEmpresa);
	
		$codOp = $dom->createElement("COD",$dadosop->id_registro);
		$DadosOp->appendChild($codOp);
	
		$PedidoVenda = $dom->createElement("CODIGOPEDIDO",$datavenda->id_registro);
		$DadosOp->appendChild($PedidoVenda);
	
		$REFERENCIA = $dom->createElement("REFERENCIA",$dataprod->referenciaproduto);
		$DadosOp->appendChild($REFERENCIA);
	
		$CLIENTE = $dom->createElement("CLIENTE", Functions_NFe::limparString(Cadastros_Model_Pessoas::getNomeEmpresa($datavenda->id_pessoa)));
		$DadosOp->appendChild($CLIENTE);
	
		$ENTRADA = $dom->createElement("ENTRADA",$datavenda->datacriado);
		$DadosOp->appendChild($ENTRADA);
	
		$ENTREGA = $dom->createElement("ENTREGA",$datavenda->agendamento_entrega);
		$DadosOp->appendChild($ENTREGA);
	
		$QTD = $dom->createElement("QTD",$dados['quantidade']);
		$DadosOp->appendChild($QTD);
		$OP->appendChild($DadosOp);
	
	
		$PRODUTO = $dom->createElement("PRODUTO");
		$IMGPRODUTO = $dom->createElement("IMGPRODUTO",$dadosop->id_registro);
		$PRODUTO->appendChild($IMGPRODUTO);
	
		$NOMEPRODUTO = $dom->createElement("NOMEPRODUTO",$dataprod->nomeproduto);
		$PRODUTO->appendChild($NOMEPRODUTO);
	
		$REFERENCIAPRODUTO = $dom->createElement("REFERENCIAPRODUTO",$dataprod->referenciaproduto);
		$PRODUTO->appendChild($REFERENCIAPRODUTO);
	
		$ADICIONAL1 = $dom->createElement("ADICIONAL1",$dados['adicional_1']);
		$PRODUTO->appendChild($ADICIONAL1);
	
		$ADICIONAL2 = $dom->createElement("ADICIONAL2",$dados['adicional_2']);
		$PRODUTO->appendChild($ADICIONAL2);
	
		$ADICIONAL3 = $dom->createElement("ADICIONAL3",$dados['adicional_3']);
		$PRODUTO->appendChild($ADICIONAL3);
	
		$PESOUNITARIO = $dom->createElement("PESOUNITARIO",$dataprod->pesoproduto);
		$PRODUTO->appendChild($PESOUNITARIO);
	
		$PESOTOTAL = $dom->createElement("PESOTOTAL",$dataprod->pesoproduto * $dados['quantidade']);
		$PRODUTO->appendChild($PESOTOTAL);
		$OP->appendChild($PRODUTO);
	
		$ProdComp = $dom->createElement("COMPOSTOS");
		foreach($produto as $prod){
			$Composto = $dom->createElement("Composto");
			$prodc = $dom->createElement("PRODUTOCOMPOSTO", $prod['produto']);
			$Composto->appendChild($prodc);
			$QUANTIDADECOMPOSTO = $dom->createElement("QUANTIDADECOMPOSTO", $prod['quantidade']);
			$Composto->appendChild($QUANTIDADECOMPOSTO);
			$ESTOQUEAQUAL = $dom->createElement("ESTOQUEATUAL", $prod['estoqueatual']);
			$Composto->appendChild($ESTOQUEAQUAL);
	
	
			$ProdComp->appendChild($Composto);
		}
	
		$ETAPAS = $dom->createElement("ETAPAS");
		foreach($etapas as $etapa){
			$ETAPA = $dom->createElement("ETAPA");
			$NOMEETAPA = $dom->createElement("NOMEETAPA",$etapa->nomeetapa);
			$ETAPA->appendChild($NOMEETAPA);
			$TIPOETAPA = $dom->createElement("TIPOETAPA",$etapa->tipoetapa);
			$ETAPA->appendChild($TIPOETAPA);
				
				
			$ETAPAS->appendChild($ETAPA);
				
		}
	
	
	
		$OP->appendChild($ProdComp);
		$OP->appendChild($ETAPAS);
		$dom->appendChild($OP);
		header("Content-type: text/xml");
		
		$xml = $dom->saveXML();
		$xml = str_replace('<?xml version="1.0" encoding="UTF-8  standalone="no"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
		//remove linefeed, carriage return, tabs e multiplos espaços
		$xml = preg_replace('/\s\s+/', ' ', $xml);
		$xml = str_replace("> <", "><", $xml);
		
		echo $xml;
	
	
	}
	
}