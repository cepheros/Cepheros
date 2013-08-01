<?php

class ErrorController extends Zend_Controller_Action
{
	
	
    
    public function init()
    {
    	if(!Zend_Auth::getInstance()->hasIdentity())
    	{
    			$this->_helper->viewRenderer->setRender('errornotloged');
    	}else{
    		$this->_helper->layout()->setLayout('layout');
    	}
    	$this->log = Zend_Registry::get('log');
    	$this->configs = Zend_Registry::get('configs');
    	$this->cache = Zend_Registry::get('cache');
    	
    	if($this->configs->phpSettings->display_errors == 1){
    		$this->view->DebugEnable = true;
    	}
    	$this->view->parameters = $this->_request->getParams();
    
    	
    }

    public function errorAction()
    {
    
         
    	//$this->_helper->layout->disableLayout();
        $errors = $this->_getParam('error_handler');
        
        if (!$errors) {
          
            return;
        }
        
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ROUTE:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Recurso N&atilde;o Localizado!';
                
                break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                
                $this->view->message = 'Erro de Sistema';
                break;
        }
        
        // Log exception, if logger available
         
       
        
        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }
        
        $this->view->request   = $errors->request;
 
        $this->log->crit($this->view->message, $errors->exception);
        $this->log->crit($errors->exception->getMessage());
        $this->log->log( $errors->exception->getTraceAsString(),Zend_Log::CRIT);
       
    }

    public function getLog()
    {
        $bootstrap = $this->getInvokeArg('bootstrap');
        if (!$bootstrap->hasResource('Log')) {
            return false;
        }
        $log = $bootstrap->getResource('Log');
        return $log;
    }


}

