<?php
class Erp_ProducaoController extends Zend_Controller_Action{

	public $log;
	public $configs;
	public $cache;
	public $userInfo;
	
	public function init(){
	
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->userInfo = Zend_Auth::getInstance()->getStorage()->read();
	
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
	
	}
	
	public function indexAction(){
	
	}
	
	
	public function listarPedidosAction(){
		
	}
	
	public function produzirPedidoAction(){
		
	}
	
	public function consultarProgramacaoAction(){
		
	}
	
	public function abrirPedidoAction(){
		
		
		
		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		
		$db = new Erp_Model_Vendas_Basicos();
		$data = $db->fetchRow("id_registro = '$id'")->toArray();
		$data['nomepessoa'] = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_pessoa']);
		$data['nomevendedor'] = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_vendedor']);
		if($data['entrega_de'] <> ''){
			$data['entrega_de'] = Functions_Datas::MyDateTime($data['entrega_de']);
		}
		if($data['entrega_ate'] <> ''){
			$data['entrega_ate'] = Functions_Datas::MyDateTime($data['entrega_ate']);
		}
		if($data['datainspecao'] <> ''){
			$data['datainspecao'] = Functions_Datas::MyDateTime($data['datainspecao']);
		}
		if($data['agendamento_entrega'] <> ''){
			$data['agendamento_entrega_ok'] = $data['agendamento_entrega'];
			$data['agendamento_entrega_hora'] = date('H:i',strtotime($data['agendamento_entrega']));
			$data['agendamento_entrega'] = Functions_Datas::MyDateTime($data['agendamento_entrega']);
		}else{
			$data['agendamento_entrega_ok'] = '';
			$data['agendamento_entrega_hora'] = '';
			$data['agendamento_entrega'] = '';
		}
	
		$this->view->basicosData = $data;
		
	
		
	}
	
	public function programarProdutoAction(){
		$id = $this->_getParam('id');
		$db = new Erp_Model_Vendas_Produtos();
		$return = $db->getAdapter()->select()
		->from(array('a'=>'tblvendas_produtos'),array('a.id_registro','a.id_venda','a.id_produto', 'a.quantidade','a.vl_unitario','a.vl_total','a.adicional_1','a.adicional_2', 'a.adicional_3','a.qtd_faturada','a.qtd_afaturar','a.comissao','b.nomeproduto','b.referenciaproduto','b.codigointerno', 'c.nomecategoria as nomecategoria','d.nomesubcategoria as nomesubcategoria'))
		->join(array('b'=>'tblprodutos_basicos'),'b.id_registro = a.id_produto',array())
		->join(array('c'=>'tblapoio_categoriasprodutos'), 'c.id_registro = b.categoriaproduto',array())
		->join(array('d'=>'tblapoio_subcategoriadeprodutos'),'d.id_registro = b.subcategoriaproduto',array());
		$return->where("a.id_registro = '$id'");
		$rs = $return->query();
		$dados = $rs->fetchAll();
		$dados = $dados[0];
		$this->view->dadosProduto = $dados;
		
		
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
		//print_r($produto);
		$this->view->compostos = $produto;
		$nomeproduto = null;
		$quantidadenecessaria = null;
		$estoqueatual = null;
		$statusprod = null;
		

		
	}
	
