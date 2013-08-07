<?php
class System_FunctionsController extends Zend_Controller_Action{
	
	
	public $log;
	public $configs;
	public $cache;
	public $userInfo;
	
	public function init(){
		ini_set("soap.wsdl_cache_enabled", 0);
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
	
	public function searchCadastroAction(){
			
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$cnpj = $_GET['cnpj'];
			
			$dados = Functions_Verificadores::validaCNPJCFP($cnpj);
		
			if($dados == 1){
			
				$db = new Cadastros_Model_Pessoas();
				if($data = $db->fetchRow("cnpj = '$cnpj'")){
					$dados = $data->toArray();
					echo "1|{$dados['id_registro']}";
				}else{
					echo "0|0";
				}
			}else{
				echo "2|2";
			}
		
	}
	
	
	public function bloqcadastroAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Cadastros_Model_Pessoas();
		$dados = Cadastros_Model_Pessoas::getCadastro($id);
		$data['bloqcad'] = date('Y-m-d H:i:s');
		$data['userbloq'] = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$log = Zend_Registry::get('log');
		try{
			$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
			$id = $db->update($data,"id_registro = '$id'");
			$log->log("Cadastro do Cliente {$dados['razaosocial']} bloqueado por  {$usuario} } ",Zend_Log::INFO);
			echo "1|Cadastro Bloqueado com sucesso!";
		}catch (Exception $e){
			$this->log->log("Erro No bloqueio cadastro do cliente {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
			echo "0|Erro no bloqueio do cadastro {$e->getMessage()}";
		}
		
	}
	
	
	public function unbloqcadastroAction(){
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Cadastros_Model_Pessoas();
		$dados = Cadastros_Model_Pessoas::getCadastro($id);
		$data['bloqcad'] = null;
		$data['userbloq'] = null;
		$log = Zend_Registry::get('log');
		try{
			$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
			$id = $db->update($data,"id_registro = '$id'");
			$log->log("Cadastro do Cliente {$dados['razaosocial']} desbloqueado por  {$usuario} } ",Zend_Log::INFO);
			echo "1|Cadastro desbloqueado com sucesso!";
		}catch (Exception $e){
			$this->log->log("Erro No desbloqueio cadastro do cliente {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
			echo "0|Erro no desbloqueio do cadastro {$e->getMessage()}";
		}
	
	}
	
	
	public function solicitaunbloqcadastroAction(){
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$id = $_GET['id'];
		$dados = Cadastros_Model_Pessoas::getCadastro($id);
		$log = Zend_Registry::get('log');
		$Userto = System_Model_Users::whoIs($dados['userbloq']);
		try{
			$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
			$db = new System_Model_Messages();
			$savedata['user_from'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
			$savedata['user_to'] = $dados['userbloq'];
			$savedata['statusmessage'] = '1';
			$savedata['assuntomessage'] = "Desbloqueio de Cadastro";
			$savedata['contentmessage'] = "<strong>Caro {$Userto}</strong><br>Foi Solicitado pelo usuário {$usuario} o desbloqueio do cadastro de {$dados['razaosocial']} por favor acesse o link abaixo e desbloqueio o cadastro caso seja necessário<br> <a href=\"/cadastros/clientes/cadastro/id/{$dados['id_registro']}\">Desbloquear cadastro de {$dados['razaosocial']} </a> ";
			$savedata['datemessage'] = date('Y-m-d H:i:s');
			$savedata['messageprioridade'] = "1";
			$savedata['flagmessage'] = "1";
			$db->insert($savedata);			
			$log->log("Solicitação de desbloqueio de cadastro do Cliente {$dados['razaosocial']} solicitada por  {$usuario} } ",Zend_Log::INFO);
			echo "1|Solicitação de desbloqueio de cadastro efetuada com sucesso!";
		}catch (Exception $e){
			$log->log("Erro No desbloqueio cadastro do cliente {$formData['razaosocial']}, usuário: {$userInfo->nomecompleto} ERRO: {$e->getMessage()} ",Zend_Log::ERR);
			echo "0|Erro na solicitação de desbloqueio do cadastro {$e->getMessage()}";
		}
	
	}
	
	public function cepAction(){
	//	ini_set("soap.wsdl_cache_enabled", "0");
		$cep = $_GET['cep'];
		$return = $_GET['return'];
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		try{
		$client = new Zend_Soap_Client($this->configs->Leader->SoapServer . "/cep?wsdl",array('encoding' => 'UTF-8', 'soap_version' => SOAP_1_2,'location'=> $this->configs->Leader->SoapServer . "/cep"));
		$cepr = $client->getlogradouro($this->configs->Leader->Cliente->codigo,$this->configs->Leader->SoapKey,$cep);
		if($return == 'json'){
			echo json_encode($cepr);
		}else{
			$db = new System_Model_Cep();
			$cepr = $db->fetchRow("cep = '$cep'");
			print_r($cepr);
			
		}
		}catch (Exception $e){
			echo $e->getMessage();
		}
	}
	
	
	public function getenderecopessoaAction(){
		$id = $_GET['id'];
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Cadastros_Model_Enderecos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
		
	}
	
	public function getcontatopessoaAction(){
		$id = $_GET['id'];
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$db = new Cadastros_Model_Contatos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	
	}
	
	
	
	public function bloqprodutoAction(){
	
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Cadastros_Model_Produtos();
		$data['datebloq'] = date('Y-m-d H:i:s');
		$data['userbloq'] = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
		$data['bloqcad'] = 1;
		$log = Zend_Registry::get('log');
		try{
			$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
			$id = $db->update($data,"id_registro = '$id'");
			echo "1|Cadastro Bloqueado com sucesso!";
		}catch (Exception $e){
			echo "0|Erro no bloqueio do cadastro {$e->getMessage()}";
		}
	
	}
	
	
	public function unbloqprodutoAction(){
	
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender();
	$id = $_GET['id'];
	$db = new Cadastros_Model_Produtos();
	$data['bloqcad'] = null;
	$data['userbloq'] = null;
	$data['bloqcad'] = 0;
	try{
	$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
	$id = $db->update($data,"id_registro = '$id'");
		echo "1|Cadastro desbloqueado com sucesso!";
		}catch (Exception $e){
					echo "0|Erro no desbloqueio do cadastro {$e->getMessage()}";
	}
	
	}
	
	
	public function solicitaunbloqprodutoAction(){
	
	$this->_helper->layout->disableLayout();
	$this->_helper->viewRenderer->setNoRender();
	
	$id = $_GET['id'];
	$dados = Cadastros_Model_Produtos::getProduto($id);
	$log = Zend_Registry::get('log');
	$Userto = System_Model_Users::whoIs($dados['userbloq']);
	try{
	$usuario = Zend_Auth::getInstance()->getStorage()->read()->nomecompleto;
	$db = new System_Model_Messages();
	$savedata['user_from'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
			$savedata['user_to'] = $dados['userbloq'];
			$savedata['statusmessage'] = '1';
			$savedata['assuntomessage'] = "Desbloqueio de Produto";
				$savedata['contentmessage'] = "<strong>Caro {$Userto}</strong><br>Foi Solicitado pelo usuário {$usuario} o desbloqueio do cadastro de produto {$dados['nomeproduto']} por favor acesse o link abaixo e desbloqueio o cadastro caso seja necessário<br> <a href=\"/cadastros/produtos/abrir/id/{$dados['id_registro']}\">Desbloquear cadastro de {$dados['nomeproduto']} </a> ";
			$savedata['datemessage'] = date('Y-m-d H:i:s');
			$savedata['messageprioridade'] = "1";
						$savedata['flagmessage'] = "1";
						$db->insert($savedata);
						echo "1|Solicitação de desbloqueio de cadastro efetuada com sucesso!";
		}catch (Exception $e){
							echo "0|Erro na solicitação de desbloqueio do cadastro {$e->getMessage()}";
						}
	
						}
						
	public function testCepAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$cep = new Soap_Cep();
		print_r($cep->getlogradouro($this->configs->Leader->Cliente->codigo, $this->configs->Leader->SoapKey, "02514010"));
		
		
	}
						
	
}
