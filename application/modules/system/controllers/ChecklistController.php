<?php
class System_ChecklistController extends Zend_Controller_Action{
	
	public function newAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		if ($this->_request->isPost()) {
			$formData = $this->_request->getPost();
		
			$db = new System_Model_ChecklistBasicos();
			$dados['nomechecklist'] = $formData['nomechecklist'];
			$dados['tipochecklist'] = $formData['tipochecklist'];
			$id = $db->insert($dados);
			
			$db2 = new System_Model_ChecklistEtapas();
			for($i=0;$i<count($formData['nomeetapa']);$i++){
				$dados2['nomeetapa'] = $formData['nomeetapa'][$i];
				$dados2['duein'] = $formData['diasestapa'][$i];
				$dados2['id_check'] = $id;
				
				$db2->insert($dados2);
			
			}
			
			echo $id;
				
		
		}
	}

	
	public function getAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		$id = $this->_getParam('id');
		$tipo = $this->_getParam('tipo');
		$id_reg = $this->_getParam('idreg');
		
		
		$checklist = array();
		$db = new System_Model_ChecklistBasicos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		$checklist['info'] = $dados;
		$db2 = new System_Model_ChecklistEtapas();
		$dado2 = $db2->fetchAll("id_check = '$id'")->toArray();
		$checklist['itens'] = $dado2;
		
		switch($tipo){
			case 'prospect':
				$dados = new Crm_Model_Prospects_Basico();
				$getdata = $dados->fetchRow("id_registro = '$id_reg'");
				$db3 = new Crm_Model_Prospects_Checklist();
				$db3->insert(array('id_prospect'=>$id_reg,'id_checklist'=>$id));
				$db4 = new Crm_Model_Prospects_ChecklistItens();
				foreach($dado2 as $pdata){
					$datadue = Functions_Datas::SomaData(Functions_Datas::MyDateTime($getdata->dateopen), $pdata['duein']);
					$db4->insert(array('id_prospect'=>$id_reg,
							'id_item'=>$pdata['id_registro'],
							'statusitem'=>'0',
							'datedue'=>Functions_Datas::inverteData($datadue)));
				
					
					$dadoreturn[] = array('id_registro'=>$pdata['id_registro'],
							'nomeetapa'=>$pdata['nomeetapa'],
							'duein'=>$datadue);
					
				}
				$checklist['itens'] = $dadoreturn;
				
				echo json_encode($checklist);
				
				break;
			
		};
		
		
			
		
		
	}
}