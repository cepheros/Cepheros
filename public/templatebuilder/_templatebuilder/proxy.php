<?php 
	//last Update 2012-01-26
	
	//File Download
	$filename = 'newsletter.html';
	
	if(isset($_GET['download'])){
		$datei = basename($_GET['download']);
		if(file_exists('temp/'.$datei)){
			header("Expires: 0");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Content-Type: application/force-download");
			header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=".$datei);
			header("Content-Transfer-Encoding: binary");
			readfile('temp/'.$datei);
		}else{
			header("HTTP/1.0 404 Not Found");
			echo 'File not Found';
		}
		exit;
	}
	
	//No AJAX Request? Output some Infos about the Server
	if(!isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
		check();
		exit();
	}
	if (function_exists ('ini_set')){
		//prevent display errors
	  ini_set("display_errors", 0);
		//but log them
	  ini_set('log_errors', 1 ); 
		//in the document root
	  ini_set('error_log', getcwd().'/php_error.log' );
	}
	
	//only this methods are allowed
	$allowedMethod = array('check','ping_mc','ping_cm','download','upload_mc','upload_cm','examplehtml');
	
	$method = $_REQUEST['method'];
	
	
	$return = array();
	$return['success'] = false;
	
	if(!in_array($method, $allowedMethod)){
		$return['response'] = 'Method '+$method+' is not allowed';
		echo json_encode($return);
		exit;
	}
	
	switch($method){
		
		
		//Check Server configuration
		case 'check':
		
			$return['response'] = '';
			if (version_compare(PHP_VERSION, '5.2.0') <= 0) {
				$return['response'] .= 'You need at least PHP version 5.2.0! (you have '.PHP_VERSION.")<br>";
			}
			if(!function_exists('curl_init')){
				$return['response'] .= 'You need the CURL extension to upload data to a service'."<br>";
			}
			if(!isWriteAble('temp/',$filename)){
				$return['response'] .= 'The upload folder isn\'t writeable'."<br>";
			}
			if(empty($return['response'])){
				$return['success'] = true;
				$return['response'] = 'Your server is good! Everything should work as expected!';
			}
			break;
			
			
		//Check Mailchimp Connection
		case 'ping_mc':
		
			$return['response'] = mcRequest($_POST['apikey'],'ping');
			
			//An error occurred
			if(isset($return['response']->error)){
				$return['response'] = $return['response']->error;
			//Timeout or something else
			}else if(!isset($return['response'])){
				$return['response'] = 'Mailchimp API isn\'t available at the moment';
			//Everything is Chimpy!
			}else{
				$return['success'] = true;
			}
			break;
			
			
		//Check Campaign Monitor Connection
		case 'ping_cm':
		
			$return['response'] = cmRequest($_POST['apikey'],'http://api.createsend.com/api/v3/clients.json');
			
			//An error occurred
			if(isset($return['response']->Code)){
				$return['response'] = $return['response']->Message;
			//Everything is good, return the clients
			}else{
				$return['success'] = true;
				$return['clients'] = $return['response'];
				$return['response'] = 'API key is good. Clients found: '.count($return['response']);
			}
			break;
			
			
		//prepare download because it's not possible to start download via ajax
		case 'download':
		
			//write file in the temp directory
			if(write('temp/'.$filename,stripslashes($_POST['data']))){
				$return['success'] = true;
				$return['response'] = 'Downloading...Please allow Popup blocker or click <a href="proxy.php?download='.$filename.'">here</a>!';
				$return['file'] = $filename;
			//shouldn't happen
			}else{
				$return['response'] = 'Couldn\'t save HTML!';
			}
			break;
			
			
		//Upload to Mailchimp
		case 'upload_mc':
		
				//save subdomain
				$dc = substr($_POST['apikey'],strrpos($_POST['apikey'],'-')+1);
		
				//Try to upload to Mailchimnp
				$return['response'] = mcRequest($_POST['apikey'],'templateAdd',array('name' => $_POST['templatename'], 'html' => stripslashes($_POST['data'])));
				
				//Oooohh, an error occurs!
				if(isset($return['response']->code)){
					
					//This is the "Template name does exist error - we can handle that
					if($return['response']->code == 506){
						
						//get all templates ever made
						$usertemplates = mcRequest($_POST['apikey'], 'templates', array('inactives' => array('include' => true)));
						foreach($usertemplates->user as $template){
							
							//this is our template which isn't unique
							if(strtolower($template->name) == strtolower($_POST['templatename'])){
								
								//try to update this template
								$return['response'] = mcRequest($_POST['apikey'],'templateUpdate',array('id' => $template->id, 'values' => array('name' => $_POST['templatename'], 'html' => stripslashes($_POST['data']))));
								
								//Update was good!
								if($return['response']){
									
									//if the Template is inactive (deleted) we have to reactivate it (undelete)
									if($template->active == 'N'){
										
										//Undelete it
										$return['response'] = mcRequest($_POST['apikey'],'templateUndel',array('id' => $template->id));
										
										//wohoo!
										if($return['response']){
											$return['success'] = true;
											$return['templateid'] = $template->id;
											$return['response'] = 'Template upload complete. <a href="https://'.$dc.'.admin.mailchimp.com/templates/edit?id='.$template->id.'">Edit it on mailchimp</a>';
										//error here
										}else{
											$return['code'] = $return['response']->code;
											$return['response'] = $return['response']->error;
										}
									//wohoo!
									}else{
										$return['success'] = true;
										$return['templateid'] = $template->id;
										$return['response'] = 'Template upload complete. <a href="https://'.$dc.'.admin.mailchimp.com/templates/edit?id='.$template->id.'">Edit it on mailchimp</a>';
									}
								//error here
								}else{
									$return['code'] = $return['response']->code;
									$return['response'] = $return['response']->error;
								}
							}
						}
					//error here
					}else{
						$return['code'] = $return['response']->code;
						$return['response'] = $return['response']->error;
				}
				
				//upload successfull!
				}else{
					$return['success'] = true;
					$return['templateid'] = $return['response'];
					$return['response'] = 'Template upload complete. <a href="https://'.$dc.'.admin.mailchimp.com/templates/edit?id='.$return['templateid'].'">Edit it on mailchimp</a>';
				}
			break;
			
			
		//Upload to Campaignmonitor
		case 'upload_cm':
		
			//CM needs needs a data on the Server to fetch HTML
			if(write('temp/'.$filename,stripslashes($_POST['data']))){
				$template = '{"Name":"'.$_POST['templatename'].'","HtmlPageURL":"'.getURL().'/temp/'.$filename.'","ZipFileURL":null}';
				
				//Create the template, same name is allowed!
				$return['response'] = cmRequest($_POST['apikey'],'http://api.createsend.com/api/v3/templates/'.$_POST['clientid'].'.json',$template);
				
				//We get an error
				if(isset($return['response']->Code)){
					$return['code'] = $return['response']->Code;
					$return['response'] = $return['response']->Message;
				
				//Template upload complete!
				}else{
					$return['success'] = true;
					$return['templateid'] = $return['response'];
					$return['response'] = 'Template upload complete. Template ID: <a href="https://login.createsend.com/loginForm.aspx">'.$return['response'].'</a>';
				}
			
			//should never happen
			}else{
				$return['response'] = 'Couldn\'t save temporary template file';
			}
			break;
			
			
		//If you didn't upload the template files correctly you get this demo from my server
		case 'examplehtml':
		
				$html = request('http://revaxarts-themes.com/newsletter.html');
				//replace colors
				$html = str_replace('bada55',$_GET['color'],$html);
				echo $html;
				exit;
				
			break;
			
		default:	
	}
	echo json_encode($return);
	exit;
	
	
	
	//prepare request for Mailchimp
	function mcRequest($apikey, $method, $data = NULL){
		
		$dc = substr($apikey,strrpos($apikey,'-')+1);
		//building the url
		$url = 'http://'.$dc.'.api.mailchimp.com/1.3/?apikey='.$apikey.'&method='.$method.'&rand='.time();
		if(isset($data)){
			foreach($data as $k => $v){
				//html is to long so we don't add it to the url
				if($k != 'html'){
					if(is_array($v)){
						foreach($v as $k2 => $v2){
							if($k2 != 'html'){
								$url .= '&'.$k.'['.$k2.']='.urlencode($v2).'';
							}else{
								$data['html'] = $v2;	
							}
						}
					}else{
						$url .= '&'.$k.'='.urlencode($v);	
					}
					unset($data[$k]);
				}
			}
			//special hack for templateUpdate
			if($method == 'templateUpdate'){
				$data = 'values[html]='.urlencode($data['html']);
			}
		}
		$request = request($url, $data);
		return json_decode($request);
	}
	
	//prepare request for Campaign Monitor
	function cmRequest($apikey, $url, $data = NULL){
		
		$request = request($url, $data, $apikey);
		return json_decode($request);
	}
	
	
	//the request function
	function request($url, $data = NULL, $username = NULL, $pwd = ''){
		$ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		if($data){
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}else{
			curl_setopt($ch, CURLOPT_POST, false);
		}
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($username){
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, $username.':'.$pwd);
		}
		
		$return = curl_exec($ch);
		curl_close($ch);
		return $return;
	}
	
	//checks is the folder is writeable
	function isWriteAble($folder = '', $filename = 'test.txt'){
		$file = $folder.$filename;
		write($file,'writetest');
		if(read($file) == 'writetest'){
			return delete($file);
		}
		return false;
	}
	
	//deletes a file
	function delete($file){
		if(file_exists($file)){
			unlink($file);
		}
		return !file_exists($file);
	}
	
	//write a file
	function write($filename, $data = ''){
		if (!$handle = fopen($filename, "w+")) {
			return false;
			exit;
		}
		if (!fwrite($handle, $data)) {
			return false;
			exit;
		}
	
		fclose($handle);
		return true;
	}
	
	//read a file
	function read($filename){
		if (!$handle = fopen($filename, "r")) {
			return false;
			exit;
		}
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		return $contents;
	}
	
	//get the URL from the location bar
	function getURL(){
		//return 'http://revaxarts.com/asdasd';
		return dirname((!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] : "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	}
	
	
	//get the URL from the location bar
	function check(){
		$goodnews = array();
		$badnews = array();
		
		if (version_compare(PHP_VERSION, '5.2.0') >= 0) {
			$goodnews[] = 'Your PHP Version is '.PHP_VERSION.' which is good';
		}else{
			$badnews[] = 'Your PHP Version is '.PHP_VERSION.' which is to low (required 5.2+)';
		}
		
		if(function_exists('curl_init')){
			$goodnews[] = 'You have the CURL Extension which is good';	
		}else{
			$badnews[] = 'You don\'t have the CURL Extension which is bad';
		}
		
		if(isWriteAble('temp/')){
			$goodnews[] = 'The temporary folder is writeable';	
		}else{
			$badnews[] = 'The temporary folder is not writeable';
		}
		
		if(empty($badnews)){
			$msg = "Everything should work as expected!";	
		}else{
			$msg = "Sorry, you have to update your Server!";	
		}
?>
<!doctype html>  
<html lang="en-us"><!--<![endif]-->
<head>
	<meta charset="utf-8">
	
	<title>Server Check for the Template Builder</title>
	
	<meta name="author" content="revaxarts.com">
	<meta name="robots" content="noindex, nofollow, nocache">
	
	<link rel="stylesheet" href="_css/style.css">
<style>
html{
	background-color:#f4f4f4;
}
body{
	padding:20px;
	margin:50px auto;
	width:600px;
	background: #FFFFFF;
	border: 1px solid #D9D9D7; 
	border-radius:3px; 
	-webkit-box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1), 0 0 0 3px #F9F9F9 inset; 
	-moz-box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1), 0 0 0 3px #F9F9F9 inset;
	box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1), 0 0 0 3px #F9F9F9 inset;
	font-size:101%;
	font-family: 'Varela Round', sans-serif;
	font-size:12px;
	-webkit-text-size-adjust:none;
}
h1{
	font-size:22px;
	width:auto;
}
.bad li, .good li{
	line-height:1.8em;
	list-style-type:square;
	margin-left:20px;
}
.bad li{
	color:#900;
}
.good li{
	color:#090;
}
footer{
	height:auto;
}
</style>
</head>
<body>
<h1><?php echo $msg?></h1> 
<hr>

<?php
if(!empty($goodnews)){
	echo '<ul class="good">';	
	foreach($goodnews as $news){
		echo '<li>'.$news.'</li>';	
	}
	echo '</ul>';	
}
?>
<?php
if(!empty($badnews)){
	echo '<ul class="bad">';	
	foreach($badnews as $news){
		echo '<li>'.$news.'</li>';	
	}
	echo '</ul>';	
}
?>
<hr>
<footer>Template Builder &copy; revaxarts.com</footer>
</body>
</html>

<?php 
		
		

	}
	
?>