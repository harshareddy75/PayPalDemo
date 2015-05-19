<?php
	require 'config.php';
	

	// Used to set the common parameters as a name value pair in an array
	function setCommonParameters($method)
	{
		global $VERSION, $USER, $PWD, $SIGNATURE;
		$parameters = array(
			'USER' => $USER,
			'PWD' => $PWD,
			'SIGNATURE' => $SIGNATURE,

			'METHOD' => $method,
			'VERSION' => $VERSION);
		return $parameters;
	}

	// Set up the curl options and post parameters, execute the curl call, get the return and save them in an array as a name value pair
	function executeFunction($parameters)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
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

	// If there is an error, print the array with the return values so its easier to debug
        function doErrorPrint($returnVal)
	{
		echo "There has been an error. Please check the return values<br>";
               	foreach ($returnVal as $key => $value)
     	        {
	               echo "Key: $key; Value: $value<br>";
                }
	}

?>
