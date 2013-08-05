<?php
class Functions_Verificadores{


	public function checkCNPJCPF($dado){
		$cnpj = preg_replace( "@[./-]@", "", $dado );
		$caracteres = strlen($cnpj);
		if($caracteres==14){
			$check = $this->validaCNPJ($cnpj);
		}else{
			$check = $this->validaCPF($cnpj);
		}

		return $check;

	}

	protected function validaCPF($cpf){
		for( $i = 0; $i < 10; $i++ ){
			if ( $cpf == str_repeat( $i , 11) or !preg_match("@^[0-9]{11}$@", $cpf ) or $cpf == "12345678909" ) return false;
			if ( $i < 9 ) $soma[] = $cpf{$i} * ( 10 - $i );
			$soma2[] = $cpf{$i} * ( 11 - $i );
		}

		if(((array_sum($soma)% 11) < 2 ? 0 : 11 - ( array_sum($soma) % 11 )) != $cpf{9})return false;
		return ((( array_sum($soma2)% 11 ) < 2 ? 0 : 11 - ( array_sum($soma2) % 11 )) != $cpf{10}) ? false : true;
	}

	public function validaCNPJ( $cnpj ) {
		if( strlen( $cnpj ) <> 14 or !is_numeric( $cnpj ) ){
			return false;
		}

		$k = 6;
		$soma1 = "";
		$soma2 = "";

		for( $i = 0; $i < 13; $i++ ){
			$k = $k == 1 ? 9 : $k;
			$soma2 += ( $cnpj{$i} * $k );
			$k--;

			if($i < 12) {
				if($k == 1) {
					$k = 9;
					$soma1 += ( $cnpj{$i} * $k );
					$k = 1;
				} else {
					$soma1 += ( $cnpj{$i} * $k );
				}
			}
		}

		$digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
		$digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;

		return ( $cnpj{12} == $digito1 and $cnpj{13} == $digito2 );
	}
	
	public static function validaCNPJCFP($dado){
		$check = new Functions_Verificadores();
		return $check->checkCNPJCPF($dado);		
	}
	
	
	public static function validCerts($id){
	
		$db2 = new System_Model_EmpresasNF();
		$dadosnf = $db2->fetchRow("id_empresa = '$id'")->toArray();
	
		$key = file_get_contents($dadosnf['certificadodigital']);
		openssl_pkcs12_read($key,$x509certdata,$dadosnf['senhacertificado']);
	
		$cert = $x509certdata['cert'];
		$flagOK = true;
		$errorMsg = "";
		$data = openssl_x509_read($cert);
		$cert_data = openssl_x509_parse($data);
		// reformata a data de validade;
		$ano = substr($cert_data['validTo'],0,2);
		$mes = substr($cert_data['validTo'],2,2);
		$dia = substr($cert_data['validTo'],4,2);
		//obtem o timeestamp da data de validade do certificado
		$dValid = gmmktime(0,0,0,$mes,$dia,$ano);
		// obtem o timestamp da data de hoje
		$dHoje = gmmktime(0,0,0,date("m"),date("d"),date("Y"));
		// compara a data de validade com a data atual
		if ($dValid < $dHoje ){
			$flagOK = false;
			$errorMsg = "A Validade do certificado expirou em ["  . $dia.'/'.$mes.'/'.$ano . "]";
		} else {
			$flagOK = $flagOK && true;
		}
		//diferen�a em segundos entre os timestamp
		$diferenca = $dValid - $dHoje;
		// convertendo para dias
		$diferenca = round($diferenca /(60*60*24),0);
		//carregando a propriedade
		$daysToExpire = $diferenca;
		// convertendo para meses e carregando a propriedade
		$m = ($ano * 12 + $mes);
		$n = (date("y") * 12 + date("m"));
		//numero de meses at� o certificado expirar
		$monthsToExpire = ($m-$n);
	
		return array('status'=>$flagOK,'error'=>$errorMsg,'meses'=>$monthsToExpire,'dias'=>$daysToExpire,'dataexpire'=>$dia.'/'.$mes.'/'.$ano);
	}
	
	public static function validadeCertificado($id){
		
		$db2 = new System_Model_EmpresasNF();
		$dadosNF = $db2->fetchRow("id_empresa = '$id'")->toArray();
		
		$validade = Functions_Datas::MyDateTime($dadosNF['validadecertificado'],null);
		$time1 = strtotime(date('Y-m-d'));
		$time2 = strtotime($dadosNF['validadecertificado']);
		if($time2 < $time1){
			$ErroCertificado = true;
		}else{
			$ErroCertificado = false;
		
			$Val2 = strtotime(Functions_Datas::inverteData(Functions_Datas::SubtraiData($validade, 45)));
			if($Val2 <= $time1){
				$CertificadoVencendo = true;
			}else{
				$CertificadoVencendo = false;
			}
		
		}
		 
		if($validade <> ''){
			$ValidadeCertificado = $validade;
		}else{
			$ValidadeCertificado = "Sem Certificado!";
		}
		
		$dadoscert = array(
				'ErroCertificado'=>$ErroCertificado,
				'CertificadoVendendo'=>$CertificadoVencendo,
				'ValidadeCertificado'=>$ValidadeCertificado
				);
		
		return $dadoscert;
	}
	
	
	
	public static function checkSoapAccess($cliente,$chave){
		$db = new Cadastros_Model_Outros();
		$dados = $db->fetchRow("id_pessoa = '$cliente' and acessosoap = '1'");
		if($dados->id_pessoa <> ''){
			if($dados->chavesoap == $chave){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
		
	}
	
	public function ValidSystemUsePermission(){
		$dados = array('StatusSistema'=>'Ativo',
				'datalimite'=>'2013-12-30'
		);
		
		return $dados;
	}
	

}