<?php 
class Crm_OrdemServicoController extends Zend_Controller_Action{


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
	
	public function novoAction(){
		$form = new Crm_Form_OrdemServicos();
		$form->novo();
		$form->populate(array('cod_os'=>Functions_Tickets::gerenateOsProtocol(),
				'status_os'=>Crm_Model_Os_Status::getRegistroPadrao(),
				'tipo_os'=>Crm_Model_Os_Tipos::getRegistroPadrao(),
				'id_empresa'=>System_Model_Empresas::getEmpresaPadrao()));
		$this->view->formulario = $form;
		
			if ($this->_request->isPost()) {
				$formdata = $this->_request->getPost();
				if ($form->isValid($formdata)) {
					$formdata['dataabertura'] = date('Y-m-d H:i:s');
					$formdata['user_open'] = $this->userInfo->id_registro;
					$formdata['accesshash'] = strtoupper(sha1(microtime()));
					$formdata['id_contato'] = $formdata['contato_cliente'];
					unset($formdata['contato_cliente']);
					unset($formdata['nome_cliente']);
					$optionsOS = $formdata['opcoes_os'];
					unset($formdata['opcoes_os']);
					unset($formdata['submit']);
					
					
					foreach ($optionsOS as $option){
						if($option == 'SendMail'){
							$formdata['sendmail'] = 1;
						}elseif($option == 'ClientCheck'){
							$formdata['clientcheck'] = 1;
						}elseif($option == 'SendSMS'){
							$formdata['sendsms'] = 1;						
						}
					}
					
					try{
						$db = new Crm_Model_Os_Basicos();
						$id = $db->insert($formdata);
						if($formdata['sendmail'] == 1){
							
						Functions_Email::mailNewOs($id);
							
						}
						if($formdata['sendsms'] == 1){
							Functions_SMS::SMSNewOs($id);														
						}
						
						$this->_redirect("/crm/ordem-servico/abrir/id/$id/acao/novo");
						
					}catch (Exception $e){
						$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ocorreu um erro com a atualizacao:<br>Erro: {$e->getMessage()}",'erro'));
					}
					
									
				}else{
					$error =  $form->getMessages();
					$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');
					
					foreach($error as $erro){
						$messages[] = Functions_Messages::renderAlert($erro[0],'info');
					}
					
					
					$this->view->AlertMessage = $messages;
					$form->populate($formdata);
				}
				
			}
			 
			
		
		
		
	}
	public function abrirAction(){
		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		if($action == 'novo'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ordem de Serviço Salva",'sucesso'));
		}elseif($action == 'update'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Ordem de Serviço Atualizada",'sucesso'));
		}
		
		$data = new Crm_Model_Os_Basicos();
		$dadosproduto = $data->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dados = $data->fetchRow("id_registro = '$id'");
		
		$datanotes = new Crm_Model_Os_Anotacoes();
		$dadosnotes = $datanotes->fetchAll("id_os = '$id'");
		$this->view->datanotes = $dadosnotes;
				
		$dadosproduto['contato_cliente'] = Cadastros_Model_Contatos::getNomeContato($dadosproduto['id_contato']);
		$dadosproduto['nome_cliente'] = Cadastros_Model_Pessoas::getNomeEmpresa($dadosproduto['id_cliente']);
		$form = new Crm_Form_OrdemServicos();
		$form->abrirbasicos($id);
		$form->populate($dadosproduto);
		$this->view->formulario = $form;
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			$formdata['user_lastupdate'] = $this->userInfo->id_registro;
			$formdata['datelastupdate'] = date('Y-m-d H:i:s');
			unset($formdata['submit']);

			$data->update($formdata,"id_registro = '{$formdata['id_registro']}'");

			if($dadosproduto['sendmail'] == 1){
				Functions_Email::mailUpdateOs($id);
			}
			if($dadosproduto['sendsms'] == 1){
			   Functions_SMS::SMSUpdateOs($id);
			   $this->log->log("Enviado SMS",Zend_Log::INFO);
			}
			$this->_redirect("/crm/ordem-servico/abrir/id/$id/acao/update");
		}
		
		
	}
	public function listarAction(){
		
	}

	public function incluirServicoAction(){
		$db = new Crm_Model_Os_Servicos();
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$data['id_servico'] = $_GET['id_servico'];
		$data['id_os'] = $id;
		$data['quantidade'] = str_replace(",", ".",$_GET['quantidade']);
		$data['vlunitario'] = str_replace(",", ".",$_GET['vlunitario']);
		$data['obstecnica'] = $_GET['obstecnica'];
		$data['dataadicionado'] = date('Y-m-d H:i:s');
		$data['useradd'] = $this->userInfo->id_registro;
		$data['totalitem'] = ($data['vlunitario'] * $data['quantidade']);
		$db->insert($data);
		
	}
	public function incluirProdutoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$db = new Crm_Model_Os_Produtos();
		$data['id_produto'] = $_GET['id_produto'];
		$data['id_os'] = $id;
		$data['quantidade'] = str_replace(",", ".",$_GET['quantidade']);
		$data['vlunitario'] = str_replace(",", ".",$_GET['vlunitario']);
		$data['obstecnica'] = $_GET['obstecnica'];
		$data['dataadicionado'] = date('Y-m-d H:i:s');
		$data['useradd'] = $this->userInfo->id_registro;
		$data['totalitem'] = ($data['vlunitario'] * $data['quantidade']);
		$db->insert($data);
	}
	
	
	public function salvaAnotacaoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		
		$data['id_os'] = $id;
		$data['usuario_note'] = $this->userInfo->id_registro;
		$data['data_note'] = date("Y-m-d H:i:s");
		$data['anotacao'] = $_POST['anotacoes_os'];
		$data['nome_usuario'] = $this->userInfo->nomecompleto;
		try{
			$db = new Crm_Model_Os_Anotacoes();
			$id = $db->insert($data);
			$anotacaoOK = nl2br($data['anotacao']);
			$dataanotacao = Functions_Datas::MyDateTime($data['data_note'],true);
			
			$dataprint = "<div id=\"data_anotacao_$id\" class=\"row-fluid formSep\">
		<div class=\"span12\">
		<div class=\"w-box\" id=\"w_sort02\">
									<div class=\"w-box-header\">
									<i class=\"splashy-group_blue\" title=\"Usuário do Sistema\"></i>
									{$data['nome_usuario']} em {$dataanotacao}
										<div class=\"pull-right\">
											<div class=\"btn-group\">
												<a class=\"btn dropdown-toggle btn-mini\" data-toggle=\"dropdown\" href=\"#\">
													<i class=\"icon-cog\"></i> <span class=\"caret\"></span>
												</a>
												<ul class=\"dropdown-menu\">
													<li><a href=\"#\" onclick=\"removeanotacao($id)\">Excluir</a></li>
												</ul>
											</div>
										</div>
									</div>
									<div class=\"w-box-content\">
									$anotacaoOK
									</div>
								</div>
							</div>
							</div>";
		echo $dataprint;	
			
			
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
		
	}
	
	public function removerNotaAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		try{
			$db = new Crm_Model_Os_Anotacoes();
			$id = $db->delete("id_registro = '$id'");
		}catch(Exception $e){
			echo $e->getMessage();
		}
		
	}
	
	public function printAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
	//	print_r($this->configs->Jasper);
	//	echo $this->configs->Leader->Cliente->codigo;
			
		try {
			$report = new Reports_Jasper($this->configs->Jasper);
			
			$cogigocliente = $this->configs->Leader->Cliente->codigo;
		
			$report = $report->run("/reports/$cogigocliente/OrdemDeServicos",'PDF',array('ID_OS'=>$id),true);
			header("Content-type: application/pdf");
			header('Content-Length: ' . filesize($report));
			header("Content-Disposition: inline; filename=os,pdf");
			readfile($report);
				
		} catch (\Exception $e) {
			echo $e->getMessage();
			die;
		}
		
		
		
		
	}
	
}