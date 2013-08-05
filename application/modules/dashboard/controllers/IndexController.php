<?php
class Dashboard_IndexController extends Zend_Controller_Action{
	
	public $log;
	public $configs;
	public $cache;
	private $SystemStatus;
	
	
	public function init(){
		
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
		$this->SystemStatus = Zend_Registry::get('StatusSistema');
		
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
		$this->_helper->layout()->setLayout('dashboard');
		
		
		
		
	}
	
	
	public function indexAction(){
		
	
		
		$message = '';
		if(is_file(UPDATES_PATH . '/sql/update.sql')){
			$this->view->sqlUpdate = true;
			$message[] = Functions_Messages::renderAlert("<strong><a href=\"/sistema/updates\">Existe uma nova atualização para o sistema clique aqui para iniciar!</a></strong>",'info');
		}
		
		
		$this->view->UserInfo = Zend_Auth::getInstance()->getStorage()->read();
		
		$db = new System_Model_Empresas();
		$dadosempresas = $db->fetchAll();
		$dbnf = new System_Model_EmpresasNF();
		if($dadosempresas){
			foreach($dadosempresas as $empresa){
				$dadosnf = $dbnf->fetchRow("id_empresa = '{$empresa->id_registro}'");
				if($dadosnf->validadecertificado){
				$validade = Functions_Datas::MyDateTime($dadosnf->validadecertificado);
				$time1 = strtotime(date('Y-m-d'));
				$time2 = strtotime($dadosnf->validadecertificado);
				if($time2 < $time1){
					$message[] = Functions_Messages::renderAlert("<strong>O Certificado Digital da empresa {$empresa->nomefantasia} esta vencido, providencie a renovação</strong>",'erro');
				}else{
								
					$Val2 = strtotime(Functions_Datas::inverteData(Functions_Datas::SubtraiData($validade, 45)));
					if($Val2 <= $time1){
						$message[] = Functions_Messages::renderAlert("<strong>O Certificado Digital da empresa {$empresa->nomefantasia} vai vencer em {$validade}, providencie a renovação</strong>");
					}
				}
				}else{
					$message[] = Functions_Messages::renderAlert("<strong>A empresa {$empresa->nomefantasia} não possui um certificado digital para emissão de Notas Fiscais</strong>",'erro');
				}
			}
			
		}elseif(!$dadosempresas->toArray()){
			$message[] = Functions_Messages::renderAlert("<strong>Voce deve cadastrar uma empresa no sistema!</strong>",'erro');
		}
		if(!System_Model_Empresas::getEmpresaPadrao()){
			$message[] = Functions_Messages::renderAlert("<strong><a href=\"/sistema/empresas\">Voce deve cadastrar uma empresa no sistema e torna-la padrão!</a></strong>",'erro');
		}
		
		
		$this->view->AlertSysMessage = $message;
		
		if(!$results = $this->cache->load("GraficoRealizadoPrevisto")){
			$meses = Functions_Datas::getMonthsForData();
			foreach($meses as $mes){
				$mesano = explode('/',$mes);
				$recebimentoprevisto[] = Erp_Model_Financeiro_LancamentosRecebimentos::getValoresRecebimentoMes($mesano[0],$mesano[1],"1,2");
				$recebimentorealizado[] = Erp_Model_Financeiro_LancamentosRecebimentos::getValoresRecebimentoMes($mesano[0],$mesano[1],"3");
				$pagamentoprevisto[] = Erp_Model_Financeiro_LancamentosPagamentos::getValoresPagamentoMes($mesano[0],$mesano[1],"1,2");
				$pagamentorealizado[] = Erp_Model_Financeiro_LancamentosPagamentos::getValoresPagamentoMes($mesano[0],$mesano[1],"3");
			}	
		
			$mesano = null;
			$results['recebimentoprevisto'] = $recebimentoprevisto;
			$results['recebimentorealizado'] = $recebimentorealizado;
			$results['pagamentoprevisto'] = $pagamentoprevisto;
			$results['pagamentorealizado'] = $pagamentorealizado;
			$this->cache->save($results,"GraficoRealizadoPrevisto");
		}
		
		
		$this->view->recPrevistoGrafico = implode(',',$results['recebimentoprevisto']);
		$this->view->recRealizadoGrafico = implode(',',$results['recebimentorealizado']);
		$this->view->pagPrevistoGrafico = implode(',',$results['pagamentoprevisto']);
		$this->view->pagRealizadoGrafico = implode(',',$results['pagamentorealizado']);
		
	}
	

	
}