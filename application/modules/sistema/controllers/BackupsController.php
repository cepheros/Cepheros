<?php
/**
 * Este arquivo é parte do projeto Cepheros - SysAdmin - ERP em PHP
 *
 * Este programa é um software livre: você pode redistribuir e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU (GPL)como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior
 * e/ou
 * sob os termos da Licença Pública Geral Menor GNU (LGPL) como é publicada pela Fundação
 * para o Software Livre, na versão 3 da licença, ou qualquer versão posterior.
 *
 *
 * Este programa é distribuído na esperança que será útil, mas SEM NENHUMA
 * GARANTIA; nem mesmo a garantia explícita definida por qualquer VALOR COMERCIAL
 * ou de ADEQUAÇÃO PARA UM PROPÓSITO EM PARTICULAR,
 * veja a Licença Pública Geral GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Publica GNU e da
 * Licença Pública Geral Menor GNU (LGPL) junto com este programa.
 * Caso contrário consulte <http://www.fsfla.org/svnwiki/trad/GPLv3> ou
 * <http://www.fsfla.org/svnwiki/trad/LGPLv3>.
 *
 * @package    	Cepheros
 * @subpackage 	SysAdmin
 * @name		BackupsController.php 
 * @version   	1.0.0
 * @license   	http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 	2009-2012 &copy; SysAdmin
 * @link      	http://www.cepheros.com.br
 * @author    	Daniel R. Chaves <daniel@danielchaves.com.br>
 *
 */

class Sistema_BackupsController extends Zend_Controller_Action{
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
		if($this->configs->phpSettings->display_errors == 1){
			$this->view->DebugEnable = true;
		}
		$this->view->parameters = $this->_request->getParams();	
	}
	
	public function indexAction(){
		
		
	}
	
}

 
