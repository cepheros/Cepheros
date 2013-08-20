<?php
class System_Model_Menus extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_menus';
	protected $_primary = 'id_registro';
	
	public static function getMenus($module,$controller){
		$db = new System_Model_Menus();
		$menus = $db->fetchAll("controller = '$controller' and module = '$module' and action <> 'index'" );
		
		return $menus;
		
		
		
	}
	
}