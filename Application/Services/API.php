<?php 

	namespace Application\Services;

	class API {

		public static function getCep($cep) {

			$curl = curl_init();

			curl_setopt_array($curl, [
				CURLOPT_URL => "https://viacep.com.br/ws/$cep/json/",
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_ENCODING => "",
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 30,
				CURLOPT_SSL_VERIFYPEER => 0,
				CURLOPT_SSL_VERIFYHOST => 0,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => "GET"
			]);

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if ($err) {
				return "cURL Error #:" . $err;
			} else {				
				return json_decode($response);
			}
		}
	}

?>