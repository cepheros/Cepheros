<?php

/**
 * Helper com a responsabilidade de disponibilizar utilitarios
 * de recurso http.
 *
 * @author Tiago
 * @since 05/05/2011
 * @version 1.0
 */
class HumanHTTPHelper
{
    const METHOD_POST = "POST";
    
    const CHARSET_ISO_8859_1 = "ISO-8859-1";
    const CHARSET_UTF_8 = "UTF-8";
    
    public static function formatRequest(
        $host, $uri, $contentType, $params = array(), $method = self::METHOD_POST, $charset = self::CHARSET_ISO_8859_1)
    {
        $postdata = "";
        foreach ($params as $key => $value) {
            if (trim($postdata) != "") {
                $postdata .= "&";
            }
            $postdata .= $key . "=" . $value;
        }
        
        $output  = $method . " " . $uri . " HTTP/1.0\r\n";
        $output .= "Host: " . $host . "\r\n";
        $output .= "User-Agent: PHP Script\r\n";
        $output .= "Content-Type: " . $contentType . "; ";
        $output .= "charset=" . $charset . "\r\n";
        $output .= "Content-Length: " . strlen($postdata) . "\r\n";
        $output .= "Connection: close\r\n\r\n";
        $output .= $postdata;
        
        return $output;
    }
}