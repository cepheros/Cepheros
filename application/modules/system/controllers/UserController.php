<?php
class System_UserController extends Zend_Controller_Action{
	
	
	
	
	/**
	 * Funcao responsável por adicionar os itens favoritos ao menu do usuário
	 * 
	 */
	
	public function addfavAction(){
		error_reporting(0);
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$data['module'] = $_GET['module'];
		$data['controller'] = $_GET['controller'];
		$data['action'] = $_GET['action'];
		$userID = Zend_Auth::getInstance()->getStorage()->read()->id_registro;
	
		$db = new System_Model_Menus();
		$dados = $db->fetchRow("module = '{$data['module']}' and controller = '{$data['controller']}' and action = '{$data['action']}'");
		if($dados->id_registro){
			$db2 = new System_Model_UsersFavoritos();
			$dados2 = $db2->fetchRow("id_user = '$userID' and id_menu = '{$dados->id_registro}'");
			if(!$dados2){
				if(!$dados2->id_registro){
					$db2->insert(array('id_user'=>Zend_Auth::getInstance()->getStorage()->read()->id_registro,
							'id_menu'=>$dados->id_registro));
					echo "OK|{$dados->nome}";
				}else{
					echo "ERRO|Favorito já existe";
		        }
			}
		}else{
			echo "ERRO|Não é possível adicionar este item ao menu de favoritos";
		}
				 
	
		}
		
}