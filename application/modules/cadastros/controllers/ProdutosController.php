<?php
class Cadastros_ProdutosController extends Zend_Controller_Action{

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

		$form = new Cadastros_Form_Produtos();
		$form->novo();
		$form->populate(array("localestoque"=>Erp_Model_Estoque_Locais::getEstoquePadrao()));
		$this->view->form = $form;


		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$estoqueatual = $formData['estoqueatual'];
				unset($formData['estoqueatual']);
				$formData['userlastalter'] = $this->userInfo->id_registro;
				$formData['datelastalter'] = date("Y-m-d h:i:s");
				$formData['bloqcad'] = 0;
				$formData['alteracoes'] = 0;
				$formData['estoqueminimo'] = str_replace(",", ".", $formData['estoqueminimo']);
				$formData['estoquemaximo'] = str_replace(",", ".", $formData['estoquemaximo']);
				$formData['pesoproduto'] = str_replace(",", ".", $formData['pesoproduto']);
				$formData['precocusto'] = str_replace(",", ".", $formData['precocusto']);
				$formData['precovenda'] = str_replace(",", ".", $formData['precovenda']);
				$formData['margemlucro'] = str_replace(",", ".", $formData['margemlucro']);
				$db = new Cadastros_Model_Produtos();
				$id = $db->insert($formData);
				$this->log->log("Novo Cadastro de produto efetuado por: {$this->userInfo->nomecompleto} Produto: {$formData['nomeproduto']} ",Zend_Log::INFO);
				if($formData['contaestoque'] == 1){
					$db2 = new Erp_Model_Estoque_Movimento();
					$mov['id_produto'] = $id;
					$mov['id_estoque'] = $formData['localestoque'];
					$mov['quantidade'] = str_replace(",", ".", $estoqueatual);
					$mov['historico'] = "Movimentação de estoque inicial (Cadastro de novo Produto)";
					$mov['usuario'] = $this->userInfo->id_registro;
					$mov['data'] = date("Y-m-d h:i:s");
					$idmovimento = $db2->insert($mov);
					$this->log->log("Movimentação de estoque do produto {$formData['nomeproduto']} - ({$estoqueatual}) efetuada por {$this->userInfo->nomecompleto} ID: {$idmovimento} ",Zend_Log::INFO);
				}

