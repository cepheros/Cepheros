<?php 

class System_FaturamentoController extends Zend_Controller_Action{
	
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
	
	
	
	public function perfilFaturamentoAction(){
		$db = new Erp_Model_Faturamento_Perfil();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		$dbnfe = new Erp_Model_Faturamento_NFe_Basicos();
		
		
	}
	
	public function novoPerfilFaturamentoAction(){
		$formBasicos = new System_Form_Faturamento_Perfil();
		$formBasicos->basicos();
		$this->view->formBasicos = $formBasicos;
		
		$formICMS = new System_Form_Faturamento_Perfil();
		$formICMS->icms();
		$this->view->formICMS = $formICMS;
		
		
		$formIPI = new System_Form_Faturamento_Perfil();
		$formIPI->ipi();
		$this->view->formIPI = $formIPI;
		
		$formPIS = new System_Form_Faturamento_Perfil();
		$formPIS->pis();
		$this->view->formPIS = $formPIS;
		
		$formCOFINS = new System_Form_Faturamento_Perfil();
		$formCOFINS->cofins();
		$this->view->formCOFINS = $formCOFINS;
	}
	
	
	public function editaPerfilFaturamentoAction(){
		$id = $this->_getParam("id");
		$db = new Erp_Model_Faturamento_Perfil();
		$dbasicos = $db->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dbasicos = $dbasicos;
		
		$formBasicos = new System_Form_Faturamento_Perfil();
		$formBasicos->basicos();
		$formBasicos->populate($dbasicos);
		$this->view->formBasicos = $formBasicos;
		
		$db2 = new Erp_Model_Faturamento_PerfilICMS();
		$dicms = $db2->fetchRow("id_perfil = '$id'")->toArray();
		$this->view->dicms = $dicms;
		unset($dicms['id_registro']);
	
		$formICMS = new System_Form_Faturamento_Perfil();
		$formICMS->icms();
		$formICMS->populate($dicms);
		$this->view->formICMS = $formICMS;
	
		
		$db3 = new Erp_Model_Faturamento_PerfilIPI();
		$dipi = $db3->fetchRow("id_perfil = '$id'")->toArray();
		$this->view->dipi = $dipi;
		unset($dipi['id_registro']);
		
		$formIPI = new System_Form_Faturamento_Perfil();
		$formIPI->ipi();
		$formIPI->populate($dipi);
		$this->view->formIPI = $formIPI;
		
		$db4 = new Erp_Model_Faturamento_PerfilPIS();
		$dpis = $db4->fetchRow("id_perfil = '$id'")->toArray();
		$this->view->dpis = $dpis;
		unset($dpis['id_registro']);
			
		$formPIS = new System_Form_Faturamento_Perfil();
		$formPIS->pis();
		$formPIS->populate($dpis);
		$this->view->formPIS = $formPIS;
	
		$db5 = new Erp_Model_Faturamento_PerfilCOFINS();
		$dcofins = $db5->fetchRow("id_perfil = '$id'")->toArray();
		$this->view->dcofins  = $dcofins;
		unset($dcofins['id_registro']);
		
		$formCOFINS = new System_Form_Faturamento_Perfil();
		$formCOFINS->cofins();
		$formCOFINS->populate($dcofins);
		$this->view->formCOFINS = $formCOFINS;
	}
	
