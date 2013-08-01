<?php 
class Functions_NFe{
	
	
	public static function montaXML($id){

		$db1= new Erp_Model_Faturamento_NFe_Basicos();
		$dadosBasicos = $db1->fetchRow("id_registro = '$id'");
		
		$dom = new DOMDocument('1.0', 'UTF-8');
		$dom->formatOutput = true;
		$dom->preserveWhiteSpace = false;
		$NFe = $dom->createElement("NFe");
		$NFe->setAttribute("xmlns", "http://www.portalfiscal.inf.br/nfe");
		$infNFe = $dom->createElement("infNFe");
		$infNFe->setAttribute("Id", $dadosBasicos->Id );
		$infNFe->setAttribute("versao", $dadosBasicos->versao);
		$chave = substr($dadosBasicos->Id, 3, 44);
		/**
		 * Identificadores
		 */
		$ide = $dom->createElement("ide");
		$cUF = $dom->createElement("cUF",  $dadosBasicos->cUF);
		$ide->appendChild($cUF);
		$cNF = $dom->createElement("cNF",  $dadosBasicos->cNF);
		$ide->appendChild($cNF);
		$NatOp = $dom->createElement("natOp",  $dadosBasicos->natOp);
		$ide->appendChild($NatOp);
		$indPag = $dom->createElement("indPag",  $dadosBasicos->indPag);
		$ide->appendChild($indPag);
		$mod = $dom->createElement("mod",  $dadosBasicos->mod);
		$ide->appendChild($mod);
		$serie = $dom->createElement("serie",  $dadosBasicos->serie);
		$ide->appendChild($serie);
		$nNF = $dom->createElement("nNF",  $dadosBasicos->nNF);
		$ide->appendChild($nNF);
		$dEmi = $dom->createElement("dEmi",  $dadosBasicos->dEmi);
		$ide->appendChild($dEmi);
		if (!empty( $dadosBasicos->dSaiEnt)) {
			$dSaiEnt = $dom->createElement("dSaiEnt", $dadosBasicos->dSaiEnt);
			$ide->appendChild($dSaiEnt);
			if (empty( $dadosBasicos->hSaiEnt)) {
				$dadosBasicos->hSaiEnt = '00:00:00';
			}
			$hSaiEnt = $dom->createElement("hSaiEnt",  $dadosBasicos->hSaiEnt);
			$ide->appendChild( $hSaiEnt);
		}
		$tpNF = $dom->createElement("tpNF",  $dadosBasicos->tpNF);
		$ide->appendChild($tpNF);
		$cMunFG = $dom->createElement("cMunFG",  $dadosBasicos->CMunFG);
		$ide->appendChild($cMunFG);
		$tpImp = $dom->createElement("tpImp",  $dadosBasicos->tpImp);
		$ide->appendChild($tpImp);
		$tpEmis = $dom->createElement("tpEmis",  $dadosBasicos->tpEmis);
		$ide->appendChild($tpEmis);
		$CDV = $dom->createElement("cDV",  $dadosBasicos->cDV);
		$ide->appendChild($CDV);
		$tpAmb = $dom->createElement("tpAmb",  $dadosBasicos->tpAmb);
		//guardar a variavel para uso posterior
		$tpAmbVar =  $dadosBasicos->tpAmb;
		
		$ide->appendChild($tpAmb);
		$finNFe = $dom->createElement("finNFe",  $dadosBasicos->finNFe);
		$ide->appendChild($finNFe);
		$procEmi = $dom->createElement("procEmi",  $dadosBasicos->procEmi);
		$ide->appendChild($procEmi);
		if (empty( $dadosBasicos->verProc)) {
			$dados[19] = "NfePHP";
		}
		$verProc = $dom->createElement("verProc",  $dadosBasicos->VerProc);
		$ide->appendChild($verProc);
		if (!empty( $dadosBasicos->dhCont) || !empty( $dadosBasicos->xJust)) {
			$dhCont = $dom->createElement("dhCont",  $dadosBasicos->dhCont);
			$ide->appendChild($dhCont);
			$xJust = $dom->createElement("xJust",  $dadosBasicos->xJust);
			$ide->appendChild($xJust);
		}
		$infNFe->appendChild($ide);
		
		/**
		 * Dados do Emitente
		 */
		
		$db2 = new Erp_Model_Faturamento_NFe_Emitente();
		$dadosEmitente = $db2->fetchRow("id_nfe = '$id'");
		
		//dados do emitente [infNFe]
		//C|XNome|XFant|IE|IEST|IM|CNAE|CRT|
		$emit = $dom->createElement("emit");
		$xNome = $dom->createElement("xNome", $dadosEmitente->xNome);
		$emit->appendChild($xNome);
		if (!empty($dados[2])) {
			$xFant = $dom->createElement("xFant", $dadosEmitente->xFant);
			$emit->appendChild($xFant);
		}
		$IE = $dom->createElement("IE",$dadosEmitente->IE);
		
		$emit->appendChild($IE);
		if (!empty($dadosEmitente->IEST)) {
			$IEST = $dom->createElement("IEST", $dadosEmitente->IEST);
			$emit->appendChild($IEST);
		}
		if (!empty($dadosEmitente->IM) && strtoupper($dadosEmitente->IM) <> 'ISENTO') {
			$IM = $dom->createElement("IM", $dadosEmitente->IM);
			$emit->appendChild($IM);
		}else{
			$IM = $dom->createElement("IM", "ISENTO");
			$emit->appendChild($IM);
		}

		if (!empty($dadosEmitente->CNAE)) {
			$cnae = $dom->createElement("CNAE", $dadosEmitente->CNAE);
			$emit->appendChild($cnae);
		}
		
		if (!empty($dadosEmitente->CRT)) {
			$CRT = $dom->createElement("CRT", $dadosEmitente->CRT);
			$emit->appendChild($CRT);
		}
		
		
		
		$infNFe->appendChild($emit);
		
		
		$cnpj = $dom->createElement("CNPJ", $dadosEmitente->CNPJ);
		$emit->insertBefore($emit->appendChild($cnpj), $xNome);
		
		$enderEmi = $dom->createElement("enderEmit");
		$xLgr = $dom->createElement("xLgr", $dadosEmitente->Xlgr);
		$enderEmi->appendChild($xLgr);
		$nro = $dom->createElement("nro", $dadosEmitente->nro);
		$enderEmi->appendChild($nro);
		if (!empty($dadosEmitente->xCpl)) {
			$xCpl = $dom->createElement("xCpl", $dadosEmitente->xCpl);
			$enderEmi->appendChild($xCpl);
		}
		$xBairro = $dom->createElement("xBairro", $dadosEmitente->xBairro);
		$enderEmi->appendChild($xBairro);
		$cMun = $dom->createElement("cMun", $dadosEmitente->cMun);
		$enderEmi->appendChild($cMun);
		$xMun = $dom->createElement("xMun", $dadosEmitente->xMun);
		$enderEmi->appendChild($xMun);
		$UF = $dom->createElement("UF", $dadosEmitente->UF);
		$enderEmi->appendChild($UF);
		if (!empty($dadosEmitente->CEP)) {
			$CEP = $dom->createElement("CEP", $dadosEmitente->CEP);
			$enderEmi->appendChild($CEP);
		}
		if (!empty($dadosEmitente->cPais)) {
			$cPais = $dom->createElement("cPais", $dadosEmitente->cPais);
			$enderEmi->appendChild($cPais);
		}
		if (!empty($dadosEmitente->xPais)) {
			$xPais = $dom->createElement("xPais", $dadosEmitente->xPais);
			$enderEmi->appendChild($xPais);
		}
		if (!empty($dadosEmitente->fone)) {
			$fone = $dom->createElement("fone", $dadosEmitente->fone);
			$enderEmi->appendChild($fone);
		}
		$emit->insertBefore($emit->appendChild($enderEmi), $IE);
		
		/**
		 * Dados Destinatário
		 */
		
		$db3 = new Erp_Model_Faturamento_NFe_Destinatario();
		$dadosDestinatario = $db3->fetchRow("id_nfe = '$id'");
		
		$dest = $dom->createElement("dest");
		//se ambiente homologação preencher conforme NT2011.002
		//válida a partir de 01/05/2011
		if ($tpAmbVar == '2') {
			$xNome = $dom->createElement("xNome", 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL');
			$dest->appendChild($xNome);
			$IE = $dom->createElement("IE", 'ISENTO');
			$dest->appendChild($IE);
		} else {
			$xNome = $dom->createElement("xNome", $dadosDestinatario->xNome);
			$dest->appendChild($xNome);

			if($dadosDestinatario->IE <> ''){
			$IE = $dom->createElement("IE", $dadosDestinatario->IE);
			$dest->appendChild($IE);
			}else{
				$IE = $dom->createElement("IE", "ISENTO");
				$dest->appendChild($IE);
			}
			
		}
		if (!empty($dadosDestinatario->ISUF)) {
			$ISUF = $dom->createElement("ISUF", $dadosDestinatario->ISUF);
			$dest->appendChild($ISUF);
		}
		if (!empty($dadosDestinatario->email)) {
			$email = $dom->createElement("email", $dadosDestinatario->email);
			$dest->appendChild($email);
		}
		$infNFe->appendChild($dest);
		
		//válida a partir de 01/05/2011
		if ($tpAmbVar == '2') {
			if ($dadosDestinatario->CNPJ != '') {
				//operação nacional em ambiente homologação usar 99999999000191
				$CNPJ = $dom->createElement("CNPJ", '99999999000191');
			} else {
				//operação com o exterior CNPJ vazio
				$CNPJ = $dom->createElement("CNPJ", '');
			}
		} elseif($tpAmbVar == '1' && $dadosDestinatario->CNPJ <> '') {
			$CNPJ = $dom->createElement("CNPJ", $dadosDestinatario->CNPJ);
		}//fim teste ambiente
		$dest->insertBefore($dest->appendChild($CNPJ), $xNome);
		
		
		if($tpAmbVar == '2' && $dadosDestinatario->CPF <> ''){
			$CPF = $dom->createElement("CPF", $dadosDestinatario->CPF);
			$dest->insertBefore($dest->appendChild($CPF), $xNome);
		}
		
		$enderDest = $dom->createElement("enderDest");
		$xLgr = $dom->createElement("xLgr", $dadosDestinatario->Xlgr);
		$enderDest->appendChild($xLgr);
		$nro = $dom->createElement("nro", $dadosDestinatario->nro);
		$enderDest->appendChild($nro);
		if (!empty($dadosDestinatario->xCpl)) {
			$xCpl = $dom->createElement("xCpl", $dadosDestinatario->xCpl);
			$enderDest->appendChild($xCpl);
		}
		$xBairro = $dom->createElement("xBairro", $dadosDestinatario->xBairro);
		$enderDest->appendChild($xBairro);
		$cMun = $dom->createElement("cMun", $dadosDestinatario->cMun);
		$enderDest->appendChild($cMun);
		$xMun = $dom->createElement("xMun", $dadosDestinatario->xMun);
		$enderDest->appendChild($xMun);
		$UF = $dom->createElement("UF", $dadosDestinatario->UF);
		$enderDest->appendChild($UF);
		if (!empty($dadosDestinatario->CEP)) {
			$CEP = $dom->createElement("CEP", $dadosDestinatario->CEP);
			$enderDest->appendChild($CEP);
		}
		if (!empty($dadosDestinatario->cPais)) {
			$cPais = $dom->createElement("cPais", $dadosDestinatario->cPais);
			$enderDest->appendChild($cPais);
		}
		if (!empty($dadosDestinatario->xPais)) {
			$xPais = $dom->createElement("xPais", $dadosDestinatario->xPais);
			$enderDest->appendChild($xPais);
		}
		if (!empty($dadosDestinatario->fone)) {
			$fone = $dom->createElement("fone", $dadosDestinatario->fone);
			$enderDest->appendChild($fone);
		}
		$dest->insertBefore($dest->appendChild($enderDest), $IE);
		
		/**
		 * RETIRADA
		 */
		
		$db4 = new Erp_Model_Faturamento_NFe_EmitenteLRetirada();
		$DadosRetirada = $db4->fetchRow("id_nfe = '$id'");
		
		if($DadosRetirada <> '' && $DadosRetirada->id_nfe <> ''){
			
			$retirada = $dom->createElement("retirada");
			if (!empty($DadosRetirada->Xlgr)) {
				$xLgr = $dom->createElement("xLgr", $DadosRetirada->Xlgr);
				$retirada->appendChild($xLgr);
			}
			if (!empty($DadosRetirada->nro)) {
				$nro = $dom->createElement("nro", $DadosRetirada->nro);
				$retirada->appendChild($nro);
			}
			if (!empty($DadosRetirada->xCpl)) {
				$xCpl = $dom->createElement("xCpl", $DadosRetirada->xCpl);
				$retirada->appendChild($xCpl);
			}
			if (!empty($DadosRetirada->xBairro)) {
				$xBairro = $dom->createElement("xBairro", $DadosRetirada->xBairro);
				$retirada->appendChild($xBairro);
			}
			if (!empty($DadosRetirada->cMun)) {
				$cMun = $dom->createElement("cMun", $DadosRetirada->cMun);
				$retirada->appendChild($cMun);
			}
			if (!empty($DadosRetirada->xMun)) {
				$xMun = $dom->createElement("xMun", $DadosRetirada->xMun);
				$retirada->appendChild($xMun);
			}
			if (!empty($DadosRetirada->UF)) {
				$UF = $dom->createElement("UF", $DadosRetirada->UF);
				$retirada->appendChild($UF);
			}
			$infNFe->appendChild($retirada);
			
			if (!empty($DadosRetirada->CNPJ)) {
				$CNPJ = $dom->createElement("CNPJ", $DadosRetirada->CNPJ);
				$retirada->insertBefore($retirada->appendChild($CNPJ), $xLgr);
			}
			
			if (!empty($DadosRetirada->CPF)) {
				$CPF = $dom->createElement("CPF", $DadosRetirada->CPF);
				$retirada->insertBefore($retirada->appendChild($CPF), $xLgr);
			}
			
		}
		
		/**
		 * Dados Entrega
		 */
		
		$db5 = new Erp_Model_Faturamento_NFe_DestinatarioEntrega();
		$DadosEntrega = $db5->fetchRow("id_nfe = '$id'");
		if($DadosEntrega <> '' && $DadosEntrega->id_nfe <> ''){
			
			$entrega = $dom->createElement("entrega");
			if (!empty($DadosEntrega->Xlgr)) {
				$xLgr = $dom->createElement("xLgr", $DadosEntrega->Xlgr);
				$entrega->appendChild($xLgr);
			}
			if (!empty($DadosEntrega->nro)) {
				$nro = $dom->createElement("nro", $DadosEntrega->nro);
				$entrega->appendChild($nro);
			}
			if (!empty($DadosEntrega->xCpl)) {
				$xCpl = $dom->createElement("xCpl", $DadosEntrega->xCpl);
				$entrega->appendChild($xCpl);
			}
			if (!empty($DadosEntrega->xBairro)) {
				$xBairro = $dom->createElement("xBairro", $DadosEntrega->xBairro);
				$entrega->appendChild($xBairro);
			}
			if (!empty($DadosEntrega->cMun)) {
				$cMun = $dom->createElement("cMun", $DadosEntrega->cMun);
				$entrega->appendChild($cMun);
			}
			if (!empty($DadosEntrega->xMun)) {
				$xMun = $dom->createElement("xMun", $DadosEntrega->xMun);
				$entrega->appendChild($xMun);
			}
			if (!empty($DadosEntrega->UF)) {
				$UF = $dom->createElement("UF", $DadosEntrega->UF);
				$entrega->appendChild($UF);
			}
			$infNFe->appendChild($entrega);
			
			if (!empty($DadosEntrega->CNPJ)) {
				$CNPJ = $dom->createElement("CNPJ", $DadosEntrega->CNPJ);
				$entrega->insertBefore($entrega->appendChild($CNPJ), $xLgr);
			}
			
			if (!empty($DadosEntrega->CPF)) {
				$CPF = $dom->createElement("CPF", $DadosEntrega->CPF);
				$entrega->insertBefore($entrega->appendChild($CPF), $xLgr);
			}
			
		}
		
		/**
		 * DADOS DOS PRODUTOS
		 */
		
		$db6 = new Erp_Model_Faturamento_NFe_Produtos();
		$DadosProd = $db6->fetchAll("id_nfe = '$id'");
		
		foreach($DadosProd as $DadosProduto){
			
			$det = $dom->createElement("det");
			$det->setAttribute("nItem", $DadosProduto->nItem);
			
			$infNFe->appendChild($det);
			
			$prod = $dom->createElement("prod");
			$cProd = $dom->createElement("cProd", $DadosProduto->cProd);
			$prod->appendChild($cProd);
			$cEAN = $dom->createElement("cEAN", $DadosProduto->cEAN);
			$prod->appendChild($cEAN);
			$xProd = $dom->createElement("xProd", $DadosProduto->xProd);
			$prod->appendChild($xProd);
			$NCM = $dom->createElement("NCM", $DadosProduto->NCM);
			$prod->appendChild($NCM);
			if (!empty($DadosProduto->EXTIPI)) {
				$EXTIPI = $dom->createElement("EXTIPI", $DadosProduto->EXTIPI);
				$prod->appendChild($EXTIPI);
			}
			$CFOP = $dom->createElement("CFOP", $DadosProduto->CFOP);
			$prod->appendChild($CFOP);
			$uCom = $dom->createElement("uCom", $DadosProduto->uCom);
			$prod->appendChild($uCom);
			$qCom = $dom->createElement("qCom", $DadosProduto->qCom);
			$prod->appendChild($qCom);
			$vUnCom = $dom->createElement("vUnCom",$DadosProduto->vUnCom);
			$prod->appendChild($vUnCom);
			$vProd = $dom->createElement("vProd", $DadosProduto->vProd);
			$prod->appendChild($vProd);
			$cEANTrib = $dom->createElement("cEANTrib", $DadosProduto->cEANTrib);
			$prod->appendChild($cEANTrib);
			if (!empty($DadosProduto->uTrib)) {
				$uTrib = $dom->createElement("uTrib", $DadosProduto->uTrib);
			} else {
				$uTrib = $dom->createElement("uTrib", $DadosProduto->uCom);
			}
			$prod->appendChild($uTrib);
			if (!empty($DadosProduto->qTrib)) {
				$qTrib = $dom->createElement("qTrib", $DadosProduto->qTrib);
			} else {
				$qTrib = $dom->createElement("qTrib", $DadosProduto->qCom);
			}
			$prod->appendChild($qTrib);
			if (!empty($DadosProduto->vUnTrib)) {
				$vUnTrib = $dom->createElement("vUnTrib", $DadosProduto->vUnTrib);
			} else {
				$vUnTrib = $dom->createElement("vUnTrib", $DadosProduto->vUnCom);
			}
			$prod->appendChild($vUnTrib);
			if (!empty($DadosProduto->vFrete) && $DadosProduto->vFrete > 0 ) {
				$vFrete = $dom->createElement("vFrete", $DadosProduto->vFrete);
				$prod->appendChild($vFrete);
			}
			if (!empty($DadosProduto->vSeg)  && $DadosProduto->vSeg > 0) {
				$vSeg = $dom->createElement("vSeg", $DadosProduto->vSeg);
				$prod->appendChild($vSeg);
			}
			if (!empty($DadosProduto->vDesc)  && $DadosProduto->vDesc > 0) {
				$vDesc = $dom->createElement("vDesc", $DadosProduto->vDesc);
				$prod->appendChild($vDesc);
			}
			if (!empty($DadosProduto->vOutro)  && $DadosProduto->vOutro > 0) {
				$vOutro = $dom->createElement("vOutro", $DadosProduto->vOutro);
				$prod->appendChild($vOutro);
			}
			if (!empty($DadosProduto->intTot) || $DadosProduto->intTot <> 0) {
				$indTot = $dom->createElement("indTot", $DadosProduto->intTot);
				$prod->appendChild($indTot);
			} else {
				$indTot = $dom->createElement("indTot", '0');
				$prod->appendChild($indTot);
			}
			
				if (!empty($DadosProduto->xPed)) {
					$xPed = $dom->createElement("xPed", $DadosProduto->xPed);
					$prod->appendChild($xPed);
				}
				if (!empty($DadosProduto->nItemPed)) {
					$nItemPed = $dom->createElement("nItemPed", $DadosProduto->nItemPed);
					$prod->appendChild($nItemPed);
				}
			
			
				$det->appendChild($prod);
				if (!empty($DadosProduto->infAdProd)) {
					$infAdProd = $dom->createElement("infAdProd",$DadosProduto->infAdProd);
					$det->appendChild($infAdProd);
				}
				
				/**
				 * IMPOSTOS SOBRE O PRODUTO:
				 */
				
				/**
				 * Valor Total IMPOSTOS (LEI DA TRANSPARENCIA)
				 * 
				 */
				
				$imp1 =  Erp_Model_Faturamento_NFe_Produtos::somaTotalCOFINS($DadosProduto->id_registro);
				$imp2 =  Erp_Model_Faturamento_NFe_Produtos::somaTotalPIS($DadosProduto->id_registro);
				$imp3 =  Erp_Model_Faturamento_NFe_Produtos::somaTotalIPI($DadosProduto->id_registro);
				$imp4 =  Erp_Model_Faturamento_NFe_Produtos::somaTotalICMS($DadosProduto->id_registro);
				$imp5 =  Erp_Model_Faturamento_NFe_Produtos::somaTotalICMSST($DadosProduto->id_registro);
				
				
								
				
				$imposto = $dom->createElement("imposto");
				$vTotTrib= $imp1 + $imp2 + $imp3 + $imp4 + $imp5;
				$vTotTrib = number_format($vTotTrib,2,'.','');
				$vTotTrib = $dom->createElement("vTotTrib", $vTotTrib);
				$imposto->appendChild($vTotTrib);
			
				if (!isset($infAdProd)) {
					$det->appendChild($imposto);
				} else {
					$det->insertBefore($det->appendChild($imposto), $infAdProd);
				}
				$infAdProd = null;
				
				
				/**
				 * ICMS
				 */
				$ICMS = $dom->createElement("ICMS");
				$imposto->appendChild($ICMS);
				
				$db7 = new Erp_Model_Faturamento_NFe_ProdutosICMS();
				$DadosICMS = $db7->fetchRow("id_produto_nfe = '{$DadosProduto->id_registro}'");
				
				switch($DadosICMS->tributacao){
					case '0':
					case '000':
					case '00':
						$ICMS00 = $dom->createElement("ICMS00");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS00->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS00->appendChild($CST);
						$modBC = $dom->createElement("modBC",$DadosICMS->modBC);
						$ICMS00->appendChild($modBC);
						$vBC = $dom->createElement("vBC", $DadosICMS->vBC);
						$ICMS00->appendChild($vBC);
						$pICMS = $dom->createElement("pICMS",$DadosICMS->pICMS);
						$ICMS00->appendChild($pICMS);
						$vICMS = $dom->createElement("vICMS", $DadosICMS->vICMS);
						$ICMS00->appendChild($vICMS);
						$ICMS->appendChild($ICMS00);
					break;
					
					case '10':
					case '010':
						$ICMS10 = $dom->createElement("ICMS10");
						$orig = $dom->createElement("orig",  $DadosICMS->orig);
						$ICMS10->appendChild($orig);
						$CST = $dom->createElement("CST",  $DadosICMS->CST);
						$ICMS10->appendChild($CST);
						$modBC = $dom->createElement("modBC",  $DadosICMS->modBC);
						$ICMS10->appendChild($modBC);
						$vBC = $dom->createElement("vBC",  $DadosICMS->vBC);
						$ICMS10->appendChild($vBC);
						$pICMS = $dom->createElement("pICMS",  $DadosICMS->pICMS);
						$ICMS10->appendChild($pICMS);
						$vICMS = $dom->createElement("vICMS",  $DadosICMS->vICMS);
						$ICMS10->appendChild($vICMS);
						$modBCST = $dom->createElement("modBCST",  $DadosICMS->modBCST);
						$ICMS10->appendChild($modBCST);
						if (!empty( $DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST",  $DadosICMS->pMVAST);
							$ICMS10->appendChild($pMVAST);
						}
						if (!empty( $DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST",  $DadosICMS->pRedBCST);
							$ICMS10->appendChild($pRedBCST);
						}
						$vBCST = $dom->createElement("vBCST",  $DadosICMS->vBCST);
						$ICMS10->appendChild($vBCST);
						$pICMSST = $dom->createElement("pICMSST",  $DadosICMS->pICMSST);
						$ICMS10->appendChild($pICMSST);
						$vICMSST = $dom->createElement("vICMSST",  $DadosICMS->vICMSST);
						$ICMS10->appendChild($vICMSST);
						$ICMS->appendChild($ICMS10);
					
					break;
					
					case '20':
					case '020':
						
						$ICMS20 = $dom->createElement("ICMS20");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS20->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS20->appendChild($CST);
						$modBC = $dom->createElement("modBC", $DadosICMS->modBC);
						$ICMS20->appendChild($modBC);
						$pRedBC = $dom->createElement("pRedBC", $DadosICMS->pRedBC);
						$ICMS20->appendChild($pRedBC);
						$vBC = $dom->createElement("vBC",$DadosICMS->vBC);
						$ICMS20->appendChild($vBC);
						$pICMS = $dom->createElement("pICMS", $DadosICMS->pICMS);
						$ICMS20->appendChild($pICMS);
						$vICMS = $dom->createElement("vICMS", $DadosICMS->vICMS);
						$ICMS20->appendChild($vICMS);
						$ICMS->appendChild($ICMS20);
					
					break;
					
					case '30':
					case '030':
						$ICMS30 = $dom->createElement("ICMS30");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS30->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS30->appendChild($CST);
						$modBCST = $dom->createElement("modBCST", $DadosICMS->modBCST);
						$ICMS30->appendChild($modBCST);
						if (!empty($DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST", $DadosICMS->pMVAST);
							$ICMS30->appendChild($pMVAST);
						}
						if (!empty($DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST", $DadosICMS->pRedBCST);
							$ICMS30->appendChild($pRedBCST);
						}
						$vBCST = $dom->createElement("vBCST", $DadosICMS->vBCST);
						$ICMS30->appendChild($vBCST);
						$pICMSST = $dom->createElement("pICMSST", $DadosICMS->pICMSST);
						$ICMS30->appendChild($pICMSST);
						$vICMSST = $dom->createElement("vICMSST", $DadosICMS->vICMSST);
						$ICMS30->appendChild($vICMSST);
						$ICMS->appendChild($ICMS30);
					
					break;
					
					case '40':
					case '41':
					case '50':
					case '040':
					case '041':
					case '050':
						$ICMS40 = $dom->createElement("ICMS40");
						$orig = $dom->createElement("orig",$DadosICMS->orig);
						$ICMS40->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS40->appendChild($CST);
						if (!empty($DadosICMS->vICMS)) {
							$vICMS = $dom->createElement("vICMS", $DadosICMS->vICMS);
							$ICMS40->appendChild($vICMS);
						}
						if (!empty($DadosICMS->motDesICMS)) {
							$motDesICMS = $dom->createElement("motDesICMS",$DadosICMS->motDesICMS);
							$ICMS40->appendChild($motDesICMS);
						}
						$ICMS->appendChild($ICMS40);
						
					break;
					
					
					case '51':
					case '051':
						
						$ICMS51 = $dom->createElement("ICMS51");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS51->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS51->appendChild($CST);
						if (!empty($DadosICMS->modBC)) {
							$modBC = $dom->createElement("modBC",$DadosICMS->modBC);
							$ICMS51->appendChild($modBC);
						}
						if (!empty($DadosICMS->pRedBC)) {
							$pRedBC = $dom->createElement("pRedBC", $DadosICMS->pRedBC);
							$ICMS51->appendChild($pRedBC);
						}
						if (!empty($DadosICMS->vBC)) {
							$vBC = $dom->createElement("vBC", $DadosICMS->vBC);
							$ICMS51->appendChild($vBC);
						}
						if (!empty($DadosICMS->pICMS)) {
							$pICMS = $dom->createElement("pICMS", $DadosICMS->pICMS);
							$ICMS51->appendChild($pICMS);
						}
						if (!empty($DadosICMS->vICMS)) {
							$vICMS = $dom->createElement("vICMS", $DadosICMS->vICMS);
							$ICMS51->appendChild($vICMS);
						}
						$ICMS->appendChild($ICMS51);
						
					break;
					
					
					case '60':
					case '060':
						$ICMS60 = $dom->createElement("ICMS60");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS60->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS60->appendChild($CST);
						$vBCST = $dom->createElement("vBCSTRet", $DadosICMS->vBCSTRet);
						$ICMS60->appendChild($vBCST);
						$vICMSST = $dom->createElement("vICMSSTRet", $DadosICMS->vICMSSTRet);
						$ICMS60->appendChild($vICMSST);
						$ICMS->appendChild($ICMS60);
					break;
					
					case '70':
					case '070':
						$ICMS70 = $dom->createElement("ICMS70");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS70->appendChild($orig);
						$CST = $dom->createElement("CST", $$DadosICMS->CST);
						$ICMS70->appendChild($CST);
						$modBC = $dom->createElement("modBC", $$DadosICMS->modBC);
						$ICMS70->appendChild($modBC);
						$pRedBC = $dom->createElement("pRedBC", $DadosICMS->pRedBC);
						$ICMS70->appendChild($pRedBC);
						$vBC = $dom->createElement("vBC", $DadosICMS->vBC);
						$ICMS70->appendChild($vBC);
						$pICMS = $dom->createElement("pICMS", $DadosICMS->pICMS);
						$ICMS70->appendChild($pICMS);
						$vICMS = $dom->createElement("vICMS", $DadosICMS->vICMS);
						$ICMS70->appendChild($vICMS);
						$modBCST = $dom->createElement("modBCST", $DadosICMS->modBCST);
						$ICMS70->appendChild($modBCST);
						if (!empty($DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST",$DadosICMS->pMVAST);
							$ICMS70->appendChild($pMVAST);
						}
						if (!empty($DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST", $DadosICMS->pRedBCST);
							$ICMS70->appendChild($pRedBCST);
						}
						$vBCST = $dom->createElement("vBCST",$DadosICMS->vBCST);
						$ICMS70->appendChild($vBCST);
						$pICMSST = $dom->createElement("pICMSST",$DadosICMS->pICMSST);
						$ICMS70->appendChild($pICMSST);
						$vICMSST = $dom->createElement("vICMSST", $DadosICMS->vICMSST);
						$ICMS70->appendChild($vICMSST);
						$ICMS->appendChild($ICMS70);
						
					break;
					
					
					case '90':
					case '090':
						$ICMS90 = $dom->createElement("ICMS90");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMS90->appendChild($orig);
						$CST = $dom->createElement("CST", $DadosICMS->CST);
						$ICMS90->appendChild($CST);
						$modBC = $dom->createElement("modBC", $DadosICMS->modBC);
						$ICMS90->appendChild($modBC);
						if (!empty($DadosICMS->pRedBC)) {
							$pRedBC = $dom->createElement("pRedBC", $DadosICMS->pRedBC);
							$ICMS90->appendChild($pRedBC);
						}
						$vBC = $dom->createElement("vBC", $DadosICMS->vBC);
						$ICMS90->appendChild($vBC);
						$pICMS = $dom->createElement("pICMS", $DadosICMS->pICMS);
						$ICMS90->appendChild($pICMS);
						$vICMS = $dom->createElement("vICMS",$DadosICMS->vICMS);
						$ICMS90->appendChild($vICMS);
						$modBCST = $dom->createElement("modBCST", $DadosICMS->modBCST);
						$ICMS90->appendChild($modBCST);
						if (!empty($DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST", $DadosICMS->pMVAST);
							$ICMS90->appendChild($pMVAST);
						}
						if (!empty($DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST", $DadosICMS->pRedBCST);
							$ICMS90->appendChild($pRedBCST);
						}
						$vBCST = $dom->createElement("vBCST", $DadosICMS->vBCST);
						$ICMS90->appendChild($vBCST);
						$pICMSST = $dom->createElement("pICMSST", $DadosICMS->pICMSST);
						$ICMS90->appendChild($pICMSST);
						$vICMSST = $dom->createElement("vICMSST", $DadosICMS->vICMSST);
						$ICMS90->appendChild($vICMSST);
						$ICMS->appendChild($ICMS90);
					break;
					
					
					case '101':
						$ICMSSN101 = $dom->createElement("ICMSSN101");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMSSN101->appendChild($orig);
						$CSOSN = $dom->createElement("CSOSN", $DadosICMS->CSOSN);
						$ICMSSN101->appendChild($CSOSN);
						$pCredSN = $dom->createElement("pCredSN", $DadosICMS->pCredSN);
						$ICMSSN101->appendChild($pCredSN);
						$vCredICMSSN = $dom->createElement("vCredICMSSN", $DadosICMS->vCredICMSSN);
						$ICMSSN101->appendChild($vCredICMSSN);
						$ICMS->appendChild($ICMSSN101);
						
					break;
					
					
					case '102':
					case '103':
					case '300':
					case '400':
						
						$ICMSSN102 = $dom->createElement("ICMSSN102");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMSSN102->appendChild($orig);
						$CSOSN = $dom->createElement("CSOSN", $DadosICMS->CSOSN);
						$ICMSSN102->appendChild($CSOSN);
						$ICMS->appendChild($ICMSSN102);
						
					break;
					
					case '201':
						
						$ICMSSN201 = $dom->createElement("ICMSSN201");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMSSN201->appendChild($orig);
						$CSOSN = $dom->createElement("CSOSN",$DadosICMS->CSOSN);
						$ICMSSN201->appendChild($CSOSN);
						$modBCST = $dom->createElement("modBCST", $DadosICMS->modBCST);
						$ICMSSN201->appendChild($modBCST);
						if (!empty($DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST", $DadosICMS->pMVAST);
							$ICMSSN201->appendChild($pMVAST);
						}
						if (!empty($$DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST", $DadosICMS->pRedBCST);
							$ICMSSN201->appendChild($pRedBCST);
						}
						$vBCST = $dom->createElement("vBCST", $DadosICMS->vBCST);
						$ICMSSN201->appendChild($vBCST);
						$pICMSST = $dom->createElement("pICMSST", $DadosICMS->pICMSST);
						$ICMSSN201->appendChild($pICMSST);
						$vICMSST = $dom->createElement("vICMSST", $DadosICMS->vICMSST);
						$ICMSSN201->appendChild($vICMSST);
						$pCredSN = $dom->createElement("pCredSN", $DadosICMS->pCredSN);
						$ICMSSN201->appendChild($pCredSN);
						$vCredICMSSN = $dom->createElement("vCredICMSSN", $DadosICMS->vCredICMSSN);
						$ICMSSN201->appendChild($vCredICMSSN);
						$ICMS->appendChild($ICMSSN201);
						
					break;
					
					case '202':
					case '203':
						
						$ICMSSN202 = $dom->createElement("ICMSSN202");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMSSN202->appendChild($orig);
						$CSOSN = $dom->createElement("CSOSN", $DadosICMS->CSOSN);
						$ICMSSN202->appendChild($CSOSN);
						$modBCST = $dom->createElement("modBCST", $DadosICMS->modBCST);
						$ICMSSN202->appendChild($modBCST);
						if (!empty($DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST", $DadosICMS->pMVAST);
							$ICMSSN202->appendChild($pMVAST);
						}
						if (!empty($DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST", $DadosICMS->pRedBCST);
							$ICMSSN202->appendChild($pRedBCST);
						}
						$vBCST = $dom->createElement("vBCST", $DadosICMS->vBCST);
						$ICMSSN202->appendChild($vBCST);
						$pICMSST = $dom->createElement("pICMSST", $DadosICMS->pICMSST);
						$ICMSSN202->appendChild($pICMSST);
						$vICMSST = $dom->createElement("pICMSST", $DadosICMS->pICMSST);
						$ICMSSN202->appendChild($vICMSST);
						$ICMS->appendChild($ICMSSN202);
						
					break;
						
					case '500':
						
						$ICMSSN500 = $dom->createElement("ICMSSN500");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMSSN500->appendChild($orig);
						$CSOSN = $dom->createElement("CSOSN",$DadosICMS->CSOSN);
						$ICMSSN500->appendChild($CSOSN);
						$vBCSTRet = $dom->createElement("vBCSTRet",$DadosICMS->vBCSTRet);
						$ICMSSN500->appendChild($vBCSTRet);
						$vICMSSTRet = $dom->createElement("vICMSSTRet", $DadosICMS->vICMSSTRet);
						$ICMSSN500->appendChild($vICMSSTRet);
						$ICMS->appendChild($ICMSSN500);
					break;
					
					case '900':
						$ICMSSN900 = $dom->createElement("ICMSSN900");
						$orig = $dom->createElement("orig", $DadosICMS->orig);
						$ICMSSN900->appendChild($orig);
						$CSOSN = $dom->createElement("CSOSN", $DadosICMS->CSOSN);
						$ICMSSN900->appendChild($CSOSN);
						if (!empty($DadosICMS->modBC)) {
							$modBC = $dom->createElement("modBC", $DadosICMS->modBC);
							$ICMSSN900->appendChild($modBC);
						}
						if (!empty($DadosICMS->vBC)) {
							$vBC = $dom->createElement("vBC", $DadosICMS->vBC);
							$ICMSSN900->appendChild($vBC);
						}
						if (!empty($DadosICMS->pRedBC)) {
							$pRedBC = $dom->createElement("pRedBC", $DadosICMS->pRedBC);
							$ICMSSN900->appendChild($pRedBC);
						}
						if (!empty($DadosICMS->pICMS)) {
							$pICMS = $dom->createElement("pICMS", $DadosICMS->pICMS);
							$ICMSSN900->appendChild($pICMS);
						}
						if (!empty($DadosICMS->vICMS)) {
							$vICMS = $dom->createElement("vICMS", $DadosICMS->vICMS);
							$ICMSSN900->appendChild($vICMS);
						}
						if (!empty($DadosICMS->modBCST)) {
							$modBCST = $dom->createElement("modBCST", $DadosICMS->modBCST);
							$ICMSSN900->appendChild($modBCST);
						}
						if (!empty($DadosICMS->pMVAST)) {
							$pMVAST = $dom->createElement("pMVAST", $DadosICMS->pMVAST);
							$ICMSSN900->appendChild($pMVAST);
						}
						if (!empty($DadosICMS->pRedBCST)) {
							$pRedBCST = $dom->createElement("pRedBCST", $DadosICMS->pRedBCST);
							$ICMSSN900->appendChild($pRedBCST);
						}
						if (!empty($DadosICMS->vBCST)) {
							$vBCST = $dom->createElement("vBCST",$DadosICMS->vBCST);
							$ICMSSN900->appendChild($vBCST);
						}
						if (!empty($DadosICMS->pICMSST)) {
							$pICMSST = $dom->createElement("pICMSST", $DadosICMS->pICMSST);
							$ICMSSN900->appendChild($pICMSST);
						}
						if (!empty($DadosICMS->vICMSST)) {
							$vICMSST = $dom->createElement("vICMSST", $DadosICMS->vICMSST);
							$ICMSSN900->appendChild($vICMSST);
						}
						if (!empty($DadosICMS->pCredSN)) {
							$pCredSN = $dom->createElement("pCredSN", $DadosICMS->pCredSN);
							$ICMSSN900->appendChild($pCredSN);
						}
						if (!empty($DadosICMS->vCredICMSSN)) {
							$vCredICMSSN = $dom->createElement("vCredICMSSN", $DadosICMS->vCredICMSSN);
							$ICMSSN900->appendChild($vCredICMSSN);
						}
						$ICMS->appendChild($ICMSSN900);
					break;
					
				}
				
				
				/**
				 * IPI
				 */
				
				$db8 = new Erp_Model_Faturamento_NFe_ProdutosIPI();
				$DadosIPI = $db8->fetchRow("id_produto_nfe = '{$DadosProduto->id_registro}'");
				
				$IPI = $dom->createElement("IPI");
				if (!empty($DadosIPI->clEnq)) {
					$clEnq = $dom->createElement("clEnq", $DadosIPI->clEnq);
					$IPI->appendChild($clEnq);
				}
				if (!empty($DadosIPI->CNPJProd)) {
					$CNPJProd = $dom->createElement("CNPJProd", $DadosIPI->CNPJProd);
					$IPI->appendChild($CNPJProd);
				}
				if (!empty($DadosIPI->cSelo)) {
					$cSelo = $dom->createElement("cSelo", $DadosIPI->cSelo);
					$IPI->appendChild($cSelo);
				}
				if (!empty($DadosIPI->qSelo)) {
					$qSelo = $dom->createElement("qSelo", $DadosIPI->qSelo);
					$IPI->appendChild($qSelo);
				}
				if (!empty($DadosIPI->cEnq)) {
					$cEnq = $dom->createElement("cEnq", $DadosIPI->cEnq);
					$IPI->appendChild($cEnq);
				}
				$imposto->appendChild($IPI);
				
				
				switch($DadosIPI->CST){
					//Grupo do IPITrib CST 00, 49, 50 e 99 0 ou 1 [IPI]
					default:	

						$IPITrib = $dom->createElement("IPITrib");
						$CST = $dom->createElement("CST", $DadosIPI->CST);
						$IPITrib->appendChild($CST);
						
						if (!empty($DadosIPI->vIPI)) {
						$vIPI = $dom->createElement("vIPI", $DadosIPI->vIPI);
						$IPITrib->appendChild($vIPI);
						
						}
						$IPI->appendChild($IPITrib);
						if (!empty($DadosIPI->vBC)) {
						$vBC = $dom->createElement("vBC", $DadosIPI->vBC);
						$IPITrib->insertBefore($IPITrib->appendChild($vBC), $vIPI);
						}
						if (!empty($DadosIPI->vIPI)) {
						$pIPI = $dom->createElement("pIPI", $DadosIPI->pIPI);
						$IPITrib->insertBefore($IPITrib->appendChild($pIPI), $vIPI);
						}
						if (!empty($DadosIPI->qSelo)) {
						$qUnid = $dom->createElement("qUnid", $DadosIPI->qUnid);
						$IPITrib->insertBefore($IPITrib->appendChild($qUnid), $vIPI);
						}
						if (!empty($DadosIPI->qSelo)) {
						$vUnid = $dom->createElement("vUnid", $DadosIPI->vUnid);
						$IPITrib->insertBefore($IPITrib->appendChild($vUnid), $vIPI);
						}
						
					break;
					
					case '01':
					case '02':
					case '03':
					case '04':
					case '1':
					case '2':
					case '3':
					case '4':
					case '51':
					case '52':
					case '53':
					case '54':
					case '55':
						$IPINT = $dom->createElement("IPINT");
						$CST = $dom->createElement("CST", $DadosIPI->CST);
						$IPINT->appendChild($CST);
						$IPI->appendChild($IPINT);
						
					break;
				}
			
				
				
				/**
				 * PIS
				 */
				
				$db9 = new Erp_Model_Faturamento_NFe_ProdutosPIS();
				$dadosPis = $db9->fetchRow("id_produto_nfe = '{$DadosProduto->id_registro}'");
				
				$PIS = $dom->createElement("PIS");
				$imposto->appendChild($PIS);
				
				if(!empty($dadosPis->pPIS)){
					$PISAliq = $dom->createElement("PISAliq");
					$CST = $dom->createElement("CST",$dadosPis->CST);
					$PISAliq->appendChild($CST);
					$vBC = $dom->createElement("vBC", $dadosPis->vBC);
					$PISAliq->appendChild($vBC);
					$pPIS = $dom->createElement("pPIS", $dadosPis->pPIS);
					$PISAliq->appendChild($pPIS);
					$vPIS = $dom->createElement("vPIS", $dadosPis->vPIS);
					$PISAliq->appendChild($vPIS);
					$PIS->appendChild($PISAliq);
				}elseif(!empty($dadosPis->qBCProd)){
					
					$PISQtde = $dom->createElement("PISQtde");
					$CST = $dom->createElement("CST", $dadosPis->CST);
					$PISQtde->appendChild($CST);
					$qBCProd = $dom->createElement("qBCProd", $dadosPis->qBCProd);
					$PISQtde->appendChild($qBCProd);
					$vAliqProd = $dom->createElement("vAliqProd", $dadosPis->vAliqProd);
					$PISQtde->appendChild($vAliqProd);
					$vPIS = $dom->createElement("vPIS", $dadosPis->vPIS);
					$PISQtde->appendChild($vPIS);
					$PIS->appendChild($PISQtde);
					
				}else{
					
					$PISNT = $dom->createElement("PISNT");
					$CST = $dom->createElement("CST", $dadosPis->CST);
					$PISNT->appendChild($CST);
					$PIS->appendChild($PISNT);
					
				}
				
				
				
				
				/**
				 * COFINS
				 */
				
				$db10 = new Erp_Model_Faturamento_NFe_ProdutosCOFINS();
				$dadosCOFINS = $db10->fetchRow("id_produto_nfe = '{$DadosProduto->id_registro}'");
							
				
				$COFINS = $dom->createElement("COFINS");
				$imposto->appendChild($COFINS);
				

				if(!empty($dadosCOFINS->pCOFINS)){
					
					$COFINSAliq = $dom->createElement("COFINSAliq");
					$CST = $dom->createElement("CST", $dadosCOFINS->CST);
					$COFINSAliq->appendChild($CST);
					$vBC = $dom->createElement("vBC", $dadosCOFINS->vBC);
					$COFINSAliq->appendChild($vBC);
					$pCOFINS = $dom->createElement("pCOFINS", $dadosCOFINS->pCOFINS);
					$COFINSAliq->appendChild($pCOFINS);
					$vCOFINS = $dom->createElement("vCOFINS", $dadosCOFINS->vCOFINS);
					$COFINSAliq->appendChild($vCOFINS);
					$COFINS->appendChild($COFINSAliq);
					
				}elseif($dadosCOFINS->qBCProd){
					
					$COFINSQtde = $dom->createElement("COFINSQtde");
					$CST = $dom->createElement("CST", $dadosCOFINS->CST);
					$COFINSQtde->appendChild($CST);
					$qBCProd = $dom->createElement("qBCProd", $dadosCOFINS->qBCProd);
					$COFINSQtde->appendChild($qBCProd);
					$vAliqProd = $dom->createElement("vAliqProd", $dadosCOFINS->vAliqProd);
					$COFINSQtde->appendChild($vAliqProd);
					$vCOFINS = $dom->createElement("vCOFINS", $dadosCOFINS->vCOFINS);
					$COFINSQtde->appendChild($vCOFINS);
					$COFINS->appendChild($COFINSQtde);
					
				}else{
					
					$COFINSNT = $dom->createElement("COFINSNT");
					$CST = $dom->createElement("CST", $dadosCOFINS->CST);
					$COFINSNT->appendChild($CST);
					$COFINS->appendChild($COFINSNT);
				
				}
				
		}

		
		/**
		 * Totais da NFE
		 */
		
		$db11 = new Erp_Model_Faturamento_NFe_Totais();
		$dadosTotais = $db11->fetchRow("id_nfe = '$id'");
		if($dadosTotais->vDesc > 0){
		    $TDesc =$dadosTotais->vDesc;
		}else{
			$TDesc = '0';
		}
		
		$total = $dom->createElement("total");
		$infNFe->appendChild($total);
		$ICMSTot = $dom->createElement("ICMSTot");
		$vBC = $dom->createElement("vBC", $dadosTotais->vBC);
		$ICMSTot->appendChild($vBC);
		$vICMS = $dom->createElement("vICMS", $dadosTotais->vICMS);
		$ICMSTot->appendChild($vICMS);
		$vBCST = $dom->createElement("vBCST", $dadosTotais->vBCST);
		$ICMSTot->appendChild($vBCST);
		$vST = $dom->createElement("vST",$dadosTotais->vST);
		$ICMSTot->appendChild($vST);
		$vProd = $dom->createElement("vProd", $dadosTotais->vProd);
		$ICMSTot->appendChild($vProd);
		$vFrete = $dom->createElement("vFrete", $dadosTotais->vFrete);
		$ICMSTot->appendChild($vFrete);
		$vSeg = $dom->createElement("vSeg", $dadosTotais->vSeg);
		$ICMSTot->appendChild($vSeg);
		$vDesc = $dom->createElement("vDesc",$TDesc);
		$ICMSTot->appendChild($vDesc);
		$vII = $dom->createElement("vII", $dadosTotais->vII);
		$ICMSTot->appendChild($vII);
		$vIPI = $dom->createElement("vIPI", $dadosTotais->vIPI);
		$ICMSTot->appendChild($vIPI);
		$vPIS = $dom->createElement("vPIS", $dadosTotais->vPIS);
		$ICMSTot->appendChild($vPIS);
		$vCOFINS = $dom->createElement("vCOFINS", $dadosTotais->vCOFINS);
		$ICMSTot->appendChild($vCOFINS);
		$vOutro = $dom->createElement("vOutro", $dadosTotais->vOutro);
		$ICMSTot->appendChild($vOutro);
		$vNF = $dom->createElement("vNF", $dadosTotais->vNF);
		$ICMSTot->appendChild($vNF);
		//Lei da Transparencia
		$TotalTrib = number_format($dadosTotais->vICMS + $dadosTotais->vST + $dadosTotais->vIPI + $dadosTotais->vPIS +$dadosTotais->vCOFINS,2,'.','');
		$vTotTrib = $dom->createElement("vTotTrib", $TotalTrib);
		$ICMSTot->appendChild($vTotTrib);
		
		$total->appendChild($ICMSTot);
		
		
		/**
		 * Transporte
		 */
		
		$transp = $dom->createElement("transp");
		$modFrete = $dom->createElement("modFrete", $dadosBasicos->modFrete);
		$transp->appendChild($modFrete);
		$infNFe->appendChild($transp);
		
		/**
		 * TRANSORTADORA
		 */
		
		$db12 = new Erp_Model_Faturamento_NFe_Transportadora();
		$dadosTransp = $db12->fetchRow("id_nfe = '$id'");
		
		if($dadosTransp->id_registro <> ''){

			$transporta = $dom->createElement("transporta");
			if (!empty($dadosTransp->xNome)) {
				$xNome = $dom->createElement("xNome", $dadosTransp->xNome);
				$transporta->appendChild($xNome);
			}
			if (!empty($dadosTransp->IE)) {
				$IE = $dom->createElement("IE", $dadosTransp->IE);
				$transporta->appendChild($IE);
			}
			if (!empty($dadosTransp->xEnder)) {
				$xEnder = $dom->createElement("xEnder", $dadosTransp->xEnder);
				$transporta->appendChild($xEnder);
			}
			if (!empty($dadosTransp->xMun)) {
				$xMun = $dom->createElement("xMun", $dadosTransp->xMun);
				$transporta->appendChild($xMun);
			}
			if (!empty($dadosTransp->UF)) {
				$UF = $dom->createElement("UF", $dadosTransp->UF);
				$transporta->appendChild($UF);
			}
			$transp->appendChild($transporta);
			
			if (!empty($dadosTransp->CNPJ)) {
				$CNPJ = $dom->createElement("CNPJ", $dadosTransp->CNPJ);
				$transporta->insertBefore($transporta->appendChild($CNPJ), $xNome);
			}
			
			if (!empty($dadosTransp->CPF)) {
				$CPF = $dom->createElement("CPF", $dadosTransp->CPF);
				$transporta->insertBefore($transporta->appendChild($CPF), $xNome);
			}
			
		}
		
		/**
		 * Veiculo de Transporte
		 */
		
		$db13 = new Erp_Model_Faturamento_NFe_TransportadoraVeiculo();
		$dadosVeic = $db13->fetchRow("id_transportadora = '{$dadosTransp->id_registro}'");
		if($dadosVeic->placa <> ''){
			$veicTransp = $dom->createElement("veicTransp");
			$placa = $dom->createElement("placa", $dadosVeic->placa);
			$veicTransp->appendChild($placa);
			$UF = $dom->createElement("UF", $dadosVeic->UF);
			$veicTransp->appendChild($UF);
			if (!empty($dadosVeic->RNTC)) {
				$RNTC = $dom->createElement("RNTC", $dadosVeic->RNTC);
				$veicTransp->appendChild($RNTC);
			}
			$transp->appendChild($veicTransp);
		}
		
		
		/**
		 * Volumes
		 */
		
		$db14 = new Erp_Model_Faturamento_NFe_Volumes();
		$dadosVolumes = $db14->fetchRow("id_nfe = '$id'");
		if($dadosVolumes->qVol <> ''){
			
		
			$vol = $dom->createElement("vol");
			$qVol = $dom->createElement("qVol", $dadosVolumes->qVol);
			$vol->appendChild($qVol);
			
			if (!empty($dadosVolumes->esp)) {
				$esp = $dom->createElement("esp", $dadosVolumes->esp);
				$vol->appendChild($esp);
			}
			if (!empty($dadosVolumes->marca)) {
				$marca = $dom->createElement("marca", $dadosVolumes->marca);
				$vol->appendChild($marca);
			}
			if (!empty($dadosVolumes->nVol)) {
				$nVol = $dom->createElement("nVol", $dadosVolumes->nVol);
				$vol->appendChild($nVol);
			}
			if (!empty($dadosVolumes->pesoL)) {
				$pesoL = $dom->createElement("pesoL", $dadosVolumes->pesoL);
				$vol->appendChild($pesoL);
			}
			if (!empty($dadosVolumes->pesoB)) {
				$pesoB = $dom->createElement("pesoB", $dadosVolumes->pesoB);
				$vol->appendChild($pesoB);
			}
			$transp->appendChild($vol);
		}

		
		/**
		 * Cobranca
		 */
		
		$cobr = $dom->createElement("cobr");
		$infNFe->appendChild($cobr);
		
		$db15 = new Erp_Model_Faturamento_NFe_Fatura();
		$DadosFta = $db15->fetchRow("id_nfe = '$id'");
		
		if($DadosFta->vDesc > 0){
			$DescFat = $DadosFta->vDesc;
		}else{
			$DescFat = 0;
		}
		
		$fat = $dom->createElement("fat");
		if (!empty($DadosFta->nFat)) {
			$nFat = $dom->createElement("nFat", $DadosFta->nFat);
			$fat->appendChild($nFat);
		}
		if (!empty($DadosFta->vOrig)) {
			$vOrig = $dom->createElement("vOrig", $DadosFta->vOrig);
			$fat->appendChild($vOrig);
		}
		if (!empty($DadosFta->vDesc) && $DadosFta->vDesc > 0) {
			$vDesc = $dom->createElement("vDesc", $DescFat);
			$fat->appendChild($vDesc);
		}
		if (!empty($DadosFta->vLiq)) {
			$vLiq = $dom->createElement("vLiq",$DadosFta->vLiq);
			$fat->appendChild($vLiq);
		}
		$cobr->appendChild($fat);
		
		$db16 = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
		$Duplicatas = $db16->fetchAll("id_nfe = '$id'");
		foreach($Duplicatas as $DadosDup){
			
			if (!isset($cobr)) {
				$cobr = $dom->createElement("cobr");
				$infNFe->appendChild($cobr);
			}
			$dup = $dom->createElement("dup");
			if (!empty($DadosDup->nDup)) {
				$nDup = $dom->createElement("nDup", $DadosDup->nDup);
				$dup->appendChild($nDup);
			}
			if (!empty($DadosDup->dVenc)) {
				$dVenc = $dom->createElement("dVenc", $DadosDup->dVenc);
				$dup->appendChild($dVenc);
			}
			if (!empty($DadosDup->vDup)) {
				$vDup = $dom->createElement("vDup", $DadosDup->vDup);
				$dup->appendChild($vDup);
			}
			$cobr->appendChild($dup);
			
		}
		
		/**
		 * INFORMACOES ADICIONAIS
		 */
		$db17 = new Erp_Model_Faturamento_NFe_Observacoes();
		$ObsNFe = $db17->fetchRow("id_nfe = '$id'");
		
		$infAdic = $dom->createElement("infAdic");
		if (!empty($ObsNFe->infAdFisco)) {
			$infAdFisco = $dom->createElement("infAdFisco", $ObsNFe->infAdFisco);
			$infAdic->appendChild($infAdFisco);
		}
		if (!empty($ObsNFe->infCpl)) {
			$infCpl = $dom->createElement("infCpl", $ObsNFe->infCpl);
			$infAdic->appendChild($infCpl);
		}
		$infNFe->appendChild($infAdic);
		
		/**
		 * Outras Observacoes
		 */
		
		$db18 = new Erp_Model_Faturamento_NFe_ObservacoesAdicionais();
		$OutrasOb = $db18->fetchAll("id_nfe = '$id'");
		
		foreach($OutrasOb as $ObservAdic){
			$obsCont = $dom->createElement("obsCont");
			$obsCont->setAttribute("xCampo", $ObservAdic->xCampo);
			$xTexto = $dom->createElement("xTexto",$ObservAdic->xTexto);
			$obsCont->appendChild($xTexto);
			$infAdic->appendChild($obsCont);
		}
		
		
		$NFe->appendChild($infNFe);
		$dom->appendChild($NFe);
		
		$xml = $dom->saveXML();
		$xml = str_replace('<?xml version="1.0" encoding="UTF-8  standalone="no"?>', '<?xml version="1.0" encoding="UTF-8"?>', $xml);
		//remove linefeed, carriage return, tabs e multiplos espaços
		$xml = preg_replace('/\s\s+/', ' ', $xml);
		$xml = str_replace("> <", "><", $xml);
		
		return $xml;
				
		
	}	
	
	
	
	
	
	
	
	public static function calculaDV($chave43)
	{
		$multiplicadores = array(2, 3, 4, 5, 6, 7, 8, 9);
		$i = 42;
		$soma_ponderada = 0;
		while ($i >= 0) {
			for ($m = 0; $m < count($multiplicadores) && $i >= 0; $m++) {
				$soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
				$i--;
			}
		}
		$resto = $soma_ponderada % 11;
		if ($resto == '0' || $resto == '1') {
			$cDV = 0;
		} else {
			$cDV = 11 - $resto;
		}
		return $cDV;
	}
	
	
	
	public static function montaChaveXML($id)
	{
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		 
		$cUF = $dados['cUF']; 
		$AAMM = $dados['AAMM']; 
		$CNPJ = $dados['CNPJ']; 
		$mod = $dados['mod'];
		$serie =  str_pad($dados['serie'],3,'0', STR_PAD_LEFT);
		$nNF =  str_pad($dados['nNF'], 9, "0", STR_PAD_LEFT);
		$tpEmis = $dados['tpEmis'];
		$cNF =  $dados['cNF'];
		if (strlen($cNF) != 8) {
			$cNF = rand(10000001, 99999999);
		}
		$tempData = $dt = explode("-", $dEmi);
		$forma = "%02d%02d%s%02d%03d%09d%01d%08d";
		$tempChave = sprintf($forma, $cUF, $AAMM, $CNPJ, $mod, $serie, $nNF, $tpEmis, $cNF);
		$cDV = Functions_NFe::calculaDV($tempChave);
		$chave = $tempChave .= $cDV;
		
		$dados = array('Chave'=>$chave,'DV'=>$cDV);
		
		return $dados;
	}
	
	
	
	
	
	
	private function limpaString($texto)
	{
		$aFind = array('&', 'á', 'à', 'ã', 'â', 'é', 'ê',
				'í', 'ó', 'ô', 'õ', 'ú', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â',
				'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ', 'Ú', 'Ü', 'Ç');
		$aSubs = array('e', 'a', 'a', 'a', 'a', 'e', 'e',
				'i', 'o', 'o', 'o', 'u', 'u', 'c', 'A', 'A', 'A', 'A',
				'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C');
		$novoTexto = str_replace($aFind, $aSubs, $texto);
		$novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/_]/", "", $novoTexto);
		return $novoTexto;
	} //fim limpaString
	
	
	public static function limparString($texto)
	{
		$aFind = array('&', 'á', 'à', 'ã', 'â', 'é', 'ê',
				'í', 'ó', 'ô', 'õ', 'ú', 'ü', 'ç', 'Á', 'À', 'Ã', 'Â',
				'É', 'Ê', 'Í', 'Ó', 'Ô', 'Õ', 'Ú', 'Ü', 'Ç');
		$aSubs = array('e', 'a', 'a', 'a', 'a', 'e', 'e',
				'i', 'o', 'o', 'o', 'u', 'u', 'c', 'A', 'A', 'A', 'A',
				'E', 'E', 'I', 'O', 'O', 'O', 'U', 'U', 'C');
		$novoTexto = str_replace($aFind, $aSubs, $texto);
		$novoTexto = preg_replace("/[^a-zA-Z0-9 @,-.;:\/_]/", "", $novoTexto);
		return $novoTexto;
	} //fim limpaString
	
	public static function limpaTel($tel){
		

		$aFind = array('(',')','-','.',' ','/');
		$aSubs = array('','','','','','');
		$novoTexto = str_replace($aFind, $aSubs, $tel);
		return $novoTexto;
			
	}
	
	public static function limpaCNPJCFP($string){
		
		$aFind = array('.','-','/',',',' ','\\');
		$aSubs = array('','','','','','');
		$novoTexto = str_replace($aFind, $aSubs, $string);
		return $novoTexto;
		
	}
	
	
	public static function limpaIE($string){
	
		$aFind = array('.','-','/',',',' ','\\');
		$aSubs = array('','','','','','');
		$novoTexto = str_replace($aFind, $aSubs, $string);
		return $novoTexto;
	
	}
	

	public static function limpaIM($string){
	
		$aFind = array('.','-','/',',',' ','\\');
		$aSubs = array('','','','','','');
		$novoTexto = str_replace($aFind, $aSubs, $string);
		return $novoTexto;
	
	}
	
	
	public static function processaNFeAprovada($id){
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '$id'");
		$tpNF = $DadosNFe->tpNF;
		$Empresa = $DadosNFe->id_empresa;
		$pedVenda = $DadosNFe->id_pedvenda;
		if($DadosNFe->processoNFeAprovada <> 1 || !$DadosNFe->processoNFeAprovada){	

			$DBDest = new Erp_Model_Faturamento_NFe_Destinatario();
			$DadosDest = $DBDest->fetchRow("id_nfe = '$id'");
			$dadosDestOutros = Cadastros_Model_Outros::getCadastro($DadosDest->id_pessoa);
			
		/** 
		 * Muda o Numero da Ultima NFe
		 */
		
		System_Model_EmpresasNF::addNFe($DadosNFe->id_empresa);
		
		
		/**
		 * Processa Itens de Faturamento
		 */
		
		$DBFatura = new Erp_Model_Faturamento_NFe_Fatura();
		$DadosFatura = $DBFatura->fetchRow("id_nfe = '$id'");
		if($DadosFatura->id_registro <> ''){
			
			$DBDuplicatas = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
			$DadosDuplicatas = $DBDuplicatas->fetchAll("id_nfe = '$id'");
			$QuantidadedeParcelas = count($DadosDuplicatas->toArray());
			$DadosDuplicatasArray = $DadosDuplicatas->toArray();
			
			if($tpNF == 1){
				$Finan = new Erp_Model_Financeiro_DadosRecebimentos();
				$Lanc = new Erp_Model_Financeiro_LancamentosRecebimentos();
			
			}else{
				$Finan = new Erp_Model_Financeiro_DadosPagamentos();
				$Lanc = new Erp_Model_Financeiro_LancamentosPagamentos();
			}
			if($QuantidadedeParcelas > 0){
			
			$data1 = array("id_pessoa"=>$DadosDest->id_pessoa,
					'id_empresa'=>$DadosNFe->id_empresa,
					'tiporegistro'=>'NFe',
					'id_registro_vinculado'=>$id,
					'datacadastro'=>$DadosNFe->dEmi,
					'user_id'=>$userInfo->id_registro,
					'totalgeral'=>$DadosFatura->vLiq,
					'primeirovencimento'=>$DadosDuplicatasArray[0]['dVenc'],
					'ultimovencimento'=>$DadosDuplicatasArray[$QuantidadedeParcelas - 1]['dVenc'],
					'totalparcelas'=>$QuantidadedeParcelas,
					'parcelaspagas'=>0,
					'parcelasavencer'=>$QuantidadedeParcelas,
					'totalpago'=>0,
					'nomelancamento'=>"(NFe: {$DadosNFe->nNF}) - {$DadosDest->xNome}",
					'totalapagar'=> $DadosFatura->vLiq,
					'statuslancamento'=>'1',
					'contapadrao'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
					'categorialanc'=>$dadosDestOutros->planodecontas,
					'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
					'numerodocumento'=>$DadosFatura->nFat,
			);
			}else{
				$data1 = array("id_pessoa"=>$DadosDest->id_pessoa,
						'id_empresa'=>$DadosNFe->id_empresa,
						'tiporegistro'=>'NFe',
						'id_registro_vinculado'=>$id,
						'datacadastro'=>$DadosNFe->dEmi,
						'user_id'=>$userInfo->id_registro,
						'totalgeral'=>$DadosFatura->vLiq,
						'primeirovencimento'=>date('Y-m-d'),
						'ultimovencimento'=>date('Y-m-d'),
						'totalparcelas'=>1,
						'parcelaspagas'=>0,
						'parcelasavencer'=>1,
						'totalpago'=>0,
						'nomelancamento'=>"(NFe: {$DadosNFe->nNF}) - {$DadosDest->xNome}",
						'totalapagar'=> $DadosFatura->vLiq,
						'statuslancamento'=>'1',
						'contapadrao'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
						'categorialanc'=>$dadosDestOutros->planodecontas,
						'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
						'numerodocumento'=>$DadosFatura->nFat,
			);
				
			};
			$id_lancamento = $Finan->insert($data1);
			$DBFatura->update(array("id_lancamento"=>$id_lancamento),"id_nfe = '$id'");
			if($QuantidadedeParcelas > 0){
			foreach($DadosDuplicatas as $Dupl){
				$Par = 1;
				$data2 = array('id_lancamento'=>$id_lancamento,
						'datavencimento'=>$Dupl->dVenc,
						'valororiginal'=>$Dupl->vDup,
						'numeroparcela'=>$Par,
						'quantidadeparcelas'=>$QuantidadedeParcelas,
						'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
						'numerodocumento'=>$Dupl->nDup,
						'statuslancamento'=>'1',
						'id_banco'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
						'user_liberacao'=>'0',
						'id_ped_venda_produto'=> implode(',', Erp_Model_Faturamento_NFe_Produtos::getIDProdVenda($id))
				);
				$id_destelanc = $Lanc->insert($data2);
				$Par + 1;
				$DBDuplicatas->update(array('id_registro_recebimentos'=>$id_destelanc),"id_registro = '{$Dupl->id_registro}'");
			}
			}else{
			
				$data2 = array('id_lancamento'=>$id_lancamento,
						'datavencimento'=>date('Y-m-d'),
						'valororiginal'=>$DadosFatura->vLiq,
						'numeroparcela'=>1,
						'quantidadeparcelas'=>1,
						'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
						'numerodocumento'=>$DadosFatura->nFat,
						'statuslancamento'=>'1',
						'id_banco'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
						'user_liberacao'=>'0',
						'id_ped_venda_produto'=> implode(',', Erp_Model_Faturamento_NFe_Produtos::getIDProdVenda($id))
				);
				$id_destelanc = $Lanc->insert($data2);
			
				//$DBDuplicatas->update(array('id_registro_recebimentos'=>$id_destelanc),"id_registro = '{$Dupl->id_registro}'");
			}
			
			
			
		}
			
			
		/**
		 * Processa Itens de Estoque
		 */
		
		$DBProdutosNFe = new Erp_Model_Faturamento_NFe_Produtos();
		$DadosProdNFe = $DBProdutosNFe->fetchAll("id_nfe = '$id'");
		
		$DBProdVenda = new Erp_Model_Vendas_Produtos();
		$DBEstoque = new Erp_Model_Estoque_Movimento();
		
		foreach($DadosProdNFe as $ProdNFe){
			if($tpNF == 1){
				
				$DVenda = Erp_Model_Vendas_Produtos::getDados($ProdNFe->id_prod_venda);
				$novaQtdaFat = $DVenda->qtd_afaturar - $ProdNFe->qCom;
				$novaQtdFat =  $DVenda->qtd_faturada + $ProdNFe->qCom;
				
				$DBProdVenda->update(array('qtd_faturada'=>$novaQtdFat,
				'qtd_afaturar'=>$novaQtdaFat),"id_registro = '{$ProdNFe->id_prod_venda}'");
				
				if(System_Model_SysConfigs::getConfig("MovEstoqueVendaNFe") == 1){
					$datainsertEstoque = array('id_produto'=>$ProdNFe->id_produto,
							'id_estoque'=>System_Model_SysConfigs::getConfig("EstoquePadraoSaidaNFe"),
							'quantidade'=>"-".$ProdNFe->qCom,
							'historico'=>"Movimento do Processo de Venda por NFe: {$DadosNFe->nNF}",
							'usuario'=>$userInfo->id_registro,
							'data'=>date('Y-m-d H:i:s')
					);
					
					$id_mov_est = $DBEstoque->insert($datainsertEstoque);
					$DBProdutosNFe->update(array('id_reg_mov_estoque'=>$id_mov_est),"id_registro = '{$ProdNFe->id_registro}'");
				}
				
				}else{

					if(System_Model_SysConfigs::getConfig("MovEstoqueVendaNFe") == 1){
						$datainsertEstoque = array('id_produto'=>$ProdNFe->id_produto,
								'id_estoque'=>System_Model_SysConfigs::getConfig("EstoquePadraoSaidaNFe"),
								'quantidade'=>$ProdNFe->qCom,
								'historico'=>"Movimento do Processo de nota de entrada NFe: {$DadosNFe->nNF}",
								'usuario'=>$userInfo->id_registro,
								'data'=>date('Y-m-d H:i:s')
						);
					
						$id_mov_est = $DBEstoque->insert($datainsertEstoque);
						$DBProdutosNFe->update(array('id_reg_mov_estoque'=>$id_mov_est),"id_registro = '{$ProdNFe->id_registro}'");
				}
				
				
			}
			
						
			
			
		}
		
		
		
		if($DadosNFe->id_pedvenda <> '' && $DadosNFe->id_pedvenda > 0){
		Functions_NFe::processaPedidosNFe($id);
		}
		
		/**
		 * Envia Email da NFe ao cliente
		 */
				
		$DadosConfig = System_Model_EmpresasNF::getDadosConfigNFe($DadosNFe->id_empresa);
		
		if($DadosConfig->sendemailtocliente == 1){
			Functions_Email::SendNFeEmail($id,$DadosNFe->id_empresa);
		}
		
		
		
		/**
		 * Envia Email da NFe ao contador
		 */
		if($DadosConfig->sendtocontabil == 1){
			Functions_Email::SendNFeEmailContabil($id,$DadosNFe->id_empresa);
		}
		
		
		$db->update(array('processoNFeAprovada'=>1),"id_registro = '$id'");
		
		return true;
		}
		
		return false;
		
	}
	
	public static function processaNFeCancelada($id){
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '$id'");
		$tpNF = $DadosNFe->tpNF;
		$Empresa = $DadosNFe->id_empresa;
		$pedVenda = $DadosNFe->id_pedvenda;
		
		
		if($DadosNFe->processoNFeAprovada <> 2 || !$DadosNFe->processoNFeAprovada){
			
			$db->update(array("status_processo"=>'6'), "id_registro = '$id'");
		
			$DBDest = new Erp_Model_Faturamento_NFe_Destinatario();
			$DadosDest = $DBDest->fetchRow("id_nfe = '$id'");
			$dadosDestOutros = Cadastros_Model_Outros::getCadastro($DadosDest->id_pessoa);
				
			
		
			$DBFatura = new Erp_Model_Faturamento_NFe_Fatura();
			$DadosFatura = $DBFatura->fetchRow("id_nfe = '$id'");
			if($DadosFatura->id_registro <> ''){
					
				$DBDuplicatas = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
				$DadosDuplicatas = $DBDuplicatas->fetchAll("id_nfe = '$id'");
				$QuantidadedeParcelas = count($DadosDuplicatas->toArray());
				$DadosDuplicatasArray = $DadosDuplicatas->toArray();
					
				if($tpNF == 1){
					$Finan = new Erp_Model_Financeiro_DadosRecebimentos();
					$Lanc = new Erp_Model_Financeiro_LancamentosRecebimentos();
						
				}else{
					$Finan = new Erp_Model_Financeiro_DadosPagamentos();
					$Lanc = new Erp_Model_Financeiro_LancamentosPagamentos();
				}
					
				
				 $Finan->delete("id_registro = '{$DadosFatura->id_lancamento}'");
				
					
				foreach($DadosDuplicatas as $Dupl){
					$Lanc->delete("id_registro = '{$Dupl->id_registro_recebimentos}'");
					
				}
					
					
					
			}
				
				
			/**
			 * Processa Itens de Estoque
			 */
		
			$DBProdutosNFe = new Erp_Model_Faturamento_NFe_Produtos();
			$DadosProdNFe = $DBProdutosNFe->fetchAll("id_nfe = '$id'");
		
			$DBProdVenda = new Erp_Model_Vendas_Produtos();
			$DBEstoque = new Erp_Model_Estoque_Movimento();
		
			foreach($DadosProdNFe as $ProdNFe){
				if($tpNF == 1){
		
					$DVenda = Erp_Model_Vendas_Produtos::getDados($ProdNFe->id_prod_venda);
					$novaQtdaFat = $DVenda->qtd_afaturar + $ProdNFe->qCom;
					$novaQtdFat =  $DVenda->qtd_faturada - $ProdNFe->qCom;
		
					$DBProdVenda->update(array('qtd_faturada'=>$novaQtdFat,
							'qtd_afaturar'=>$novaQtdaFat),"id_registro = '{$ProdNFe->id_prod_venda}'");
		
					if(System_Model_SysConfigs::getConfig("MovEstoqueVendaNFe") == 1){
						$datainsertEstoque = array('id_produto'=>$ProdNFe->id_produto,
								'id_estoque'=>System_Model_SysConfigs::getConfig("EstoquePadraoSaidaNFe"),
								'quantidade'=>$ProdNFe->qCom,
								'historico'=>"Movimento do Processo de Cancelamento de NFe: {$DadosNFe->nNF}",
								'usuario'=>$userInfo->id_registro,
								'data'=>date('Y-m-d H:i:s')
						);
							
						$id_mov_est = $DBEstoque->insert($datainsertEstoque);
						$DBProdutosNFe->update(array('id_reg_mov_estoque'=>$id_mov_est),"id_registro = '{$ProdNFe->id_registro}'");
					}
		
				}else{
		
					if(System_Model_SysConfigs::getConfig("MovEstoqueVendaNFe") == 1){
						$datainsertEstoque = array('id_produto'=>$ProdNFe->id_produto,
								'id_estoque'=>System_Model_SysConfigs::getConfig("EstoquePadraoSaidaNFe"),
								'quantidade'=>"-".$ProdNFe->qCom,
								'historico'=>"Movimento do Processo de cancelamento de nota de entrada NFe: {$DadosNFe->nNF}",
								'usuario'=>$userInfo->id_registro,
								'data'=>date('Y-m-d H:i:s')
						);
							
						$id_mov_est = $DBEstoque->insert($datainsertEstoque);
						$DBProdutosNFe->update(array('id_reg_mov_estoque'=>$id_mov_est),"id_registro = '{$ProdNFe->id_registro}'");
					}
		
		
				}
					
		
					
					
			}
		
		if($DadosNFe->id_pedvenda <> ''){
			Functions_NFe::processaPedidosNFe($id);
		}
		
			/**
			 * Envia Email da NFe ao cliente
			 */
		
			$DadosConfig = System_Model_EmpresasNF::getDadosConfigNFe($DadosNFe->id_empresa);
		
			if($DadosConfig->sendemailtocliente == 1){
				Functions_Email::SendNFeEmail($id,$DadosNFe->id_empresa);
			}
		
		
		
			/**
			 * Envia Email da NFe ao contador
			 */
			if($DadosConfig->sendtocontabil == 1){
				Functions_Email::SendNFeEmailContabil($id,$DadosNFe->id_empresa);
			}
		
		
			$db->update(array('processoNFeAprovada'=>2),"id_registro = '$id'");
		
			return true;
		}
		
		return false;
		
		
		
	}
	
	public static function processaPedidosNFe($id){
		$db = new Erp_Model_Faturamento_NFe_Produtos();
		$NFeProdutos = $db->fetchRow("id_nfe = '$id'");
	//	print_r($NFeProdutos);
		//echo $NFeProdutos->id_prod_venda;
		
			if($NFeProdutos->id_prod_venda <> ''){
				$db2 = new Erp_Model_Vendas_Produtos();
				$dadosVendaProd = $db2->fetchRow("id_registro = '{$NFeProdutos->id_prod_venda}'");
				$dbvenda = new Erp_Model_Vendas_Basicos;
				
			//	echo $dadosVendaProd->id_venda;

				if(!Erp_Model_Vendas_Produtos::faltaFaturar($dadosVendaProd->id_venda)){
					$dbvenda->update(array("statusfaturamento"=>'3'),"id_registro = '{$dadosVendaProd->id_venda}' ");
					
				}else{
					$dbvenda->update(array("statusfaturamento"=>'0'),"id_registro = '{$dadosVendaProd->id_venda}' ");
				}
				
				
				
				
			}
			
		
		
		
	}
	
	public static function checkHash($filename){

		$docxml = file_get_contents($filename);
		// carrega o documento no DOM
		$xmldoc = new DOMDocument();
		$xmldoc->preservWhiteSpace = false; //elimina espaços em branco
		$xmldoc->formatOutput = false;
		// muito importante deixar ativadas as opçoes para limpar os espacos em branco
		// e as tags vazias
		$xmldoc->loadXML($docxml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
		//extrair a tag com os dados a serem assinados
		$node = $xmldoc->getElementsByTagName('infNFe')->item(0);
		$digInfo = $xmldoc->getElementsByTagName('DigestValue')->item(0)->nodeValue;
		//extrai os dados da tag para uma string
		$dados = $node->C14N(false,false,NULL,NULL);
			
		$hashValue = hash('sha1',$dados,true);
			
		$digValue = base64_encode($hashValue);
		if($digValue == $digInfo){
			return true;
		}else{
			return false;
		}
		
		
		
	}
	
	
	public static function processaXMLEntrada($file){
		
		$xml = file_get_contents($file);
		$doc = new DOMDocument();
		$doc->preservWhiteSpace = FALSE; //elimina espaços em branco
		$doc->formatOutput = FALSE;
		$doc->loadXML($xml,LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
		$infNFe = $doc->getElementsByTagName('infNFe')->item(0);
		$dados['versao']=trim($infNFe->getAttribute("versao"));
		$dados['chaveacesso']= substr(trim($infNFe->getAttribute("Id")),3);
		$dados['Id']= $infNFe->getAttribute("Id");
		$dados['cUF'] = $infNFe->getElementsByTagName('cUF')->item(0)->nodeValue;
		$dados['cNF'] = $infNFe->getElementsByTagName('cNF')->item(0)->nodeValue;
		$dados['natOp'] = $infNFe->getElementsByTagName('natOp')->item(0)->nodeValue;
		$dados['indPag'] = $infNFe->getElementsByTagName('indPag')->item(0)->nodeValue;
		$dados['mod'] = $infNFe->getElementsByTagName('mod')->item(0)->nodeValue;
		$dados['serie'] = $infNFe->getElementsByTagName('serie')->item(0)->nodeValue;
		$dados['nNF'] = $infNFe->getElementsByTagName('nNF')->item(0)->nodeValue;
		$dados['dEmi'] = $infNFe->getElementsByTagName('dEmi')->item(0)->nodeValue;
		$dados['dSaiEnt'] = $infNFe->getElementsByTagName('dSaiEnt')->item(0)->nodeValue;
		$dados['hSaiEnt'] = $infNFe->getElementsByTagName('hSaiEnt')->item(0)->nodeValue;
		$dados['tpNF'] = $infNFe->getElementsByTagName('tpNF')->item(0)->nodeValue;
		$dados['cMunFG'] = $infNFe->getElementsByTagName('cMunFG')->item(0)->nodeValue;
		$dados['tpImp'] = $infNFe->getElementsByTagName('tpImp')->item(0)->nodeValue;
		$dados['tpEmis'] = $infNFe->getElementsByTagName('tpEmis')->item(0)->nodeValue;
		$dados['cDV'] = $infNFe->getElementsByTagName('cDV')->item(0)->nodeValue;
		$dados['tpAmb'] = $infNFe->getElementsByTagName('tpAmb')->item(0)->nodeValue;
		$dados['finNFe'] = $infNFe->getElementsByTagName('finNFe')->item(0)->nodeValue;
		$dados['procEmi'] = $infNFe->getElementsByTagName('procEmi')->item(0)->nodeValue;
		$dados['verProc'] = $infNFe->getElementsByTagName('verProc')->item(0)->nodeValue;
		$dados['AAMM'] = date('ym',strtotime($dados['dEmi']));
		$dados['processoNFeAprovada'] = '1';
		/**
		 * EMITENTE DA NFE
		 */
		
		$demi = $doc->getElementsByTagName('emit')->item(0);
		$emit['CNPJ'] = $demi->getElementsByTagName('CNPJ')->item(0)->nodeValue;
		$dados['CNPJ'] = $emit['CNPJ'];
		$emit['CNPJFormatado'] =  $c2=substr($emit['CNPJ'],0,2).".".substr($emit['CNPJ'],2,3).".".substr($emit['CNPJ'],5,3)."/".substr($emit['CNPJ'],8,4)."-".substr($emit['CNPJ'],12,2);
		$emit['xNome'] = $demi->getElementsByTagName('xNome')->item(0)->nodeValue;
		$emit['xFant'] = $demi->getElementsByTagName('xFant')->item(0)->nodeValue;
		$emit['nro'] = $demi->getElementsByTagName('nro')->item(0)->nodeValue;
		$emit['xCpl'] = $demi->getElementsByTagName('xCpl')->item(0)->nodeValue;
		$emit['xBairro'] = $demi->getElementsByTagName('xBairro')->item(0)->nodeValue;
		$emit['xLgr'] = $demi->getElementsByTagName('xLgr')->item(0)->nodeValue;
		$emit['cMun'] = $demi->getElementsByTagName('cMun')->item(0)->nodeValue;
		$emit['xMun'] = $demi->getElementsByTagName('xMun')->item(0)->nodeValue;
		$emit['UF'] = $demi->getElementsByTagName('UF')->item(0)->nodeValue;
		$emit['CEP'] = $demi->getElementsByTagName('CEP')->item(0)->nodeValue;
		$emit['cPais'] = $demi->getElementsByTagName('cPais')->item(0)->nodeValue;
		$emit['xPais'] = $demi->getElementsByTagName('xPais')->item(0)->nodeValue;
		$emit['IE'] = $demi->getElementsByTagName('IE')->item(0)->nodeValue;
		$emit['IEST'] = $demi->getElementsByTagName('IEST')->item(0)->nodeValue;
		$emit['CNAE'] = $demi->getElementsByTagName('CNAE')->item(0)->nodeValue;
		$emit['CRT'] = $demi->getElementsByTagName('CRT')->item(0)->nodeValue;
		$emit['fone'] = $demi->getElementsByTagName('fone')->item(0)->nodeValue;
		
		
		/**
		 * DESTINATARIO DA NFE
		 */
		$dDest = $doc->getElementsByTagName('dest')->item(0);
		
		$dest['CNPJ'] = $dDest->getElementsByTagName('CNPJ')->item(0)->nodeValue;
		$dest['CNPJFormatado'] =  $c2=substr($dest['CNPJ'],0,2).".".substr($dest['CNPJ'],2,3).".".substr($dest['CNPJ'],5,3)."/".substr($dest['CNPJ'],8,4)."-".substr($dest['CNPJ'],12,2);
		$dest['xNome'] = $dDest->getElementsByTagName('xNome')->item(0)->nodeValue;
		$dest['xFant'] = $dDest->getElementsByTagName('xFant')->item(0)->nodeValue;
		$dest['nro'] = $dDest->getElementsByTagName('nro')->item(0)->nodeValue;
		$dest['xCpl'] = $dDest->getElementsByTagName('xCpl')->item(0)->nodeValue;
		$dest['xBairro'] = $dDest->getElementsByTagName('xBairro')->item(0)->nodeValue;
		$dest['xLgr'] = $dDest->getElementsByTagName('xLgr')->item(0)->nodeValue;
		$dest['cMun'] = $dDest->getElementsByTagName('cMun')->item(0)->nodeValue;
		$dest['xMun'] = $dDest->getElementsByTagName('xMun')->item(0)->nodeValue;
		$dest['UF'] = $dDest->getElementsByTagName('UF')->item(0)->nodeValue;
		$dest['CEP'] = $dDest->getElementsByTagName('CEP')->item(0)->nodeValue;
		$dest['cPais'] = $dDest->getElementsByTagName('cPais')->item(0)->nodeValue;
		$dest['xPais'] = $dDest->getElementsByTagName('xPais')->item(0)->nodeValue;
		$dest['IE'] = $dDest->getElementsByTagName('IE')->item(0)->nodeValue;
		$dest['ISUF'] = $dDest->getElementsByTagName('ISUF')->item(0)->nodeValue;
		$dest['fone'] = $dDest->getElementsByTagName('fone')->item(0)->nodeValue;
		$dest['email'] = $dDest->getElementsByTagName('email')->item(0)->nodeValue;
		
		
		
		/**
		 * PRODUTOS DA NFe
		 */

		
		$det=$doc->getElementsByTagName('det');
		$itens="";
		for ($i = 0; $i < $det->length; $i++) {
			$item = $det->item($i);
			$s="";
			$s['nItem'] = $i + 1;
			$s['cProd']= $item->getElementsByTagName('cProd')->item(0)->nodeValue;
			$s['cEAN']= $item->getElementsByTagName('cEAN')->item(0)->nodeValue;
			$s['xProd']= $item->getElementsByTagName('xProd')->item(0)->nodeValue;
			$s['NCM']= $item->getElementsByTagName('NCM')->item(0)->nodeValue;
			$s['EXTIPI']= $item->getElementsByTagName('EXTIPI')->item(0)->nodeValue;
			$s['CFOP']= $item->getElementsByTagName('CFOP')->item(0)->nodeValue;
			$s['uCom']= $item->getElementsByTagName('uCom')->item(0)->nodeValue;
			$s['qCom']= $item->getElementsByTagName('qCom')->item(0)->nodeValue;
			$s['vUnCom']= $item->getElementsByTagName('vUnCom')->item(0)->nodeValue;
			$s['vProd']= $item->getElementsByTagName('vProd')->item(0)->nodeValue;
			$s['cEANTrib']= $item->getElementsByTagName('cEANTrib')->item(0)->nodeValue;
			$s['uTrib']= $item->getElementsByTagName('uTrib')->item(0)->nodeValue;
			$s['qTrib']= $item->getElementsByTagName('qTrib')->item(0)->nodeValue;
			$s['vUnTrib']= $item->getElementsByTagName('vUnTrib')->item(0)->nodeValue;
			$s['vFrete']= $item->getElementsByTagName('vFrete')->item(0)->nodeValue;
			$s['vSeg']= $item->getElementsByTagName('vSeg')->item(0)->nodeValue;
			$s['vDesc']= $item->getElementsByTagName('vDesc')->item(0)->nodeValue;
			$s['vOutro']= $item->getElementsByTagName('vOutro')->item(0)->nodeValue;
			$s['intTot']= $item->getElementsByTagName('intTot')->item(0)->nodeValue;
			$s['infAdProd']= $item->getElementsByTagName('infAdProd')->item(0)->nodeValue;
			//ICMS
			$s['ICMS'] = '';
			$sicms = $item->getElementsByTagName('ICMS')->item(0);
			
			$vicms['orig'] =  $sicms->getElementsByTagName('orig')->item(0)->nodeValue;
			$vicms['CST'] =  $sicms->getElementsByTagName('CST')->item(0)->nodeValue;
			$vicms['modBC'] =  $sicms->getElementsByTagName('modBC')->item(0)->nodeValue;
			$vicms['vBC'] =  $sicms->getElementsByTagName('vBC')->item(0)->nodeValue;
			$vicms['pICMS'] =  $sicms->getElementsByTagName('pICMS')->item(0)->nodeValue;
			$vicms['vICMS'] =  $sicms->getElementsByTagName('vICMS')->item(0)->nodeValue;
			$vicms['modBCST'] =  $sicms->getElementsByTagName('modBCST')->item(0)->nodeValue;
			$vicms['pMVAST'] =  $sicms->getElementsByTagName('pMVAST')->item(0)->nodeValue;
			$vicms['pRedBCST'] =  $sicms->getElementsByTagName('pRedBCST')->item(0)->nodeValue;
			$vicms['vBCST'] =  $sicms->getElementsByTagName('vBCST')->item(0)->nodeValue;
			$vicms['pICMSST'] =  $sicms->getElementsByTagName('pICMSST')->item(0)->nodeValue;
			$vicms['vICMSST'] =  $sicms->getElementsByTagName('vICMSST')->item(0)->nodeValue;
			$vicms['pRedBC'] =  $sicms->getElementsByTagName('pRedBC')->item(0)->nodeValue;
			$vicms['motDesICMS'] =  $sicms->getElementsByTagName('motDesICMS')->item(0)->nodeValue;
			$vicms['vBCSTRet'] =  $sicms->getElementsByTagName('vBCSTRet')->item(0)->nodeValue;
			$vicms['vICMSSTRet'] =  $sicms->getElementsByTagName('vICMSSTRet')->item(0)->nodeValue;
			$vicms['CSOSN'] =  $sicms->getElementsByTagName('CSOSN')->item(0)->nodeValue;
			$vicms['pCredSN'] =  $sicms->getElementsByTagName('pCredSN')->item(0)->nodeValue;
			$vicms['vCredICMSSN'] =  $sicms->getElementsByTagName('vCredICMSSN')->item(0)->nodeValue;
			
			$vicms['tributacao'] =  ($vicms['CST'] <> '' ) ? $vicms['CST'] : $vicms['CSOSN'] ;
			
			$s['ICMS'] = $vicms;
			//IPI
			$s['IPI'] = '';
			$sipi = $item->getElementsByTagName('IPI')->item(0);
			if($sipi){
			$vipi['CST'] = $sipi->getElementsByTagName('CST')->item(0)->nodeValue;
			$vipi['cIEnq'] = $sipi->getElementsByTagName('cIEnq')->item(0)->nodeValue;
			$vipi['CNPJProd'] = $sipi->getElementsByTagName('CNPJProd')->item(0)->nodeValue;
			$vipi['cSelo'] = $sipi->getElementsByTagName('cSelo')->item(0)->nodeValue;
			$vipi['qSelo'] = $sipi->getElementsByTagName('qSelo')->item(0)->nodeValue;
			$vipi['cEnq'] = $sipi->getElementsByTagName('cEnq')->item(0)->nodeValue;
			$vipi['vBC'] = $sipi->getElementsByTagName('vBC')->item(0)->nodeValue;
			$vipi['pIPI'] = $sipi->getElementsByTagName('pIPI')->item(0)->nodeValue;
			$vipi['qUnid'] = $sipi->getElementsByTagName('qUnid')->item(0)->nodeValue;
			$vipi['vUnid'] = $sipi->getElementsByTagName('vUnid')->item(0)->nodeValue;
			$vipi['vIPI'] = $sipi->getElementsByTagName('vIPI')->item(0)->nodeValue;
			$s['IPI'] = $vipi;
			}
			//PIS
			$s['PIS'] = '';
			$spis = $item->getElementsByTagName('PIS')->item(0);
			$vpis['CST'] = $spis->getElementsByTagName('CST')->item(0)->nodeValue;
			$vpis['vBC'] = $spis->getElementsByTagName('vBC')->item(0)->nodeValue;
			$vpis['pPIS'] = $spis->getElementsByTagName('pPIS')->item(0)->nodeValue;
			$vpis['vPIS'] = $spis->getElementsByTagName('vPIS')->item(0)->nodeValue;
			$vpis['qBCProd'] = $spis->getElementsByTagName('qBCProd')->item(0)->nodeValue;
			$vpis['vAliqProd'] = $spis->getElementsByTagName('vAliqProd')->item(0)->nodeValue;
			$s['PIS'] = $vpis;
			//COFINS
			$s['COFINS'] = '';
			$scofins = $item->getElementsByTagName('COFINS')->item(0);
			$vcofins['CST'] = $scofins->getElementsByTagName('CST')->item(0)->nodeValue;
			$vcofins['vBC'] = $scofins->getElementsByTagName('vBC')->item(0)->nodeValue;
			$vcofins['pCOFINS'] = $scofins->getElementsByTagName('pCOFINS')->item(0)->nodeValue;
			$vcofins['vCOFINS'] = $scofins->getElementsByTagName('vCOFINS')->item(0)->nodeValue;
			$vcofins['qBCProd'] = $scofins->getElementsByTagName('qBCProd')->item(0)->nodeValue;
			$vcofins['vAliqProd'] = $scofins->getElementsByTagName('vAliqProd')->item(0)->nodeValue;
			$s['COFINS'] = $vcofins;
			
			$Produto[] = $s;
		}
		
		/**
		 * TOTAIS
		 */
		
		
		$dTotais = $doc->getElementsByTagName('total')->item(0);
		$tot['vBC'] = $dTotais->getElementsByTagName('vBC')->item(0)->nodeValue;
		$tot['vICMS'] = $dTotais->getElementsByTagName('vICMS')->item(0)->nodeValue;
		$tot['vBCST'] = $dTotais->getElementsByTagName('vBCST')->item(0)->nodeValue;
		$tot['vST'] = $dTotais->getElementsByTagName('vST')->item(0)->nodeValue;
		$tot['vFrete'] = $dTotais->getElementsByTagName('vFrete')->item(0)->nodeValue;
		$tot['vSeg'] = $dTotais->getElementsByTagName('vSeg')->item(0)->nodeValue;
		$tot['vDesc'] = $dTotais->getElementsByTagName('vDesc')->item(0)->nodeValue;
		$tot['vII'] = $dTotais->getElementsByTagName('vII')->item(0)->nodeValue;
		$tot['vIPI'] = $dTotais->getElementsByTagName('vIPI')->item(0)->nodeValue;
		$tot['vPIS'] = $dTotais->getElementsByTagName('vPIS')->item(0)->nodeValue;
		$tot['vProd'] = $dTotais->getElementsByTagName('vProd')->item(0)->nodeValue;
		$tot['vCOFINS'] = $dTotais->getElementsByTagName('vCOFINS')->item(0)->nodeValue;
		$tot['vOutro'] = $dTotais->getElementsByTagName('vOutro')->item(0)->nodeValue;
		$tot['vNF'] = $dTotais->getElementsByTagName('vNF')->item(0)->nodeValue;
		
		/**
		 * TRANSPORTE
		 */
		
		$dTtransp = $doc->getElementsByTagName('transp')->item(0);
		$dados['modFrete'] =  $dTtransp->getElementsByTagName('modFrete')->item(0)->nodeValue;
		$transp['CNPJ'] =  $dTtransp->getElementsByTagName('CNPJ')->item(0)->nodeValue;
		$transp['CPF'] =  $dTtransp->getElementsByTagName('CPF')->item(0)->nodeValue;
		$transp['xNome'] =  $dTtransp->getElementsByTagName('xNome')->item(0)->nodeValue;
		$transp['IE'] =  $dTtransp->getElementsByTagName('IE')->item(0)->nodeValue;
		$transp['xEnder'] =  $dTtransp->getElementsByTagName('xEnder')->item(0)->nodeValue;
		$transp['xMun'] =  $dTtransp->getElementsByTagName('xMun')->item(0)->nodeValue;
		$transp['UF'] =  $dTtransp->getElementsByTagName('UF')->item(0)->nodeValue;
		$transp['veiculo'] = '';
		$veic['placa']= $dTtransp->getElementsByTagName('placa')->item(0)->nodeValue;
		$veic['UF']= $dTtransp->getElementsByTagName('UF')->item(0)->nodeValue;
		$veic['RNTC']= $dTtransp->getElementsByTagName('RNTC')->item(0)->nodeValue;
		$veic['balsa']= $dTtransp->getElementsByTagName('balsa')->item(0)->nodeValue;
		$veic['vagao']= $dTtransp->getElementsByTagName('vagao')->item(0)->nodeValue;
		$transp['veiculo'] = $veic;
		$transp['volumes'] = '';
		$vol['esp'] = $dTtransp->getElementsByTagName('esp')->item(0)->nodeValue;
		$vol['qVol'] = $dTtransp->getElementsByTagName('qVol')->item(0)->nodeValue;
		$vol['marca'] = $dTtransp->getElementsByTagName('marca')->item(0)->nodeValue;
		$vol['nVol'] = $dTtransp->getElementsByTagName('nVol')->item(0)->nodeValue;
		$vol['pesoL'] = $dTtransp->getElementsByTagName('pesoL')->item(0)->nodeValue;
		$vol['pesoB'] = $dTtransp->getElementsByTagName('pesoB')->item(0)->nodeValue;
		$transp['volumes'] = $vol;
		
		/**
		 * Fatura e Duplicatas
		 */
		$dFatura = $doc->getElementsByTagName('fat')->item(0);
		if($dFatura){
		$fatura['nFat'] = $dFatura->getElementsByTagName('nFat')->item(0)->nodeValue;
		$fatura['vOrig'] = $dFatura->getElementsByTagName('vOrig')->item(0)->nodeValue;
		$fatura['vDesc'] = $dFatura->getElementsByTagName('vDesc')->item(0)->nodeValue;
		$fatura['vLiq'] = $dFatura->getElementsByTagName('vLiq')->item(0)->nodeValue;
		}
		
		$dup= $doc->getElementsByTagName('dup');
		$itens="";
		for ($y = 0; $y < $dup->length; $y++) {
			$itemdup = $dup->item($y);
								
					$ddup['nDup'] = $itemdup->getElementsByTagName('nDup')->item(0)->nodeValue;
					$ddup['dVenc'] = $itemdup->getElementsByTagName('dVenc')->item(0)->nodeValue;
					$ddup['vDup'] = $itemdup->getElementsByTagName('vDup')->item(0)->nodeValue;
					$ItDup[] =  $ddup;				
	
		
		}
		
		$fatura['duplicatas'] = $ItDup;

		
		/**
		 * Observações
		 */
		$dobs = $doc->getElementsByTagName('infAdic')->item(0);
		$obs['infAdFisco'] = $dobs->getElementsByTagName('infAdFisco')->item(0)->nodeValue;
		$obs['infCpl'] = $dobs->getElementsByTagName('infCpl')->item(0)->nodeValue;
		$obs['outros'] = '';
		$obscont = $doc->getElementsByTagName('obsCont');
		for ($i = 0; $i < $obscont->length; $i++) {
			$obsoutros = $obscont->item($i);
			$doutros['xCampo'] = $obsoutros->getAttribute("xCampo");
			$doutros['xTexto'] = $obsoutros->getElementsByTagName('xTexto')->item(0)->nodeValue;
			$outros[] = $doutros;
			
		}
		
		$obs['outros'] = $outros;
		
		
		
		
		
		
		
		
		$NFeData = array('Basicos'=>$dados,
		'Emitente'=>$emit,
		'Destinatario'=>$dest,
		'Produtos'=>$Produto,
		'Totais'=>$tot,
		'Transporte'=>$transp,
		'Fatura'=>$fatura,				
		'Observacoes'=>$obs);
		return $NFeData;
		
		
		
		
		
		
		
		
	}
	
	public static function processaNFeEntrada($id){

	//	echo "Processando NFE";
		
		$userInfo = Zend_Auth::getInstance()->getStorage()->read();
		
		$db = new Erp_Model_Faturamento_NFe_Basicos();
		$DadosNFe = $db->fetchRow("id_registro = '$id'");
		$tpNF = $DadosNFe->tpNF;
		$Empresa = $DadosNFe->id_empresa;
		$pedVenda = $DadosNFe->id_pedvenda;
			
			$DBDest = new Erp_Model_Faturamento_NFe_Emitente();
			$DadosDest = $DBDest->fetchRow("id_nfe = '$id'");
			$dadosDestOutros = Cadastros_Model_Outros::getCadastro($DadosDest->id_pessoa);
				
			/**
			 * Muda o Numero da Ultima NFe
			*/
		
		//	System_Model_EmpresasNF::addNFe($DadosNFe->id_empresa);
		
		
			/**
			 * Processa Itens de Faturamento
			*/
		
			$DBFatura = new Erp_Model_Faturamento_NFe_Fatura();
			$DadosFatura = $DBFatura->fetchRow("id_nfe = '$id'");
			if($DadosFatura->id_registro <> ''){
			//	echo "Fatura Encontrada";
					
				$DBDuplicatas = new Erp_Model_Faturamento_NFe_FaturaDuplicatas();
				$DadosDuplicatas = $DBDuplicatas->fetchAll("id_nfe = '$id'");
				$QuantidadedeParcelas = count($DadosDuplicatas->toArray());
				$DadosDuplicatasArray = $DadosDuplicatas->toArray();
					
				if($tpNF == 0){
					$Finan = new Erp_Model_Financeiro_DadosRecebimentos();
					$Lanc = new Erp_Model_Financeiro_LancamentosRecebimentos();
			//		echo " Recebimento ";
				}else{
					$Finan = new Erp_Model_Financeiro_DadosPagamentos();
					$Lanc = new Erp_Model_Financeiro_LancamentosPagamentos();
			//		echo " Pagamento ";
				}
				if($QuantidadedeParcelas > 0){
						
					$data1 = array("id_pessoa"=>$DadosDest->id_pessoa,
							'id_empresa'=>$DadosNFe->id_empresa,
							'tiporegistro'=>'NFe',
							'id_registro_vinculado'=>$id,
							'datacadastro'=>$DadosNFe->dEmi,
							'user_id'=>$userInfo->id_registro,
							'totalgeral'=>$DadosFatura->vLiq,
							'primeirovencimento'=>$DadosDuplicatasArray[0]['dVenc'],
							'ultimovencimento'=>$DadosDuplicatasArray[$QuantidadedeParcelas - 1]['dVenc'],
							'totalparcelas'=>$QuantidadedeParcelas,
							'parcelaspagas'=>0,
							'parcelasavencer'=>$QuantidadedeParcelas,
							'totalpago'=>0,
							'nomelancamento'=>"(NFe: {$DadosNFe->nNF}) - {$DadosDest->xNome}",
							'totalapagar'=> $DadosFatura->vLiq,
							'statuslancamento'=>'1',
							'contapadrao'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
							'categorialanc'=>$dadosDestOutros->planodecontas,
							'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
							'numerodocumento'=>$DadosFatura->nFat,
					);
				}else{
					$data1 = array("id_pessoa"=>$DadosDest->id_pessoa,
							'id_empresa'=>$DadosNFe->id_empresa,
							'tiporegistro'=>'NFe',
							'id_registro_vinculado'=>$id,
							'datacadastro'=>$DadosNFe->dEmi,
							'user_id'=>$userInfo->id_registro,
							'totalgeral'=>$DadosFatura->vLiq,
							'primeirovencimento'=>date('Y-m-d'),
							'ultimovencimento'=>date('Y-m-d'),
							'totalparcelas'=>1,
							'parcelaspagas'=>0,
							'parcelasavencer'=>1,
							'totalpago'=>0,
							'nomelancamento'=>"(NFe: {$DadosNFe->nNF}) - {$DadosDest->xNome}",
							'totalapagar'=> $DadosFatura->vLiq,
							'statuslancamento'=>'1',
							'contapadrao'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
							'categorialanc'=>$dadosDestOutros->planodecontas,
							'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
							'numerodocumento'=>$DadosFatura->nFat,
					);
		
				};
			//	print_r($data1);
				$id_lancamento = $Finan->insert($data1);
				$DBFatura->update(array("id_lancamento"=>$id_lancamento),"id_nfe = '$id'");
				
				if($QuantidadedeParcelas > 0){
				//	echo "VARIAS PARCELAAS";
					foreach($DadosDuplicatas as $Dupl){
						//print_r($Dupl);
						
						$Par = 1;

						$datapar = array('id_lancamento'=>$id_lancamento,
								'datavencimento'=>$Dupl->dVenc,
								'valororiginal'=>$Dupl->vDup,
								'numeroparcela'=>$Par,
								'quantidadeparcelas'=>$QuantidadedeParcelas,
								'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
								'numerodocumento'=>$Dupl->nDup,
								'statuslancamento'=>'1',
								'id_banco'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
								'user_liberacao'=>'0',
								
						);
						
						
						try{
						$id_destelanc = $Lanc->insert($datapar);
						$DBDuplicatas->update(array('id_registro_recebimentos'=>$id_destelanc),"id_registro = '{$Dupl->id_registro}'");
						$Par++;
						}catch (Exception $e){
						echo $e->getMessage();
						}
						
					}
				}else{
						echo "PARCELA UNICA";
					$data2 = array('id_lancamento'=>$id_lancamento,
							'datavencimento'=>date('Y-m-d'),
							'valororiginal'=>$DadosFatura->vLiq,
							'numeroparcela'=>1,
							'quantidadeparcelas'=>1,
							'tipodocumento'=>System_Model_SysConfigs::getConfig("TipoDocNFeLancamento"),
							'numerodocumento'=>$DadosFatura->nFat,
							'statuslancamento'=>'1',
							'id_banco'=>Erp_Model_Financeiro_ContaCorrente::getContaPadrao(),
							'user_liberacao'=>'0',
							
					);
						try{
						$id_destelanc = $Lanc->insert($data2);
						$DBDuplicatas->update(array('id_registro_recebimentos'=>$id_destelanc),"id_registro = '{$Dupl->id_registro}'");
						}catch (Exception $e){
						echo $e->getMessage();
						}
						
					
				}
					
				//	print_r($data2);
					
			}
				
				
			/**
			 * Processa Itens de Estoque
			 */
		
			$DBProdutosNFe = new Erp_Model_Faturamento_NFe_Produtos();
			$DadosProdNFe = $DBProdutosNFe->fetchAll("id_nfe = '$id'");
		
			$DBProdVenda = new Erp_Model_Vendas_Produtos();
			$DBEstoque = new Erp_Model_Estoque_Movimento();
		
			foreach($DadosProdNFe as $ProdNFe){
				if($tpNF == 1){
		
				if(System_Model_SysConfigs::getConfig("MovEstoqueNFeEntrada") == 1){
						$datainsertEstoque = array('id_produto'=>$ProdNFe->id_produto,
								'id_estoque'=>System_Model_SysConfigs::getConfig("EstoquePadraoEntradaNFe"),
								'quantidade'=>$ProdNFe->qCom,
								'historico'=>"Movimento do Processo de Entrada por NFe: {$DadosNFe->nNF}",
								'usuario'=>$userInfo->id_registro,
								'data'=>date('Y-m-d H:i:s')
						);
							
						$id_mov_est = $DBEstoque->insert($datainsertEstoque);
						$DBProdutosNFe->update(array('id_reg_mov_estoque'=>$id_mov_est),"id_registro = '{$ProdNFe->id_registro}'");
					}
		
				}else{
		
					if(System_Model_SysConfigs::getConfig("MovEstoqueVendaNFe") == 1){
						$datainsertEstoque = array('id_produto'=>$ProdNFe->id_produto,
								'id_estoque'=>System_Model_SysConfigs::getConfig("EstoquePadraoSaidaNFe"),
								'quantidade'=>'-'.$ProdNFe->qCom,
								'historico'=>"Movimento do Processo de entrada NFe: {$DadosNFe->nNF}",
								'usuario'=>$userInfo->id_registro,
								'data'=>date('Y-m-d H:i:s')
						);
							
						$id_mov_est = $DBEstoque->insert($datainsertEstoque);
						$DBProdutosNFe->update(array('id_reg_mov_estoque'=>$id_mov_est),"id_registro = '{$ProdNFe->id_registro}'");
					}
		
		
				}
					
		
					
					
			}
		
		
			$db->update(array('processoNFeAprovada'=>1),"id_registro = '$id'");
		
			return true;
		}
		
	
	
}

?>