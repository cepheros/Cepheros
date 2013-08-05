<?php
class Functions_Messages{
	
	public static function renderAlert($message,$type = null){
		switch($type){
			case 'erro':
				$typemessage = 'alert-error';
			break;
			case 'info':
				$typemessage = 'alert-info';
			break;
			case 'sucesso':
				$typemessage = 'alert-success';
			break;
			break;
			case 'alerta':
				$typemessage = '';
			break;
		    default:
				$typemessage = '';
			break;
					
		}
		
		$message = "<div class=\"alert $typemessage\">
								<a class=\"close\" data-dismiss=\"alert\">x</a>
								$message
							</div>
						";
		
		return $message;
	}
	
	
}