	public function salvarNovoPerfilAction(){
		$db = new Erp_Model_Faturamento_Perfil();
		$formData = $this->_request->getPost();
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		try{
			
			if($formData['nomedoperfil'] == ''){ $formData['nomedoperfil']  = '0'; };
			if($formData['observacoesfisco'] == ''){  $formData['observacoesfisco'] = ''; };
			if($formData['cfop'] == ''){  $formData['cfop'] = '0'; };
			if($formData['naturezaoperacao'] == ''){ $formData['naturezaoperacao']  = '0'; };
			if($formData['suframa'] == ''){ $formData['suframa']  = '0'; };
			if($formData['tipoperfil'] == ''){ $formData['tipoperfil']  = '0'; };
			if($formData['finalidadeemissao'] == ''){ $formData['finalidadeemissao']  = '0'; };
			if($formData['valorprodcompetotal'] == ''){ $formData['valorprodcompetotal']  = '0'; };
			if($formData['incluirpedcompra'] == ''){  $formData['incluirpedcompra'] = '0'; };
			if($formData['incluirpedvenda'] == ''){ $formData['incluirpedvenda']   = '0'; };
			if($formData['aliqaplicavelcalculocredito'] == ''){ $formData['aliqaplicavelcalculocredito']  = '0'; }else{ $formData['aliqaplicavelcalculocredito'] = str_replace(',','.', $formData['aliqaplicavelcalculocredito']);};
			if($formData['moddeterbcicms'] == ''){ $formData['moddeterbcicms']  = '0'; };
			if($formData['aliqicms'] == ''){ $formData['aliqicms']  = '0'; }else{ $formData['aliqicms'] = str_replace(',','.', $formData['aliqicms']);};
			if($formData['moddeterbcicmsst'] == ''){ $formData['moddeterbcicmsst']  = '0'; };
			if($formData['redutorbcicmsst'] == ''){  $formData['redutorbcicmsst'] = '0'; }else{ $formData['redutorbcicmsst'] = str_replace(',','.', $formData['redutorbcicmsst']);};
			if($formData['margemvladicicmsst'] == ''){ $formData['margemvladicicmsst']  = '0'; };
			if($formData['aliqicmsst'] == ''){  $formData['aliqicmsst']  = '0'; };
			if($formData['uficmsstdevop'] == ''){  $formData['uficmsstdevop'] = '0'; };
			if($formData['redutorbcicms'] == ''){  $formData['redutorbcicms'] = '0'; };
			if($formData['bcoperacaopropria'] == ''){ $formData['bcoperacaopropria']  = '0'; };
			if($formData['motivodadesoneracao'] == ''){ $formData['motivodadesoneracao']   = '0'; };
			if($formData['bcicmsstretironaufremetente'] == ''){ $formData['bcicmsstretironaufremetente']  = '0'; };
			if($formData['bcicmsstufdestino'] == ''){  $formData['bcicmsstufdestino'] = '0'; };
			if($formData['sittributaria_ipi'] == ''){  $formData['sittributaria_ipi'] = '0'; };
			if($formData['classedeenquadramento_ipi'] == ''){  $formData['classedeenquadramento_ipi'] = '0'; };
			if($formData['codenquadramento_ipi'] == ''){  $formData['codenquadramento_ipi'] = '0'; };
			if($formData['cnpjprodutor_ipi'] == ''){  $formData['cnpjprodutor_ipi'] = '0'; };
			if($formData['codselocontrole_ipi'] == ''){  $formData['codselocontrole_ipi'] = '0'; };
			if($formData['tipocalculo_ipi'] == ''){  $formData['tipocalculo_ipi'] = '0'; };
			if($formData['alipi'] == ''){  $formData['alipi'] = '0'; };
			if($formData['vlfixo_ipi'] == ''){  $formData['vlfixo_ipi'] = '0'; };
			if($formData['sittrib_pis'] == ''){  $formData['sittrib_pis'] = '0'; };
			if($formData['tipocalculo_pis'] == ''){  $formData['tipocalculo_pis'] = '0'; };
			if($formData['aliqpis_pis'] == ''){  $formData['aliqpis_pis'] = '0'; };
			if($formData['tipocalculost_pis'] == ''){  $formData['tipocalculost_pis'] = '0'; };
			if($formData['aliqpis_st_pis'] == ''){  $formData['aliqpis_st_pis'] = '0'; };
			if($formData['aliqreal__pis'] == ''){  $formData['aliqreal__pis'] = '0'; };
			if($formData['aliqrealst__pis'] == ''){  $formData['aliqrealst__pis'] = '0'; };
			if($formData['sittrib_cofins'] == ''){  $formData['sittrib_cofins'] = '0'; };
			if($formData['tipocalculo_cofins'] == ''){  $formData['tipocalculo_cofins'] = '0'; };
			if($formData['aliqcofins_cofins'] == ''){  $formData['aliqcofins_cofins'] = '0'; };
			if($formData['tipocalculost_cofins'] == ''){  $formData['tipocalculost_cofins'] = '0'; };
			if($formData['aliqcofins_st_cofins'] == ''){  $formData['aliqcofins_st_cofins'] = '0'; };
			if($formData['aliqreal__cofins'] == ''){  $formData['aliqreal__cofins'] = '0'; };
			if($formData['aliqrealst__cofins'] == ''){  $formData['aliqrealst__cofins'] = '0'; };
			
		$dbasicos = array(
		'nomedoperfil'=>$formData['nomedoperfil'],
		'observacoesfisco'=>$formData['observacoesfisco'],
		'cfop'=>$formData['cfop'],
		'naturezaoperacao'=>$formData['naturezaoperacao'],
		'suframa'=>$formData['suframa'],
		'tipoperfil'=>$formData['tipoperfil'],
		'finalidadeemissao'=>$formData['finalidadeemissao'],
		'vlprodcompoetotal'=>$formData['valorprodcompetotal'],
		'incluirpedcompra'=>$formData['incluirpedcompra'],
		'incluirpedvenda'=>$formData['incluirpedvenda']
		);
		
		$idperfil = $db->insert($dbasicos);
		
		$dicms= array(
				'id_perfil'=>$idperfil,
				'sittributaria'=>$formData['sittributaria'],
				'aliqaplicavelcalculocredito'=>$formData['aliqaplicavelcalculocredito'],
				'moddetermbcicms'=>$formData['moddeterbcicms'],
				'aliqicms'=>$formData['aliqicms'],
				'moddeterbcicmsst'=>$formData['moddeterbcicmsst'],
				'redutorbcicmsst'=>$formData['redutorbcicmsst'],
				'margemvladicicmsst'=>$formData['margemvladicicmsst'],
				'aliqicmsst'=>$formData['aliqicmsst'],
				'uficmsstdevop'=>$formData['uficmsstdevop'],
				'redutorbcicms'=>$formData['redutorbcicms'],
				'bcoperacaopropria'=>$formData['bcoperacaopropria'],
				'motivodadesoneracao'=>$formData['motivodadesoneracao'],
				'bcicmsstretironaufremetente'=>$formData['bcicmsstretironaufremetente'],
				'bcicmsstufdestino'=>$formData['bcicmsstufdestino']
		);
		$db2 = new Erp_Model_Faturamento_PerfilICMS();
		$db2->insert($dicms);
		
		$dipi = array('id_perfil'=>$idperfil,
				'sittributaria_ipi'=>$formData['sittributaria_ipi'],
				'classedeenquadramento_ipi'=>$formData['classedeenquadramento_ipi'],
				'codenquadramento_ipi'=>$formData['codenquadramento_ipi'],
				'cnpjprodutor_ipi'=>$formData['cnpjprodutor_ipi'],
				'codselocontrole_ipi'=>$formData['codselocontrole_ipi'],
				'tipocalculo_ipi'=>$formData['tipocalculo_ipi'],
				'alipi'=>$formData['alipi'],
				'vlfixo_ipi'=>$formData['vlfixo_ipi']
		);
		$db3 = new Erp_Model_Faturamento_PerfilIPI();
		$db3->insert($dipi);
		
		$dpis = array('id_perfil'=>$idperfil,
		'sittrib_pis'=>$formData['sittrib_pis'],
		'tipocalculo_pis'=>$formData['tipocalculo_pis'],
		'aliqpis_pis'=>$formData['aliqpis_pis'],
		'tipocalculost_pis'=>$formData['tipocalculost_pis'],
		'aliqpis_st_pis'=>$formData['aliqpis_st_pis'],
		'aliqreal__pis'=>$formData['aliqreal__pis'],
		'aliqrealst__pis'=>$formData['aliqrealst__pis']
		);
		
		$db4 = new Erp_Model_Faturamento_PerfilPIS();
		$db4->insert($dpis);
		
		$dcofins = array('id_perfil'=>$idperfil,
		'sittrib_cofins'=>$formData['sittrib_cofins'],
		'tipocalculo_cofins'=>$formData['tipocalculo_cofins'],
		'aliqcofins_cofins'=>$formData['aliqcofins_cofins'],
		'tipocalculost_cofins'=>$formData['tipocalculost_cofins'],
		'aliqcofins_st_cofins'=>$formData['aliqcofins_st_cofins'],
		'aliqreal__cofins'=>$formData['aliqreal__cofins'],
		'aliqrealst__cofins'=>$formData['aliqrealst__cofins']
		);
		
		$db5 = new Erp_Model_Faturamento_PerfilCOFINS();
		$db5->insert($dcofins);
		
		$this->_redirect("/system/faturamento/edita-perfil-faturamento/id/$idperfil");
		
		}catch(Exception $e){
			echo $e->getMessage();
			
		}
		
		
		
	}
	
	
	public function editarPerfilAction(){
		
		$db = new Erp_Model_Faturamento_Perfil();
		$formData = $this->_request->getPost();
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$id_pefil = $formData['id_registro'];
		
		try{
				
			if($formData['nomedoperfil'] == ''){ $formData['nomedoperfil']  = '0'; };
			if($formData['observacoesfisco'] == ''){  $formData['observacoesfisco'] = ''; };
			if($formData['cfop'] == ''){  $formData['cfop'] = '0'; };
			if($formData['naturezaoperacao'] == ''){ $formData['naturezaoperacao']  = '0'; };
			if($formData['suframa'] == ''){ $formData['suframa']  = '0'; };
			if($formData['tipoperfil'] == ''){ $formData['tipoperfil']  = '0'; };
			if($formData['finalidadeemissao'] == ''){ $formData['finalidadeemissao']  = '0'; };
			if($formData['valorprodcompetotal'] == ''){ $formData['valorprodcompetotal']  = '0'; };
			if($formData['incluirpedcompra'] == ''){  $formData['incluirpedcompra'] = '0'; };
			if($formData['incluirpedvenda'] == ''){ $formData['incluirpedvenda']   = '0'; };
			if($formData['aliqaplicavelcalculocredito'] == ''){ $formData['aliqaplicavelcalculocredito']  = '0'; };
			if($formData['moddeterbcicms'] == ''){ $formData['moddeterbcicms']  = '0'; };
			if($formData['aliqicms'] == ''){ $formData['aliqicms']  = '0'; };
			if($formData['moddeterbcicmsst'] == ''){ $formData['moddeterbcicmsst']  = '0'; };
			if($formData['redutorbcicmsst'] == ''){  $formData['redutorbcicmsst'] = '0'; };
			if($formData['margemvladicicmsst'] == ''){ $formData['margemvladicicmsst']  = '0'; };
			if($formData['aliqicmsst'] == ''){  $formData['aliqicmsst']  = '0'; };
			if($formData['uficmsstdevop'] == ''){  $formData['uficmsstdevop'] = '0'; };
			if($formData['redutorbcicms'] == ''){  $formData['redutorbcicms'] = '0'; };
			if($formData['bcoperacaopropria'] == ''){ $formData['bcoperacaopropria']  = '0'; };
			if($formData['motivodadesoneracao'] == ''){ $formData['motivodadesoneracao']   = '0'; };
			if($formData['bcicmsstretironaufremetente'] == ''){ $formData['bcicmsstretironaufremetente']  = '0'; };
			if($formData['bcicmsstufdestino'] == ''){  $formData['bcicmsstufdestino'] = '0'; };
			if($formData['sittributaria_ipi'] == ''){  $formData['sittributaria_ipi'] = '0'; };
			if($formData['classedeenquadramento_ipi'] == ''){  $formData['classedeenquadramento_ipi'] = '0'; };
			if($formData['codenquadramento_ipi'] == ''){  $formData['codenquadramento_ipi'] = '0'; };
			if($formData['cnpjprodutor_ipi'] == ''){  $formData['cnpjprodutor_ipi'] = '0'; };
			if($formData['codselocontrole_ipi'] == ''){  $formData['codselocontrole_ipi'] = '0'; };
			if($formData['tipocalculo_ipi'] == ''){  $formData['tipocalculo_ipi'] = '0'; };
			if($formData['alipi'] == ''){  $formData['alipi'] = '0'; };
			if($formData['vlfixo_ipi'] == ''){  $formData['vlfixo_ipi'] = '0'; };
			if($formData['sittrib_pis'] == ''){  $formData['sittrib_pis'] = '0'; };
			if($formData['tipocalculo_pis'] == ''){  $formData['tipocalculo_pis'] = '0'; };
			if($formData['aliqpis_pis'] == ''){  $formData['aliqpis_pis'] = '0'; };
			if($formData['tipocalculost_pis'] == ''){  $formData['tipocalculost_pis'] = '0'; };
			if($formData['aliqpis_st_pis'] == ''){  $formData['aliqpis_st_pis'] = '0'; };
			if($formData['aliqreal__pis'] == ''){  $formData['aliqreal__pis'] = '0'; };
			if($formData['aliqrealst__pis'] == ''){  $formData['aliqrealst__pis'] = '0'; };
			if($formData['sittrib_cofins'] == ''){  $formData['sittrib_cofins'] = '0'; };
			if($formData['tipocalculo_cofins'] == ''){  $formData['tipocalculo_cofins'] = '0'; };
			if($formData['aliqcofins_cofins'] == ''){  $formData['aliqcofins_cofins'] = '0'; };
			if($formData['tipocalculost_cofins'] == ''){  $formData['tipocalculost_cofins'] = '0'; };
			if($formData['aliqcofins_st_cofins'] == ''){  $formData['aliqcofins_st_cofins'] = '0'; };
			if($formData['aliqreal__cofins'] == ''){  $formData['aliqreal__cofins'] = '0'; };
			if($formData['aliqrealst__cofins'] == ''){  $formData['aliqrealst__cofins'] = '0'; };
				
			$dbasicos = array(
					'nomedoperfil'=>$formData['nomedoperfil'],
					'observacoesfisco'=>$formData['observacoesfisco'],
					'cfop'=>$formData['cfop'],
					'naturezaoperacao'=>$formData['naturezaoperacao'],
					'suframa'=>$formData['suframa'],
					'tipoperfil'=>$formData['tipoperfil'],
					'finalidadeemissao'=>$formData['finalidadeemissao'],
					'vlprodcompoetotal'=>$formData['valorprodcompetotal'],
					'incluirpedcompra'=>$formData['incluirpedcompra'],
					'incluirpedvenda'=>$formData['incluirpedvenda']
			);
		
			$idperfil = $db->update($dbasicos,"id_registro = '$id_pefil'");
		
			$dicms= array(
					'id_perfil'=>$id_pefil,
					'sittributaria'=>$formData['sittributaria'],
					'aliqaplicavelcalculocredito'=>$formData['aliqaplicavelcalculocredito'],
					'moddetermbcicms'=>$formData['moddeterbcicms'],
					'aliqicms'=>$formData['aliqicms'],
					'moddeterbcicmsst'=>$formData['moddeterbcicmsst'],
					'redutorbcicmsst'=>$formData['redutorbcicmsst'],
					'margemvladicicmsst'=>$formData['margemvladicicmsst'],
					'aliqicmsst'=>$formData['aliqicmsst'],
					'uficmsstdevop'=>$formData['uficmsstdevop'],
					'redutorbcicms'=>$formData['redutorbcicms'],
					'bcoperacaopropria'=>$formData['bcoperacaopropria'],
					'motivodadesoneracao'=>$formData['motivodadesoneracao'],
					'bcicmsstretironaufremetente'=>$formData['bcicmsstretironaufremetente'],
					'bcicmsstufdestino'=>$formData['bcicmsstufdestino']
			);
			$db2 = new Erp_Model_Faturamento_PerfilICMS();
			$db2->update($dicms,"id_perfil = '$id_pefil'");
		
			$dipi = array('id_perfil'=>$id_pefil,
					'sittributaria_ipi'=>$formData['sittributaria_ipi'],
					'classedeenquadramento_ipi'=>$formData['classedeenquadramento_ipi'],
					'codenquadramento_ipi'=>$formData['codenquadramento_ipi'],
					'cnpjprodutor_ipi'=>$formData['cnpjprodutor_ipi'],
					'codselocontrole_ipi'=>$formData['codselocontrole_ipi'],
					'tipocalculo_ipi'=>$formData['tipocalculo_ipi'],
					'alipi'=>$formData['alipi'],
					'vlfixo_ipi'=>$formData['vlfixo_ipi']
			);
			$db3 = new Erp_Model_Faturamento_PerfilIPI();
			$db3->update($dipi,"id_perfil = '$id_pefil'");
		
			$dpis = array('id_perfil'=>$id_pefil,
					'sittrib_pis'=>$formData['sittrib_pis'],
					'tipocalculo_pis'=>$formData['tipocalculo_pis'],
					'aliqpis_pis'=>$formData['aliqpis_pis'],
					'tipocalculost_pis'=>$formData['tipocalculost_pis'],
					'aliqpis_st_pis'=>$formData['aliqpis_st_pis'],
					'aliqreal__pis'=>$formData['aliqreal__pis'],
					'aliqrealst__pis'=>$formData['aliqrealst__pis']
			);
		
			$db4 = new Erp_Model_Faturamento_PerfilPIS();
			$db4->update($dpis,"id_perfil = '$id_pefil'");
		
			$dcofins = array('id_perfil'=>$id_pefil,
					'sittrib_cofins'=>$formData['sittrib_cofins'],
					'tipocalculo_cofins'=>$formData['tipocalculo_cofins'],
					'aliqcofins_cofins'=>$formData['aliqcofins_cofins'],
					'tipocalculost_cofins'=>$formData['tipocalculost_cofins'],
					'aliqcofins_st_cofins'=>$formData['aliqcofins_st_cofins'],
					'aliqreal__cofins'=>$formData['aliqreal__cofins'],
					'aliqrealst__cofins'=>$formData['aliqrealst__cofins']
			);
		
			$db5 = new Erp_Model_Faturamento_PerfilCOFINS();
			$db5->update($dcofins,"id_perfil = '$id_pefil'");
		
			$this->_redirect("/system/faturamento/edita-perfil-faturamento/id/$id_pefil");
		
		}catch(Exception $e){
			echo $e->getMessage();
				
		}
		
		
	}
	
	
	public function cadastraPessoaNfeEmitenteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		
		$bdNF = new Erp_Model_Faturamento_NFe_Basicos();
		$dadosNF = $bdNF->fetchRow("id_registro = '$id'");
		
