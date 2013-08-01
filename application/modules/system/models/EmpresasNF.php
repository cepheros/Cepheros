<?php

class System_Model_EmpresasNF extends Zend_Db_Table_Abstract{
	protected $_name = 'tblsystem_empresa_nf';
	protected $_primary = 'id_registro';
	protected $_dependentTables = array('tblsystem_empresas');


	/**
	 * getConfifNFe
	 * Retorna um array com as configurações para emissao de NF-e
	 * para um determinado cadastro de empresa
	 *
	 *
	 * @name getConfifNFe
	 * @param int $id Identificador do registro no cadastro de empresas
	 * @return array Dados de configuração da Nota Fiscal Eletronica.
	 */

	public static function getConfigNFe($id){
		$protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
  		$baseURL = $protocol . "://" . $_SERVER['HTTP_HOST'];
		$db = new System_Model_EmpresasNF();
		$dados = $db->fetchRow("id_empresa = '$id'");
		$dadosempresa = System_Model_Empresas::getDataEmpresa($id);
		$aConfig['ambiente']= $dados->ambienteemissao;
		$aConfig['empresa'] = $dadosempresa->razaosocial;
		$aConfig['id_empresa'] = $id;
		$aConfig['UF'] =  $dadosempresa->estadoend;
		$aConfig['cnpj'] = str_replace(array('.','-','/'), array('','',''), $dadosempresa->cnpj);
		$aConfig['certName'] = $dados->certificadodigital;
		$aConfig['keyPass'] = $dados->senhacertificado;
		$aConfig['passPhrase'] = $dados->frasecertificado;
		$aConfig['arquivosDir'] = APPLICATION_PATH."/data/files/nfe/$id/";
		$aConfig['arquivoURLxml'] = 'nfe_ws2.xml';
		$aConfig['baseurl'] = $baseURL;
		$aConfig['danfeLogo'] = $dados->logotipodanfe;
		$aConfig['danfeLogoPos'] = $dados->danfelogopos;
		$aConfig['danfeFormato'] = $dados->danfeformato;
		$aConfig['danfePapel'] = $dados->danfepapel;
		$aConfig['danfeCanhoto'] = $dados->danfecanhoto;
		$aConfig['danfeFonte'] = $dados->danfefonte;
		$aConfig['danfePrinter'] = '';
		$aConfig['schemes'] = $dados->schemes;
		$aConfig['proxyIP'] = '';
		$aConfig['mailFROM'] = '';
	    return $aConfig;
	}
	
	/**
	 * getLastNFe
	 * Verifica no banco de dados qual o numero da última NF-e Emitida
	 * para um determinado cadastro de empresa
	 *
	 *
	 * @name getLastNFe
	 * @param int $id Identificador do registro no cadastro de empresas do sistema
	 * @param boolean  $soma TRUE Retorna o numero da ultima NF-e somado de mais 1 FALSE - Retorna somente o numero
	 * @return int Numero da NFe
	 */
	
	public static function getLastNFe($id,$soma = true){
		$db = new System_Model_EmpresasNF();
		$dados = $db->fetchRow("id_empresa = '$id'");
		if($soma){
			return $dados->lastnfe + 1;
		}else{
			return $dados->lastnfe;
		}		
	}
	
	/**
	 * addNFe
	 * Funcao para atualizar o numero da ultima nfe emitida
	 * @param int $id Identificador unico do cadastro da empresa no sistema
	 * @return boolean TRUE para atualizado FALSE para erro
	 */
	
	public static function addNFe($id){
		$db = new System_Model_EmpresasNF();
		$dados = $db->fetchRow("id_empresa = '$id'");
		$newNFe = $dados->lastnfe + 1;
		$updatedata['lastnfe'] = $newNFe;		
		try{
		$db->update($updatedata,"id_registro = '{$dados->id_registro}'");
				return true;
		}catch (Exception $e){
			return false;
		}
		}
		
		
		public static function updateLastNFe($NFe,$empresa){
			$db = new System_Model_EmpresasNF();
			$updatedata['lastnfe'] = $NFe;
			try{
				$db->update($updatedata,"id_empresa = '$empresa'");
				return true;
			}catch (Exception $e){
				return false;
			}
		}
	
		public static function getDadosConfigNFe($id){
			$db = new System_Model_EmpresasNF();
			$dados = $db->fetchRow("id_empresa = '$id'");
			return $dados;
			
			
		}
		
		public static function getLogo($id){
			$db = new System_Model_EmpresasNF();
			$dados = $db->fetchRow("id_empresa = '$id'");
			return $dados->logotipodanfe;
				
				
		}
}
