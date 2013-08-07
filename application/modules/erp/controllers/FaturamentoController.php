<?php
class Erp_FaturamentoController extends Zend_Controller_Action{

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
		$db_empresas = new System_Model_Empresas();
		$dataemp = $db_empresas->fetchAll();
		$this->view->empresas = $dataemp;
		$statusret = '';
		foreach($dataemp as $emp){
		
			$Config = System_Model_EmpresasNF::getConfigNFe($emp->id_registro);
			//print_r($Config);
			if($Config['certName'] <> ''){
				try{			
					$nfe = new NFe_ToolsNFePHP($Config,2,false);
					$status = $nfe->statusServico('','',2,$statusret);
					$datastatus[] = array('empresa'=>$emp->razaosocial,
									'status'=>$status,
									'statusret'=>$statusret
									);
			
					$status = null;
					$statusret = null;
				}catch (Exception $e){
					echo $e->getMessage();
				}
			}
		}
		
		$this->view->statusServico = $datastatus;
		
				
	}

	public function novoAction(){
		$this->_helper->layout()->setLayout('vendedor/layout');

	}

	public function abrirAction(){

	}
	
	public function novaPedidoAction(){
		
		$messages[] = Functions_Messages::renderAlert("Selecione um pedido para faturar, você pode incluir posteriormente outros pedidos no mesmo faturamento!",'info');
		
		
		$this->view->AlertMessage = $messages;
		
	}
	
	public function novaAvulsaAction(){
		
	}

	public function listarAction(){
		$tipo = $this->_request->getParam("type",3);
		$this->view->tipo = $tipo;
		

	}
	
	public function listarEntradaAction(){
		$tipo = $this->_request->getParam("type",3);
		$this->view->tipo = $tipo;
	
	
	}
	
	
	public function abrirPedidoAction(){
		$id = $this->_request->getParam("id",0);
		
		$dv = new Erp_Model_Vendas_Basicos();
		$dadosvendas = $dv->fetchRow($dv->select()->where("id_registro = ?",$id));
		$this->view->dadosVenda = $dadosvendas;

		$pr = new Erp_Model_Vendas_Produtos();
		$this->view->vendaProdutos = $pr->fetchAll("id_venda = '$id'");		
		
		$ps = new Cadastros_Model_Pessoas();
		$this->view->dadosPessoa = $ps->fetchRow("id_registro = '{$dadosvendas->id_pessoa}'");
		
		$pe = new Cadastros_Model_Enderecos();
		$this->view->pessoaEnderecos = $pe->fetchAll("id_pessoa = '{$dadosvendas->id_pessoa}'");
		
		$po = new Cadastros_Model_Outros();
		$this->view->pessoaOutros = $po->fetchRow("id_pessoa = '{$dadosvendas->id_pessoa}'");
		
		$pc = new Cadastros_Model_Contatos();
		$this->view->pessoaContatos = $pc->fetchAll("id_pessoa = '{$dadosvendas->id_pessoa}'");
		
		
		
		
		
		
	}
	
	
	public function dadosEndEmpresaAction(){
		$this->_helper->layout->disableLayout();
		$id = $this->_request->getParam("id",0);
	
	
		$pe = new Cadastros_Model_Enderecos();
		$this->view->pessoaEnderecos = $pe->fetchAll("id_pessoa = '{$id}'");
	
			
		$pc = new Cadastros_Model_Contatos();
		$this->view->pessoaContatos = $pc->fetchAll("id_pessoa = '{$id}'");
	
	
	
	
	
	
	}
	
	public function getDataPerfilAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_Perfil();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
		
		
	}
	
	public function salvaPedidoEmissaoAction(){
		
		$formData = $this->_request->getPost();
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		
		try{
			
		
		
		
		$dadosNfe = System_Model_EmpresasNF::getDadosConfigNFe($formData['empresa']);
		$dadosEmissor = System_Model_Empresas::getDataEmpresa($formData['empresa']);
		$dadosOperacao = Erp_Model_Faturamento_Perfil::getDataOperacao($formData['perfilfaturamento']);
		$dadosDestinatario = Cadastros_Model_Pessoas::getPessoa($formData['id_pessoa']);
		
		if($dadosNfe['danfeformato'] == 'P'){
			$formatodanfe = '1';
		}else{
			$formatodanfe = '2';
		}
		
		$dbasicos = new Erp_Model_Faturamento_NFe_Basicos();
		$dsbasicos = array(
				'versao'=>$dadosNfe->versaonfe,
				'Id'=>"NFe",
				'cUF'=>$dadosEmissor['coduf'],
				'cNF'=> rand(10000001, 99999999) ,
				'natOp'=> Functions_NFe::limparString($formData['NaturezaOperacao']),
				'indPag'=>$formData['tipodepagamenamento'],
				'mod'=>$dadosNfe->modelonfe,
				'serie'=>$dadosNfe->serienfe,
				'nNF'=>$dadosNfe->lastnfe + 1,
				'dEmi'=>date('Y-m-d'),
				'dSaiEnt'=> Functions_Datas::inverteData($formData['DataSaida']),
				'hSaiEnt'=>$formData['HoraSaida'],
				'tpNF'=>$dadosOperacao->tipoperfil ,
				'cMunFG'=>$dadosEmissor['codmun'],
				'cUF'=>$dadosEmissor->coduf,
				'AAMM'=>date('ym'),
				'tpimp'=>$formatodanfe,
				'CNPJ'=>Functions_NFe::limpaCNPJCFP($dadosEmissor->cnpj),
				'tpEmis'=>'1',
				'cDV'=>'0',
				'tpAmb'=>$dadosNfe->ambienteemissao,
				'finNFe'=>$dadosOperacao->finalidadeemissao,
				'procEmi'=>'0',
				'verProc'=>$this->configs->Leader->SysNameNFe,
				'modFrete'=>$formData['modalidadefrete'],
				'id_empresa'=>$formData['empresa'],
				'id_pedvenda'=>$formData['id_pedido_venda'],
				'id_perfil'=>$formData['perfilfaturamento'],
				'tipo_nfe'=>'1',
				'status_processo'=>'0'
						
		);
		
		
		$idNFe = $dbasicos->insert($dsbasicos);
		
		$chave = Functions_NFe::montaChaveXML($idNFe);
		
		$dbasicos->update(array('Id'=>"NFe{$chave['Chave']}",
		'cDV'=>$chave['DV'],
		'chaveacesso'=>$chave['Chave']
		),"id_registro = '$idNFe'");
		
				
		
		$dbemi = new Erp_Model_Faturamento_NFe_Emitente();
		$demitente = array(
				'id_nfe'=>$idNFe,
				'id_empresa'=>$dadosEmissor->id_registro,
				'id_pessoa'=>'0',
				'CNPJ'=> Functions_NFe::limpaCNPJCFP($dadosEmissor->cnpj),
				'CPF'=>'',
				'xNome'=> Functions_NFe::limparString($dadosEmissor->razaosocial),
				'xFant'=> Functions_NFe::limparString($dadosEmissor->nomefantasia),
				'IE'=> Functions_NFe::limpaIE($dadosEmissor->inscestadual),
				'IEST'=>'',
				'IM'=>Functions_NFe::limpaIM($dadosEmissor->inscmunicipal),
				'CNAE'=>Functions_NFe::limpaCNPJCFP($dadosEmissor->cnae),
				'CRT'=>$dadosEmissor->regimetributario,
				'xLgr'=> Functions_NFe::limparString($dadosEmissor->endereco),
				'nro'=>Functions_NFe::limparString($dadosEmissor->numeroend),
				'xCpl'=>Functions_NFe::limparString($dadosEmissor->complementoend),
				'xBairro'=>Functions_NFe::limparString($dadosEmissor->bairroend),
				'cMun'=>Functions_NFe::limparString($dadosEmissor->codmun),
				'xMun'=>Functions_NFe::limparString($dadosEmissor->cidadeend),
				'UF'=>Functions_NFe::limparString($dadosEmissor->estadoend),
				'CEP'=>Functions_NFe::limparString($dadosEmissor->cep),
				'cPais'=>Functions_NFe::limparString($dadosEmissor->codpais),
				'xPais'=>'Brasil',
				'fone'=>Functions_NFe::limpaTel($dadosEmissor->telefone),	
				
		);
		$dbemi->insert($demitente);
		
		$endFaturamento = Cadastros_Model_Enderecos::getEnderecoCadastro($formData['endFaturamento']);
		
		$dadosContatoDest = Cadastros_Model_Contatos::getContato($formData['envviarEmail']);
		
		$dbdest = new Erp_Model_Faturamento_NFe_Destinatario();
		$ddestinatario = array(
				'id_nfe'=>$idNFe,
				'id_empresa'=>'0',
				'id_pessoa'=>$dadosDestinatario->id_registro,
				'CNPJ'=> Functions_NFe::limpaCNPJCFP($dadosDestinatario->cnpj),
				'CPF'=>'',
				'xNome'=> Functions_NFe::limparString($dadosDestinatario->razaosocial),
				'xFant'=> Functions_NFe::limparString($dadosDestinatario->nomefantasia),
				'IE'=> Functions_NFe::limpaIE($dadosDestinatario->inscestadual),
				'ISUF'=>'',
				'xLgr'=> Functions_NFe::limparString($endFaturamento->logradouro),
				'nro'=>Functions_NFe::limparString($endFaturamento->numero),
				'xCpl'=>Functions_NFe::limparString($endFaturamento->complemento),
				'xBairro'=>Functions_NFe::limparString($endFaturamento->bairro),
				'cMun'=>Functions_NFe::limparString($endFaturamento->codibge),
				'xMun'=>Functions_NFe::limparString($endFaturamento->cidade),
				'UF'=>Functions_NFe::limparString($endFaturamento->estado),
				'CEP'=>Functions_NFe::limparString($endFaturamento->cep),
				'cPais'=>Functions_NFe::limparString($endFaturamento->codpais),
				'xPais'=>'Brasil',
				'fone'=>Functions_NFe::limpaTel($dadosContatoDest->telcomercial),
				'email'=>$dadosContatoDest->email	
		);
		
		$dbdest->insert($ddestinatario);
		
		
		$dprodutos = new Erp_Model_Faturamento_NFe_Produtos();
		for($i=0;$i<count($formData['id_regprdvenda']);$i++){
			$qCon = str_replace(',', '.', $formData['quantidadeprod'][$i]);
			if($qCon > 0){

			$nItem = $i+1;
			$dadosProduto = Cadastros_Model_Produtos::getProduto($formData['id_produto'][$i]);
			
			$vCon = str_replace(',', '.', $formData['vlprodunit'][$i]);
			if($formData['descontoprodunit'][$i] <> ''){
				$descP = str_replace(',', '.', $formData['descontoprodunit'][$i]);
				if($descP > 0){
					$desconto = (($vCon / 100)* $descP);
				}else{
					$desconto = 0;
				}
			}else{
				$desconto = 0;
			}
			if($formData['vFrete'][$i] <> ''){
				$vFrete = str_replace(',', '.', $formData['vFrete'][$i]);
			}else{
				$vFrete = 0;
			}
			
			if($formData['vSeg'][$i] <> ''){
				$vSeg = str_replace(',', '.', $formData['vSeg'][$i]);
			}else{
				$vSeg = 0;
			}
			
			if($formData['vOutro'][$i] <> ''){
				$vOutro = str_replace(',', '.', $formData['vOutro'][$i]);
			}else{
				$vOutro = 0;
			}
			
			
			$vProd = $vCon * $qCon;
			
			if($formData['id_regprdvenda'] <> 0){
				$dbVP = new Erp_Model_Vendas_Produtos();
				$DadosVendaProd = $dbVP->fetchRow("id_registro = '{$formData['id_regprdvenda']}'");
				
				if($DadosVendaProd->adicional_1 <> ''){
					$AD1 = " {$DadosVendaProd->adicional_1}";
				}else{
					$AD1 = '';
				}
				if($DadosVendaProd->adicional_2 <> ''){
					$AD2 = " {$DadosVendaProd->adicional_2}";
				}else{
					$AD2 = '';
				}
				if($DadosVendaProd->adicional_3 <> ''){
					$AD3 = " {$DadosVendaProd->adicional_3}";
				}else{
					$AD3 = '';
				}
				
			}else{
				$AD1 = "";
				$AD2 = "";
				$AD3 = "";
			}
					
			$arrProdutos = array(
					'id_nfe'=>$idNFe,
					'id_produto'=>$formData['id_produto'][$i],
					'id_prod_venda'=>$formData['id_regprdvenda'][$i],
					'id_prod_compra'=>'0',
					'nItem'=>$nItem,
					'cProd'=>Functions_NFe::limparString($dadosProduto['codigonfe']),
					'xProd'=>Functions_NFe::limparString($dadosProduto['nomeproduto'] . $AD1 . $AD2),
					'NCM'=>Functions_NFe::limparString($dadosProduto['ncmproduto']),
					'CFOP'=>$dadosOperacao['cfop'],
					'uCom'=> System_Model_Unidadesdemedida::getUnCon($dadosProduto['unidadedemedida']),
					'qCom'=>$qCon,
					'vUnCom'=>$vCon,
					'vProd'=>$vProd,
					'uTrib'=> System_Model_Unidadesdemedida::getUnCon($dadosProduto['unidadedemedida']),
					'qTrib'=>$qCon,
					'vUnTrib'=>$vCon,
					'vFrete'=>$vFrete,
					'vSeg'=>$vSeg,
					'vDesc'=>$desconto,
					'vOutro'=>$vOutro,
					'intTot'=>$dadosOperacao->vlprodcompoetotal,
					'infAdProd'=>Functions_NFe::limparString($dadosProduto['infadicionaisnfe'] . $AD3),
					
			);
			
			if($dadosProduto['eangtin'] <> ''){
				$arrProdutos['cEAN'] = Functions_NFe::limparString($dadosProduto['eangtin']);
				$arrProdutos['cEANTrib'] = Functions_NFe::limparString($dadosProduto['eangtin']);
			}
							
			$id_produto_nf = $dprodutos->insert($arrProdutos);
			$dbProdICMS = new Erp_Model_Faturamento_NFe_ProdutosICMS();

			$ICMSType = Erp_Model_Faturamento_PerfilICMS::getPerfil($formData['perfilfaturamento']);
			
			switch($ICMSType->sittributaria){
				case '00':
				case '0':
					$valorICMS = (($vProd / 100) * $ICMSType->aliqicms);				
					$dadosICMS = array(
							'vBC'=>$vProd,
							'pICMS'=>$ICMSType->aliqicms,
							'vICMS'=>$valorICMS,
							'CST'=>'00',
							'modBC'=>$ICMSType->moddetermbcicms
					);
				break;
				
				case '10':
					$valorICMS = (($vProd / 100) * $ICMSType->aliqicms);
					$BCICMSST = $vProd - (($vProd / 100) * $ICMSType->redutorbcicmsst);
					$ValorICMSST = (($vProd / 100) * $ICMSType->aliqicmsst);
					$dadosICMS = array(
							'CST'=>'10',
							'modBC'=>$ICMSType->moddetermbcicms,
							'vBC'=>$vProd,
							'pICMS'=>$ICMSType->aliqicms,
							'vICMS'=>$valorICMS,
							'modBCST'=>$ICMSType->moddeterbcicmsst,
							'pMVAST'=>$ICMSType->margemvladicicmsst,
							'pRedBCST'=>$ICMSType->redutorbcicmsst,
							'pICMSST'=>$ICMSType->aliqicmsst,
							'vBCST'=>$BCICMSST,
							'vICMSST'=>$ValorICMSST
					);
				break;
				
				case '20':
					$valorBaseCalculo =  $vProd -(($vProd / 100) * $ICMSType->redutorbcicms); 
					$valorICMS = (($valorBaseCalculo / 100) * $ICMSType->aliqicms);
					$dadosICMS = array(
							'CST'=>'20',
							'modBC'=>$ICMSType->moddetermbcicms,
							'vBC'=>$vProd,
							'pRedBC'=>$ICMSType->redutorbcicms,
							'vBC'=>$valorBaseCalculo,
							'pICMS'=>$ICMSType->aliqicms,
							'vICMS'=>$valorICMS
						);
				break;
				
				case '30':
					
					$valorICMS = (($vProd / 100) * $ICMSType->aliqicms);
					$BCICMSST = $vProd  - (($vProd / 100) * $ICMSType->redutorbcicmsst);
					$ValorICMSST = (($vProd / 100) * $ICMSType->aliqicmsst);
					$dadosICMS = array(
							'CST'=>'30',
							'modBCST'=>$ICMSType->moddeterbcicmsst,
							'pMVAST'=>$ICMSType->margemvladicicmsst,
							'pRedBCST'=>$ICMSType->redutorbcicmsst,
							'pICMSST'=>$ICMSType->aliqicmsst,
							'vBCST'=>$BCICMSST,
							'vICMSST'=>$ValorICMSST
							);					
				break;
				
				case '40':
				case '41':
				case '50':
					$dadosICMS = array(
							'CST'=>$ICMSType->sittributaria,
					);
					if($ICMSType->aliqicms <> '' && $ICMSType->aliqicms > 0){
						$valorICMS = (($vProd / 100) * $ICMSType->aliqicms);
						$dadosICMS['vICMS'] = $valorICMS;
						$dadosICMS['motDescICMS'] = $ICMSType->motivodadesoneracao;
					};
					
					if($ICMSType->motivodadesoneracao <> '' && $ICMSType->motivodadesoneracao <> 0){
						$dadosICMS['motDescICMS'] = $ICMSType->motivodadesoneracao;
					}
					
				break;
				
				case '51':
					
					$valorBaseCalculo = $vProd - (($vProd / 100) * $ICMSType->redutorbcicms); 
					$valorICMS = (($valorBaseCalculo / 100) * $ICMSType->aliqicms);
					$dadosICMS = array(
							'CST'=>'51',
							'modBC'=>$ICMSType->moddetermbcicms,
							'vBC'=>$vProd,
							'pRedBC'=>$ICMSType->redutorbcicms,
							'vBC'=>$valorBaseCalculo,
							'pICMS'=>$ICMSType->aliqicms,
							'vICMS'=>$valorICMS
						);
					
				break;
				
				case '60':
					$valorICMSRetido = (($vProd / 100) * $ICMSType->aliqicmsretidoant);
					$dadosICMS = array(
							'CST'=>'60',
							'vBCSTRet'=>$vProd,
							'vICMSSTRet'=>$valorICMSRetido,
						);
						
				break;
				
				case '70':
					$valorBaseCalculo = $vProd -(($vProd / 100) * $ICMSType->redutorbcicms);
					$valorICMS = (($valorBaseCalculo / 100) * $ICMSType->aliqicms);
					$BCICMSST = $vProd  - (($vProd / 100) * $ICMSType->redutorbcicmsst);
					$ValorICMSST = (($vProd / 100) * $ICMSType->aliqicmsst);
					
					$dadosICMS = array(
							'CST'=>'70',
							'modBC'=>$ICMSType->moddetermbcicms,
							'vBC'=>$vProd,
							'pRedBC'=>$ICMSType->redutorbcicms,
							'vBC'=>$valorBaseCalculo,
							'pICMS'=>$ICMSType->aliqicms,
							'vICMS'=>$valorICMS,
							'modBCST'=>$ICMSType->moddeterbcicmsst,
							'pMVAST'=>$ICMSType->margemvladicicmsst,
							'pRedBCST'=>$ICMSType->redutorbcicmsst,
							'pICMSST'=>$ICMSType->aliqicmsst,
							'vBCST'=>$BCICMSST,
							'vICMSST'=>$ValorICMSST
					);
					
					
				break;
				
				case '90':
					
					$valorBaseCalculo = $vProd - (($vProd / 100) * $ICMSType->redutorbcicms);
					$valorICMS = (($valorBaseCalculo / 100) * $ICMSType->aliqicms);
					$BCICMSST =  $vProd  - (($vProd / 100) * $ICMSType->redutorbcicmsst);
					$ValorICMSST = (($vProd / 100) * $ICMSType->aliqicmsst);
						
					$dadosICMS = array(
							'CST'=>'90',
							'modBC'=>$ICMSType->moddetermbcicms,
							'vBC'=>$vProd,
							'pRedBC'=>$ICMSType->redutorbcicms,
							'vBC'=>$valorBaseCalculo,
							'pICMS'=>$ICMSType->aliqicms,
							'vICMS'=>$valorICMS,
							'modBCST'=>$ICMSType->moddeterbcicmsst,
							'pMVAST'=>$ICMSType->margemvladicicmsst,
							'pRedBCST'=>$ICMSType->redutorbcicmsst,
							'pICMSST'=>$ICMSType->aliqicmsst,
							'vBCST'=>$BCICMSST,
							'vICMSST'=>$ValorICMSST
					);
								
				break;
				
				case '101':
					$valorCredito = (($vProd / 100) * $ICMSType->aliqaplicavelcalculocredito);
					$dadosICMS = array(
							'CSOSN'=>'101',
							'pCredSN'=>$ICMSType->aliqaplicavelcalculocredito,
							'vCredICMSSN'=>$valorCredito
						);				
				break;
				
				case '102':
				case '102':
				case '300':
				case '400':
					
					
					$dadosICMS = array(
							'CSOSN'=>$ICMSType->sittributaria
							);

				break;
				
				case '201':
					$valorCredito = (($vProd / 100) * $ICMSType->aliqaplicavelcalculocredito);
					$BCICMSST = $vProd  - (($vProd / 100) * $ICMSType->redutorbcicmsst) ;
					$ValorICMSST = (($vProd / 100) * $ICMSType->aliqicmsst);
					$dadosICMS = array(
							'CSOSN'=>'201',
							'pCredSN'=>$ICMSType->aliqaplicavelcalculocredito,
							'vCredICMSSN'=>$valorCredito,
							'modBCST'=>$ICMSType->moddeterbcicmsst,
							'pMVAST'=>$ICMSType->margemvladicicmsst,
							'pRedBCST'=>$ICMSType->redutorbcicmsst,
							'pICMSST'=>$ICMSType->aliqicmsst,
							'vBCST'=>$BCICMSST,
							'vICMSST'=>$ValorICMSST
					);
					
				break;
				
				case '202':
					$valorCredito = (($vProd / 100) * $ICMSType->aliqaplicavelcalculocredito);
					$BCICMSST = $vProd  - (($vProd / 100) * $ICMSType->redutorbcicmsst) ;
					$ValorICMSST = (($vProd / 100) * $ICMSType->aliqicmsst);
					$dadosICMS = array(
							'CSOSN'=>'202',
							'modBCST'=>$ICMSType->moddeterbcicmsst,
							'pMVAST'=>$ICMSType->margemvladicicmsst,
							'pRedBCST'=>$ICMSType->redutorbcicmsst,
							'pICMSST'=>$ICMSType->aliqicmsst,
							'vBCST'=>$BCICMSST,
							'vICMSST'=>$ValorICMSST
					);
				break;
				
				case '500':
					$VlICMS = (($vProd / 100) * $ICMSType->aliqicms);
					$dadosICMS = array(
							'CSOSN'=>'500',
							'vBCSTRet'=>$vProd,
							'vICMSRet'=>$VlICMS,
							
					);
					
				break;
				
				case '900':
					$valorCredito = (($vProd / 100) * $ICMSType->aliqaplicavelcalculocredito);
					$dadosICMS = array(
							'CSOSN'=>'900',
							'pCredSN'=>$ICMSType->aliqaplicavelcalculocredito,
							'vCredICMSSN'=>$valorCredito
					);
					
				break;
				
				
				
						
				
				
			}
			$dadosICMS['tributacao'] = $ICMSType->sittributaria;
			$dadosICMS['orig'] = $dadosProduto['origemproduto'];
			$dadosICMS['id_nfe'] = $idNFe;
			$dadosICMS['id_produto_nfe'] = $id_produto_nf;
			$dbProdICMS->insert($dadosICMS);
			

			$dbProdIPI = new Erp_Model_Faturamento_NFe_ProdutosIPI();
			$IPIType = Erp_Model_Faturamento_PerfilIPI::getPerfil($formData['perfilfaturamento']);
			
			$dadosIPI['id_nfe'] = $idNFe;
			$dadosIPI['id_produto_nfe'] = $id_produto_nf;
			
			if($IPIType->classedeenquadramento_ipi <> '' && $IPIType->classedeenquadramento_ipi <> 0){
			$dadosIPI['cIEnq'] = $IPIType->classedeenquadramento_ipi;
			};
			if($IPIType->cnpjprodutor_ipi <> '' && $IPIType->cnpjprodutor_ipi <> 0){
			$dadosIPI['CNPJProd'] = $IPIType->cnpjprodutor_ipi;
			}
			if($IPIType->codselocontrole_ipi <> '' && $IPIType->codselocontrole_ipi <> 0){
			$dadosIPI['cSelo'] = $IPIType->codselocontrole_ipi ;
			}
			if($IPIType->qSelo <> '' && $IPIType->qSelo <> 0){
			$dadosIPI['qSelo'] = $IPIType->qSelo;
			}
			if($IPIType->classedeenquadramento_ipi <> '' && $IPIType->classedeenquadramento_ipi <> 0){
			$dadosIPI['cEnq'] = $IPIType->classedeenquadramento_ipi;
			}else{
				$dadosIPI['cEnq'] = '999';
			}
			
			switch($IPIType->sittributaria_ipi){
				case '00':
				case '0':
			    case '49':
			    case '50':
			    case '99':
			    	
			    	$dadosIPI['CST'] = $IPIType->sittributaria_ipi;
			    	if($IPIType->tipocalculo_ipi == 1){
			    		
			    		$dadosIPI['vBC'] = $vProd;
			    		$dadosIPI['pIPI'] = $IPIType->alipi;
			    		$valorIPI = (($vProd / 100) * $IPIType->alipi);
			    	}elseif($IPIType->tipocalculo_ipi == 2){
			    		$dadosIPI['qUnid'] = $qCon;
			    		$dadosIPI['vUnid'] = $IPIType->vlfixo_ipi;
			    		$valorIPI = $qCon * $IPIType->vlfixo_ipi;
			    	}
			    	$dadosIPI['vIPI'] = $valorIPI;
			    break;
			    
			    case '01':
			    case '02':
			    case '03':
			    case '04':
			    case '51':
			    case '41':
			    case '53':
			    case '54':
			    case '55':
			       	$dadosIPI['CST'] = $IPIType->sittributaria_ipi;
			    break;
				
			}
			
			$dbProdIPI->insert($dadosIPI);
			
			$dbProdPIS = new Erp_Model_Faturamento_NFe_ProdutosPIS();
			$PISType = Erp_Model_Faturamento_PerfilPIS::getPerfil($formData['perfilfaturamento']);
			
			switch($PISType->sittrib_pis){
				case '1':
				case '01':
				case '2':
				case '02':
					$ValorPIS = (($vProd / 100) * $PISType->aliqpis_pis);
					$dadosPIS['CST'] = $PISType->sittrib_pis;
					$dadosPIS['vBC'] = $vProd;
					$dadosPIS['pPIS'] = $PISType->aliqpis_pis;
					$dadosPIS['vPIS'] = $ValorPIS;
					
				break;
				
				case '3':
				case '03':
					$dadosPIS['CST'] = $PISType->sittrib_pis;
					$dadosPIS['qBCProd'] = $qCon;
					$dadosPIS['vAliqProd'] = $PISType->aliqreal_pis;
					$dadosPIS['vPIS'] = $qCon * $PISType->aliqreal_pis;			
				break;			
			
				case '04':
				case '4':
				case '06':
				case '6':
				case '07':
				case '7':
				case '08':
				case '8':
				case '09':
				case '9':
					$dadosPIS['CST'] = $PISType->sittrib_pis;
				break;
				
				default:
					$dadosPIS['CST'] = $PISType->sittrib_pis;
					$dadosPIS['vBC'] = $vProd;
					if($PISType->tipocalculo_pis == 1){
						$ValorPIS = (($vProd / 100) * $PISType->aliqpis_pis);
						$dadosPIS['pPIS'] = $PISType->aliqpis_pis;
						$dadosPIS['vPIS'] = $ValorPIS;
					}elseif($PISType->tipocalculo_pis == 2){
						$dadosPIS['qBCProd'] = $qCon;
						$dadosPIS['vAliqProd'] = $PISType->aliqreal_pis;
						$dadosPIS['vPIS'] = $qCon * $PISType->aliqreal_pis;
					}
					
			break;
			}
			
			$dadosPIS['id_nfe'] = $idNFe;
			$dadosPIS['id_produto_nfe'] = $id_produto_nf;
			
			$dbProdPIS->insert($dadosPIS);
			
			$dbProdCOFINS = new Erp_Model_Faturamento_NFe_ProdutosCOFINS();
			$COFINSType =  Erp_Model_Faturamento_PerfilCOFINS::getPerfil($formData['perfilfaturamento']);
			
			
			switch($COFINSType->sittrib_cofins){
				case '1':
				case '01':
				case '2':
				case '02':
					$ValorPIS = (($vProd / 100) * $COFINSType->aliqcofins_cofins);
					$dadosCOFINS['CST'] = $COFINSType->sittrib_cofins;
					$dadosCOFINS['vBC'] = $vProd;
					$dadosCOFINS['pCOFINS'] = $COFINSType->aliqcofins_cofins;
					$dadosCOFINS['vCOFINS'] = $ValorPIS;
						
					break;
			
				case '3':
				case '03':
					$dadosCOFINS['CST'] = $COFINSType->sittrib_cofins;
					$dadosCOFINS['qBCProd'] = $qCon;
					$dadosCOFINS['vAliqProd'] = $COFINSType->aliqreal_cofins;
					$dadosCOFINS['vCOFINS'] = $qCon * $COFINSType->aliqreal_cofins;
					break;
						
				case '04':
				case '4':
				case '06':
				case '6':
				case '07':
				case '7':
				case '08':
				case '8':
				case '09':
				case '9':
					$dadosCOFINS['CST'] = $COFINSType->sittrib_cofins;
					break;
			
				default:
					$dadosPIS['CST'] = $COFINSType->sittrib_cofins;
					$dadosPIS['vBC'] = $vProd;
					
					if($PISType->tipocalculo_pis == 1){
						$ValorPIS = (($vProd / 100) * $COFINSType->aliqcofins_cofins);
						$dadosCOFINS['pCOFINS'] = $COFINSType->aliqcofins_cofins;
						$dadosCOFINS['vCOFINS'] = $ValorPIS;
					}elseif($PISType->tipocalculo_pis == 2){
						$dadosCOFINS['qBCProd'] = $qCon;
						$dadosCOFINS['vAliqProd'] = $COFINSType->aliqreal_cofins;
						$dadosCOFINS['vCOFINS'] = $qCon * $COFINSType->aliqreal_cofins;
					}
						
					break;
			}
			
			
			
			$dadosCOFINS['id_nfe'] = $idNFe;
			$dadosCOFINS['id_produto_nfe'] = $id_produto_nf;
			$dbProdCOFINS->insert($dadosCOFINS);
		
		//** FINAL DO IF DE PRODUTOS	
			}
		}
		
		//**Calculos Totais
		$dbtotal = new Erp_Model_Faturamento_NFe_Totais();
		$dadosTotal['id_nfe'] = $idNFe;
		$dadosTotal['vBC'] = Erp_Model_Faturamento_NFe_Basicos::somaBaseCalculo($idNFe);
		$dadosTotal['vICMS'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalICMS($idNFe);
		$dadosTotal['vBCST'] = Erp_Model_Faturamento_NFe_Basicos::somaBaseCalculoST($idNFe);
		$dadosTotal['vST'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalICMSST($idNFe);
		$dadosTotal['vProd'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalProdutos($idNFe);
		$dadosTotal['vFrete'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalFrete($idNFe);
		$dadosTotal['vSeg'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalSeguro($idNFe);
		$dadosTotal['vDesc'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalDesconto($idNFe);
		$dadosTotal['vII'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalII($idNFe);
		$dadosTotal['vIPI'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalIPI($idNFe);
		$dadosTotal['vPIS'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalPIS($idNFe);
		$dadosTotal['vCOFINS'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalCOFINS($idNFe);
		$dadosTotal['vOutro'] = Erp_Model_Faturamento_NFe_Basicos::somaTotalOutros($idNFe);
		$dadosTotal['vNF'] = $dadosTotal['vProd'] - $dadosTotal['vDesc'] + $dadosTotal['vST'] + $dadosTotal['vFrete']  + $dadosTotal['vSeg'] + $dadosTotal['vOutro'] + $dadosTotal['vII'] + $dadosTotal['vIPI'];
		$dbtotal->insert($dadosTotal);
		
		
		/** 
		 * Informacoes da Transportadora e de Transporte
		 */
		
		if($formData['id_transportadora'] <> '0' && $formData['id_transportadora'] <> ''){
			
			$CadTransportadora = Cadastros_Model_Pessoas::getPessoa($formData['id_transportadora']);
			
			$EndTransportadora = Cadastros_Model_Enderecos::getEnderecoPadraoCadastro($formData['id_transportadora']);
			$dbTransp = new Erp_Model_Faturamento_NFe_Transportadora();
			
			$dadosTransp['id_nfe'] = $idNFe;
			$dadosTransp['id_pessoa'] = $formData['id_transportadora'];
			if($CadTransportadora->tipopessoa == 1){
			$dadosTransp['CNPJ'] = Functions_NFe::limpaCNPJCFP($CadTransportadora->cnpj);
			}else{
			$dadosTransp['CPF'] = Functions_NFe::limpaCNPJCFP($CadTransportadora->cnpj);
			}
			$dadosTransp['xNome'] = Functions_NFe::limparString($CadTransportadora->razaosocial);
			if($CadTransportadora->inscestadual <> ''){
			$dadosTransp['IE'] = Functions_NFe::limpaCNPJCFP($CadTransportadora->inscestadual);
			}
			$dadosTransp['xEnder'] = Functions_NFe::limparString("{$EndTransportadora->logradouro}, {$EndTransportadora->numero}");
			$dadosTransp['xMun'] = Functions_NFe::limparString($EndTransportadora->cidade);
			$dadosTransp['UF'] = $EndTransportadora->estado;		
			
			$id_db_transp = $dbTransp->insert($dadosTransp);
			
			if($formData['placaveiculo'] <> ''){
				$dbTranbVeic = new Erp_Model_Faturamento_NFe_TransportadoraVeiculo();
				$dadosVeic['id_nfe'] = $idNFe;
				$dadosVeic['id_transportadora'] = $id_db_transp;
				$dadosVeic['placa'] = Functions_NFe::limpaCNPJCFP($formData['placaveiculo']);
				$dadosVeic['UF'] = Functions_NFe::limpaCNPJCFP($formData['ufplaca']);
				$dadosVeic['RNTC'] = Functions_NFe::limpaCNPJCFP($formData['ANTT']);
				$dbTranbVeic->insert($dadosVeic);
			}
			
			
			
			
		}
		
		/**
		 * VOLUMES
		 */
		if($formData['tipovolume'] <> ''){
			
			$dbVolumes = new Erp_Model_Faturamento_NFe_Volumes();
			$dadosVolumes['id_nfe'] = $idNFe;
			$dadosVolumes['qVol'] = str_replace(',','.',$formData['quantidadevolumes']);
			$dadosVolumes['esp'] = Functions_NFe::limparString($formData['tipovolume']);
			if($formData['marcavolume'] <> ''){
			$dadosVolumes['marca'] = Functions_NFe::limparString($formData['marcavolume']);
			}
			if($formData['numeracaovolumes'] <> ''){
			$dadosVolumes['nVol'] = Functions_NFe::limparString($formData['numeracaovolumes']);
			}
			$dadosVolumes['pesoL'] = str_replace(',','.',$formData['pesoliquido']);
			$dadosVolumes['pesoB'] = str_replace(',','.',$formData['pesobruto']);
			
			
			$dbVolumes->insert($dadosVolumes);
			
		}
		
		/**
		 * Cobranca
		 */
		if($formData['DescontosMoeda'] <> ''){
			$descontoMoeda = str_replace(',','.',$formData['DescontosMoeda']);
		}else{
			$descontoMoeda = '0';
		}
		
		if($formData['DescontorPercent'] <> ''){
			$descontoPer = str_replace(',','.',$formData['DescontorPercent']);
			$VlDescontoPer = (($dadosTotal['vProd'] / 100) * $descontoPer);
		}else{
			$VlDescontoPer = '0';
		}
		
		$dbFatura = new Erp_Model_Faturamento_NFe_Fatura();
		$dadosFatura['id_nfe'] = $idNFe;
		$dadosFatura['nFat'] = $idNFe;
		$dadosFatura['vOrig'] = $dadosTotal['vProd'];
		$dadosFatura['vDesc'] = $dadosTotal['vDesc'] + $descontoMoeda;
		$dadosFatura['vLiq'] = $dadosTotal['vProd'] - $dadosFatura['vDesc'] - $VlDescontoPer;
		$id_Fatura = $dbFatura->insert($dadosFatura);
		
		if($formData['parcela'] <> ''){
		$parcelas = count($formData['parcela']);
		$valorParcela = $dadosFatura['vLiq'] / $parcelas;
			
		$dbDuplicata = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
		for($i=0;$i<$parcelas;$i++){
			$nParc = $i + 1;
			$dadosDuplicata['id_nfe'] = $idNFe;
			$dadosDuplicata['id_fatura'] = $id_Fatura;
			$dadosDuplicata['nDup'] = "{$dadosFatura['nFat']}/$nParc";
			$dadosDuplicata['dVenc'] = Functions_Datas::inverteData($formData['parcela'][$i]);
			$dadosDuplicata['vDup'] = $valorParcela;		
		$dbDuplicata->insert($dadosDuplicata)	;
		$dadosDuplicata = null;			
		}
	
		}
		
		
		/**
		 * Informacoes Adicionais
		 */
		
		$DADOSPROCURA = array('{ALIQCREDICMS}',' {VALORCREDICMS}');
		$DADOSREPLACE = array($dadosICMS['pCredSN'], Erp_Model_Faturamento_NFe_Produtos::somaTotalICMSSimples($idNFe));
		
		$formData['obsfisco'] = str_replace($DADOSPROCURA, $DADOSREPLACE, $formData['obsfisco']);
		
		$dbInfAd = new Erp_Model_Faturamento_NFe_Observacoes();
		$dadosOBS['id_nfe'] = $idNFe;
		$dadosOBS['infAdFisco'] = Functions_NFe::limparString(str_replace(PHP_EOL,';',$formData['obsfisco']));
		$dadosOBS['infCpl'] = Functions_NFe::limparString(str_replace(PHP_EOL,';',$formData['obsfaturamento']));
		$dbInfAd->insert($dadosOBS);
		
		/**
		 * InfComplementares
		 */
		
		$dbInfComp = new Erp_Model_Faturamento_NFe_ObservacoesAdicionais();
		foreach($formData['obsadicionais'] as $key=>$value){
			if($value <> ''){
			$dadosOBSAdd['id_nfe'] = $idNFe;
			$dadosOBSAdd['xCampo'] = Functions_NFe::limparString($key);
			$dadosOBSAdd['xTexto'] = Functions_NFe::limparString($value);
			$dbInfComp->insert($dadosOBSAdd);
			$dadosOBSAdd = null;
			}
		}
		
		
		
		$this->_redirect("/erp/faturamento/monta-xml/id/$idNFe");
		
		}catch (Exception $e){
			$this->_helper->layout->setLayout('layout');
			$this->_helper->viewRenderer->setRender('salva-pedido-emissao');
			echo "ERRO";
			echo $e->getMessage();
			echo $e->getTraceAsString();
			$this->view->idPedido = $_POST['id_pedido_venda'];
			$this->view->errMessage = $e->getMessage();
		}
		
		
		
		
	}
	
	public function montaXmlAction(){
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
		$this->view->idProcesso = $id;
		$this->view->PedVenda = $Dados->id_pedvenda;
		
		$dbProc = new Erp_Model_Faturamento_NFe_Processos();
		$xml = Functions_NFe::montaXML($id);
		$configs = System_Model_EmpresasNF::getConfigNFe($Dados->id_empresa);
		
		$sAmb = ($configs['ambiente'] == 2) ? 'homologacao' : 'producao';
		
		$NFe = new NFe_ToolsNFePHP($configs);
		
		if ($arqAssinado = $NFe->signXML($xml, 'infNFe')){
			
			$dbProc->insert(array('id_nfe'=>$id,
			'tipoProcesso'=>"Assinatura",
			'statusProcesso'=>1,
			'dateProcesso'=>date('Y-m-d H:i:s'),
			'userProcesso'=> $this->userInfo->id_registro));
			$db->update(array('status_processo'=>'1'), "id_registro = '$id'");
			
			$this->view->ArquivoXML =  $arqAssinado;
			
			$xsdFile = NFeCONFIGS_PATH.'/schemes/'.$configs['schemes']."/nfe_v2.00.xsd";
			$aErro = '';
			$c = $NFe->validXML($arqAssinado,$xsdFile,$aErro);
			if (!$c){
				$this->view->ProcessoOK = false;
				$this->view->error = 'Validação';
				$this->view->ErrorMessages = array($aErro);
				foreach ($aErro as $er){
					$dbProc->insert(array('id_nfe'=>$id,
							'tipoProcesso'=>"Validacao",
							'statusProcesso'=>2,
							'dateProcesso'=>date('Y-m-d H:i:s'),
							'userProcesso'=> $this->userInfo->id_registro,
					'xText'=>$er));
					
					
				}
			} else {
				$dbProc->insert(array('id_nfe'=>$id,
						'tipoProcesso'=>"Validacao",
						'statusProcesso'=>1,
						'dateProcesso'=>date('Y-m-d H:i:s'),
						'userProcesso'=> $this->userInfo->id_registro));
					
				$this->view->ProcessoOK = true;
				
				$savePath  = APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/validadas/{$Dados->chaveacesso}-nfe.xml";
				file_put_contents($savePath, $arqAssinado);
				$db->update(array("localxml"=>$savePath), "id_registro = '$id'");			
				
				
			}
			
			
			
		} else {
			$this->view->ProcessoOK = false;
			$this->view->error = 'Assinatura';
			$this->view->ErrorMessages = array(0=>$nfe->errMsg);
			
		}
		
	
		
	}
	
	public function preVisualizaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
		
		$arq = $Dados->localxml;
		
		if ( is_file($arq) ){
			$docxml = file_get_contents($arq);
			$danfe = new NFe_DanfeNFePHP($docxml, 'P', 'A4','','I','');
			$id = $danfe->montaDANFE();
			$teste = $danfe->printDANFE($id.'.pdf','I');
		}
		
		
	}
	
	
	public function imprimirDanfeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
	
		$arq = $Dados->localxml;
		$logotipo = System_Model_EmpresasNF::getLogo($Dados->id_empresa);
		if($logotipo <> ''){
			$logo = $logotipo;
		}
	
		if ( is_file($arq) ){
			$docxml = file_get_contents($arq);
			$danfe = new NFe_DanfeNFePHP($docxml, 'P', 'A4',$logo,'I','');
			$id = $danfe->montaDANFE();
			$teste = $danfe->printDANFE($id.'.pdf','I');
		}
	
	
	}
	
	
	public function imprimirDanfeEmitenteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
	
		$arq = $Dados->localxml;
		$logo = '';
	
		if ( is_file($arq) ){
			$docxml = file_get_contents($arq);
			$danfe = new NFe_DanfeNFePHP($docxml, 'P', 'A4',$logo,'I','');
			$id = $danfe->montaDANFE();
			$teste = $danfe->printDANFE($id.'.pdf','I');
		}
	
	
	}
	
	public function emitirNfeAction(){
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
		$this->view->idProcesso = $id;
		$this->view->PedVenda = $Dados->id_pedvenda;
		
		$dbProc = new Erp_Model_Faturamento_NFe_Processos();
		$configs = System_Model_EmpresasNF::getConfigNFe($Dados->id_empresa);
		$sAmb = ($configs['ambiente'] == 2) ? 'homologacao' : 'producao';
		$NFe = new NFe_ToolsNFePHP($configs);
		
		$arq = $Dados->localxml;
		$lote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15);
		$aNFe = array(0=>file_get_contents($arq));
		$modSOAP = 2;
		
		if ($aResp = $NFe->sendLot($aNFe, $lote, $modSOAP)){
			if ($aResp['bStat']){
				
				$procEnvio = $dbProc->insert(array('id_nfe'=>$id,
						'tipoProcesso'=>"Envio",
						'statusProcesso'=>1,
						'protocolo'=>$aResp['nRec'],
						'dateProcesso'=>date('Y-m-d H:i:s'),
						'userProcesso'=> $this->userInfo->id_registro,
						'xText'=>$aResp['xMotivo'],
				'xCode'=>$aResp['cStat']));
				
				$this->view->ProcessoOK = true;
				$this->view->error = 'Envio';
				$this->view->Retorno = $aResp;
				$this->view->id_prod_envio = $procEnvio;
				
				$savePath  = APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/enviadas/{$Dados->chaveacesso}-nfe.xml";
				$arqEnviado = file_get_contents(APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/validadas/{$Dados->chaveacesso}-nfe.xml");
				unlink(APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/validadas/{$Dados->chaveacesso}-nfe.xml");
				if(file_put_contents($savePath, $arqEnviado)){;
				$db->update(array("localxml"=>$savePath,'status_processo'=>'2'), "id_registro = '$id'");
				}
						
			} else {
				
				$this->view->ProcessoOK = true;
				$this->view->error = 'Envio';
				$this->view->ErrorMessages = array(0=>$NFe->errMsg);
				
			}
			} else {
				$this->view->ProcessoOK = false;
				$this->view->error = 'Envio';
				$this->view->ErrorMessages = array(0=>$NFe->errMsg);
				
			}
			
		
		
		
		
		
	}
	
	public function consultaRetornoAction(){
	//	$this->_helper->layout->disableLayout();
	//	$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
		$this->view->idProcesso = $id;
		$this->view->PedVenda = $Dados->id_pedvenda;
		$dbProc = new Erp_Model_Faturamento_NFe_Processos();
		$Processos = $dbProc->fetchAll("id_nfe = '$id' and tipoProcesso = 'Envio'");
		
		$configs = System_Model_EmpresasNF::getConfigNFe($Dados->id_empresa);
		$sAmb = ($configs['ambiente'] == 2) ? 'homologacao' : 'producao';
		$NFe = new NFe_ToolsNFePHP($configs);
		
		
		foreach ($Processos as $processo){

			if ($aResp = $NFe->getProtocol($processo->protocolo, '', $configs['ambiente'], '2')){
				$resp[] = $aResp;
				$StatusRet = true;
				$dirAprovadas = APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/enviadas/aprovadas/{$Dados->AAMM}";

				if($aResp['aProt'][0]['cStat'] ==  '100'){
					if(!is_dir($dirAprovadas)){
						mkdir($dirAprovadas);
					}
					

					$procEnvio = $dbProc->insert(array('id_nfe'=>$id,
							'tipoProcesso'=>"Aprovada",
							'statusProcesso'=>2,
							'protocolo'=>$aResp['nRec'],
							'dateProcesso'=>date('Y-m-d H:i:s'),
							'userProcesso'=> $this->userInfo->id_registro,
							'xText'=>$aResp['aProt'][0]['xMotivo'],
							'xCode'=>$aResp['aProt'][0]['cStat'] ));
					
					$saveFile  = $dirAprovadas."/{$Dados->chaveacesso}-procNFe.xml";
					
					$nfefile = $Dados->localxml;
					$protfile =  APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/temporarias/{$Dados->chaveacesso}-prot.xml";					
					if(!is_file($saveFile)){
					if ($xml = $NFe->addProt($nfefile, $protfile)){
						
						if(file_put_contents($saveFile, $xml)){
							unlink($nfefile);
							$db->update(array("localxml"=>$saveFile,'status_processo'=>'3'), "id_registro = '$id'");
							
						}
						
					}
					}
					
					if(Functions_NFe::processaNFeAprovada($id)){
						$db->update(array("processoNFeAprovada"=>'1'), "id_registro = '$id'");
					}
					
					
					
				}elseif($aResp['aProt'][0]['cStat'] >  '200'){
					
					$procEnvio = $dbProc->insert(array('id_nfe'=>$id,
							'tipoProcesso'=>"Reprovada",
							'statusProcesso'=>2,
							'protocolo'=>$aResp['nRec'],
							'dateProcesso'=>date('Y-m-d H:i:s'),
							'userProcesso'=> $this->userInfo->id_registro,
							'xText'=>$aResp['aProt'][0]['xMotivo'],
							'xCode'=>$aResp['aProt'][0]['cStat'] ));
					
					$dirReprovadas = APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/enviadas/reprovadas/{$Dados->AAMM}";
					
					if(!is_dir($dirReprovadas)){
						mkdir($dirReprovadas);
					}
					
					$nfefile = file_get_contents($Dados->localxml);
					$saveFile  = $dirReprovadas."/{$Dados->chaveacesso}-procNFe.xml";
					if(!is_file($saveFile)){
					if(file_put_contents($saveFile, $nfefile)){
						unlink($Dados->localxml);
						$db->update(array("localxml"=>$saveFile,'status_processo'=>'4'), "id_registro = '$id'");
					
					}
					}
					
					}elseif($aResp['aProt'][0]['cStat'] ==  '110'){
						
						$dirReprovadas = APPLICATION_PATH."/data/files/nfe/{$Dados->id_empresa}/$sAmb/enviadas/denegadas/{$Dados->AAMM}";
							
						if(!is_dir($dirReprovadas)){
							mkdir($dirReprovadas);
						}
						
						$procEnvio = $dbProc->insert(array('id_nfe'=>$id,
								'tipoProcesso'=>"Denegada",
								'statusProcesso'=>2,
								'protocolo'=>$aResp['nRec'],
								'dateProcesso'=>date('Y-m-d H:i:s'),
								'userProcesso'=> $this->userInfo->id_registro,
								'xText'=>$aResp['aProt'][0]['xMotivo'],
								'xCode'=>$aResp['aProt'][0]['cStat'] ));
							
						$nfefile = file_get_contents($Dados->localxml);
						$saveFile  = $dirReprovadas."/{$Dados->chaveacesso}-procNFe.xml";
						if(!is_file($saveFile)){	
						if(file_put_contents($saveFile, $nfefile)){
							unlink($Dados->localxml);
							$db->update(array("localxml"=>$saveFile,'status_processo'=>'5'), "id_registro = '$id'");
						
						}
						}
			
					
					
					
				}
				
				
				
			} else {
				//não houve retorno mostrar erro de comunicação
				$StatusRet = false;
				$erros[] =  $NFe->errMsg;
			}
			
		}
		
		$this->view->respostas = $resp;
		$this->view->StatusRet = $StatusRet;
		$this->view->erros = $erros;
		
		
		
		
	}
	
	public function getXmlAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$Dados = $db->fetchRow("id_registro = '$id'");
		
		header("Content-type: xml");
		
		readfile($Dados->localxml);
		
	}
	
	public function emitirCceAction(){
		
	//	$this->_helper->layout->disableLayout();
	//	$this->_helper->viewRenderer->setNoRender();
		try{
		$formData = $this->_request->getPost();
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '{$formData['CCeID']}'");
		
		$configs = System_Model_EmpresasNF::getConfigNFe($DadosNFe->id_empresa);
		$sAmb = ($configs['ambiente'] == 2) ? 'homologacao' : 'producao';
		$NFe = new NFe_ToolsNFePHP($configs);
		
		$CCe = $formData['CCe_Dados'];
		$Cce = str_replace(PHP_EOL, ';', $CCe);
		$CCe = Functions_NFe::limparString($Cce);
		$SeqEv =  Erp_Model_Faturamento_NFe_Processos::contaProcessos($formData['CCeID'], 'CCe') + 1;
		
		if($DadosCCe = $NFe->envCCe($DadosNFe->chaveacesso,$Cce,$SeqEv,$configs['ambiente'])){
		//	echo $DadosCCe;

			$xmlretCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
			$xmlretCCe->formatOutput = false;
			$xmlretCCe->preserveWhiteSpace = false;
			$xmlretCCe->loadXML($DadosCCe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
			$retEvento = $xmlretCCe->getElementsByTagName("retEvento")->item(0);
			$cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
			$xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
			
			if ($cStat == '' || $cStat != 135){
				$this->view->StatusRet = false;
				if($cStat ==''){
					$this->view->message = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
				}else{
					$this->view->message = array("cStat"=>$cStat,'xMotivo'=>$xMotivo);
					$retorno = array('verAplic'=>$retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
							'cStat'=>$retEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
							'xMotivo'=>$retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
							'chNFe'=>$retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
							'tpEvento'=>$retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
							'xEvento'=>$retEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
							'nSeqEvento'=>$retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
							'nProt'=>$retEvento->getElementsByTagName('nProt')->item(0)->nodeValue,
							'dhRegEvento'=>$retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
							'id_evento'=>$id_evento
					
								
					);
					
					$this->view->dadosRetorno = $retorno;
				}
				
				
			}else{
				
				$dbEv = new Erp_Model_Faturamento_NFe_Processos();
				$nProt = $retEvento->getElementsByTagName('nProt')->item(0)->nodeValue	;
				$ArqCCe = APPLICATION_PATH."/data/files/nfe/{$DadosNFe->id_empresa}/$sAmb/cartacorrecao/$DadosNFe->chaveacesso-$SeqEv-procCCe.xml";
				$id_evento = $dbEv->insert(array('id_nfe'=>$DadosNFe->id_registro,
						'tipoProcesso'=>"CCe",
						'statusProcesso'=>1,
						'protocolo'=>$nProt,
						'dateProcesso'=>date('Y-m-d H:i:s'),
						'userProcesso'=> $this->userInfo->id_registro,
						'xText'=>$xMotivo,
						'xCode'=>$cStat,
						'xmlpath_processo'=>$ArqCCe
				));
				
				$this->view->StatusRet = true;
				$retorno = array('verAplic'=>$retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
						'cStat'=>$retEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
						'xMotivo'=>$retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
						'chNFe'=>$retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
						'tpEvento'=>$retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
						'xEvento'=>$retEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
						'nSeqEvento'=>$retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
						'nProt'=>$retEvento->getElementsByTagName('nProt')->item(0)->nodeValue,
						'dhRegEvento'=>$retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
						'id_evento'=>$id_evento
						
					
				);	

				$this->view->dadosRetorno = $retorno;
						
			}
			
			
		}
		
		}catch (Exception $e){
			echo $e->getMessage();
		}
		
		
		
		
		
		
	}
	
	
	public function printCceAction(){
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$id = $this->_request->getParam("id",0);
			$db = new Erp_Model_Faturamento_NFe_Processos();
			$DadosProcesso = $db->fetchRow("id_registro = '$id'");
			$arq = $DadosProcesso->xmlpath_processo;
			
			$db2 = new Erp_Model_Faturamento_NFe_Emitente();
			$DadosEmitente = $db2->fetchRow("id_nfe = '{$DadosProcesso->id_nfe}'");
			
			$db3 = new Erp_Model_Faturamento_NFe_Basicos();
			$DadosBasicos= $db3->fetchRow("id_registro = '{$DadosProcesso->id_nfe}'");
			
			$Configs = System_Model_EmpresasNF::getDadosConfigNFe($DadosBasicos->id_empresa);
			
			$aEnd = array('razao'=>$DadosEmitente->xNome,
					'logradouro' => $DadosEmitente->Xlgr,
					'numero' => $DadosEmitente->nro,
					'complemento' => $DadosEmitente->xCpl,
					'bairro' => $DadosEmitente->xBairro,
					'CEP' => $DadosEmitente->CEP,
					'municipio' => $DadosEmitente->xMun,
					'UF'=>$DadosEmitente->UF,
					'telefone'=>$DadosEmitente->fone,
					'email'=>'');
			
			
			if($Configs->logotipodanfe <> ''){
				$logo = $Configs->logotipodanfe;
			}
			
			$cce = new NFe_DacceNFePHP($arq, 'P', 'A4',$logo,'I',$aEnd,'','Times',1);
			$teste = $cce->printCCe('CCe.pdf','I');
			
			
			
			
		
	}
	
	public function getXmlCceAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
				
		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Processos();
		$DadosProcesso = $db->fetchRow("id_registro = '$id'");
		
		header("Content-type: xml");
		
		readfile($DadosProcesso->xmlpath_processo);
		
	}
	
	public function getEventosAction(){
		$id = $this->_request->getParam("id",0);
		$this->view->configs = $this->configs;
		$this->_helper->layout()->setLayout('modal');
		
		$db = new Erp_Model_Faturamento_NFe_Processos();
		$DadosProcesso = $db->fetchAll("id_nfe = '$id'");
		
		$this->view->Processos = $DadosProcesso;
		
		
		
	}
	
	
	public function cancelarNfeAction(){
		
	
		
		$formData = $this->_request->getPost();
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '{$formData['NFeID']}'");
		$configs = System_Model_EmpresasNF::getConfigNFe($DadosNFe->id_empresa);
		$sAmb = ($configs['ambiente'] == 2) ? 'homologacao' : 'producao';
		
		$xml = file_get_contents($DadosNFe->localxml);
		
		$XmlNFe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
		$XmlNFe->formatOutput = false;
		$XmlNFe->preserveWhiteSpace = false;
		$XmlNFe->loadXML($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
		$retEvento = $XmlNFe->getElementsByTagName("protNFe")->item(0);
		$protocolo = $retEvento->getElementsByTagName("infProt")->item(0);
		$protocoloN = $protocolo->getElementsByTagName("nProt")->item(0)->nodeValue;
		
	
		
	
		$NFe = new NFe_ToolsNFePHP($configs);
		
		$DadosCld = $formData['Cancelamento_Dados'];
		$DadosCld = str_replace(PHP_EOL, ';', $DadosCld);
		$DadosCld = Functions_NFe::limparString($DadosCld);
		$SeqEv = 1;
		
		
		if($DadosCCe = $NFe->cancelEvent($DadosNFe->chaveacesso,$protocoloN,$DadosCld,$configs['ambiente'],2)){
		//	echo $DadosCCe;
			
			$xmlretCCe = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
			$xmlretCCe->formatOutput = false;
			$xmlretCCe->preserveWhiteSpace = false;
			$xmlretCCe->loadXML($DadosCCe,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
			$retEvento = $xmlretCCe->getElementsByTagName("retEvento")->item(0);
			$cStat = !empty($retEvento->getElementsByTagName('cStat')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('cStat')->item(0)->nodeValue : '';
			$xMotivo = !empty($retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
				
			if ($cStat == '' || $cStat != 135){
				$this->view->StatusRet = false;
				if($cStat ==''){
					$this->view->message = "cStat está em branco, houve erro na comunicação Soap verifique a mensagem de erro e o debug!!";
				}else{
					$this->view->message = array("cStat"=>$cStat,'xMotivo'=>$xMotivo);
					$retorno = array('verAplic'=>$retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
							'cStat'=>$retEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
							'xMotivo'=>$retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
							'chNFe'=>$retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
							'tpEvento'=>$retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
							'xEvento'=>$retEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
							'nSeqEvento'=>$retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
							'nProt'=>$retEvento->getElementsByTagName('nProt')->item(0)->nodeValue,
							'dhRegEvento'=>$retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
							'id_evento'=>$formData['NFeID']
								
			
					);
						
					$this->view->dadosRetorno = $retorno;
				}
			
			
			}else{
			
				$dbEv = new Erp_Model_Faturamento_NFe_Processos();
				$nProt = $retEvento->getElementsByTagName('nProt')->item(0)->nodeValue	;
				$ArqCCe = APPLICATION_PATH."/data/files/nfe/{$DadosNFe->id_empresa}/$sAmb/canceladas/$DadosNFe->chaveacesso-$SeqEv-procCanc.xml";
				$id_evento = $dbEv->insert(array('id_nfe'=>$DadosNFe->id_registro,
						'tipoProcesso'=>"Cancelamento",
						'statusProcesso'=>1,
						'protocolo'=>$nProt,
						'dateProcesso'=>date('Y-m-d H:i:s'),
						'userProcesso'=> $this->userInfo->id_registro,
						'xText'=>$xMotivo,
						'xCode'=>$cStat,
						'xmlpath_processo'=>$ArqCCe
				));
			
				$this->view->StatusRet = true;

				if($NFeCanc = $NFe->addProt($DadosNFe->localxml,$ArqCCe)){
					file_put_contents($DadosNFe->localxml, $NFeCanc);
				}
				Functions_NFe::processaNFeCancelada($formData['NFeID']);
				$retorno = array('verAplic'=>$retEvento->getElementsByTagName('verAplic')->item(0)->nodeValue,
						'cStat'=>$retEvento->getElementsByTagName('cStat')->item(0)->nodeValue,
						'xMotivo'=>$retEvento->getElementsByTagName('xMotivo')->item(0)->nodeValue,
						'chNFe'=>$retEvento->getElementsByTagName('chNFe')->item(0)->nodeValue,
						'tpEvento'=>$retEvento->getElementsByTagName('tpEvento')->item(0)->nodeValue,
						'xEvento'=>$retEvento->getElementsByTagName('xEvento')->item(0)->nodeValue,
						'nSeqEvento'=>$retEvento->getElementsByTagName('nSeqEvento')->item(0)->nodeValue,
						'nProt'=>$retEvento->getElementsByTagName('nProt')->item(0)->nodeValue,
						'dhRegEvento'=>$retEvento->getElementsByTagName('dhRegEvento')->item(0)->nodeValue,
						'id_evento'=>$formData['NFeID']
			
							
				);
			
				$this->view->dadosRetorno = $retorno;
			
			}
				
				
			
			
			
		}else{
			echo $NFe->errMsg;
			echo $retCancelamento;
			echo $DadosProcesso->protocolo;
		}
		
		
		
		
	
		
	}
	
	
	public function inutilizarAction(){
		
	}
	
	public function inutilizaFaixaAction(){
		
		$Configs = System_Model_EmpresasNF::getConfigNFe($_POST['id_empresa']);
		$NFe = new NFe_ToolsNFePHP($Configs);
		
		$sAmb = ($Configs['ambiente'] == 2) ? 'homologacao' : 'producao';
		$nAno = date('y');
		$nSerie = $_POST['nSerie'];
		$nIni = $_POST['nIni'];
		$nFim = $_POST['nFim'];
		$xJust = $_POST['xJust'];
		$tpAmp = $Configs['ambiente'];
		
		if($Retorno = $NFe->inutNF($nAno,$nSerie,$nIni,$nFim,$xJust,$tpAmp)){
			
			$id = 'ID'.$Configs['cUF'].$nAno.$Configs['cnpj'].'55'.str_pad($nSerie,3,'0',STR_PAD_LEFT).str_pad($nIni,9,'0',STR_PAD_LEFT).str_pad($nFin,9,'0',STR_PAD_LEFT);
			
			
			$XMLPath = APPLICATION_PATH."/data/files/nfe/{$_POST['id_empresa']}/$sAmb/inutilizadas/$id-procInut.xml";
			
			$doc = new DOMDocument('1.0', 'utf-8'); //cria objeto DOM
			$doc->formatOutput = false;
			$doc->preserveWhiteSpace = false;
			$doc->loadXML($Retorno,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
			$cStat = !empty($doc->getElementsByTagName('cStat')->item(0)->nodeValue) ? $doc->getElementsByTagName('cStat')->item(0)->nodeValue : '';
			$xMotivo = !empty($doc->getElementsByTagName('xMotivo')->item(0)->nodeValue) ? $doc->getElementsByTagName('xMotivo')->item(0)->nodeValue : '';
			$nProt = !empty($doc->getElementsByTagName('nProt')->item(0)->nodeValue) ? $doc->getElementsByTagName('nProt')->item(0)->nodeValue : '';
		
		$db = new Erp_Model_Faturamento_NFe_Inutilizar();
		$dados = array('id_empresa'=>$_POST['id_empresa'],
			'nAno'=>$nAno,
			'nSerie'=>$nSerie,
			'nIni'=>$nIni,
			'nFim'=>$nFim,
			'xJust'=>$xJust,
			'tpAmp'=>$tpAmp,
				'cStat'=>$cStat,
				'xMotivo'=>$xMotivo,
				'datasolicitacao'=>date('Y-m-d H:i:s'),
				'user_id'=>$this->userInfo->id_registro,
				'xmlpath'=>$XMLPath,
				'nProt'=>$nProt
		);
		
		$db->insert($dados);
		System_Model_EmpresasNF::updateLastNFe($nFim, $_POST['id_empresa']);
		$this->view->Success = true;
		$this->view->xml = $Retorno;
		$this->view->dados = $dados;
	}else{
		
		$dados = array('id_empresa'=>$_POST['id_empresa'],
				'nAno'=>$nAno,
				'nSerie'=>$nSerie,
				'nIni'=>$nIni,
				'nFim'=>$nFim,
				'xJust'=>$xJust,
				'tpAmp'=>$tpAmp,
				'cStat'=>'000',
				'xMotivo'=>$NFe->errMsg,
				'datasolicitacao'=>date('Y-m-d H:i:s'),
				'user_id'=>$this->userInfo->id_registro
		);
		
		$this->view->Success = false;
		$this->view->erro = $NFe->errMsg;
	}
		
		
	}
	
	
	public function importarNfeAction(){
		//$this->_helper->layout->disableLayout();
	//	$this->_helper->viewRenderer->setNoRender();
		
		try{
			$destinationFolder = APPLICATION_PATH."/data/temp/";
			$upload_adapter = new Zend_File_Transfer_Adapter_Http();
			$upload_adapter->setDestination( $destinationFolder );
			$filename =$upload_adapter->getFileName();
			$hash = $upload_adapter->getHash('md5');
			$minetype = $upload_adapter->getMimeType();
		
			$db = new System_Model_Empresas();
			
			$sucesso = '';
			$erro = '';
					
				
			if( $upload_adapter->receive() ){
			
			if(Functions_NFe::checkHash($filename)){
				
					
				$nfe = Functions_NFe::processaXMLEntrada($filename);
				
				$Empresa = System_Model_Empresas::getDataEmpresaCNPJ($nfe['Destinatario']['CNPJFormatado']);
				if($Empresa->id_registro <> ''){
					$sucess['Destinatario'] = "Empresa Destinataria Encontrada: {$nfe['Destinatario']['xNome']}";

					$dbBasicos = new Erp_Model_Faturamento_NFe_Basicos();					
					$nfe['Basicos']['id_empresa'] = $Empresa->id_registro;
					$nfe['Basicos']['status_processo'] = '3';
					$nfe['Basicos']['tipo_nfe'] = '4';
					$nfe['Basicos']['id_perfil'] = '0';
					$nfe['Basicos']['id_pedvenda'] = '0';
					($nfe['Basicos']['hSaiEnt'] == '') ? $nfe['Basicos']['hSaiEnt'] = '00:00:00' : $nfe['Basicos']['hSaiEnt'] = $nfe['Basicos']['hSaiEnt'] ;
					unset($nfe['Destinatario']['CNPJFormatado']);
					
					$idNFE = $dbBasicos->insert($nfe['Basicos']);
					
					$dadosPessoa = Cadastros_Model_Pessoas::getEmpresaCNPJ($nfe['Emitente']['CNPJFormatado']);
					if($dadosPessoa->id_registro == ''){
						$err['Emitente'] = "<a href=\"/system/faturamento/cadastra-pessoa-nfe-emitente/id/$idNFE\" target=\"_blank\"> Empresa {$nfe['Emitente']['xNome']} emitente da NFe não esta cadastrada nesse sistema clique aqui para cadastrar</a>";
					}else{
						$nfe['Emitente']['id_pessoa'] = $dadosPessoa->id_registro;
						$sucess['Emitente'] = "Empresa Destinataria Encontrada: {$nfe['Destinatario']['xNome']}";
					}
					$dbEmitente = new Erp_Model_Faturamento_NFe_Emitente();
					unset($nfe['Emitente']['CNPJFormatado']);
					$nfe['Emitente']['id_nfe'] = $idNFE;
					$dbEmitente->insert($nfe['Emitente']);
					
					$dbDest= new Erp_Model_Faturamento_NFe_Destinatario();
					unset($nfe['Destinatario']['CNPJFormatado']);
					$nfe['Destinatario']['id_nfe'] = $idNFE;
					$nfe['Destinatario']['id_empresa'] = $Empresa->id_registro;
					$dbDest->insert($nfe['Destinatario']);
					
				
										
					foreach($nfe['Produtos'] as $Prod){
						
						$ProdSistema = Cadastros_Model_Produtos::getProdutoCodNFe($Prod['cProd']);
						if($ProdSistema->id_produto <> ''){
							$Prod['id_produto'] = $ProdSistema->id_produto;
						}else{
							//$erro[] = "O Produto {$Prod[xProd]} não esta cadastrado no sistema, deseja cadastra-lo?";
						}
							$Prod['id_prod_venda'] = '0';
							$Prod['id_prod_compra'] = '0';
							$Prod['id_nfe'] = $idNFE;
							
							if(!$Prod['cEAN']){
								unset($Prod['cEAN']);
							}
							
							if(!$Prod['cEANTrib']){
								unset($Prod['cEANTrib']);
							}
						
						$dadosICMS = $Prod['ICMS'];
						unset($Prod['ICMS']);
						$dadosPIS = $Prod['PIS'];
						unset($Prod['PIS']);
						$dadosIPI = $Prod['IPI'];
						unset($Prod['IPI']);
						$dadosCOFINS = $Prod['COFINS'];
						unset($Prod['COFINS']);
						
						$dbProd = new Erp_Model_Faturamento_NFe_Produtos();
						$id_prod_nfe = $dbProd->insert($Prod);
						
						$dbProdICMS = new Erp_Model_Faturamento_NFe_ProdutosICMS();
						$dadosICMS['id_nfe'] = $idNFE;
						$dadosICMS['id_produto_nfe'] = $id_prod_nfe;
						$dbProdICMS->insert($dadosICMS);
						
						$dbProdPIS = new Erp_Model_Faturamento_NFe_ProdutosPIS();
						$dadosPIS['id_nfe'] = $idNFE;
						$dadosPIS['id_produto_nfe'] = $id_prod_nfe;
						$dbProdPIS->insert($dadosPIS);
						
						$dbProdCOFINS= new Erp_Model_Faturamento_NFe_ProdutosCOFINS();
						$dadosCOFINS['id_nfe'] = $idNFE;
						$dadosCOFINS['id_produto_nfe'] = $id_prod_nfe;
						$dbProdCOFINS->insert($dadosCOFINS);
						if($dadosIPI['CST'] <> ''){
						$dbProdIPI= new Erp_Model_Faturamento_NFe_ProdutosIPI();
						$dadosIPI['id_nfe'] = $idNFE;
						$dadosIPI['id_produto_nfe'] = $id_prod_nfe;
						$dbProdIPI->insert($dadosIPI);
						}
						

						if($ProdSistema->id_produto <> ''){
							$Prod['id_produto'] = $ProdSistema->id_produto;
						}else{
							$errp[] = "<a href=\"/system/faturamento/cadastra-produto-nfe-emitente/id/{$id_prod_nfe}\" target=\"_blank\">O Produto {$Prod[xProd]} não esta cadastrado no sistema, clique aqui para cadastrar </a> ou <a href=\"/system/faturamento/vincula-produto-nfe-emitente/id/{$id_prod_nfe}\" target=\"_blank\">Aqui para vincular o produto a um produto existente</a>";
						}
						
							
					}
					$err['Produtos'] = $errp;
					
					$dadosVolumes = $nfe['Transporte']['volumes'];
					$dbTotais = new Erp_Model_Faturamento_NFe_Totais();
					$nfe['Totais']['id_nfe'] = $idNFE;
					$dbTotais->insert($nfe['Totais']);
					if($nfe['Transporte']['CNPJ'] <> ''){
						$dataTransp = Cadastros_Model_Pessoas::getEmpresaCNPJ(Functions_Auxilio::formatText($nfe['Transporte']['CNPJ'],"##.###.###/####-##"));
						if($dataTransp->id_registro){
							$nfe['Transporte']['id_pessoa'] = $dataTransp->id_registro;
						};
						
					$dbTransp = new Erp_Model_Faturamento_NFe_Transportadora();
					$dadosVeiculo = $nfe['Transporte']['veiculo'];
					unset($nfe['Transporte']['veiculo']);
					unset($nfe['Transporte']['volumes']);
					$nfe['Transporte']['id_nfe'] = $idNFE;
					$id_transp = $dbTransp->insert($nfe['Transporte']);
					
					
					if(!$dataTransp->id_registro){
						$err['Transportadora'] = "<a href=\"/system/faturamento/cadastra-transportadora-nfe-emitente/id/{$id_transp}\" target=\"_blank\">A Transportadora {$nfe['Transporte']['xNome']}  não esta cadastrada no sistema, clique aqui para cadastrar</a> ";
					};
					
					if($dadosVeiculo['placa'] || $dadosVeiculo['RNTC']){
						$dbVeic = new Erp_Model_Faturamento_NFe_TransportadoraVeiculo();
						$dadosVeiculo['id_nfe'] = $idNFE;
						$dadosVeiculo['id_transportadora'] = $id_transp;
						$dbVeic->insert($dadosVeiculo);
					
					}
					
					
					}
					
					if($dadosVolumes['qVol'] && $dadosVolumes['qVol'] > 0){
					$dadosVolumes['id_nfe'] = $idNFE;
					$dbVolumes = new Erp_Model_Faturamento_NFe_Volumes();
					$dbVolumes->insert($dadosVolumes);
					}
					
					
				
					if($nfe['Fatura']['nFat'] <> ''){
				
						$duplicatas = $nfe['Fatura']['duplicatas'];
						unset( $nfe['Fatura']['duplicatas'] );
						$nfe['Fatura']['id_nfe'] = $idNFE;
						$dbFatura = new Erp_Model_Faturamento_NFe_Fatura();
						$id_Fatura = $dbFatura->insert($nfe['Fatura']);
						$dbDup = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
						foreach($duplicatas as $dupl){
							
							$dupl['id_nfe'] = $idNFE;
							$dupl['id_fatura'] = $id_Fatura;
							$dbDup->insert($dupl);
							
						}
						
						
					}else{
						
						$duplicatas = $nfe['Fatura']['duplicatas'];
						unset( $nfe['Fatura']['duplicatas'] );
												
						foreach($duplicatas as $DUPL){
							if($DUPL['vDup']){
						    $total += $DUPL['vDup'];
							}
										
						}
						
					
						
						$nfe['Fatura']['id_nfe'] = $idNFE;
						$nfe['Fatura']['vOrig'] = $total;
						$nfe['Fatura']['vDesc'] = '0';
						$nfe['Fatura']['vLiq'] = $total;
						
						$dbFatura = new Erp_Model_Faturamento_NFe_Fatura();
						$id_Fatura = $dbFatura->insert($nfe['Fatura']);
						
						$dbDup = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
						
						foreach($duplicatas as $dupl){
							
							$dupl['id_nfe'] = $idNFE;
							$dupl['id_fatura'] = $id_Fatura;
							$id_dup =  $dbDup->insert($dupl); 
							
						
						}
						
						
						
						
						
					}
					
					

					$dbOBS = new Erp_Model_Faturamento_NFe_Observacoes();
					$oboutros = $nfe['Observacoes']['outros'];
					unset($nfe['Observacoes']['outros']);
					$nfe['Observacoes']['id_nfe'] = $idNFE;
					$dbOBS->insert($nfe['Observacoes']);
					
					$dbobsou = new Erp_Model_Faturamento_NFe_ObservacoesAdicionais();
					foreach($oboutros as $ob){
						$ob['id_nfe'] = $idNFE;
						$dbobsou->insert($ob);
					}
			
				
					if(!is_dir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/")){
						mkdir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/");
					}
					
					if(!is_dir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/{$nfe['Basicos']['AAMM']}")){
						mkdir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/{$nfe['Basicos']['AAMM']}");
					}
					
					$FileLocation = APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/{$nfe['Basicos']['AAMM']}/{$nfe['Emitente']['CNPJ']}-{$nfe['Basicos']['nNF']}-$idNFE.xml";
					
					$file = file_get_contents($filename);
					file_put_contents($FileLocation, $file);
					unlink($filename);
					$dbBasicos->update(array('localxml'=>$FileLocation),"id_registro = '$idNFE'");
					
					
					
					$erro = $err;
					$sucesso = $sucess;
					$this->view->Fatura = $nfe['Fatura'];
					$this->view->Duplicatas = $duplicatas;
					$this->view->NFe = $nfe;
					$this->view->idNFe = $idNFE;
					
					$this->view->Sucesso = $sucesso;
					$this->view->Erros = $erro;
					
					
					
					if($erro['Emitente'] == ''  && $erro['Produtos'][0] == ''){
						//echo "Processando NFE";
						Functions_NFe::processaNFeEntrada($idNFE);
					}else{
						echo "Processando NFE ERRO";
					}
					
					
					
					
					
					
					
				}else{
					$erro['Destinatario'] = "A Empresa {$nfe['Destinatario']['xNome']} destinatária da NFe não esta cadastrada nesse sistema";
					$this->view->Sucesso = $sucesso;
					$this->view->Erros = $erro;
					$this->view->NFe = $nfe;
				};
				
				
				
			}else{
				$erro[] = "NFe Inválida";
				$this->view->Sucesso = $sucesso;
				$this->view->Erros = $erro;
			}
			
			
			
			
		
			
		
			}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
			$erro[] =  $e->getMessage();
			$this->view->Sucesso = $sucesso;
			$this->view->Erros = $erro;
		}
		
		
		
		
	}
	
	
	public function reprocessaNfeEntradaAction(){

		$id = $this->_request->getParam("id",0);
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$dados = $db->fetchRow("id_registro = '$id'");
		$filename = $dados->localxml;
		
		if(Functions_NFe::checkHash($filename)){
		$db->delete("id_registro = '$id'");
				
			$nfe = Functions_NFe::processaXMLEntrada($filename);
		
			$Empresa = System_Model_Empresas::getDataEmpresaCNPJ($nfe['Destinatario']['CNPJFormatado']);
			if($Empresa->id_registro <> ''){
				$sucess['Destinatario'] = "Empresa Destinataria Encontrada: {$nfe['Destinatario']['xNome']}";
		
				$dbBasicos = new Erp_Model_Faturamento_NFe_Basicos();
				$nfe['Basicos']['id_empresa'] = $Empresa->id_registro;
				$nfe['Basicos']['status_processo'] = '3';
				$nfe['Basicos']['tipo_nfe'] = '4';
				$nfe['Basicos']['id_perfil'] = '0';
				$nfe['Basicos']['id_pedvenda'] = '0';
				($nfe['Basicos']['hSaiEnt'] == '') ? $nfe['Basicos']['hSaiEnt'] = '00:00:00' : $nfe['Basicos']['hSaiEnt'] = $nfe['Basicos']['hSaiEnt'] ;
				unset($nfe['Destinatario']['CNPJFormatado']);
					
				$idNFE = $dbBasicos->insert($nfe['Basicos']);
					
				$dadosPessoa = Cadastros_Model_Pessoas::getEmpresaCNPJ($nfe['Emitente']['CNPJFormatado']);
				if($dadosPessoa->id_registro == ''){
					$err['Emitente'] = "<a href=\"/system/faturamento/cadastra-pessoa-nfe-emitente/id/$idNFE\" target=\"_blank\"> Empresa {$nfe['Emitente']['xNome']} emitente da NFe não esta cadastrada nesse sistema clique aqui para cadastrar</a>";
				}else{
					$nfe['Emitente']['id_pessoa'] = $dadosPessoa->id_registro;
					$sucess['Emitente'] = "Empresa Destinataria Encontrada: {$nfe['Destinatario']['xNome']}";
				}
				$dbEmitente = new Erp_Model_Faturamento_NFe_Emitente();
				unset($nfe['Emitente']['CNPJFormatado']);
				$nfe['Emitente']['id_nfe'] = $idNFE;
				$dbEmitente->insert($nfe['Emitente']);
					
				$dbDest= new Erp_Model_Faturamento_NFe_Destinatario();
				unset($nfe['Destinatario']['CNPJFormatado']);
				$nfe['Destinatario']['id_nfe'] = $idNFE;
				$nfe['Destinatario']['id_empresa'] = $Empresa->id_registro;
				$dbDest->insert($nfe['Destinatario']);
					
		
		
				foreach($nfe['Produtos'] as $Prod){
		
					$ProdSistema = Cadastros_Model_Produtos::getProdutoCodNFe($Prod['cProd']);
					if($ProdSistema->id_produto <> ''){
						$Prod['id_produto'] = $ProdSistema->id_produto;
					}else{
						//$erro[] = "O Produto {$Prod[xProd]} não esta cadastrado no sistema, deseja cadastra-lo?";
					}
					$Prod['id_prod_venda'] = '0';
					$Prod['id_prod_compra'] = '0';
					$Prod['id_nfe'] = $idNFE;
						
					if(!$Prod['cEAN']){
						unset($Prod['cEAN']);
					}
						
					if(!$Prod['cEANTrib']){
						unset($Prod['cEANTrib']);
					}
		
					$dadosICMS = $Prod['ICMS'];
					unset($Prod['ICMS']);
					$dadosPIS = $Prod['PIS'];
					unset($Prod['PIS']);
					$dadosIPI = $Prod['IPI'];
					unset($Prod['IPI']);
					$dadosCOFINS = $Prod['COFINS'];
					unset($Prod['COFINS']);
		
					$dbProd = new Erp_Model_Faturamento_NFe_Produtos();
					$id_prod_nfe = $dbProd->insert($Prod);
		
					$dbProdICMS = new Erp_Model_Faturamento_NFe_ProdutosICMS();
					$dadosICMS['id_nfe'] = $idNFE;
					$dadosICMS['id_produto_nfe'] = $id_prod_nfe;
					$dbProdICMS->insert($dadosICMS);
		
					$dbProdPIS = new Erp_Model_Faturamento_NFe_ProdutosPIS();
					$dadosPIS['id_nfe'] = $idNFE;
					$dadosPIS['id_produto_nfe'] = $id_prod_nfe;
					$dbProdPIS->insert($dadosPIS);
		
					$dbProdCOFINS= new Erp_Model_Faturamento_NFe_ProdutosCOFINS();
					$dadosCOFINS['id_nfe'] = $idNFE;
					$dadosCOFINS['id_produto_nfe'] = $id_prod_nfe;
					$dbProdCOFINS->insert($dadosCOFINS);
					if($dadosIPI['CST'] <> ''){
						$dbProdIPI= new Erp_Model_Faturamento_NFe_ProdutosIPI();
						$dadosIPI['id_nfe'] = $idNFE;
						$dadosIPI['id_produto_nfe'] = $id_prod_nfe;
						$dbProdIPI->insert($dadosIPI);
					}
		
		
					if($ProdSistema->id_produto <> ''){
						$Prod['id_produto'] = $ProdSistema->id_produto;
					}else{
						$errp[] = "<a href=\"/system/faturamento/cadastra-produto-nfe-emitente/id/{$id_prod_nfe}\" target=\"_blank\">O Produto {$Prod[xProd]} não esta cadastrado no sistema, clique aqui para cadastrar </a> ou
						 <a href=\"/system/faturamento/vincula-produto-nfe-emitente/id/{$id_prod_nfe}\" target=\"_blank\">Aqui para vincular o produto a um produto existente</a>";
					}
		
						
				}
				$err['Produtos'] = $errp;
					
				$dadosVolumes = $nfe['Transporte']['volumes'];
				$dbTotais = new Erp_Model_Faturamento_NFe_Totais();
				$nfe['Totais']['id_nfe'] = $idNFE;
				$dbTotais->insert($nfe['Totais']);
				if($nfe['Transporte']['CNPJ'] <> ''){
					$dataTransp = Cadastros_Model_Pessoas::getEmpresaCNPJ(Functions_Auxilio::formatText($nfe['Transporte']['CNPJ'],"##.###.###/####-##"));
					if($dataTransp->id_registro){
						$nfe['Transporte']['id_pessoa'] = $dataTransp->id_registro;
					};
		
					$dbTransp = new Erp_Model_Faturamento_NFe_Transportadora();
					$dadosVeiculo = $nfe['Transporte']['veiculo'];
					unset($nfe['Transporte']['veiculo']);
					unset($nfe['Transporte']['volumes']);
					$nfe['Transporte']['id_nfe'] = $idNFE;
					$id_transp = $dbTransp->insert($nfe['Transporte']);
						
						
					if(!$dataTransp->id_registro){
						$err['Transportadora'] = "<a href=\"/system/faturamento/cadastra-transportadora-nfe-emitente/id/{$id_transp}\" target=\"_blank\">A Transportadora {$nfe['Transporte']['xNome']}  não esta cadastrada no sistema, clique aqui para cadastrar</a> ";
					};
						
					if($dadosVeiculo['placa'] || $dadosVeiculo['RNTC']){
						$dbVeic = new Erp_Model_Faturamento_NFe_TransportadoraVeiculo();
						$dadosVeiculo['id_nfe'] = $idNFE;
						$dadosVeiculo['id_transportadora'] = $id_transp;
						$dbVeic->insert($dadosVeiculo);
							
					}
						
						
				}
					
				if($dadosVolumes['qVol'] && $dadosVolumes['qVol'] > 0){
					$dadosVolumes['id_nfe'] = $idNFE;
					$dbVolumes = new Erp_Model_Faturamento_NFe_Volumes();
					$dbVolumes->insert($dadosVolumes);
				}
					
					
		
				if($nfe['Fatura']['nFat'] <> ''){
		
					$duplicatas = $nfe['Fatura']['duplicatas'];
					unset( $nfe['Fatura']['duplicatas'] );
					$nfe['Fatura']['id_nfe'] = $idNFE;
					$dbFatura = new Erp_Model_Faturamento_NFe_Fatura();
					$id_Fatura = $dbFatura->insert($nfe['Fatura']);
					$dbDup = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
					foreach($duplicatas as $dupl){
							
						$dupl['id_nfe'] = $idNFE;
						$dupl['id_fatura'] = $id_Fatura;
						$dbDup->insert($dupl);
							
					}
		
		
				}else{
		
					$duplicatas = $nfe['Fatura']['duplicatas'];
					unset( $nfe['Fatura']['duplicatas'] );
		
					foreach($duplicatas as $DUPL){
						if($DUPL['vDup']){
							$total += $DUPL['vDup'];
						}
		
					}
		
						
		
					$nfe['Fatura']['id_nfe'] = $idNFE;
					$nfe['Fatura']['vOrig'] = $total;
					$nfe['Fatura']['vDesc'] = '0';
					$nfe['Fatura']['vLiq'] = $total;
		
					$dbFatura = new Erp_Model_Faturamento_NFe_Fatura();
					$id_Fatura = $dbFatura->insert($nfe['Fatura']);
		
					$dbDup = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
		
					foreach($duplicatas as $dupl){
							
						$dupl['id_nfe'] = $idNFE;
						$dupl['id_fatura'] = $id_Fatura;
						$id_dup =  $dbDup->insert($dupl);
							
		
					}
		
		
		
		
		
				}
					
					
		
				$dbOBS = new Erp_Model_Faturamento_NFe_Observacoes();
				$oboutros = $nfe['Observacoes']['outros'];
				unset($nfe['Observacoes']['outros']);
				$nfe['Observacoes']['id_nfe'] = $idNFE;
				$dbOBS->insert($nfe['Observacoes']);
					
				$dbobsou = new Erp_Model_Faturamento_NFe_ObservacoesAdicionais();
				foreach($oboutros as $ob){
					$ob['id_nfe'] = $idNFE;
					$dbobsou->insert($ob);
				}
					
		
				if(!is_dir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/")){
					mkdir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/");
				}
					
				if(!is_dir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/{$nfe['Basicos']['AAMM']}")){
					mkdir(APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/{$nfe['Basicos']['AAMM']}");
				}
					
				$FileLocation = APPLICATION_PATH."/data/files/nfe/{$Empresa->id_registro}/recebidas/{$nfe['Basicos']['AAMM']}/{$nfe['Emitente']['CNPJ']}-{$nfe['Basicos']['nNF']}-$idNFE.xml";
					
				$file = file_get_contents($filename);
				file_put_contents($FileLocation, $file);
				unlink($filename);
				$dbBasicos->update(array('localxml'=>$FileLocation),"id_registro = '$idNFE'");
					
					
					
				$erro = $err;
				$sucesso = $sucess;
				$this->view->Fatura = $nfe['Fatura'];
				$this->view->Duplicatas = $duplicatas;
				$this->view->NFe = $nfe;
				$this->view->idNFe = $idNFE;
					
				$this->view->Sucesso = $sucesso;
				$this->view->Erros = $erro;
					
					
					
				if($erro['Emitente'] == ''  && $erro['Produtos'][0] == ''){
					//echo "Processando NFE";
					Functions_NFe::processaNFeEntrada($idNFE);
				}else{
					echo "Processando NFE ERRO";
				}
					
					
					
					
					
					
					
			}else{
				$erro['Destinatario'] = "A Empresa {$nfe['Destinatario']['xNome']} destinatária da NFe não esta cadastrada nesse sistema";
				$this->view->Sucesso = $sucesso;
				$this->view->Erros = $erro;
				$this->view->NFe = $nfe;
			};
		
		
		
		}else{
			$erro[] = "NFe Inválida";
			$this->view->Sucesso = $sucesso;
			$this->view->Erros = $erro;
		}
	}
	
	public function testAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		
		Functions_NFe::processaPedidosNFe($id);
		
		
	}
	
	public function consultarNfeRecebidasAction(){
		set_time_limit(0);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$db_empresas = new System_Model_Empresas();
		$dataemp = $db_empresas->fetchAll();
		$this->view->empresas = $dataemp;
		$statusret = '';
		$indcont = '1';
		while($indcont == 1){
		foreach($dataemp as $emp){
		
			$Config = System_Model_EmpresasNF::getConfigNFe($emp->id_registro);
			if($Config['certName'] <> ''){
			$NFe = new NFe_ToolsNFePHP($Config);
			$modSOAP = '2'; //usando cURL
			$tpAmb = '2';//usando produção
			$indNFe = '0';
			$indEmi = '0';
			$ultNSU = 0;
			$AN = true;
			$retorno = array();
			
			if (!$xml = $NFe->getListNFe($AN, $indNFe, $indEmi, $ultNSU, $tpAmb, $modSOAP, $retorno)){
				// header('Content-type: text/html; charset=UTF-8');
				echo "Houve erro !! $nfe->errMsg";
				echo '<br><br><PRE>';
						echo htmlspecialchars($nfe->soapDebug);
						echo '</PRE><BR>';
} else {
						// header('Content-type: text/xml; charset=UTF-8');
							echo"<textarea style=\"width:100%\">";
    print_r($xml);
			    echo "</textarea>";
			    
			    var_dump($retorno);
			    $indcont = $retorno['indCont'];
			    echo $indcont;
}
			
		
			
			
		}
	
		
		}
		
		
	}
	
	}
	
	
	
	
}