		$dbEmitente = new Erp_Model_Faturamento_NFe_Emitente();
		$DEmitente= $dbEmitente->fetchRow("id_nfe = '$id'");
		
		
		$DbPessoa = new Cadastros_Model_Pessoas();
		$dadosPessoa = array('id_empresa'=>$dadosNF->id_empresa,
				'razaosocial'=>$DEmitente->xNome,
				'nomefantasia'=>$DEmitente->xFant,
				'cnpj'=>Functions_Auxilio::formatText($DEmitente->CNPJ,'##.###.###/####-##'),
				'inscestadual'=>$DEmitente->IE,
				'datacadastro'=>date('Y-m-d'),
				'dataabertura'=>date('Y-m-d'),
				'inscmunicipal'=>$DEmitente->IM,
				'userid'=>$this->userInfo->id_registro,
				'tipopessoa'=>'1',
				'tipocadastro'=>'2',
				'categoria'=>'1'
	
		);
		if($dadosPessoa['inscmunicipal'] == ''){
			$dadosPessoa['inscmunicipal'] = 'ISENTO';
		}
		
		$id_pessoa = $DbPessoa->insert($dadosPessoa);
		
		$dPessoaEnd = new Cadastros_Model_Enderecos();
		$dadosEndereco = array('id_pessoa'=>$id_pessoa,
		'tipoendereco'=>'1',
		'cep'=>$DEmitente->CEP,
		'logradouro'=>$DEmitente->Xlgr,
		'numero'=>$DEmitente->nro,
		'complemento'=>$DEmitente->xCpl,
		'bairro'=>$DEmitente->xBairro,
		'cidade'=>$DEmitente->xMun,
		'estado'=>$DEmitente->UF,
		'codpais'=>$DEmitente->cPais,
		'codibge'=>$DEmitente->cMun,
		'isdefault'=>'1');
		if($dadosEndereco['codpais'] ==  ''){
			$dadosEndereco['codpais'] = '1058';
		}
		
