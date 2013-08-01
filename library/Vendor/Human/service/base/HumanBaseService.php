<?php

/**
 * Classe abstrata com a responsabilidade de disponibilizar recursos
 * de comunicação com o server API genéricos.
 *
 * @author Tiago
 * @abstract
 * @since 06/05/2011
 * @version 1.0
 */
abstract class HumanBaseService implements IHumanBaseService
{
    /**
     * Uri de envio de mensagem sms.
     *
     * @var string
     */
    const URI_SEND = "send";
    const URI_QUERY = "query";
    
    private $account;
    private $password;
    
    private $host;
    private $uri;
    
    private $config;
    
    public function __construct($account, $password)
    {
        $this->account  = $account;
        $this->password = $password;
        
        $this->config   = include HUMAN_ROOT . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'main.php';
        
        $this->host = $this->config["host"];
    }
    
    public function setHost($host)
    {
        if (!is_null($host) && trim($host) != "") {
            $this->host = $host;
        }
    }
    
    public function getHost()
    {
        return $this->host;
    }
    
    public function setUri($uri)
    {
        if (isset($this->config["uri"][$uri])) {
            $this->uri = $this->config["uri"][$uri];
        } elseif (!is_null($uri) && trim($uri) != "") {
            $this->uri = $uri;
        }
    }
    
    public function getUri()
    {
        return $this->uri;
    }
    
    public function getAccount()
    {
        return $this->account;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    /**
     * Método preparado para envio de mensagem
     *
     * @param array $params
     * @param string $contentType
     * @return string
     */
    public function send($params = array(), $contentType = self::CONTENT_TYPE_APP_FORM_URLENCODED)
    {
    	$this->setUri(self::URI_SEND);
    	
    	return HumanConnectionHelper::sendRequest($this->host, $this->uri, $params, $contentType);
    }
    
    /**
     * M�todo preparado para consulta de mensagens
     *
     * @param array $params
     * @param string $contentType
     * @return string
     */
    public function query($params = array(), $contentType = self::CONTENT_TYPE_APP_FORM_URLENCODED)
    {
    	$this->setUri(self::URI_QUERY);
    	
    	return HumanConnectionHelper::sendRequest($this->host, $this->uri, $params, $contentType);
    }
}