<?php
	// This page executes the last set of functions needed for the transaction to be finished

	// Get the session variables
	session_start();
	
	require 'commonFunctions.php';
	
	if ($_SESSION['TOKEN'] != "")
	{
		$method = "DoExpressCheckoutPayment";

		// Set up common parameters like user, pwd etc
		$parameters = setCommonParameters($method);
	
		$parameters['TOKEN'] = $_SESSION['TOKEN'];
	
		// Set unique parameters for each transaction
		if ($_SESSION['item'] == "router")
		{
                        $parameters['PAYERID'] = $_SESSION['PAYERID'];
                        $parameters['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
                        $parameters['PAYMENTREQUEST_0_AMT'] = $_SESSION['PAYMENTREQUEST_0_AMT'];
                        $parameters['PAYMENTREQUEST_0_CURRENCYCODE'] = $_SESSION['PAYMENTREQUEST_0_CURRENCYCODE'];
                        $parameters['PAYMENTREQUEST_0_ITEMAMT'] = $_SESSION['PAYMENTREQUEST_0_ITEMAMT'];
                        $parameters['PAYMENTREQUEST_0_SHIPTONAME'] = $_SESSION['PAYMENTREQUEST_0_SHIPTONAME'];
                        $parameters['PAYMENTREQUEST_0_SHIPTOSTREET'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOSTREET'];
			if (isset($_SESSION['PAYMENTREQUEST_0_SHIPTOSTREET2']))
				$parameters['PAYMENTREQUEST_0_SHIPTOSTREET2'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOSTREET2'];
                        if (isset($_SESSION['PAYMENTREQUEST_0_SHIPTOCITY']))
	                        $parameters['PAYMENTREQUEST_0_SHIPTOCITY'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOCITY'];
                        if (isset($_SESSION['PAYMENTREQUEST_0_SHIPTOSTATE']))
                                $parameters['PAYMENTREQUEST_0_SHIPTOSTATE'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOSTATE'];
                        if (isset($_SESSION['PAYMENTREQUEST_0_SHIPTOZIP']))
                                $parameters['PAYMENTREQUEST_0_SHIPTOZIP'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOZIP'];
                        if (isset($_SESSION['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE']))
                                $parameters['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOCOUNTRYCODE'];
                        if (isset($_SESSION['PAYMENTREQUEST_0_SHIPTOPHONENUM']))
                                $parameters['PAYMENTREQUEST_0_SHIPTOPHONENUM'] = $_SESSION['PAYMENTREQUEST_0_SHIPTOPHONENUM'];
                        $parameters['L_PAYMENTREQUEST_0_NAME0'] = $_SESSION['L_PAYMENTREQUEST_0_NAME0'];
                        $parameters['L_PAYMENTREQUEST_0_DESC0'] = $_SESSION['L_PAYMENTREQUEST_0_DESC0'];
                        $parameters['L_PAYMENTREQUEST_0_QTY0'] = $_SESSION['L_PAYMENTREQUEST_0_QTY0'];
                        $parameters['L_PAYMENTREQUEST_0_AMT0'] = $_SESSION['L_PAYMENTREQUEST_0_AMT0'];

		}
		else
		{
			// Change the method as it is not a DoExpressCheckoutPayment call anymore but reuse the same array instead of creating a new one
			date_default_timezone_set('Asia/Singapore');
			$parameters['METHOD'] = 'CreateRecurringPaymentsProfile';
			$todaysDateArr = getdate();
			$todaysDate = $todaysDateArr['year']."-".$todaysDateArr['mon']."-".$todaysDateArr['mday']."T".$todaysDateArr['hours'].":".$todaysDateArr['minutes'].":".$todaysDateArr['seconds']."Z";
			$parameters['PROFILESTARTDATE'] = $todaysDate;
			$parameters['DESC'] = 'SGD 25 per month for 1 year for one year for internet subscription';
			$parameters['BILLINGPERIOD'] = 'Month';
			$parameters['BILLINGFREQUENCY'] = 1;
			$parameters['AMT'] = 25;
			$parameters['CURRENCYCODE'] = 'SGD';
			$parameters['EMAIL'] = $_SESSION['EMAIL'] ;
			 
		}

		// Execute the curl call and get the return parameters in an array as a name value pair
                $returnVal = executeFunction($parameters);
                if (isset($returnVal['ACK']) && $returnVal['ACK'] == 'Success')
                {
                        echo "Congratulations, the transaction was successful";
			if ($_SESSION['item'] == "internet")
			{
				echo "<br>The pofile id is ".$returnVal['PROFILEID']." and the status is ".$returnVal['PROFILESTATUS'];
			}
                }
                else
                {
                        doErrorPrint($returnVal);
                }

	}
	session_unset();
?>

