<?php
class System_ConfiguratorController extends Zend_Controller_Action{
	
	public function init(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
	}
	
	public function newdeptoAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new Crm_Model_TicketsDeptos();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
		
		$this->_redirect("/sistema/configuracoes/departamentos");
	
	}
	
	public function getdeptodataAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsDeptos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function removedeptoAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsDeptos();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/departamentos");
		
	}
	
	public function newticketstatusAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		switch($data['typestatus']){
			case 1:
				$data['isdefault'] = 1;
		        $data['isclosed'] = '0';
		        $data['issuspended'] = 0;
		        $data['ispendent'] = 0;
		        unset($data['typestatus']);
			break;
			case 2:
				$data['isdefault'] = 0;
				$data['isclosed'] = 1;
				$data['issuspended'] = 0;
				$data['ispendent'] = 0;
				unset($data['typestatus']);
			break;
			case 3:
				$data['isdefault'] = 0;
				$data['isclosed'] = 0;
				$data['issuspended'] = 1;
				$data['ispendent'] = 0;
			  	unset($data['typestatus']);
			break;
			case 4:
				$data['isdefault'] = 0;
				$data['isclosed'] = 0;
				$data['issuspended'] = 0;
				$data['ispendent'] = 1;
				unset($data['typestatus']);
			break;
				
		}
		
		$db = new Crm_Model_TicketsStatus();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
		
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/sistema/configuracoes/status-tickets");
	
	}
	
	public function getstatusticketdataAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsStatus();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function removestatusticketAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsStatus();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/status-tickets");
	
	}
	
	public function newparserAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_Tickets_Parser();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/sistema/configuracoes/fila-mensagens");
	
	}
	
	public function getparserdataAction(){
		$id = $this->_getParam('id');
		$db = new System_Model_Tickets_Parser();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function removeparserAction(){
		$id = $this->_getParam('id');
		$db = new System_Model_Tickets_Parser();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/fila-mensagens");
	
	}
	
	public function newtipoticketAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new Crm_Model_TicketsTipos();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
		
		$this->_redirect("/sistema/configuracoes/tipos-tickets");
		
		
	}
	
	public function gettipoticketdataAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsTipos();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function removetipoticketAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsTipos();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/tipos-tickets");
	
	}
	
	
	public function newprioridadeticketAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new Crm_Model_TicketsPrioridades();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/sistema/configuracoes/prioridade-tickets");
	
	
	}
	
	public function getprioridadeticketdataAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsPrioridades();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function removeprioridadeticketAction(){
		$id = $this->_getParam('id');
		$db = new Crm_Model_TicketsPrioridades();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/prioridade-tickets");
	
	}
	
	

	public function newtipopessoaAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_Tipopessoas();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/sistema/configuracoes/tipos-pessoas");
	
	
	}
	
	public function gettipopessoadataAction(){
		$id = $this->_getParam('id');
		$db = new System_Model_Tipopessoas();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
	}
	
	public function removetipopessoaAction(){
		$id = $this->_getParam('id');
		$db = new System_Model_Tipopessoas();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/tipos-pessoas");
	
	}
	
	
	public function newmensagemsistemaAction(){
		$data = $this->_request->getPost();
		unset($data['submit']);
		$db = new System_Model_MensagensSistema();
		if($data['id_registro'] == 0){
			unset($data['id_registro']);
			$db->insert($data);
		}else{
			$db->update($data,"id_registro = '{$data['id_registro']}'");
		}
	
		$this->_redirect("/sistema/configuracoes/mensagens-sistema");
	
	
	}
	
	public function getmensagemsistemaAction(){
		$id = $this->_getParam('id');
		$db = new System_Model_MensagensSistema();
		$dados = $db->fetchRow("id_registro = '$id'")->toArray();
		echo json_encode($dados);
		
	}
	
	public function removemensagemsistemaAction(){
		$id = $this->_getParam('id');
		$db = new System_Model_MensagensSistema();
		$db->delete("id_registro = '$id'");
		$this->_redirect("/sistema/configuracoes/mensagens-sistema");
	
	}
	
	public function atualizaprospectconfigsAction(){
		
	}
	
	
	
	
	
}