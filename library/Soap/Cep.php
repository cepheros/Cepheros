<?php
class Soap_Cep{
    
   /**
    * Retorna o Endereco de um determinado cep
    * @param int $client codigo do cliente junto ao soap server
    * @param string $code codigo de acesso a api 
    * @param string $MyCep cep que o sistema deve localizar
	* @return array $retdados array de dados com as informaçoes localizadas
    */
    
    public function getlogradouro($client,$code,$MyCep){
    	

    	if(!Functions_Verificadores::checkSoapAccess($client, $code)){
    		$retdados['Status'] = 'FAIL';
    		$retdados['message'] = "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
    		return $retdados; 
    	}else{
    		try{
        		$db = new System_Model_Cep();
        		$cepdb = $db->fetchRow("cep = '$MyCep'");
        		$cepr['Status'] = "OK";
        		$cepr['cep'] = $MyCep;
        		$cepr['tipo_endereco'] = utf8_encode($cepdb->tipo_endereco);
        		$cepr['endereco'] = utf8_encode($cepdb->endereco);
        		$cepr['complemento'] = $cepdb->complemento;
        		$cepr['bairro'] = utf8_encode($cepdb->bairro);
        		$cepr['cidade'] = utf8_encode($cepdb->cidade);
        		$cepr['estado'] = utf8_encode($cepdb->estado);
        		$cepr['cod_mun'] = utf8_encode($cepdb->cod_mun);
        		$retdados = $cepr;
        		return $retdados;
    		}catch(Exception $e){
    			$retdados['Status'] = 'FAIL';
    			$retdados['message'] = $e->getMessage();
    			return $retdados;
    		}
    	}
    }
    
    
    /**
     * Procura pelo cep de um determinado logradouro
     * @param int $client 
     * @param string code 
     * @param string $search
     * @return array
     */
    
    public function searchcep($client,$code,$search){
    	$cliente = new Cadastros_Model_Outros();
    	$dados = $cliente->fetchRow("id_pessoa = '$client'");
    	if($dados->chavesoap <> $code){
    		return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
    	}else{
      	 $db = new System_Model_Cep();
         $cep = $db->fetchAll("endereco like '%$search%'")->toArray();
         $ret = array();
    	     foreach($cep as $retorno){
        		$retorno['tipo_endereco'] = utf8_encode($retorno['tipo_endereco']);
        		$retorno['endereco'] = utf8_encode($retorno['endereco']);
        		$retorno['complemento'] = utf8_encode($retorno['complemento']);
        		$retorno['bairro'] = utf8_encode($retorno['bairro']);
        		$retorno['cidade'] = utf8_encode($retorno['cidade']);
        		$ret[] = $retorno;
   		 	}
   		 
   		return $ret;
    	}
    }
    
     /**
     * Funcao para inserir um novo registro na tabela de cep
     * 
     * @param int $client 
     * @param string code
     * @param string $tipo_endereco 
     * @param string $endereco
     * @param string $complemento
     * @param string $cep
     * @param string $bairro
     * @param string $cidade
     * @param string $cepcidade
     * @param string $estado
     * @param string $cod_mun
     * @return int $id
     */
    
    public function savenewlog($client,$code,$tipo_endereco,$endereco,$complemento = NULL,$cep,$bairro,$cidade,$cepcidade,$estado,$cod_mun){
    	$cliente = new Cadastros_Model_Outros();
    	$dados = $cliente->fetchRow("id_pessoa = '$client'");
    	if($dados->chavesoap <> $code){
    		return "Codigo de acesso a API incorreto para o cliente ($client) e o codigo {$code}";
    	}else{
    	$data['tipo_endereco'] = utf8_decode($tipo_endereco);
    	$data['endereco'] = utf8_decode($endereco);
    	$data['complemento'] = utf8_decode($complemento);
    	$data['cep'] = utf8_decode($cep);
    	$data['bairro'] = utf8_decode($bairro);
    	$data['cidade'] = utf8_decode($cidade);
    	$data['cepcidade'] = utf8_decode($cepcidade);
    	$data['estado'] = utf8_decode($estado);
    	$data['cod_mun'] = utf8_decode($cod_mun);
    	
    	$db = new System_Model_Cep();
    	$id = $db->insert($data);
    	
    	return $id;
    	}
    	
    }
    
    
    
    
         
    
    
}