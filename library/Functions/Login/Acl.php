<?php

/**
 * Leader_Login_Acl
 * Execucoes dos sistemas Leader
 * Classe de Suporta para definicao de regras de acesso ao sistema
 *  
 * @author Daniel Chaves, dchaves@leaderportal.com.br
 * @author Lyjor Amorim, lamorim@leaderportal.com.br
 * @author Andr� Renovatto, arenovatto@leaderportal.com.br
 * @version 1.0
 * @license http://leaderservices.com.br/lisence
 * @copyrigth 2011 - Leader Tecnologia & Srvi�os Ltda
 * @package Cepheros Leader
 */ 
class Functions_Login_Acl extends Zend_Acl {
    /**
     * __construct
     *
     * @param Zend_Db_Adapter $db
     * @param integer $role
     */
    public function __construct($db,$role) {
        $this->loadRoles($db);
        $roles = new System_Model_Login_Roles($db);
        $inhRole= $role;
        while (!empty($inhRole)) {
            $this->loadResources($db,$inhRole);
            $this->loadPermissions($db,$inhRole);
            $inhRole= $roles->getParentRole($inhRole);
        }
    }
    /**
     * Load all the roles from the DB
     *
     * @param Zend_Db_Adapter $db
     * @return boolean
     */
    public function loadRoles($db) {
    	if (empty($db)) {
    		return false;
    	}
        $roles = new System_Model_Login_Roles($db);
        $allRoles = $roles->getRoles();
        foreach ($allRoles as $role) {
            if (!empty($role->id_parent)) {
                $this->addRole(new Zend_Acl_Role($role->id),$role->id_parent);
            } else {
                $this->addRole(new Zend_Acl_Role($role->id));
            }
        }
        return true;
    }
    /**
     * Load all the resources for the specified role
     *
     * @param Zend_Db_Adapter $db
     * @param integer $role
     * @return boolean
     */
    public function loadResources($db,$role) {
    	if (empty($db)) {
    		return false;
    	}
    	$resources= new System_Model_Login_Resources($db);
    	$allResources= $resources->getResources($role);
    	foreach ($allResources as $res) {
                if (!$this->has($res)) {
                    $this->addResource(new Zend_Acl_Resource($res['resource']));
                }
    	}
        return true;
    }
    /**
     * Load all the permission for the specified role
     *
     * @param Zend_Db_Adapter $db
     * @param integer $role
     * @return boolean
     */
    public function loadPermissions($db,$role) {
    	if (empty($db)) {
    		return false;
    	}
    	$permissions= new System_Model_Login_Permissions($db);
    	$allPermissions= $permissions->getPermissions($role);
    	foreach ($allPermissions as $res) {
    		if ($res['permission']=='allow') {
    			$this->allow($res['id_role'],$res['resource']);
    		} else {
    			$this->deny($res['id_role'],$res['resource']);
    		}	
    	}
        return true;
    }

}