	public function liberarVendasAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Erp_Model_Vendas_Basicos();
		$id = $_POST['id'];
		$data = $db->update(array("liberaprod"=>NULL,
				'userlibera'=>NULL,
				'datalibera'=>NULL
		),"id_registro = '$id'");
		
	}
	
	
	public function confirmaProducaoAction(){
		
		
		
		$id = $this->_getParam('id');
		$dbproduto = new Erp_Model_Vendas_Produtos();
		$dadosproduto = $dbproduto->fetchRow("id_registro = '$id'");
		$db = new Erp_Model_Producao_Basicos();
		$data1 = array('id_prod_venda'=>$id,
				'quantidade'=>$dadosproduto->quantidade,
				'id_user'=>$this->userInfo->id_registro,
				'dataregistro'=>date('Y-m-d H:i:s'),
				'accesscode'=>sha1(md5(microtime().$id.$this->userInfo->id_registro.strtotime(date('Y-m-d H:i:s'))))
		);
		
		$id_prod = $db->insert($data1);
		
		$data2 = array('id_prod'=>$id_prod,
				'userprod'=>$this->userInfo->id_registro,
				'dataprod'=>date('Y-m-d H:i:s')
		);
		
		$dbproduto->update($data2, "id_registro = '$id'");
		
	
		
		$dbv = new Erp_Model_Vendas_Basicos();
		$data = $dbv->fetchRow("id_registro = '{$dadosproduto->id_venda}'")->toArray();
		$data['nomepessoa'] = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_pessoa']);
		$data['nomevendedor'] = Cadastros_Model_Pessoas::getNomeEmpresa($data['id_vendedor']);
		if($data['entrega_de'] <> ''){
			$data['entrega_de'] = Functions_Datas::MyDateTime($data['entrega_de']);
		}
		if($data['entrega_ate'] <> ''){
			$data['entrega_ate'] = Functions_Datas::MyDateTime($data['entrega_ate']);
		}
		if($data['datainspecao'] <> ''){
			$data['datainspecao'] = Functions_Datas::MyDateTime($data['datainspecao']);
		}
		if($data['agendamento_entrega'] <> ''){
			$data['agendamento_entrega_ok'] = $data['agendamento_entrega'];
			$data['agendamento_entrega_hora'] = date('H:i',strtotime($data['agendamento_entrega']));
			$data['agendamento_entrega'] = Functions_Datas::MyDateTime($data['agendamento_entrega']);
		}else{
			$data['agendamento_entrega_ok'] = '';
			$data['agendamento_entrega_hora'] = '';
			$data['agendamento_entrega'] = '';
		}
		
		$this->view->basicosData = $data;
		
		
		$cprodutos = $dbproduto->fetchAll("id_venda = {$dadosproduto->id_venda}");
		$nprodutos = count($cprodutos);
		$cprodutosprod = $dbproduto->fetchAll("id_venda = {$dadosproduto->id_venda} and id_prod <> '0' ");
		$nprodutosprod = count($cprodutosprod);
					
		if($nprodutos == $nprodutosprod){
		   $messages[] = Functions_Messages::renderAlert("Programação do Pedido Finalizada",'sucesso');
			$data3 = array('pedidoemproducao'=>'1',
					'dataentradaprod'=>date('Y-m-d H:i:s'),
					'userrespprod'=>$this->userInfo->id_registro
			);
			
			$dbv->update($data3, "id_registro = '{$dadosproduto->id_venda}'");
			
		}else{
			$resta = $nprodutos - $nprodutosprod;
			$messages[] = Functions_Messages::renderAlert("Ainda restam {$resta} produtos a serem programados",'info');
		}
		
		
		$messages[] = Functions_Messages::renderAlert("<a href=\"/erp/producao/print-op/id/{$id_prod}\" target=\"_blank\">Produção Confirmada, clique aqui para imprimir a OP</a>",'sucesso');
		$this->view->AlertMessage = $messages; 
		
	
	
	}
	
	public function cancelaProgramacaoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_POST['id_prog_cancel'];
		$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
		$result = $recaptcha->verify(
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']
		);
		if (!$result->isValid()) {
			echo "ERRO";
		}else{
			try{
			$dbproduto = new Erp_Model_Vendas_Produtos();
			$dadosproduto = $dbproduto->fetchRow("id_prod = '$id'");
			$db = new Erp_Model_Producao_Basicos();
			$dbv = new Erp_Model_Vendas_Basicos();
			$db->delete("id_registro = '$id'");
			$data2 = array('id_prod'=>0,
					'userprod'=>NULL,
					'dataprod'=>NULL
			);
			
			$dbproduto->update($data2, "id_registro = '{$dadosproduto->id_registro}'");
			
			$cprodutos = $dbproduto->fetchAll("id_venda = '{$dadosproduto->id_venda}'");
			$nprodutos = count($cprodutos);
			$cprodutosprod = $dbproduto->fetchAll("id_venda = '{$dadosproduto->id_venda}' and id_prod <> '0' ");
			$nprodutosprod = count($cprodutosprod);
				
			if($nprodutosprod < $nprodutos){
				
				$data3 = array('pedidoemproducao'=>0,
						'dataentradaprod'=>NULL,
						'userrespprod'=>NULL
				);
					
				$dbv->update($data3, "id_registro = '{$dadosproduto->id_venda}'");
					
			}else{
				$resta = $nprodutos - $nprodutosprod;
				
			}
				echo $id;
				echo "OK";
			}catch (Exception $e){
				echo $e->getMessage();
				echo $e->getTrace();
				echo $e->getTraceAsString();
			}
		}
	}
	
	
	public function printOpAction(){
		$this->_helper->layout->disableLayout();
		//$this->_helper->viewRenderer->setNoRender();
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
      //  echo "<img src=\"/default/arquivos/render-op-code/id/$id\">";
     
     
	}
	
	public function consultaCompletaAction(){
		$this->_helper->layout->disableLayout();
		$id = $this->_getParam('id');
		$etdb = new Erp_Model_Producao_Etapas();
		$etapas = $etdb->fetchAll();
		$this->view->etapasProd = $etapas;
		$this->view->IdProd = $id;
		
		
	}
	
	
	public function lancarModalAction(){
		$this->_helper->layout->disableLayout();
		$id = $this->_getParam('id');
		$this->view->idOP = $id;
		$form = new Erp_Form_Producao;
		$form->lancamento();
		$form->populate(array('id_producao'=>$id));
		$this->view->form = $form;
		
		
		
	}
	
	public function salvarLancamentoModalAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
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
					'usuario'=>$this->userInfo->id_registro,
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
	
	
	public function montaXmlOpAction(){
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
		$xml = $dom->saveXML();
		echo $xml;
		
		
	}
	
	
}