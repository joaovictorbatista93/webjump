<?php

	spl_autoload_register(function($nameClass) {
		$path = '';
		$fixo = "/var/www/html/webjump/";
		$caminho  = str_replace('\\', DIRECTORY_SEPARATOR, $nameClass . ".php");
		$fileName = $fixo . $caminho;
		if ($nameClass == "Connection") {
			require_once("/var/www/html/webjump/Infra/Connection/Connection.php");
		}
		else {
		    require_once($fileName);
		}
	});
?>
