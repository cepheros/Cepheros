
<?php
/**
 * Este arquivo é parte do projeto SysAdmin - ERP em PHP
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
 * @package   SysAdmin
 * @name      
 * @version   1.0.0
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL v.3
 * @copyright 2009-2012 &copy; SysAdmin
 * @link      http://www.cepheros.com.br
 * @author    Daniel R. Chaves <dchaves at 32dll dot com dot br>
 *
 */
 
class Functions_Auxilio{
	
	/**
	 * getSysUrl
	 * Retorna a URL do Sistema
	 * @return string
	 */
	
	public static function getSysUrl(){
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		$baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		return $baseURL;
	}
	
	/**
	 * getLogoUrl
	 * Retorna a url do logo da empresa desejada
	 * @param int $empresa id do registro da empresa no sistema 
	 * @return string URL do Logo da empresa
	 */
	
	public static function getLogoUrl($empresa = null){
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
		$baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		if($empresa){
			$logo = "$baseURL/default/arquivos/get-logo-report/id/$empresa";
		}else{
			$logo = "$baseURL/default/arquivos/get-logo-report";
		}
		
		return $logo;
		
	}
	
	/**
	 * getNFeSchemes
	 * Utilizada para recuperar as pastas de schemes da NFe do sistema, facilitando assim a configuração de novos schemas
	 * @return lista de pastas com os Schemas
	 */
	
	public static function getNFeSchemes(){
		// pega o endereço do diretório
		$diretorio = NFeCONFIGS_PATH . '/schemes';
		// abre o diretório
		$ponteiro  = opendir($diretorio);
		// monta os vetores com os itens encontrados na pasta
		while ($nome_itens = readdir($ponteiro)) {
			$itens[] = $nome_itens;
		}
		
		
		sort($itens);
		
		// percorre o vetor para fazer a separacao entre arquivos e pastas
		foreach ($itens as $listar) {
			// retira "./" e "../" para que retorne apenas pastas e arquivos
			if ($listar!="." && $listar!=".."){
		
				// checa se o tipo de arquivo encontrado é uma pasta
				if (is_dir(NFeCONFIGS_PATH . '/schemes/'.$listar)) {
					// caso VERDADEIRO adiciona o item à variável de pastas
					$pastas[$listar]=$listar;
				} else{
					// caso FALSO adiciona o item à variável de arquivos
					$arquivos[]=$listar;
				}
			}
		}
		
			
		return $pastas;
		
	}
	
	/**
	 * formatText
	 * Formata uma string de acordo com uma mascara de texto
	 * @param string $campo string sem a mascara
	 * @param string $mascara como a mascara deve ser aplicada ex "##.##.##"
	 * @return string texto com a mascara aplicada
	 */
	
	
	public static function formatText($campo='',$mascara=''){
		//remove qualquer formatação que ainda exista
		$sLimpo = preg_replace("(/[' '-./ t]/)",'',$campo);
		// pega o tamanho da string e da mascara
		$tCampo = strlen($sLimpo);
		$tMask = strlen($mascara);
		if ( $tCampo > $tMask ) {
			$tMaior = $tCampo;
		} else {
			$tMaior = $tMask;
		}
		//contar o numero de cerquilhas da mascara
		$aMask = str_split($mascara);
		$z=0;
		$flag=FALSE;
		foreach ( $aMask as $letra ){
			if ($letra == '#'){
				$z++;
			}
		}
		if ( $z > $tCampo ) {
			//o campo é menor que esperado
			$flag=TRUE;
		}
		//cria uma variável grande o suficiente para conter os dados
		$sRetorno = '';
		$sRetorno = str_pad($sRetorno, $tCampo+$tMask, " ",STR_PAD_LEFT);
		//pega o tamanho da string de retorno
		$tRetorno = strlen($sRetorno);
		//se houve entrada de dados
		if( $sLimpo != '' && $mascara !='' ) {
			//inicia com a posição do ultimo digito da mascara
			$x = $tMask;
			$y = $tCampo;
			$cI = 0;
			for ( $i = $tMaior-1; $i >= 0; $i-- ) {
				if ($cI < $z){
					// e o digito da mascara é # trocar pelo digito do campo
					// se o inicio da string da mascara for atingido antes de terminar
					// o campo considerar #
					if ( $x > 0 ) {
						$digMask = $mascara[--$x];
					} else {
						$digMask = '#';
					}
					//se o fim do campo for atingido antes do fim da mascara
					//verificar se é ( se não for não use
					if ( $digMask=='#' ) {
						$cI++;
						if ( $y > 0 ) {
							$sRetorno[--$tRetorno] = $sLimpo[--$y];
						} else {
							//$sRetorno[--$tRetorno] = '';
						}
					} else {
						if ( $y > 0 ) {
							$sRetorno[--$tRetorno] = $mascara[$x];
						} else {
							if ($mascara[$x] =='('){
								$sRetorno[--$tRetorno] = $mascara[$x];
							}
						}
						$i++;
					}
				}
			}
			if (!$flag){
				if ($mascara[0]!='#'){
					$sRetorno = '(' . trim($sRetorno);
				}
			}
			return trim($sRetorno);
		} else {
			return '';
		}
	}
	
	/**
	 * renderCaptcha
	 * Funcao para renderizar o capcha no sistema
	 * return classe de catpcha do Zend Framework ja configurada.
	 */
	public static function renderCaptcha(){
		$config = Zend_Registry::get('configs');
		$pubKey = $config->ReCaptcha->pubKey;
		$privKey = $config->ReCaptcha->privKey;
		$lang = $config->ReCaptcha->lang;
		$theme = $config->ReCaptcha->theme;
		$C = new Zend_Service_ReCaptcha($pubKey,$privKey);
		$C->setOptions(array('theme'=>$theme,'lang'=>$lang));
		return $C;
		
	}
}