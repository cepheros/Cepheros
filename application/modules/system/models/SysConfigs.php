<?php
class System_Model_SysConfigs extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_configs';
	protected $_primary = 'id_registro';
	
	
	
	public static function newConfig($name,$value){
		$db = new System_Model_SysConfigs;
		$db->insert(array('configname'=>$name,'configvalue'=>$value));
		
		
	}
	
	public static function getConfig($name){
		$db = new System_Model_SysConfigs;
		$dado = $db->fetchRow("configname = '$name'");
		if($dado){
			return $dado->configvalue;
		}else{
			return null;
		}		
	}
	
	public static function updateConfig($name,$value = 0){
		$db = new System_Model_SysConfigs;
			$db->update(array('configname'=>$name,'configvalue'=>$value),"configname = '$name'");		
	}
	
	

}
