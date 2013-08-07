<?php
class System_FinanceiroController extends Zend_Controller_Action{
	
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
	
	public function catFinanceiraAction(){
		$db = new Erp_Model_Financeiro_CategoriasLancamentos();
		$db2 = new Erp_Model_Financeiro_TiposLancamentos();
		
		if ($this->_request->isPost()) {
			$tipo = $_POST['tipo'];

			if($tipo == 'categoria'){
				$db->insert(array('tipocategoria'=>$_POST['tipocategoria'],
						'nomecategoria'=>$_POST['nomecategoria']
				));
				$messages[] = Functions_Messages::renderAlert("Categoria Adicionada:",'sucesso');
				
			}elseif ($tipo == 'subcategoria'){
				$db2->insert(array('id_categoria'=>$_POST['id_categoria'],
						'nomesubcategoria'=>$_POST['nomesubcategoria']
				));
				
				$messages[] = Functions_Messages::renderAlert("SubCategoria Adicionada:",'sucesso');
			}elseif($tipo =='deletasubcategoria'){
				$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
				$result = $recaptcha->verify(
						$_POST['recaptcha_challenge_field'],
						$_POST['recaptcha_response_field']
				);
				if (!$result->isValid()) {
					$messages[] = Functions_Messages::renderAlert("Caracteres Incorretos, tente novamente:",'erro');
				}else{
				
				$db2->delete("id_registro = '{$_POST['id_registro']}'");
				$messages[] = Functions_Messages::renderAlert("SubCategoria Excluida:",'sucesso');
				}
			}
			elseif($tipo =='deletacategoria'){
				$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
				$result = $recaptcha->verify(
						$_POST['recaptcha_challenge_field'],
						$_POST['recaptcha_response_field']
				);
				if (!$result->isValid()) {
					$messages[] = Functions_Messages::renderAlert("Caracteres Incorretos, tente novamente:",'erro');
				}else{
				
					$db->delete("id_registro = '{$_POST['id_registro']}'");
					$messages[] = Functions_Messages::renderAlert("Categoria Excluida:",'sucesso');
				}
			}elseif($tipo =='editacategoria'){
				$db->update(array('nomecategoria'=>$_POST['valor']), "id_registro = '{$_POST['id_registro']}'");
				$messages[] = Functions_Messages::renderAlert("Categoria Editada:",'sucesso');
				
			}elseif($tipo =='editasubcategoria'){
				$db2->update(array('nomesubcategoria'=>$_POST['valor']), "id_registro = '{$_POST['id_registro']}'");
				$messages[] = Functions_Messages::renderAlert("SubCategoria Editada:",'sucesso');
				
			}			
		}
		
		$this->view->AlertMessage = $messages;
		
	}
	
	public function gerContasAction(){
		$db = new Erp_Model_Financeiro_ContaCorrente();
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		
	}
	
	public function deletaContaAction(){
		$this->view->configs = $this->configs;
		
		if ($this->_request->isPost()) {
			$this->_helper->layout->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			
			$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
			$result = $recaptcha->verify(
					$_POST['recaptcha_challenge_field'],
					$_POST['recaptcha_response_field']
			);
			if (!$result->isValid()) {
				echo "ERRO";
			}else{
				$db3 = new Erp_Model_Financeiro_ContaCorrente();
				$db3->delete("id_registro = '{$_POST['id']}'");
				echo "OK";
			}
				
		}else{
			$this->_helper->layout()->setLayout('modal');
			$id = $this->_getParam('id');
			$tipo = $this->_getParam('tipo');
			$this->view->tipo = $tipo;
			$this->view->id = $id;
			
		}
		
	}
	
