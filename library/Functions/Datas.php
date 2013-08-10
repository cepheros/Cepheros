<?php
class Functions_Datas{
	
	
	public static function inverteData($data){
		$reverteHora = explode(' ',$data);
		$novadata = implode("-", array_reverse(explode("/", $reverteHora[0])));
			
		if( isset($reverteHora[1]) ) {
			$novadata .= " {$reverteHora[1]}";
		}
			
		return $novadata;
	}
	
	public static function MyDateTime($date,$time=FALSE){
		if($time){
			return date('d/m/Y H:i:s',strtotime($date));
		}else{
			return date('d/m/Y',strtotime($date));
		}
	}
	
	
	
	
	public static function SomaData($data, $dias)	{
		$data_e = explode("/",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1],$data_e[0] + $dias,$data_e[2]));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 * SomaMes($data,$meses)
	 * Função para somar uma quantidade x de meses a uma determinada data
	 * @param string $data Recebe a data a ser somada
	 * @param int $meses recebe a quantidade de meses a ser somada
	 * @return Nova data com a quantidade de meses informada
	 * @access public
	 */
	
	public static function SomaMes($data, $meses)	{
		$data_e = explode("/",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1] + $meses,$data_e[0],$data_e[2]));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 *  Fun��o para somar uma quantidade x de anos a uma determinada data
	 * @param string $data Recebe a data a ser somada
	 * @param int $anos recebe a quantidade de anos a ser somada
	 * @return Nova data com a quantidade de anos informada
	 * @access public
	 */
	public static function SomaAnos($data, $anos)	{
		$data_e = explode("/",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1],$data_e[0],$data_e[2]+ $anos));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 *  Fun��o para subtrair uma quantidade x de dias a uma determinada data
	 * @param string $data Recebe a data a ser somada
	 * @param int $dias recebe a quantidade de dias a ser subtraida
	 * @return Nova data com a quantidade de dias informada
	 * @access public
	 */
	
	public static function SubtraiData($data, $dias)	{
		$data_e = explode("/",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1],$data_e[0] - $dias,$data_e[2]));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 * Fun��o para subtrair uma quantidade x de meses a uma determinada data
	 * @param string $data Recebe a data a ser somada
	 * @param int $meses recebe a quantidade de meses a ser subtraida
	 * @return Nova data com a quantidade de meses informada
	 * @access public
	 */
	
	public static	function SubtraiMes($data, $meses){
		$data_e = explode("/",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1] - $meses,$data_e[0],$data_e[2]));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 *  Fun��o para subtrair uma quantidade x de anos a uma determinada data
	 * @param string $data Recebe a data a ser somada
	 * @param int $anos recebe a quantidade de anos a ser subtraida
	 * @return Nova data com a quantidade de anos informada
	 * @access public
	 */
	public static function SubtraiAno($data, $anos){
		$data_e = explode("/",$data);
		$data2 = date("m/d/Y", mktime(0,0,0,$data_e[1],$data_e[0],$data_e[2] - $anos));
		$data2_e = explode("/",$data2);
		$data_final = $data2_e[1] . "/". $data2_e[0] . "/" . $data2_e[2];
		return $data_final;
	}
	
	/**
	 *  Fun��o calcular a diferen�a em dias de duas determinadas datas
	 * @param string $datainicial Recebe a data inicial
	 * @param string $datafinal Recebe a data final
	 * @return Quantidade de dias entre a data inicial e a data final
	 * @access public
	 */
	public static function CanculaDias($datainicial,$datafinal){
		// Define os valores a serem usados
		$data_inicial = implode("-", array_reverse(explode("/", $datainicial)));
		$data_final = implode("-", array_reverse(explode("/", $datafinal)));
	
		// Usa a fun��o strtotime() e pega o timestamp das duas datas:
		$time_inicial = strtotime($data_inicial);
		$time_final = strtotime($data_final);
	
		// Calcula a diferen�a de segundos entre as duas datas:
		$diferenca = $time_final - $time_inicial; // 19522800 segundos
	
		// Calcula a diferen�a de dias
		$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias
	
		//Exibe uma mensagem de resultado:
		return $dias;
	}
	
	/**
	 *  Fun��o calcular a diferen�a em dias de duas determinadas datas
	 * @param string $date1 Recebe a data inicial
	 * @param string $date2 Recebe a data final
	 * @return Quantidade de dias entre a data inicial e a data final
	 * @access public
	 */
	
	public static function DirerencaDatas ($date1, $date2){
		$diff  = $date1 > $date2 ? $date1 - $date2 : $date2 - $date1;
		return $diff / 3600;
	}
	
	public static function dateTimeDiff($data_ref,$currentdate = ''){
	
		if($currentdate == ''){
			$current_date = date('Y-m-d H:i:s');
		}else{
			$current_date = $currentdate;
		}
	
		// Extract from $current_date
		$current_year = substr($current_date,0,4);
		$current_month = substr($current_date,5,2);
		$current_day = substr($current_date,8,2);
	
		// 	Extract from $data_ref
		$ref_year = substr($data_ref,0,4);
		$ref_month = substr($data_ref,5,2);
		$ref_day = substr($data_ref,8,2);
	
		// 	create a string yyyymmdd 20071021
		$tempMaxDate = $current_year . $current_month . $current_day;
		$tempDataRef = $ref_year . $ref_month . $ref_day;
	
		$tempDifference = $tempMaxDate-$tempDataRef;
	
		// 	If the difference is GT 10 days show the date
		if($tempDifference >= 10){
			$wr = $data_ref;
		} else {
	
			// Extract $current_date H:m:ss
			$current_hour = substr($current_date,11,2);
			$current_min = substr($current_date,14,2);
			$current_seconds = substr($current_date,17,2);
	
			// 	Extract $data_ref Date H:m:ss
			$ref_hour = substr($data_ref,11,2);
			$ref_min = substr($data_ref,14,2);
			$ref_seconds = substr($data_ref,17,2);
	
			$hDf = $current_hour-$ref_hour;
			$mDf = $current_min-$ref_min;
			$sDf = $current_seconds-$ref_seconds;
	
			// 	Show time difference ex: 2 min 54 sec ago.
			if($dDf<1){
				if($hDf>0){
					if($mDf<0){
						$mDf = 60 + $mDf;
						$hDf = $hDf - 1;
						$wh = $mDf . ' mins ';
					} else {
						$wh =  $hDf. ' h ' . $mDf . ' m ';
					}
				} else {
					if($mDf>0){
						$wh =  $mDf . ' m ' . $sDf . ' s ';
					} else {
						$wh = $sDf . ' s';
					}
				}
			} else {
				$wh =  $dDf . ' dias';
			}
	
		}
		return $wh;
	}
	
	
	
	public static function get_time_difference( $start, $end = '' )
	{
		if($end == ''){
			$end = date('Y-m-d H:i:s');
		}
		$uts['start']      =    strtotime( $start );
		$uts['end']        =    strtotime( $end );
		if( $uts['start']!==-1 && $uts['end']!==-1 )
		{
			if( $uts['end'] >= $uts['start'] )
			{
				$diff    =    $uts['end'] - $uts['start'];
				if( $days=intval((floor($diff/86400))) )
					$diff = $diff % 86400;
				if( $hours=intval((floor($diff/3600))) )
					$diff = $diff % 3600;
				if( $minutes=intval((floor($diff/60))) )
					$diff = $diff % 60;
				$diff    =    intval( $diff );
				return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
			}
			else
			{
				trigger_error( "Ending date/time is earlier than the start date/time", E_USER_WARNING );
			}
		}
		else
		{
			trigger_error( "Invalid date/time data detected", E_USER_WARNING );
		}
		return( false );
	}
	
	public static function getMonthsGraprh($datainicial = null){
		
		if(!$datainicial){
		$datainicial = date('d/m/Y');
		}
		
		for($i=6; $i>0;$i--){
			$data[] = date('Y-m-d',strtotime(Functions_Datas::inverteData(Functions_Datas::SubtraiMes($datainicial, $i))));
		}
		
		for($i=0; $i<6;$i++){
			$data[] = date('Y-m-d',strtotime(Functions_Datas::inverteData(Functions_Datas::SomaMes($datainicial, $i))));
		}
		
		foreach($data as $dado){
			$mesi = Functions_Datas::getMonthByDate($dado).'/'.date('y',strtotime($dado));
			
			$mes[] = "'$mesi'";
			
		}
		return $mes;
		
		
	}
	
	public static function getMonthsForData($datainicial = null){
	
		if(!$datainicial){
			$datainicial = date('d/m/Y');
		}
	
		for($i=6; $i>0;$i--){
			$mes[] = date('m/Y',strtotime(Functions_Datas::inverteData(Functions_Datas::SubtraiMes($datainicial, $i))));
		}
	
		for($i=0; $i<6;$i++){
			$mes[] = date('m/Y',strtotime(Functions_Datas::inverteData(Functions_Datas::SomaMes($datainicial, $i))));
		}
		
		
	
		return $mes;
	
	
	}
	
	public static function getMonthByDate($date,$abreviado= TRUE){
		
		$month  = date('m',strtotime($date));
	//	echo $month;
		switch ($month){
			
			case 1:
				if($abreviado){
					$mes = "Jan";
				}else{
					$mes = "Janeiro";
				}
				
			break;
			case 2:
				if($abreviado){
					$mes = "Fev";
				}else{
					$mes = "Fevereiro";
				}
			break;
			case 3:
				if($abreviado){
					$mes = "Mar";
				}else{
					$mes = "Março";
				}
			break;
			case 4:
				if($abreviado){
					$mes = "Abr";
				}else{
					$mes = "Abril";
				}
			break;
			case 5:
				if($abreviado){
					$mes = "Mai";
				}else{
					$mes = "Maio";
				}
			break;
			case 6:
				if($abreviado){
					$mes = "Jun";
				}else{
					$mes = "Junho";
				}
			break;
			case 7:
				if($abreviado){
					$mes = "Jul";
				}else{
					$mes = "Julho";
				}
			break;
			case 8:
				if($abreviado){
					$mes = "Ago";
				}else{
					$mes = "Agosto";
				}
			break;
			case 9:
				if($abreviado){
					$mes = "Set";
				}else{
					$mes = "Setembro";
				}
			break;
			case 10:
				if($abreviado){
					$mes = "Out";
				}else{
					$mes = "Outubro";
				}
			break;
			case 11:
				if($abreviado){
					$mes = "Nov";
				}else{
					$mes = "Novembro";
				}
			break;
			case 12:
				if($abreviado){
					$mes = "Dez";
				}else{
					$mes = "Dezembro";
				}
			break;
			
			
			
		}
		return $mes;
			
		
	}

}