		$dPessoaEnd->insert($dadosEndereco);
		
		$DContatos = new Cadastros_Model_Contatos();
		$dadosContatos = array('id_pessoa'=>$id_pessoa,
		'tipocontato'=>'2',
		'nomecontato'=>$DEmitente->xNome,
		'isdefault'=>1,
		'telcomercial'=> Functions_Auxilio::formatText($DEmitente->fone,"(##)####-####"));

		$DContatos->insert($dadosContatos);
		
		$db2 = new Cadastros_Model_Outros();
		$data2['id_pessoa'] = $id_pessoa;
		$db2->insert($data2);
		
		$dbEmitente->update(array('id_pessoa'=>$id_pessoa),"id_nfe = '$id'");
		
		
		
		$this->_redirect("/cadastros/pessoas/cadastro/id/$id_pessoa");
		
		
		
		
		
	}
	
	public function cadastraProdutoNfeEmitenteAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_request->getParam("id",0);
		
		$bdNF = new Erp_Model_Faturamento_NFe_Produtos();
		$dadosProdNFe = $bdNF->fetchRow("id_registro = '$id'");
		
		$dbNFe = new Erp_Model_Faturamento_NFe_Basicos();
		$dadosNFe = $dbNFe->fetchRow("id_registro = '{$dadosProdNFe->id_nfe}'");
		
		$dbNFeEmi = new Erp_Model_Faturamento_NFe_Emitente();
		$dadosNFeEmitente = $dbNFeEmi->fetchRow("id_nfe = '{$dadosProdNFe->id_nfe}'");
		
		$dbProd = new Cadastros_Model_Produtos();
		$dadosProduto = array('nomeproduto'=>$dadosProdNFe->xProd,
				'nomeproduto'=>$dadosProdNFe->xProd,
				'referenciaproduto'=>$dadosProdNFe->cProd,
				'codigointerno'=>$dadosProdNFe->cProd,
				'codigonfe'=>$dadosProdNFe->cProd,
				'categoriaproduto'=>'0',
				'subcategoriaproduto'=>'0',
				'eangtin'=>$dadosProdNFe->cEAN,
				'ncmproduto'=>$dadosProdNFe->NCM,
				'origemproduto'=>'0',
				'precocusto'=>$dadosProdNFe->vUnCom,
				'orcarautomatico'=>0
				
		);
		
		$idProduto = $dbProd->insert($dadosProduto);
		
		$dbVinc = new Cadastros_Model_ProdutosVinculos();
		$dVinc = array('id_produto'=>$idProduto,
		'id_pessoa'=>$dadosNFeEmitente->id_pessoa,
		'codvinculado'=>$dadosProdNFe->cProd);
		$dbVinc->insert($dVinc);
		
		$this->_redirect("/cadastros/produtos/abrir/id/$idProduto");
		
		
		
		
	}
	
	
	public function configGeraisAction(){
		
		
	}
	
	
	
	
}