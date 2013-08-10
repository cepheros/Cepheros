<?php
class System_FilesController extends Zend_Controller_Action{
	
	public function init(){
	
		if(!Zend_Auth::getInstance()->hasIdentity())
		{
			$this->_redirect('/');
		}
		$this->log = Zend_Registry::get('log');
		$this->configs = Zend_Registry::get('configs');
		$this->cache = Zend_Registry::get('cache');
	
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();
		
		$this->DocsPath = $this->configs->SYS->DocsPath;
	
	}
	
	public function indexAction(){
		error_reporting(E_ALL);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		Echo "Criando Diretórios";
		Echo $this->DocsPath .'/pessoas';
		if(!is_dir($this->DocsPath .'/pessoas')){
			echo "Criando Diretorio Pessoas";
			mkdir($this->DocsPath .'/pessoas');
		}else{
			echo "Diretorio Existe";
		}
		
		if(is_dir($this->DocsPath ."/pessoas/14")){
			mkdir($this->DocsPath ."/pessoas/14");
		}
		
		if(is_dir($this->DocsPath ."/pessoas/14/1")){
			mkdir($this->DocsPath ."/pessoas/14/1");
		}
		
	}
	
	public function saveFileAction(){
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
			$data['filename'] = $filename;
			$data['filetype'] = $minetype;
			$data['filehash'] = $hash;
			$data['dateadded'] = date('Y-m-d H:i:s');
			$data['useradded'] =  Zend_Auth::getInstance()->getStorage()->read()->id_registro;
			$db->update($data, "id_registro = '$id_file'");
			
			$logdata->log("Arquivo Adicionado ao Sistema: Folder: $destinationFolder Arquivo: {$data['filename']}, UsuÃ¡rio: {$data['useradded']} ",Zend_Log::INFO);
				
				
		}
		}catch (Exception $e){
			$this->log->log("ERRO UPLOAD: {$e->getMessage()} ",Zend_Log::ERR);
		}
		
		
	}
	
	public function getFileAction(){
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Files();
		$data = $db->fetchRow("id_registro = '$id'");
		//	print_r($data);
		$file = file_get_contents($data->filename);
		$filesize = filesize($data->filename);
		$filename = explode(".",$data->filename);
		$number = count($filename);
		$filerextension = $filename[$number -1];
		$name = $data->filehash.".".$filerextension;
		header("Content-type: {$data->filetype}");
		header("Content-length: $filesize");
		header("Content-Disposition: attachment; filename=$name");
		header("Content-Description: PHP Generated Data");
		
		echo $file;
		
	}
	
	public function deleteFileAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $_GET['id'];
		$db = new System_Model_Files();
		$data = $db->fetchRow("id_registro = '$id'");
		$db->delete("id_registro = '$id'");
		unlink($data->filename);
						
	}
	
	public function renderAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$db = new System_Model_Files();
		$data = $db->fetchRow("id_registro = '$id'");
		//	print_r($data);
		$filename = explode(".",$data->filename);
		$number = count($filename);
		//$file = file_get_contents($data->filename);
		$filename = explode(".",$data->filename);
		$filerextension = $filename[$number -1];
		$name = $data->filehash.".".$filerextension;
		header("Content-type: {$data->filetype}");
		header('Content-Length: ' . filesize($data->filename));
		header("Content-Disposition: inline; filename=$name");
		readfile($data->filename);
	
	}
	
	
	
}