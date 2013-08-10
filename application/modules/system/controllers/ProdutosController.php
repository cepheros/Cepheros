<?php
class System_ProdutosController extends Zend_Controller_Action{
	
	
	
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
	
	public function categoriasAction(){
		$db = new System_Model_Categoriaprodutos();
		$data = $db->fetchAll();
		$this->view->dados = $data;
		
	}
	
	public function subcategoriasAction(){
		$db = new System_Model_Subcategoriaprodutos();
		$data = $db->fetchAll();
		$this->view->dados = $data;
		
	}
	
	
	
	public function unidadesAction(){
		
		$db = new System_Model_Unidadesdemedida();
		$data = $db->fetchAll();
		$this->view->dados = $data;
		
	}
	
	public function configsAction(){
		
		if ($this->_request->isPost()) {
			
			foreach($_POST as $key=>$value){
				if($key == 'EstoqueAlertPercent'){
					$value = str_replace(",", ".", $value );
				}
				System_Model_SysConfigs::updateConfig($key, $value);
			}
			
		}
		
	}
	
	
	//acoes de categorias de produtos:
	
	
	public function getcategoriadataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Categoriaprodutos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function novaCategoriaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_Categoriaprodutos();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/system/produtos/categorias");
	
	}
	
	public function removecategoriaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Categoriaprodutos();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/system/produtos/categorias");
	
	}
	
	
	//acoes de subcategorias de produtos:
	
	public function getsubcategoriadataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Subcategoriaprodutos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$dados['nomecategoria'] = System_Model_Categoriaprodutos::getNomeCategoria($dados['id_categoria']);
		echo json_encode($dados);
	}
	
	public function novaSubcategoriaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_Subcategoriaprodutos();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/system/produtos/subcategorias");
	
	}
	
	public function removesubcategoriaAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Subcategoriaprodutos();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/system/produtos/subcategorias");
	
	}
	
	//acoes para unidades de medida:
	
	
	public function getunidadedataAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Unidadesdemedida();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$dados['multiplicador'] = number_format($dados['multiplicador'],'3',',','');
		echo json_encode($dados);
	}
	
	public function novaUnidadeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_Unidadesdemedida();
		$data['multiplicador'] = str_replace(",", ".", $data['multiplicador'] );
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/system/produtos/unidades");
	
	}
	
	public function removeunidadeAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Unidadesdemedida();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/system/produtos/unidades");
	
	}
	
	
}
