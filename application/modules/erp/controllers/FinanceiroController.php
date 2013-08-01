<?php
class Erp_FinanceiroController extends Zend_Controller_Action{

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
		
		
	public function novaContaReceberAction(){
	$form = new Erp_Form_Financeiro();
	$form->tipocatlanc = 1;
	$form->lancamento();
	$form->populate(array('id_empresa'=>System_Model_Empresas::getEmpresaPadrao()));
	$this->view->form = $form;
	}
	
	public function novaContaPagarAction(){
		$form = new Erp_Form_Financeiro();
		$form->tipocatlanc = 2;
		$form->lancamento();
		$form->populate(array('id_empresa'=>System_Model_Empresas::getEmpresaPadrao()));
		$this->view->form = $form;		
	}
	
	public function listaContaPagarAction(){
		$tipo = $this->_getParam('tipo');
		$this->view->tipo = $tipo;
	
	}
	
	public function listaContaReceberAction(){
		$tipo = $this->_getParam('tipo');
		$this->view->tipo = $tipo;
	
	}
	
	public function fluxoCaixaAction(){
		$db = new Erp_Model_Financeiro_FluxoCaixa();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		
		
		
	}
	
	public function processarRecebimentosAction(){
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			$formdata['pessoapagamento'] = Cadastros_Model_Pessoas::getNomeEmpresa($formdata['id_pessoa']);
			$form = new Erp_Form_Financeiro();
			$form->tipocatlanc = 1;
			$form->lancamento();
			$form->populate($formdata);
			$this->view->form = $form;
			$this->view->data = $formdata;
				
		}
	}
		
		
		public function processarPagamentosAction(){
		
			if ($this->_request->isPost()) {
				$formdata = $this->_request->getPost();
				$formdata['pessoapagamento'] = Cadastros_Model_Pessoas::getNomeEmpresa($formdata['id_pessoa']);
				$form = new Erp_Form_Financeiro();
				$form->tipocatlanc =2;
				$form->lancamento();
				$form->populate($formdata);
				$this->view->form = $form;
				$this->view->data = $formdata;
		
			}


	}
	
	public function salvarRecebimentosAction(){
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			$db1 = new Erp_Model_Financeiro_DadosRecebimentos();
			$db2 = new Erp_Model_Financeiro_LancamentosRecebimentos();
			$db3 = new Erp_Model_Financeiro_FluxoCaixa();
			$datahorahoje = date("Y-m-d H:i:s");
			$datahoje = date("Y-m-d");
			$ultimo = count($formdata['parcela']) - 1;
			
			$data1 = array("id_pessoa"=>$formdata['id_pessoa'],
					'id_empresa'=>$formdata['id_empresa'],
					'tiporegistro'=>'Manual',
					'id_registro_vinculado'=>'0',
					'datacadastro'=>$datahorahoje,
					'user_id'=>$this->userInfo->id_registro,
					'totalgeral'=>str_replace(",", ".",$formdata['totalgeral']),
					'primeirovencimento'=>Functions_Datas::inverteData($formdata['primeirovencimento']),
					'ultimovencimento'=>Functions_Datas::inverteData($formdata['vencimentoparcela'][$ultimo]),
					'totalparcelas'=>$formdata['numeroparcelas'],
					'parcelaspagas'=>0,
					'parcelasavencer'=>$formdata['numeroparcelas'],
					'totalpago'=>0,
					'nomelancamento'=>$formdata['nomelancamento'],
					'totalapagar'=> str_replace(",", ".",$formdata['totalgeral']),
					'statuslancamento'=>'1',
					'contapadrao'=>$formdata['contapadrao'],
					'categorialanc'=>$formdata['categoria'],
					'tipodocumento'=>$formdata['tipodocumento'],
					'numerodocumento'=>$formdata['numerodocumento'],
			);
			$id_lancamento = $db1->insert($data1);
			
			
			for($i=0;$i<count($formdata['parcela']);$i++){
			$data2 = array('id_lancamento'=>$id_lancamento,
					'datavencimento'=>Functions_Datas::inverteData($formdata['vencimentoparcela'][$i]),
					'valororiginal'=>str_replace(",", ".",$formdata['valorparcela'][$i]),
					'numeroparcela'=>$formdata['parcela'][$i],
					'quantidadeparcelas'=>$formdata['numeroparcelas'],
					'tipodocumento'=>$formdata['tipodocumento'],
					'numerodocumento'=>$formdata['numerodocumento'],
					'statuslancamento'=>$formdata['statuspgto'][$i],
					'id_banco'=>$formdata['contapadrao'],
					'user_liberacao'=>'0'
					);
			$id_destelanc = $db2->insert($data2);		
			
			
			}
			
			$this->view->id_lancamento = $id_lancamento;
			
		}
		
	}
	
	
	public function salvarPagamentosAction(){
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			$db1 = new Erp_Model_Financeiro_DadosPagamentos();
			$db2 = new Erp_Model_Financeiro_LancamentosPagamentos();
			$db3 = new Erp_Model_Financeiro_FluxoCaixa();
			$datahorahoje = date("Y-m-d H:i:s");
			$datahoje = date("Y-m-d");
			$ultimo = count($formdata['parcela']) - 1;
				
			$data1 = array("id_pessoa"=>$formdata['id_pessoa'],
					'id_empresa'=>$formdata['id_empresa'],
					'tiporegistro'=>'Manual',
					'id_registro_vinculado'=>'0',
					'datacadastro'=>$datahorahoje,
					'user_id'=>$this->userInfo->id_registro,
					'totalgeral'=>str_replace(",", ".",$formdata['totalgeral']),
					'primeirovencimento'=>Functions_Datas::inverteData($formdata['primeirovencimento']),
					'ultimovencimento'=>Functions_Datas::inverteData($formdata['vencimentoparcela'][$ultimo]),
					'totalparcelas'=>$formdata['numeroparcelas'],
					'parcelaspagas'=>0,
					'parcelasavencer'=>$formdata['numeroparcelas'],
					'totalpago'=>0,
					'totalapagar'=> str_replace(",", ".",$formdata['totalgeral']),
					'statuslancamento'=>'1',
					'contapadrao'=>$formdata['contapadrao'],
					'categorialanc'=>$formdata['categoria'],
					'tipodocumento'=>$formdata['tipodocumento'],
					'numerodocumento'=>$formdata['numerodocumento'],
			);
			$id_lancamento = $db1->insert($data1);
				
				
			for($i=0;$i<count($formdata['parcela']);$i++){
				$data2 = array('id_lancamento'=>$id_lancamento,
						'datavencimento'=>Functions_Datas::inverteData($formdata['vencimentoparcela'][$i]),
						'valororiginal'=>str_replace(",", ".",$formdata['valorparcela'][$i]),
						'numeroparcela'=>$formdata['parcela'][$i],
						'quantidadeparcelas'=>$formdata['numeroparcelas'],
						'tipodocumento'=>$formdata['tipodocumento'],
						'numerodocumento'=>$formdata['numerodocumento'],
						'statuslancamento'=>$formdata['statuspgto'][$i],
						'id_banco'=>$formdata['contapadrao'],
						'user_liberacao'=>'0'
				);
				$id_destelanc = $db2->insert($data2);
					
					
			}
				
			$this->view->id_lancamento = $id_lancamento;
				
		}
	
	}
	
	public function baixaRecebimentoAction(){
		$this->_helper->layout()->setLayout('modal');;
		$id = $this->_getParam('id');
		$dados = Erp_Model_Financeiro_LancamentosRecebimentos::getAllDataLanc($id);
		$datapopulate = array("id_registro"=>$dados['id_registro'],
				'datavencimento'=>$dados['id_registro'],
				'valorbaixa'=>number_format($dados['valororiginal'],2,',',''),
				'tipopagamento'=>$dados['tipodocumento'],
				'contapagamento'=>$dados['id_banco'],
				'numerodocumento'=>$dados['numerodocumento'],
				'categoria'=>$dados['categorialanc'],
				'valorpago'=>number_format($dados['valororiginal'],2,',',''),
				'valormultas'=>'0,00',
				'valorjuros'=>'0,00',
				'valordescontos'=>'0,00',
				'pessoapagamento'=>$dados['razaosocial'],
				'nomelancamento'=>$dados['nomelancamento'],
				'datapagamento'=>date('d/m/Y')
						
		);
		$this->view->dados = $dados;
		$form = new Erp_Form_Financeiro();
		$form->tipocatlanc = 1;
		$form->baixa();
		$form->populate($datapopulate);
		$this->view->form = $form;
		
		
		
	}
	
	
	public function baixaPagamentoAction(){
		$this->_helper->layout()->setLayout('modal');;
		$id = $this->_getParam('id');
		$dados = Erp_Model_Financeiro_LancamentosPagamentos::getAllDataLanc($id);
		$datapopulate = array("id_registro"=>$dados['id_registro'],
				'datavencimento'=>$dados['id_registro'],
				'valorbaixa'=>number_format($dados['valororiginal'],2,',',''),
				'tipopagamento'=>$dados['tipodocumento'],
				'contapagamento'=>$dados['id_banco'],
				'numerodocumento'=>$dados['numerodocumento'],
				'categoria'=>$dados['categorialanc'],
				'valorpago'=>number_format($dados['valororiginal'],2,',',''),
				'valormultas'=>'0,00',
				'valorjuros'=>'0,00',
				'valordescontos'=>'0,00',
				'pessoapagamento'=>$dados['razaosocial'],
				'nomelancamento'=>$dados['nomelancamento'],
				'datapagamento'=>date('d/m/Y')
	
		);
		$this->view->dados = $dados;
		$form = new Erp_Form_Financeiro();
		$form->tipocatlanc = 2;
		$form->baixa();
		$form->populate($datapopulate);
		$this->view->form = $form;
	
	
	
	}
	
	public function baixarRecebimentoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		if ($this->_request->isPost()) {
		try{	
			$formdata = $this->_request->getPost();
			$dados = Erp_Model_Financeiro_LancamentosRecebimentos::getAllDataLanc($formdata['id_registro']);
			$saldoatual = Erp_Model_Financeiro_FluxoCaixa::saldoAtual($formdata['contapagamento']);
			$saldocomesse = str_replace(",", ".",$formdata['valorpago']) + str_replace(",", ".",$saldoatual);
		$db1 = new Erp_Model_Financeiro_FluxoCaixa();
		$datafluxo = array("id_conta"=>$formdata['contapagamento'],
				'dataregistro'=>date('Y-m-d H:i:s'),
				'datalancamento'=>Functions_Datas::inverteData($formdata['datapagamento']),				
				'valorregistro'=>str_replace(",", ".",$formdata['valorpago']),
				'categoria'=>$formdata['categoria'],
				'situacao'=>'0',
				'id_pessoa'=>$this->userInfo->id_registro,
				'tipolancamento'=>'1',
				'saldocomesse'=>$saldocomesse,
				'observacoes'=>"Recebimento da Parcela {$dados['numeroparcela']}/{$dados['quantidadeparcelas']} Lançamento: {$dados['id_lancamento']}  ",
				'nomelancamento'=>$formdata['pessoapagamento']
		);
		$id_fluxo = $db1->insert($datafluxo);
		$db2 = new Erp_Model_Financeiro_DadosRecebimentos();
		$dataLanc = $db2->fetchRow("id_registro = '{$dados['id_lancamento']}'");
		$updlanc = array("parcelaspagas"=> $dataLanc->parcelaspagas + 1,
				'parcelasavencer'=>$dataLanc->parcelaspagas - 1,
				'totalpago'=>$dataLanc->totalpago + str_replace(",", ".",$formdata['valorpago']),
				'totalapagar'=>$dataLanc->totalapagar - str_replace(",", ".",$formdata['valorpago'])
		);
		$db2->update($updlanc,"id_registro = '{$dados['id_lancamento']}'");
		
				
		$db3 = new Erp_Model_Financeiro_LancamentosRecebimentos();
		$dataupreg = array('data_sysbaixa'=>date('Y-m-d H:i:s'),
				'user_sysbaixa'=>$this->userInfo->id_registro,
				'databaixa'=>Functions_Datas::inverteData($formdata['datapagamento']),
				'valorbaixa'=>str_replace(",", ".",$formdata['valorbaixa']),
				'valorjuros'=>str_replace(",", ".",$formdata['valorjuros']),
				'valormultas'=>str_replace(",", ".",$formdata['valormultas']),
				'valordescontos'=>str_replace(",", ".",$formdata['valordescontos']),
				'valorpago'=>str_replace(",", ".",$formdata['valorpago']),
				'tipopagamento'=>$formdata['tipopagamento'],
				'contapagamento'=>$formdata['contapagamento'],
				'id_registro_fluxo'=>$id_fluxo,
				'linhadigitavel'=>$formdata['linhadigitavel'],
				'statuslancamento'=>System_Model_SysConfigs::getConfig("StatusLancamentoEncerrado")
		);
		
		$db3->update($dataupreg, "id_registro = '{$formdata['id_registro']}'");
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		}
		
	}
	
	
	public function baixarPagamentoAction(){
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		if ($this->_request->isPost()) {
			try{
				$formdata = $this->_request->getPost();
				$dados = Erp_Model_Financeiro_LancamentosPagamentos::getAllDataLanc($formdata['id_registro']);
				$saldoatual = Erp_Model_Financeiro_FluxoCaixa::saldoAtual($formdata['contapagamento']);
				$saldocomesse = str_replace(",", ".",$saldoatual) - str_replace(",", ".",$formdata['valorpago']);
				$db1 = new Erp_Model_Financeiro_FluxoCaixa();
				$datafluxo = array("id_conta"=>$formdata['contapagamento'],
						'dataregistro'=>date('Y-m-d H:i:s'),
						'datalancamento'=>Functions_Datas::inverteData($formdata['datapagamento']),
						'valorregistro'=>str_replace(",", ".","-".$formdata['valorpago']),
						'categoria'=>$formdata['categoria'],
						'situacao'=>'0',
						'id_pessoa'=>$this->userInfo->id_registro,
						'tipolancamento'=>'2',
						'saldocomesse'=>$saldocomesse,
						'observacoes'=>"Pagamento da Parcela {$dados['numeroparcela']}/{$dados['quantidadeparcelas']} Lançamento: {$dados['id_lancamento']}  ",
						'nomelancamento'=>$formdata['pessoapagamento']
				);
				$id_fluxo = $db1->insert($datafluxo);
				$db2 = new Erp_Model_Financeiro_DadosPagamentos();
				$dataLanc = $db2->fetchRow("id_registro = '{$dados['id_lancamento']}'");
				$updlanc = array("parcelaspagas"=> $dataLanc->parcelaspagas + 1,
						'parcelasavencer'=>$dataLanc->parcelaspagas - 1,
						'totalpago'=>$dataLanc->totalpago + str_replace(",", ".",$formdata['valorpago']),
						'totalapagar'=>$dataLanc->totalapagar - str_replace(",", ".",$formdata['valorpago'])
				);
				$db2->update($updlanc,"id_registro = '{$dados['id_lancamento']}'");
	
	
				$db3 = new Erp_Model_Financeiro_LancamentosPagamentos();
				$dataupreg = array('data_sysbaixa'=>date('Y-m-d H:i:s'),
						'user_sysbaixa'=>$this->userInfo->id_registro,
						'databaixa'=>Functions_Datas::inverteData($formdata['datapagamento']),
						'valorbaixa'=>str_replace(",", ".",$formdata['valorbaixa']),
						'valorjuros'=>str_replace(",", ".",$formdata['valorjuros']),
						'valormultas'=>str_replace(",", ".",$formdata['valormultas']),
						'valordescontos'=>str_replace(",", ".",$formdata['valordescontos']),
						'valorpago'=>str_replace(",", ".",$formdata['valorpago']),
						'tipopagamento'=>$formdata['tipopagamento'],
						'contapagamento'=>$formdata['contapagamento'],
						'id_registro_fluxo'=>$id_fluxo,
						'linhadigitavel'=>$formdata['linhadigitavel'],
						'statuslancamento'=>System_Model_SysConfigs::getConfig("StatusLancamentoEncerrado")
				);
	
				$db3->update($dataupreg, "id_registro = '{$formdata['id_registro']}'");
			}catch(Exception $e){
				echo $e->getMessage();
			}
	
		}
	
	}
	
	
	public function liberarPagamentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
			
		$db3 = new Erp_Model_Financeiro_LancamentosPagamentos();
		$data = array("user_liberacao"=>$this->userInfo->id_registro,
				"datalibera"=>date('Y-m-d H:i:s')
		);
		try{
		if($tipo == 'parcela'){
			$db3->update($data,"id_registro = '$id'");
		}else{
			$db3->update($data,"id_lancamento = '$id'");
		}
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		
	}
	
	public function bloqueiaPagamentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
			
		$db3 = new Erp_Model_Financeiro_LancamentosPagamentos();
		$data = array("user_liberacao"=>0,
				"datalibera"=>null
		);
		
		if($tipo == 'parcela'){
			$db3->update($data,"id_registro = '$id'");
		}else{
			$db3->update($data,"id_lancamento = '$id'");
		}
	}
	
	
	public function cancelaPagamentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_POST['id'];
		$tipo = $_POST['tipo'];
		$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
		$result = $recaptcha->verify(
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']
		);
		if (!$result->isValid()) {
			echo "ERRO";
		}else{
		$db3 = new Erp_Model_Financeiro_LancamentosPagamentos();
		$data = array("statuslancamento"=>System_Model_SysConfigs::getConfig("StatusLancamentoCancelado"));
	
		if($tipo == 'parcela'){
			$db3->update($data,"id_registro = '$id'");
		}else{
			$db3->update($data,"id_lancamento = '$id'");
		}
		echo "OK";
		}
	}
	
	public function cancelaRecebimentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_POST['id'];
		$tipo = $_POST['tipo'];
		$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
		$result = $recaptcha->verify(
				$_POST['recaptcha_challenge_field'],
				$_POST['recaptcha_response_field']
		);
		if (!$result->isValid()) {
			echo "ERRO";
		}else{
			$db3 = new Erp_Model_Financeiro_LancamentosRecebimentos();
			$data = array("statuslancamento"=>System_Model_SysConfigs::getConfig("StatusLancamentoCancelado"));
	
			if($tipo == 'parcela'){
				$db3->update($data,"id_registro = '$id'");
			}else{
				$db3->update($data,"id_lancamento = '$id'");
			}
			echo "OK";
		}
	}
	
	
	public function reativaPagamentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
		$statusaberto = explode(',',System_Model_SysConfigs::getConfig("StatusLancamentoAberto"));	
		
		$db3 = new Erp_Model_Financeiro_LancamentosPagamentos();
		$data = array("statuslancamento"=> $statusaberto[0] );
	
		if($tipo == 'parcela'){
			$db3->update($data,"id_registro = '$id'");
		}else{
			$db3->update($data,"id_lancamento = '$id'");
		}
	}
	
	public function reativaRecebimentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
		$statusaberto = explode(',',System_Model_SysConfigs::getConfig("StatusLancamentoAberto"));
		$db3 = new Erp_Model_Financeiro_LancamentosRecebimentos();
		$data = array("statuslancamento"=> $statusaberto[0] );
	
		if($tipo == 'parcela'){
			$db3->update($data,"id_registro = '$id'");
		}else{
			$db3->update($data,"id_lancamento = '$id'");
		}
	}
	
	
	public function confirmCancelaPagarAction(){
		$this->view->configs = $this->configs;
		$this->_helper->layout()->setLayout('modal');;
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
		$this->view->tipo = $tipo;
		$this->view->id = $id;
		
	}
	
	public function confirmCancelaReceberAction(){
		$this->view->configs = $this->configs;
		$this->_helper->layout()->setLayout('modal');;
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
		$this->view->tipo = $tipo;
		$this->view->id = $id;
	
	}
	
	
	
	
	public function imprimirRecebimentoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		print_r($this->configs->Jasper);
		$id = $this->_getParam('id');
		//	print_r($this->configs->Jasper);
		//	echo $this->configs->Leader->Cliente->codigo;
			
		try {
			$report = new Reports_Jasper($this->configs->Jasper);
		
			$cogigocliente = $this->configs->Leader->Cliente->codigo;
		
			$report = $report->run("/reports/SysAdmin/recibo",'PDF',array('Parameter_1'=>"http://sysadmin.localhost/default/arquivos/get-logo-report/id/1",
					'NR_RECIBO'=>"0000012",
					'VALOR_MOEDA'=>"222,00",
					'NOMEEMPRESA'=>'LEADER TECNOLOGIA',
					'VALOREXTENSO'=>'DUZENTOS E VINTE E DOIS REAIS',
					'DESCRICAORECIBO'=>'TESTE DE RECIBO',
					'NOMEPESSOA'=>'TESTE DE NOME DE PESSOA',
					'DATAELOCAL'=>'SAO PAULO, 22 DE JUNHO DE 2013',
					'CPFRG'=>'219.811.268-05'
			),true);
			echo $report;
			//header("Content-type: application/pdf");
			//header('Content-Length: ' . filesize($report));
			//header("Content-Disposition: inline; filename=recibo.pdf");
			//readfile($report);
		
		} catch (\Exception $e) {
			echo $e->getMessage();
			die;
		}
		
		
		
	}
	
	public function imprimirPagamentoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$type = $_POST['typeprinter'];
		$id_lancamento = $_POST['id_lancamento'];
		
		switch($type){
			case 'recibo':
				$report = new Reports_Jasper($this->configs->Jasper);
				
				$report = $report->run("/reports/recibo",'PDF',array('Parameter_1'=>"http://sysadmin.localhost/default/arquivos/get-logo-report/id/1",
						'NR_RECIBO'=>"0000012",
						'VALOR_MOEDA'=>"222,00",
						'NOMEEMPRESA'=>'LEADER TECNOLOGIA',
						'VALOREXTENSO'=>'DUZENTOS E VINTE E DOIS REAIS',
						'DESCRICAORECIBO'=>'TESTE DE RECIBO',
						'NOMEPESSOA'=>'TESTE DE NOME DE PESSOA',
						'DATAELOCAL'=>'SAO PAULO, 22 DE JUNHO DE 2013',
						'CPFRG'=>'219.811.268-05'
				),true);
				echo $report;
			//	header("Content-type: application/pdf");
			//	header('Content-Length: ' . filesize($report));
			//	header("Content-Disposition: inline; filename=recibo.pdf");
			//	readfile($report);
				
			break;
			case 'formlib':
				$report = new Reports_Jasper($this->configs->Jasper);
				
				$report = $report->run("/reports/SysAdmin/liberacaodeparcela",'PDF',array(
						'LogoTipo'=>Functions_Auxilio::getLogoUrl(),
						'ID_Registro'=>$id_lancamento
				),true);
				echo $report;
			break;
			case 'parcela':
			break;
			case 'lancamento':
			break;
		}
		
		
	
	}
	
	public function editaParcelaPagarAction(){
		
		$this->view->configs = $this->configs;
		$id = $this->_getParam('id');
		$db = new Erp_Model_Financeiro_LancamentosPagamentos();
		$db2 = new Erp_Model_Financeiro_DadosPagamentos();
		if ($this->_request->isPost()) {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			
			$dadosL = array('id_pessoa'=>$_POST['id_pessoa'],
					'id_empresa'=>$_POST['id_empresa'],
					'nomelancamento'=>$_POST['nomelancamento'],
					'categorialanc'=>$_POST['categoria'],
					'contapadrao'=>$_POST['contapadrao'],
					'totalgeral'=> str_replace(',', '.', $_POST['totalgeral']),
					'primeirovencimento'=> Functions_Datas::inverteData($_POST['primeirovencimento']),
					'tipodocumento'=> $_POST['tipodocumento'],
					'numerodocumento'=> $_POST['numerodocumento']
				
			);
			
			$dadosP = array('valororiginal'=>  str_replace(',', '.',$_POST['lanc_valororiginal']),
					'numerodocumento'=>$_POST['lanc_numerodocumento'],
					'datavencimento'=> Functions_Datas::inverteData($_POST['lanc_datavencimento']),
					'numeroparcela'=>$_POST['lanc_numeroparcela'],
					'linhadigitavel'=>$_POST['lanc_linhadigitavel']
					
			);
		try{
			
			$db->update($dadosP,"id_registro = '{$_POST['lanc_id_registro']}'");

			$db2->update($dadosL,"id_registro = '{$_POST['id_registro']}'");
			//print_r($_POST);
			echo "Parcela Atualizada com Sucesso";
				
		}catch (Exception $e){
			echo $e->getMessage();
		}
			
		}else{
			$this->_helper->layout()->setLayout('modal');;
		
			$dadosL = $db->fetchRow("id_registro = '$id'");
			$dadosP = $db2->fetchRow("id_registro = '{$dadosL->id_lancamento}'")->toArray();
			$this->view->dadosL = $dadosL;
			$this->view->dadosP = $dadosP;
				
			$dadosP['pessoapagamento'] = Cadastros_Model_Pessoas::getNomeEmpresa($dadosP['id_pessoa']);
			$dadosP['categoria'] = $dadosP['categorialanc'];
			$dadosP['primeirovencimento'] = Functions_Datas::MyDateTime($dadosP['primeirovencimento']);
			$dadosP['totalgeral'] = number_format($dadosP['totalgeral'],2,',','');
			$form = new Erp_Form_Financeiro();
			$form->tipocatlanc =2;
			$form->lancamento();
			$form->populate($dadosP);
			$this->view->form = $form;
		
		}
		
		
		
	}
	
	public function editaParcelaReceberAction(){
		
		$this->view->configs = $this->configs;
		$id = $this->_getParam('id');
		$db = new Erp_Model_Financeiro_LancamentosRecebimentos();
		$db2 = new Erp_Model_Financeiro_DadosRecebimentos();
		if ($this->_request->isPost()) {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
				
			$dadosL = array('id_pessoa'=>$_POST['id_pessoa'],
					'id_empresa'=>$_POST['id_empresa'],
					'nomelancamento'=>$_POST['nomelancamento'],
					'categorialanc'=>$_POST['categoria'],
					'contapadrao'=>$_POST['contapadrao'],
					'totalgeral'=> str_replace(',', '.', $_POST['totalgeral']),
					'primeirovencimento'=> Functions_Datas::inverteData($_POST['primeirovencimento']),
					'tipodocumento'=> $_POST['tipodocumento'],
					'numerodocumento'=> $_POST['numerodocumento']
		
			);
				
			$dadosP = array('valororiginal'=>  str_replace(',', '.',$_POST['lanc_valororiginal']),
					'numerodocumento'=>$_POST['lanc_numerodocumento'],
					'datavencimento'=> Functions_Datas::inverteData($_POST['lanc_datavencimento']),
					'numeroparcela'=>$_POST['lanc_numeroparcela'],
					'linhadigitavel'=>$_POST['lanc_linhadigitavel']
						
			);
			try{
					
				$db->update($dadosP,"id_registro = '{$_POST['lanc_id_registro']}'");
		
				$db2->update($dadosL,"id_registro = '{$_POST['id_registro']}'");
				//print_r($_POST);
				echo "Parcela Atualizada com Sucesso";
		
			}catch (Exception $e){
				echo $e->getMessage();
			}
				
		}else{
			$this->_helper->layout()->setLayout('modal');;
		
			$dadosL = $db->fetchRow("id_registro = '$id'");
			$dadosP = $db2->fetchRow("id_registro = '{$dadosL->id_lancamento}'")->toArray();
			$this->view->dadosL = $dadosL;
			$this->view->dadosP = $dadosP;
		
			$dadosP['pessoapagamento'] = Cadastros_Model_Pessoas::getNomeEmpresa($dadosP['id_pessoa']);
			$dadosP['categoria'] = $dadosP['categorialanc'];
			$dadosP['primeirovencimento'] = Functions_Datas::MyDateTime($dadosP['primeirovencimento']);
			$dadosP['totalgeral'] = number_format($dadosP['totalgeral'],2,',','');
			$form = new Erp_Form_Financeiro();
			$form->tipocatlanc =1;
			$form->lancamento();
			$form->populate($dadosP);
			$this->view->form = $form;
		
		}
	
	}
	
	public function editaLancamentoPagarAction(){
		$this->view->configs = $this->configs;
		
		$id = $this->_getParam('id');
		$db = new Erp_Model_Financeiro_LancamentosPagamentos();
		$db2 = new Erp_Model_Financeiro_DadosPagamentos();
		
		if ($this->_request->isPost()) {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			
			$dadosL = array('id_pessoa'=>$_POST['id_pessoa'],
					'id_empresa'=>$_POST['id_empresa'],
					'nomelancamento'=>$_POST['nomelancamento'],
					'categorialanc'=>$_POST['categoria'],
					'contapadrao'=>$_POST['contapadrao'],
					'totalgeral'=> str_replace(',', '.', $_POST['totalgeral']),
					'primeirovencimento'=> Functions_Datas::inverteData($_POST['primeirovencimento']),
					'tipodocumento'=> $_POST['tipodocumento'],
					'numerodocumento'=> $_POST['numerodocumento']
			
			);
			for($i=0;$i<count($_POST['lanc_id_registro']);$i++){	
			$dadosP = array('valororiginal'=>  str_replace(',', '.',$_POST['lanc_valororiginal'][$i]),
					'numerodocumento'=>$_POST['lanc_numerodocumento'][$i],
					'datavencimento'=> Functions_Datas::inverteData($_POST['lanc_datavencimento'][$i]),
					'numeroparcela'=>$_POST['lanc_numeroparcela'][$i],
					'linhadigitavel'=>$_POST['lanc_linhadigitavel'][$i]
						
			);
			$db->update($dadosP,"id_registro = '{$_POST['lanc_id_registro'][$i]}'");
			$dadosP = null;
			}
			
			$db2->update($dadosL,"id_registro = '{$_POST['id_registro']}'");
			
			
			echo "Lançamento Atualizado com Sucesso";
		}else{
			$lancamentosbaixados = System_Model_SysConfigs::getConfig("StatusLancamentoEncerrado");
			$this->_helper->layout()->setLayout('modal');
			$dadosL = $db->fetchAll("id_lancamento = '$id' and statuslancamento not in ($lancamentosbaixados)");
			$dadosP = $db2->fetchRow("id_registro = '$id'")->toArray();
			$this->view->dadosL = $dadosL;
			$this->view->dadosP = $dadosP;
			$dadosP['pessoapagamento'] = Cadastros_Model_Pessoas::getNomeEmpresa($dadosP['id_pessoa']);
			$dadosP['categoria'] = $dadosP['categorialanc'];
			$dadosP['primeirovencimento'] = Functions_Datas::MyDateTime($dadosP['primeirovencimento']);
			$dadosP['totalgeral'] = number_format($dadosP['totalgeral'],2,',','');
			$form = new Erp_Form_Financeiro();
			$form->tipocatlanc =2;
			$form->lancamento();
			$form->populate($dadosP);
			$this->view->form = $form;
		}
			
	}
	
	public function editaLancamentoReceberAction(){
		
		$this->view->configs = $this->configs;
		
		$id = $this->_getParam('id');
		$db = new Erp_Model_Financeiro_LancamentosRecebimentos();
		$db2 = new Erp_Model_Financeiro_DadosRecebimentos();
		
		if ($this->_request->isPost()) {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
				
			$dadosL = array('id_pessoa'=>$_POST['id_pessoa'],
					'id_empresa'=>$_POST['id_empresa'],
					'nomelancamento'=>$_POST['nomelancamento'],
					'categorialanc'=>$_POST['categoria'],
					'contapadrao'=>$_POST['contapadrao'],
					'totalgeral'=> str_replace(',', '.', $_POST['totalgeral']),
					'primeirovencimento'=> Functions_Datas::inverteData($_POST['primeirovencimento']),
					'tipodocumento'=> $_POST['tipodocumento'],
					'numerodocumento'=> $_POST['numerodocumento']
						
			);
			for($i=0;$i<count($_POST['lanc_id_registro']);$i++){
				$dadosP = array('valororiginal'=>  str_replace(',', '.',$_POST['lanc_valororiginal'][$i]),
						'numerodocumento'=>$_POST['lanc_numerodocumento'][$i],
						'datavencimento'=> Functions_Datas::inverteData($_POST['lanc_datavencimento'][$i]),
						'numeroparcela'=>$_POST['lanc_numeroparcela'][$i],
						'linhadigitavel'=>$_POST['lanc_linhadigitavel'][$i]
		
				);
				$db->update($dadosP,"id_registro = '{$_POST['lanc_id_registro'][$i]}'");
				$dadosP = null;
			}
				
			$db2->update($dadosL,"id_registro = '{$_POST['id_registro']}'");
				
				
			echo "Lançamento Atualizado com Sucesso";
		}else{
			$lancamentosbaixados = System_Model_SysConfigs::getConfig("StatusLancamentoEncerrado");
			$this->_helper->layout()->setLayout('modal');
			$dadosL = $db->fetchAll("id_lancamento = '$id' and statuslancamento not in ($lancamentosbaixados)" );
			$dadosP = $db2->fetchRow("id_registro = '$id'")->toArray();
			$this->view->dadosL = $dadosL;
			$this->view->dadosP = $dadosP;
			$dadosP['pessoapagamento'] = Cadastros_Model_Pessoas::getNomeEmpresa($dadosP['id_pessoa']);
			$dadosP['categoria'] = $dadosP['categorialanc'];
			$dadosP['primeirovencimento'] = Functions_Datas::MyDateTime($dadosP['primeirovencimento']);
			$dadosP['totalgeral'] = number_format($dadosP['totalgeral'],2,',','');
			$form = new Erp_Form_Financeiro();
			$form->tipocatlanc =1;
			$form->lancamento();
			$form->populate($dadosP);
			$this->view->form = $form;
		}
		
	}
	
	
	public function cancelaPagamentoLancadoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
	
	
		$dados = Erp_Model_Financeiro_LancamentosPagamentos::getAllDataLanc($id);
		$saldoatual = Erp_Model_Financeiro_FluxoCaixa::saldoAtual($dados['contapagamento']);
		$saldocomesse = str_replace(",", ".",$saldoatual) + str_replace(",", ".",$dados['valorpago']);
		$db1 = new Erp_Model_Financeiro_FluxoCaixa();
		
		$datafluxo = array("id_conta"=>$dados['contapagamento'],
				'dataregistro'=>date('Y-m-d H:i:s'),
				'datalancamento'=>$dados['databaixa'],
				'valorregistro'=>str_replace(",", ".",$dados['valorpago']),
				'categoria'=>$dados['categorialanc'],
				'situacao'=>'0',
				'id_pessoa'=>$this->userInfo->id_registro,
				'tipolancamento'=>'1',
				'saldocomesse'=>$saldocomesse,
				'observacoes'=>"Cancelamento do Pagamento da Parcela {$dados['numeroparcela']}/{$dados['quantidadeparcelas']} Lançamento: {$dados['id_lancamento']}  ",
				'nomelancamento'=>$dados['razaosocial']
		);
		$id_fluxo = $db1->insert($datafluxo);
		
		$db2 = new Erp_Model_Financeiro_DadosPagamentos();
		$dataLanc = $db2->fetchRow("id_registro = '{$dados['id_lancamento']}'");
		$updlanc = array("parcelaspagas"=> $dataLanc->parcelaspagas - 1,
				'parcelasavencer'=>$dataLanc->parcelaspagas + 1,
				'totalpago'=>$dataLanc->totalpago - str_replace(",", ".",$dados['valorpago']),
				'totalapagar'=>$dataLanc->totalapagar + str_replace(",", ".",$dados['valorpago'])
		);
		$db2->update($updlanc,"id_registro = '{$dados['id_lancamento']}'");
		
		$statusaberto = explode(',',System_Model_SysConfigs::getConfig("StatusLancamentoAberto"));
		$db3 = new Erp_Model_Financeiro_LancamentosPagamentos();
		$dataupreg = array('data_sysbaixa'=>null,
				'user_sysbaixa'=>null,
				'databaixa'=>null,
				'valorbaixa'=>null,
				'valorjuros'=>null,
				'valormultas'=>null,
				'valordescontos'=>null,
				'valorpago'=>null,
				'tipopagamento'=>null,
				'contapagamento'=>null,
				'id_registro_fluxo'=>null,
				'linhadigitavel'=>null,
				'statuslancamento'=>$statusaberto[0]
		);
		
		$db3->update($dataupreg, "id_registro = '{$dados['id_registro']}'");
		
	}
	
	public function cancelaRecebimentoLancadoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
		
		
		$dados = Erp_Model_Financeiro_LancamentosRecebimentos::getAllDataLanc($id);
		$saldoatual = Erp_Model_Financeiro_FluxoCaixa::saldoAtual($dados['contapagamento']);
		$saldocomesse = str_replace(",", ".",$saldoatual) - str_replace(",", ".",$dados['valorpago']);
		$db1 = new Erp_Model_Financeiro_FluxoCaixa();
		
		$datafluxo = array("id_conta"=>$dados['contapagamento'],
				'dataregistro'=>date('Y-m-d H:i:s'),
				'datalancamento'=>$dados['databaixa'],
				'valorregistro'=>str_replace(",", ".",'-'.$dados['valorpago']),
				'categoria'=>$dados['categorialanc'],
				'situacao'=>'0',
				'id_pessoa'=>$this->userInfo->id_registro,
				'tipolancamento'=>'1',
				'saldocomesse'=>$saldocomesse,
				'observacoes'=>"Cancelamento do Recebimento da Parcela {$dados['numeroparcela']}/{$dados['quantidadeparcelas']} Lançamento: {$dados['id_lancamento']}  ",
				'nomelancamento'=>$dados['razaosocial']
		);
		$id_fluxo = $db1->insert($datafluxo);
		
		$db2 = new Erp_Model_Financeiro_DadosRecebimentos();
		$dataLanc = $db2->fetchRow("id_registro = '{$dados['id_lancamento']}'");
		$updlanc = array("parcelaspagas"=> $dataLanc->parcelaspagas + 1,
				'parcelasavencer'=>$dataLanc->parcelaspagas - 1,
				'totalpago'=>$dataLanc->totalpago + str_replace(",", ".",$dados['valorpago']),
				'totalapagar'=>$dataLanc->totalapagar - str_replace(",", ".",$dados['valorpago'])
		);
		$db2->update($updlanc,"id_registro = '{$dados['id_lancamento']}'");
		
		$statusaberto = explode(',',System_Model_SysConfigs::getConfig("StatusLancamentoAberto"));
		$db3 = new Erp_Model_Financeiro_LancamentosRecebimentos();
		$dataupreg = array('data_sysbaixa'=>null,
				'user_sysbaixa'=>null,
				'databaixa'=>null,
				'valorbaixa'=>null,
				'valorjuros'=>null,
				'valormultas'=>null,
				'valordescontos'=>null,
				'valorpago'=>null,
				'tipopagamento'=>null,
				'contapagamento'=>null,
				'id_registro_fluxo'=>null,
				'linhadigitavel'=>null,
				'statuslancamento'=>$statusaberto[0]
		);
		
		$db3->update($dataupreg, "id_registro = '{$dados['id_registro']}'");
		
	}
	
	
	public function conciliarLancamentoAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$data = array('situacao'=>'1');
		$db = new Erp_Model_Financeiro_FluxoCaixa();
		$db->update($data, "id_registro = '$id'");
		
		
		
	}
	
	public function novoLancamentoCaixaAction(){
		$db = new Erp_Model_Financeiro_FluxoCaixa();
		if ($this->_request->isPost()) {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();

			if($_POST['id_registro'] <> '' ||$_POST['id_registro'] <> '' ){
				$dadosupd = array('id_conta'=>$_POST['id_conta'],
						'datalancamento'=> Functions_Datas::inverteData($_POST['datalancamento']),
						'valorregistro'=> str_replace(',','.',$_POST['valorregistro']),
						'categoria'=>$_POST['categoria'],
						'tipolancamento'=>$_POST['tipolancamento'],
						'observacoes'=>$_POST['observacoes'],
						'nomelancamento'=>$_POST['nomelancamento'],
						'dataregistro'=> date('Y-m-d H:i:s') ,
						'id_user'=>$this->userInfo->id_registro,
						'id_pessoa'=>$_POST['id_pessoa']
						
				);
				
				$db->update($dadosupd,"id_registro = '{$_POST['id_registro']}'");
				echo "Lançamento Attualizado com sucesso!";
			}else{
				if($_POST['tipolancamento'] <> '3'){
					$valor = $_POST['valorregistro'];
					if($_POST['tipolancamento'] == 1){
						$valor = str_replace(',','.',$valor);
					}else{
						$valor = '-'.str_replace(',','.',$valor);
					}
					
					
					$dados = array('id_conta'=>$_POST['id_conta'],
							'datalancamento'=> Functions_Datas::inverteData($_POST['datalancamento']),
							'valorregistro'=>$valor,
							'categoria'=>$_POST['categoria'],
							'tipolancamento'=>$_POST['tipolancamento'],
							'observacoes'=>$_POST['observacoes'],
							'nomelancamento'=>$_POST['nomelancamento'],
							'dataregistro'=> date('Y-m-d H:i:s') ,
							'id_user'=>$this->userInfo->id_registro,
							'id_pessoa'=>$_POST['id_pessoa']
				
					);
				$db->insert($dados);
				echo "Lançamento Inserido com sucesso !";
				}else{
					
					$dados = array('id_conta'=>$_POST['id_conta'],
							'datalancamento'=> Functions_Datas::inverteData($_POST['datalancamento']),
							'valorregistro'=>'-'.str_replace(',','.',$_POST['valorregistro']),
							'categoria'=>$_POST['categoria'],
							'tipolancamento'=>$_POST['tipolancamento'],
							'observacoes'=>$_POST['observacoes'],
							'nomelancamento'=>$_POST['nomelancamento'],
							'dataregistro'=> date('Y-m-d H:i:s') ,
							'id_user'=>$this->userInfo->id_registro,
							'id_pessoa'=>$_POST['id_pessoa']
					
					);
					$db->insert($dados);
					
					$dados = array('id_conta'=>$_POST['contatransferencia'],
							'datalancamento'=> Functions_Datas::inverteData($_POST['datalancamento']),
							'valorregistro'=> str_replace(',','.',$_POST['valorregistro']),
							'categoria'=>$_POST['categoria'],
							'tipolancamento'=>$_POST['tipolancamento'],
							'observacoes'=>$_POST['observacoes'],
							'nomelancamento'=>$_POST['nomelancamento'],
							'dataregistro'=> date('Y-m-d H:i:s') ,
							'id_user'=>$this->userInfo->id_registro,
							'id_pessoa'=>$_POST['id_pessoa']
								
					);
					$db->insert($dados);
					
					echo "Transferência realizada com sucesso!";
					
				}
			}
			
			
		}else{
		$this->_helper->layout()->setLayout('modal');
		$id = $this->_getParam('id');
		$form = new Erp_Form_Financeiro();
		$form->fluxoCaixa();
		if($id <> ''){
			$this->view->tipo = 'Edit';
			
			$dados  = $db->fetchRow("id_registro = '$id'")->toArray();
			$this->view->tipo = 'Edit';
			$dados['valorregistro'] = number_format($dados['valorregistro'],2,',','');
			$dados['datalancamento'] = Functions_Datas::MyDateTime($dados['datalancamento']);
			$form->populate($dados);
		}else{
			$this->view->tipo = 'New';
		}
		
		$this->view->form = $form;
		
		}
		
		
		
		
		
	}
	

	
}