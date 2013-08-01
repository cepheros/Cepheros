<?php

/**
 * Classe responsavel por manipular envio de mensagens multiplas para o gateway
 *
 * @author Tiago
 * @since 05/05/2011
 * @version 1.0
 */
class HumanMultipleSend extends HumanBaseService
{
    /**
     * Layout de lista ou arquivo tipo "A":
     * to;msg
     *
     * @var char
     */
    const TYPE_A = 'A';
    
    /**
     * Layout de lista ou arquivo tipo "B":
     * to;msg;from
     *
     * @var char
     */
    const TYPE_B = 'B';
    
    /**
     * Layout de lista ou arquivo tipo "C":
     * to;msg;id
     *
     * @var char
     */
    const TYPE_C = 'C';
    
    /**
     * Layout de lista ou arquivo tipo "D":
     * to;msg;id;from
     *
     * @var char
     */
    const TYPE_D = 'D';
    
    /**
     * Layout de lista ou arquivo tipo "E":
     * to;msg;id;from;schedule
     *
     * @var char
     */
    const TYPE_E = 'E';
    
    /**
     * Retorno do callback, retorno inativo
     * @var integer
     */
    const CALLBACK_INACTIVE            = 0;
    
    /**
     * Retorno do callback, somente retorno de status final da mensagem
     * @var integer
     */
    const CALLBACK_FINAL_STATUS        = 1;
    
    /**
     * Retorno do callback, retorno de status intermediário e final da mensagem
     * @var integer
     */
    const CALLBACK_INTERMEDIARY_STATUS = 2;
    
    public function __construct($account, $password)
    {
        parent::__construct($account, $password);
        $this->setUri(parent::URI_SEND);
    }
    
    /**
     * Funcao para enviar parametros por HTTP/POST utilizando um arquivo .csv local.
     *
     * @param char $type
     * @param string $pathToFile
     * @param integer $callbackOption (0, 1, 2)
     * @return string
     */
    public function sendMultipleFileCSV($type, $pathToFile, $callbackOption = self::CALLBACK_INACTIVE)
    {
        $msgList = file($pathToFile);
        $numbers = "";
        
        foreach ($msgList as $lineNum => $line) {
            $numbers .= $line;
        }
        
        return $this->sendMultipleList($type, $numbers, $callbackOption);
    }
    
    /**
     * Funcao para enviar parametros por HTTP/POST utilizando um list.
     *
     * @param char $type
     * @param string $msgList
     * @param integer $callbackOption (0, 1, 2)
     * @return string
     */
    public function sendMultipleList($type, $msgList, $callbackOption = self::CALLBACK_INACTIVE)
    {
        $params = array(
            "dispatch"       => "sendMultiple",
            "account"        => $this->getAccount(),
            "code"           => $this->getPassword(),
            "type"           => $type,
            "callbackOption" => $callbackOption,
            "list"           => $msgList,
        );
        
        return $this->send($params);
    }
    
    public function queryMultipleStatus(array $idList)
    {
    	$ids = implode(";", $idList);
    	
    	$params = array(
            "dispatch"       => "checkMultiple",
            "account"        => $this->getAccount(),
            "code"           => $this->getPassword(),
            "idList"         => $ids,
        );
        
        return $this->query($params);
    }
}