				$this->_redirect("cadastros/produtos/abrir/id/$id/acao/novo");

			}else{
				$error =  $form->getMessages();
				$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');

				foreach($error as $erro){
					$messages[] = Functions_Messages::renderAlert($erro[0],'info');
				}


				$this->view->AlertMessage = $messages;
				$form->populate($formData);
			}
		}

	}

	public function abrirAction(){

		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		if($action == 'novo'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro de produto Efetuado",'sucesso'));
		}

		$data = new Cadastros_Model_Produtos();
		$dadosproduto = $data->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dados = $dadosproduto;


		$ddfiles = new System_Model_Files();
		$dadosfiles = $ddfiles->fetchAll("tipofile = 'produtos' and idreg = '$id'")->toArray();

		if($dadosfiles){
			$this->view->arquivos = $dadosfiles;
		}else{
			$this->view->arquivos = false;
		}

		$estoqueatual = Erp_Model_Estoque_Movimento::estoqueAtual($id, "1");
		$dadosproduto['estoqueatual'] =  number_format($estoqueatual,3,',','');
		$dadosproduto['pesoproduto'] =  number_format($dadosproduto['pesoproduto'],3,',','');
		$dadosproduto['estoqueminimo'] =  number_format($dadosproduto['estoqueminimo'],3,',','');
		$dadosproduto['estoquemaximo'] =  number_format($dadosproduto['estoquemaximo'],3,',','');
		$dadosproduto['precocusto'] =  number_format($dadosproduto['precocusto'],2,',','');
		$dadosproduto['precovenda'] =  number_format($dadosproduto['precovenda'],2,',','');
		$dadosproduto['margemlucro'] =  number_format($dadosproduto['margemlucro'],2,',','');
		$form = new Cadastros_Form_Produtos();
		$form->novo();
		$form->populate($dadosproduto);
		$this->view->form = $form;




		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$estoqueatual = $formData['estoqueatual'];
				unset($formData['estoqueatual']);
				$formData['userlastalter'] = $this->userInfo->id_registro;
				$formData['datelastalter'] = date("Y-m-d h:i:s");
				$formData['bloqcad'] = 0;
				$formData['alteracoes'] = $dadosproduto['alteracoes'] + 1;
				$formData['estoqueminimo'] = str_replace(",", ".", $formData['estoqueminimo']);
				$formData['estoquemaximo'] = str_replace(",", ".", $formData['estoquemaximo']);
				$formData['pesoproduto'] = str_replace(",", ".", $formData['pesoproduto']);
				$formData['precocusto'] = str_replace(",", ".", $formData['precocusto']);
				$formData['precovenda'] = str_replace(",", ".", $formData['precovenda']);
				$formData['margemlucro'] = str_replace(",", ".", $formData['margemlucro']);
				$db = new Cadastros_Model_Produtos();
				$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
				$this->log->log("Cadastro de produto editado por: {$this->userInfo->nomecompleto} Produto: {$formData['nomeproduto']} ",Zend_Log::INFO);

				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro de produto editado!",'sucesso'));

			}else{
				$error =  $form->getMessages();
				$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');

				foreach($error as $erro){
					$messages[] = Functions_Messages::renderAlert($erro[0],'info');
				}


				$this->view->AlertMessage = $messages;
				$form->populate($formData);
			}
		}




	}

	public function listarAction(){
		$action = $this->_getParam('acao');
		if($action == 'excluir'){
			$produto = $this->_getParam('produto');
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("<strong>Cadastro do produto $produto excluído com sucesso</strong>",'sucesso'));
		}


	}

	public function excluirAction(){

		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		$this->view->AlertMessage = array(Functions_Messages::renderAlert("Confirme a remoçao deste cadastro<br><strong>Todos os dados de estoques, pedidos e outros que tiverem este produto vinculado serao excluídos!</strong>",'erro'));


		$data = new Cadastros_Model_Produtos();
		$dadosproduto = $data->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dados = $dadosproduto;

		$estoqueatual = Erp_Model_Estoque_Movimento::estoqueAtual($id, "1");
		$dadosproduto['estoqueatual'] =  number_format($estoqueatual,3,',','');
		$dadosproduto['pesoproduto'] =  number_format($dadosproduto['pesoproduto'],3,',','');
		$dadosproduto['estoqueminimo'] =  number_format($dadosproduto['estoqueminimo'],3,',','');
		$dadosproduto['estoquemaximo'] =  number_format($dadosproduto['estoquemaximo'],3,',','');
		$dadosproduto['precocusto'] =  number_format($dadosproduto['precocusto'],2,',','');
		$dadosproduto['precovenda'] =  number_format($dadosproduto['precovenda'],2,',','');
		$dadosproduto['margemlucro'] =  number_format($dadosproduto['margemlucro'],2,',','');
		$form = new Cadastros_Form_Produtos();
		$form->novo();
		$form->populate($dadosproduto);
		$this->view->form = $form;




		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$estoqueatual = $formData['estoqueatual'];
				unset($formData['estoqueatual']);
				$formData['userlastalter'] = $this->userInfo->id_registro;
				$formData['datelastalter'] = date("Y-m-d h:i:s");
				$formData['bloqcad'] = 0;
				$formData['alteracoes'] = 0;
				$formData['estoqueminimo'] = str_replace(",", ".", $formData['estoqueminimo']);
				$formData['estoquemaximo'] = str_replace(",", ".", $formData['estoquemaximo']);
				$formData['pesoproduto'] = str_replace(",", ".", $formData['pesoproduto']);
				$formData['precocusto'] = str_replace(",", ".", $formData['precocusto']);
				$formData['precovenda'] = str_replace(",", ".", $formData['precovenda']);
				$formData['margemlucro'] = str_replace(",", ".", $formData['margemlucro']);
				$db = new Cadastros_Model_Produtos();
				$id = $db->delete("id_registro = '{$formData['id_registro']}'");
				$this->log->log("Cadastro de produto excluido por: {$this->userInfo->nomecompleto} Produto: {$formData['nomeproduto']} ",Zend_Log::INFO);

				$this->_redirect("cadastros/produtos/listar/acao/excluir/produto/{$formData['nomeproduto']}");

			}else{
				$error =  $form->getMessages();
				$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');

				foreach($error as $erro){
					$messages[] = Functions_Messages::renderAlert($erro[0],'info');
				}


				$this->view->AlertMessage = $messages;
				$form->populate($formData);
			}
		}

	}


	public function getsubcategoriasAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();


		$id = $this->_getParam('id');
		$db = new System_Model_Subcategoriaprodutos();

		$sub =  $db->fetchAll("id_categoria = '$id'")->toArray();
		foreach($sub as $depto){
			$rdepto[$depto['id_registro']] = $depto['nomesubcategoria'];
		}

		echo json_encode($sub);

	}

	public function novoCompostoAction(){

	}

	public function criarCompostoAction(){
		$id = $this->_getParam('id');
		$data = new Cadastros_Model_Produtos();
		$dadosproduto = $data->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dadosdoproduto = $dadosproduto;
		$messages = '';

		if(System_Model_SysConfigs::getConfig('CanotADDSameProduct') == 1){
			$messages[] = Functions_Messages::renderAlert("<strong>O proprio produto não pode ser adicionado ao composto</strong>",'info');
		}

		if(System_Model_SysConfigs::getConfig('ProdutosNotCompostosADD') == 1){
			$messages[] = Functions_Messages::renderAlert("<strong>Somente produtos não compostos podem ser adicionados a esta composição</strong>",'info');
		}
		$this->view->AlertMessage = $messages;

	}

	public function listarCompostoAction(){

	}

	public function salvaCompostoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$id = $this->_getParam('id');
		$db = new Cadastros_Model_ProdutosCompostos;
		$data['id_produto'] = $id;
		$data['id_composto'] = $_GET['idcomposto'];
		$data['quantidadecomposto'] =  str_replace(",", ".", $_GET['quantidade']);
		$db->insert($data);

		if(Cadastros_Model_ProdutosCompostos::countCompostos($id) > 0){
			$produto = new Cadastros_Model_Produtos();
			$dados['produtocomposto'] = 1;
			$produto->update($dados, "id_registro = '$id'");
		}


	}
	public function removeCompostoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new Cadastros_Model_ProdutosCompostos;
		$dados = $db->fetchRow("id_registro = '$id'");
		try{
			$db->delete("id_registro = '$id'");
		if(Cadastros_Model_ProdutosCompostos::countCompostos($dados['id_produto'])> 0){
			$produto = new Cadastros_Model_Produtos();
			$dados1['produtocomposto'] = 1;
			$produto->update($dados1, "id_registro = '{$dados->id_produto}'");
		}elseif(Cadastros_Model_ProdutosCompostos::countCompostos($dados['id_produto']) == 0 || Cadastros_Model_ProdutosCompostos::countCompostos($dados->id_produto) == '' ){
			$produto = new Cadastros_Model_Produtos();
			$dados1['produtocomposto'] = 0;
			$produto->update($dados1, "id_registro = '{$dados->id_produto}'");
		}

		}catch (Exception $e){
			echo "Erro";
			echo $e->getMessage();
		}

	}

	public function abrirCompostoAction(){

		$id = $this->_getParam('id');
		$action = $this->_getParam('acao');
		if($action == 'novo'){
			$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro de produto Efetuado",'sucesso'));
		}

		$data = new Cadastros_Model_Produtos();
		$dadosproduto = $data->fetchRow("id_registro = '$id'")->toArray();
		$this->view->dados = $dadosproduto;

		$ddfiles = new System_Model_Files();
		$dadosfiles = $ddfiles->fetchAll("tipofile = 'produtos' and idreg = '$id'")->toArray();

		if($dadosfiles){
			$this->view->arquivos = $dadosfiles;
		}else{
			$this->view->arquivos = false;
		}

		$estoqueatual = Erp_Model_Estoque_Movimento::estoqueAtual($id, "1");
		$dadosproduto['estoqueatual'] =  number_format($estoqueatual,3,',','');
		$dadosproduto['pesoproduto'] =  number_format($dadosproduto['pesoproduto'],3,',','');
		$dadosproduto['estoqueminimo'] =  number_format($dadosproduto['estoqueminimo'],3,',','');
		$dadosproduto['estoquemaximo'] =  number_format($dadosproduto['estoquemaximo'],3,',','');
		$dadosproduto['precocusto'] =  number_format($dadosproduto['precocusto'],2,',','');
		$dadosproduto['precovenda'] =  number_format($dadosproduto['precovenda'],2,',','');
		$dadosproduto['margemlucro'] =  number_format($dadosproduto['margemlucro'],2,',','');
		$form = new Cadastros_Form_Produtos();
		$form->novo();
		$form->populate($dadosproduto);
		$this->view->form = $form;






		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
			if ($form->isValid($formData)) {
				unset($formData['submit']);
				$estoqueatual = $formData['estoqueatual'];
				unset($formData['estoqueatual']);
				$formData['userlastalter'] = $this->userInfo->id_registro;
				$formData['datelastalter'] = date("Y-m-d h:i:s");
				$formData['bloqcad'] = 0;
				$formData['alteracoes'] = $dadosproduto['alteracoes'] + 1;
				$formData['estoqueminimo'] = str_replace(",", ".", $formData['estoqueminimo']);
				$formData['estoquemaximo'] = str_replace(",", ".", $formData['estoquemaximo']);
				$formData['pesoproduto'] = str_replace(",", ".", $formData['pesoproduto']);
				$formData['precocusto'] = str_replace(",", ".", $formData['precocusto']);
				$formData['precovenda'] = str_replace(",", ".", $formData['precovenda']);
				$formData['margemlucro'] = str_replace(",", ".", $formData['margemlucro']);
				$db = new Cadastros_Model_Produtos();
				$id = $db->update($formData,"id_registro = '{$formData['id_registro']}'");
				$this->log->log("Cadastro de produto editado por: {$this->userInfo->nomecompleto} Produto: {$formData['nomeproduto']} ",Zend_Log::INFO);

				$this->view->AlertMessage = array(Functions_Messages::renderAlert("Cadastro de produto editado!",'sucesso'));

			}else{
				$error =  $form->getMessages();
				$messages[] = Functions_Messages::renderAlert("Você não preencheu alguns campos obrigatórios, preeencha os campos relacionados abaixo tente enviar novamente:",'erro');

				foreach($error as $erro){
					$messages[] = Functions_Messages::renderAlert($erro[0],'info');
				}


				$this->view->AlertMessage = $messages;
				$form->populate($formData);
			}
		}




	}


	public function saveFileAction(){
		$this->DocsPath = $this->configs->SYS->DocsPath;
		$logdata = $this->log;
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

		$data = $this->_request->getPost();
		$logdata->log(serialize($data),Zend_Log::INFO);

		try{

			$db = new System_Model_Files();
			$data2['accesshash'] = sha1(md5(microtime()));
			$data2['tipofile'] = $data['tipofile'];
			$data2['idreg'] = $data['idreg'];
			$data2['obsfile'] = "";
			$data2['nomeamigavel'] = $data['nomeamigavel'];
			$data2['tags'] = $data['tags'];
			$id_file = $db->insert($data2);
		}catch(Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}

		$logdata->log("Path:" . $this->DocsPath .'/'.$data['tipofile'] ,Zend_Log::INFO);

		if(!is_dir($this->DocsPath .'/'.$data['tipofile'])){
			mkdir($this->DocsPath .'/'.$data['tipofile']);
		}

		if(!is_dir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}")){
			mkdir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}");
		}

		if(!is_dir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}/{$id_file}")){
			mkdir($this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}/{$id_file}");
		}

		try{
			$destinationFolder =  $this->DocsPath ."/{$data['tipofile']}/{$data['idreg']}/{$id_file}";
			$upload_adapter = new Zend_File_Transfer_Adapter_Http();
			$upload_adapter->setDestination( $destinationFolder );
			$filename =$upload_adapter->getFileName();
			$hash = $upload_adapter->getHash('md5');
			$minetype = $upload_adapter->getMimeType();

			if( $upload_adapter->receive() ){

				$data3['filename'] = $filename;
				$data3['filetype'] = $minetype;
				$data3['filehash'] = $hash;
				$data3['dateadded'] = date('Y-m-d H:i:s');
				$data3['useradded'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
				$db->update($data3, "id_registro = '$id_file'");

				$logdata->log("Arquivo Adicionado ao Sistema: Folder: $destinationFolder Arquivo: {$data['filename']}, UsuÃ¡rio: {$data['useradded']} ",Zend_Log::INFO);


			}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}

		$this->_redirect("/cadastros/produtos/abrir-composto/id/{$data['idreg']}/action/upload");


	}

	public function setDefaultImageAction(){

		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();

			$id = $this->_getParam('id');
			$db = new Cadastros_Model_ProdutosCompostos;
			$data['imagempadrao'] = $_GET['idimage'];

			$produto = new Cadastros_Model_Produtos();

			$produto->update($data, "id_registro = '$id'");


	}


}
