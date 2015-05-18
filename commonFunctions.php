<?php
	require 'config.php';
	
	function setCommonParameters($method)
	{
		global $VERSION, $USER, $PWD, $SIGNATURE;
//		echo "zxcv";
		$parameters = array(
			'USER' => $USER,
			'PWD' => $PWD,
			'SIGNATURE' => $SIGNATURE,

			'METHOD' => $method,
			'VERSION' => $VERSION);
		return $parameters;
	}

	function executeFunction($parameters)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_URL, 'https://api-3t.sandbox.paypal.com/nvp');
		curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
		
		$response = curl_exec($curl);
		curl_close($curl);
		
		$returnValues = array();
		if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches))
		{
			foreach ($matches['name'] as $offset => $name)
			{
				$returnValues[$name] = urldecode($matches['value'][$offset]);
    			}
		}
		return $returnValues;
	}

        function doErrorPrint($returnVal)
	{
		echo "There has been an error. Please check the return values<br>";
               	foreach ($returnVal as $key => $value)
     	        {
	               echo "Key: $key; Value: $value<br>";
                }
	}

?>
