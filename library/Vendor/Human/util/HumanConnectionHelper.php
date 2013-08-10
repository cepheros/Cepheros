<?php

/**
 * Utilitarios para conexao com servidores.
 *
 * @author Tiago
 * @since 05/05/2011
 * @version 1.0
 */
class HumanConnectionHelper
{
    /**
     * Cria e testa uma conexão com um servidor
     *
     * @param string $host
     * @return  resource fsockopen.
     * @throws RuntimeException
     */
    public static function connectService($host)
    {
        
        $da = fsockopen($host, 80, $errno, $errstr);
    
        if (!$da && $errno != 0) {
            throw new RuntimeException($errstr . " (" . $errno . ")");
        }
        
        return $da;
    }
    
    /**
     * Retorna o response do socket
     *
     * @param resource $sock
     * @return string
     */
    public static function getResponse($sock)
    {
        $response = "";
        while (!feof($sock)) {
            $response .= fgets($sock, 128);
        }
        
        $response        = explode("\r\n\r\n", $response);
        $header          = $response[0];
        $responsecontent = $response[1];
        
        if (!(strpos($header, "Transfer-Encoding: chunked") === false)) {
            
            $aux = explode("\r\n", $responsecontent);
            $countAux = count($aux);
            
            for ($i = 0; $i < $countAux; $i++) {
                if ($i == 0 || ($i%2) == 0) {
                    $aux[$i] = "";
                }
            }
            $responsecontent = implode("", $aux);
            
        }
        
        $responsecontent = explode("\n", rtrim($responsecontent));
		
        $result = array();
        
        foreach ($responsecontent as $resp) {
        	$humanResponse = new HumanResponse();
        	
        	$respAux = explode(" - ", $resp);
        	
        	$humanResponse->setCode($respAux[0]);
        	$humanResponse->setMessage($respAux[1]);
        	
        	array_push($result, $humanResponse);
        }
        
        return $result;
        
    }
    
	public static function sendRequest($host, $uri, $params, $contentType)
    {
    	$da = HumanConnectionHelper::connectService($host);
        
        // Prepara os dados para HTTP POST
        $output = HumanHTTPHelper::formatRequest($host, $uri, $contentType, $params);
        
        fwrite($da, $output);
        
        return HumanConnectionHelper::getResponse($da);
    }
}