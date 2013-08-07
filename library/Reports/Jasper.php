<?php
class Reports_Jasper{

		private $jasperServer;
	
		public function __construct($configs){
			$this->jasperServer = $configs;
		}
	
		private function _requestMock($report, $format, $params)
		{
	
			if (is_array($params)) {
				$reportParams = "";
				foreach ($params as $name => $value) {
					$reportParams .= "<parameter name=\"$name\"><![CDATA[$value]]></parameter>\n";
				}
			} else {
				$reportParams = '';
			}
	
			$xmlTemplate = <<<XML_TEMPLATE
		<request operationName="runReport" locale="br">
			<argument name="RUN_OUTPUT_FORMAT">{$format}</argument>
			<resourceDescriptor name="" wsType="" uriString="{$report}" isNew="false">
				<label>null</label>
				{$reportParams}
			</resourceDescriptor>
		</request>
XML_TEMPLATE;
				echo $xmlTemplate;
				return $xmlTemplate;
				
		}
	
		public function run($reportName,$formatType = 'PDF', $reportParams = null, $saveFile = false)
		{
			$client = new \SoapClient(	$this->jasperServer->WSDL,
					array('login' => $this->jasperServer->username,
							'password' => $this->jasperServer->password,
							"trace" => 1, "exceptions" => 0)
			);
	
			$client->runReport($this->_requestMock($reportName, $formatType, $reportParams));
	
			preg_match('/boundary="(.*?)"/', $client->__getLastResponseHeaders(), $gotcha);
			
			echo $client->__getLastResponseHeaders();
	
			$bound = $gotcha[1];
	
			$invokeReturnPart = explode($bound, $client->__getLastResponse());
	
			$output = substr($invokeReturnPart[2], (strpos($invokeReturnPart[2], '<report>') + 9));
	
			$content = trim(str_replace("\n--","",$output));
			if ($saveFile === true) {
				$filename = $this->jasperServer->path_to_save . DIRECTORY_SEPARATOR . 'generate_report_at_'.time().".{$formatType}";
				file_put_contents($filename, $content);
				return $filename;
			} else {
				return $content;
			}
		}
	}
