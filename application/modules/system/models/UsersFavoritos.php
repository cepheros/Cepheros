<?php
class System_Model_UsersFavoritos extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_usersfav';
	
	
	public function getFavoritos($user){
		try{
			$select = $this->getAdapter()->select()->from(array('a'=>'tblsystem_usersfav'),array('a.id_registro','b.module','b.controller','b.action','b.nome'))
			->join(array('b'=>'tblsystem_menus'),'b.id_registro = a.id_menu',array());			 
			
			return $this->getAdapter()->fetchAll($select);
		}catch(Zend_Db_Exception $e){
			return $e->getMessage();
		}
		 
		}
	
	
}