<?php
/**
 * Login_Model_Roles
 *  
 * @author Enrico Zimuel (enrico@zimuel.it)
 */
class System_Model_Login_Roles extends Zend_Db_Table_Abstract
{
    protected $_name = 'roles';
    protected $_primary = 'id';
    protected $_dependentTables = array('Users','Permissions');
    /**
     * getRoles
     * 
     * @return object
     */
    public function getRoles() {
    	return $this->fetchAll(null,'id');
    }
    /**
     * getParentRole
     *
     * @param integer $role
     * @return integer|boolean
     */
    public function getParentRole($role) {
        $select= $this->select('id_parent')
                      ->from(array('r'=>'roles'))
                      ->where('r.id=?',$role);
        $record= $this->fetchRow($select);
        if (!empty($record['id_parent'])) {
            return $record['id_parent'];
        }
        return false;
    }
    
    

    public static function renderCombo(){
    	$db = new System_Model_Login_Roles();
    	$dados = $db->fetchAll()->toArray();
    	$rdepto[''] = '- Selecione -';
    	foreach($dados as $depto){
    		$rdepto[$depto['id']] = $depto['role'];
    	}
    	return $rdepto;
    }
    
    public static function getNomeRole($id){
    	$db = new System_Model_Login_Roles();
    	$dados = $db->fetchRow("id = '$id'");
    	return $dados->role;
    }
        
        
}