	public function novaContaAction(){
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			unset($formdata['submit']);
			$this->_helper->viewRenderer->setNoRender();
			$this->_helper->layout->disableLayout();
			$db = new Erp_Model_Financeiro_ContaCorrente();
			if($formdata['valoremissaoboleto'] <> ''){
			$formdata['valoremissaoboleto'] = str_replace(",", ".", $formdata['valoremissaoboleto'] );
			}else{
				$formdata['valoremissaoboleto'] = '0.00';
			}
			if($formdata['id_registro'] <> ''){
				$db->update($formdata, "id_registro = '{$formdata['id_registro']}'");
				echo "DADOS ATUALIZADOS COM SUCESSO";
			}else{
				$db->insert($formdata);
				echo "DADOS INSERIDOS COM SUCESSO";
			}
			
		}else{
			$this->_helper->layout()->setLayout('modal');
			$form = new System_Form_Financeiro_Banco();
			$id = $this->_getParam('id');
			if($id <> ''){
			$db = new Erp_Model_Financeiro_ContaCorrente();
			$dados = $db->fetchRow("id_registro = '$id'")->toArray();
			$dados['valoremissaoboleto'] = number_format($dados['valoremissaoboleto'],2,',','');
			$form->populate($dados);
			}		
			$this->view->form = $form;
		}
	}
	
	public function configGeraisAction(){
		
		if ($this->_request->isPost()) {
			if($_POST['FinanValorMaxSemLib'] <> ''){
				$value = str_replace(",", ".", $_POST['FinanValorMaxSemLib'] );
				System_Model_SysConfigs::updateConfig("FinanValorMaxSemLib", $value);
			}
			
			if($_POST['FinanMustValidade'] <> ''){
				System_Model_SysConfigs::updateConfig("FinanMustValidade", $_POST['FinanMustValidade']);
			}else{
				System_Model_SysConfigs::updateConfig("FinanMustValidade", '0');
			}
			
			if($_POST['CategoriaLancComissoes'] <> ''){
				System_Model_SysConfigs::updateConfig("CategoriaLancComissoes", $_POST['CategoriaLancComissoes']);
			}else{
				System_Model_SysConfigs::updateConfig("CategoriaLancComissoes", '0');
			}
			
			if($_POST['TipoDocComissoes'] <> ''){
				System_Model_SysConfigs::updateConfig("TipoDocComissoes", $_POST['TipoDocComissoes']);
			}else{
				System_Model_SysConfigs::updateConfig("TipoDocComissoes", '0');
			}
			
			if($_POST['StatusLancamentoEncerrado'] <> ''){
				System_Model_SysConfigs::updateConfig("StatusLancamentoEncerrado", implode(',', $_POST['StatusLancamentoEncerrado']));
			}
			
			if($_POST['StatusLancamentoAberto'] <> ''){
				System_Model_SysConfigs::updateConfig("StatusLancamentoAberto", implode(',', $_POST['StatusLancamentoAberto']));
			}
			
			if($_POST['StatusLancamentoLiberado'] <> ''){
				System_Model_SysConfigs::updateConfig("StatusLancamentoLiberado", implode(',', $_POST['StatusLancamentoLiberado']));
			}
			
			if($_POST['StatusLancamentoCancelado'] <> ''){
				System_Model_SysConfigs::updateConfig("StatusLancamentoCancelado", implode(',', $_POST['StatusLancamentoCancelado']));
			}
			
			if($_POST['FinanTypeUserCanLib'] <> ''){
				System_Model_SysConfigs::updateConfig("FinanTypeUserCanLib", implode(',', $_POST['FinanTypeUserCanLib']));
			}
			
			if($_POST['FinanEmailResumoUsers'] <> ''){
				System_Model_SysConfigs::updateConfig("FinanEmailResumoUsers", implode(',', $_POST['FinanEmailResumoUsers']));
			}
			
			
			
			if($_POST['FinanEmailResumo'] <> ''){
				System_Model_SysConfigs::updateConfig("FinanEmailResumo", $_POST['FinanEmailResumo']);
			}else{
				System_Model_SysConfigs::updateConfig("FinanEmailResumo", '0');
			}
		}
		
	}

	public function tiposDocumentosAction(){
		$db = new Erp_Model_Financeiro_TiposDocumentos();
		
		if ($this->_request->isPost()) {
			$id = $_POST['id_registro'];
			$nome = $_POST['nomecategoria'];
			$action = $_POST['action'];
				
			switch($action){
				case 'new':
					$db->insert(array('nomedocumento'=>$nome));
					break;
				case 'update':
					$db->update(array('nomedocumento'=>$nome),"id_registro = '$id'");
					break;
				case 'delete':
					$db->delete("id_registro = '$id'");
					break;
		
			}
				
		}
		
		$data = $db->fetchAll();
		$this->view->data = $data;
		
	}
	
	public function statusPagamentosAction(){
		$db = new Erp_Model_Financeiro_StatusLancamentos();
		
		if ($this->_request->isPost()) {
			$id = $_POST['id_registro'];
			$nome = $_POST['nomecategoria'];
			$action = $_POST['action'];
			
			switch($action){
				case 'new':
				$db->insert(array('nomestatus'=>$nome));
				break;
				case 'update':
					$db->update(array('nomestatus'=>$nome),"id_registro = '$id'");
				break;
				case 'delete':
					$db->delete("id_registro = '$id'");
				break;
				
			}
			
		}
		
		$data = $db->fetchAll();
		$this->view->data = $data;
		
	}
	
	
	public function perfilPagamentoAction(){
		$db = new Erp_Model_Financeiro_Perfil();
		
		if ($this->_request->isPost()) {
			$formdata = $this->_request->getPost();
			switch($formdata['tiporegistro']){
				case 'novo':
					unset($formdata['tiporegistro']);
					unset($formdata['submit']);
					$db->insert($formdata);
					$messages[] = Functions_Messages::renderAlert("Perfil Incluído:",'sucesso');
				break;
				
				case "update":
					unset($formdata['tiporegistro']);
					unset($formdata['submit']);
					$db->update($formdata,"id_registro = '{$formdata['id_registro']}'");
					$messages[] = Functions_Messages::renderAlert("Perfil Atualizado:",'sucesso');
				break;
				
				case 'delete':
					
					$recaptcha = new Zend_Service_ReCaptcha('6Ldk6-ISAAAAALqcpxh2182Y0Yd8ZqHr2p_QIGoO','6Ldk6-ISAAAAAHpOarrRAqW6S9uvJEdERs2MgP6h');
					$result = $recaptcha->verify(
							$_POST['recaptcha_challenge_field'],
							$_POST['recaptcha_response_field']
					);
					if (!$result->isValid()) {
						$messages[] = Functions_Messages::renderAlert("As palavras digitadas estão incorretas:",'erro');
					}else{
						$db3 = new Erp_Model_Financeiro_ContaCorrente();
						$db->delete("id_registro = '{$formdata['id_registro']}'");
						$messages[] = Functions_Messages::renderAlert("Perfil Excluído:",'sucesso');
					}
					
				break;
			}
			
		}
		
		$dados = $db->fetchAll();
		$this->view->dados = $dados;
		
		$this->view->AlertMessage = $messages;
		
		
	}
	
	public function editaPerfilAction(){
		$this->_helper->layout()->setLayout('modal');
		$id = $this->_getParam('id');
		
		if($id <> ''){
			$db = new Erp_Model_Financeiro_Perfil();
			$dados = $db->fetchRow("id_registro = '$id'");
			$this->view->dados = $dados;
		}
	
		
	}
	
	
}