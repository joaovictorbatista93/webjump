<?php

	namespace Domain\Helper;

	use \DateTime;

	class Util {

		public function validateDate($date, $format = 'd/m/Y')
		{
		    $d = DateTime::createFromFormat($format, $date);

		    if($d && $d->format($format) === $date) {
		    	$date = strtr($date, '/', '-');
		    	return date('Y-m-d H:i:s', strtotime($date));
		    }

		    return $date;
		}

		public function convertClassName($className)
		{
        	$className = ucfirst($className);
        	$str = '';
        	$newStr = '';

			if (preg_match("/_/", $className)) {
				for ($i=0; $i < strlen($className); $i++) {
					if (substr($className, $i, 1) == "_") {
						$first = ucfirst(substr($className, 0, $i));
						$str .= substr($className, $i+1, strlen($className) - $i);
						$newStr .= $first . ucfirst($str);

						$className = str_replace($className, $newStr , $className);
					}
				}
			}

			return $className;
		}
	}
?>
