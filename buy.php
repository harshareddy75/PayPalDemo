<?php
	// THis file mainly executes the SetExpressCheckout and passes on to the other files for the other tasks
	require 'commonFunctions.php';
	$postVar = $_POST["item"];
	if ($postVar == "router" || $postVar == "internet")
	{
		$method = "SetExpressCheckout";

		// Common properties like user name, password signature etc will be added in this function
		$parameters = setCommonParameters($method);

		// Add parameters which are different for both the cases
		if ($postVar == "router")
		{
			$parameters['NOSHIPPING'] = '2';
                        $parameters['PAYMENTREQUEST_0_AMT'] = 60;
                        $parameters['PAYMENTREQUEST_0_ITEMAMT'] = 60;

                        $parameters['L_PAYMENTREQUEST_0_NAME0'] = 'Router';
                        $parameters['L_PAYMENTREQUEST_0_DESC0'] = 'Router for home usage';
                        $parameters['L_PAYMENTREQUEST_0_QTY0'] = 1;
                        $parameters['L_PAYMENTREQUEST_0_AMT0'] = 60;

                        $parameters['L_BILLINGTYPE0'] = 'MerchantInitiatedBilling';
                        $parameters['L_BILLINGAGREEMENTDESCRIPTION0'] = 'Router bill';

		}
		else
		{
                        $parameters['NOSHIPPING'] = '1';
                        $parameters['PAYMENTREQUEST_0_AMT'] = 0;
                        $parameters['PAYMENTREQUEST_0_ITEMAMT'] = 0;

                        $parameters['L_PAYMENTREQUEST_0_NAME0'] = 'Subscription Charges';
                        $parameters['L_PAYMENTREQUEST_0_DESC0'] = 'Internet subscription charges at $25 a month for an year';
//                        $parameters['L_PAYMENTREQUEST_0_QTY0'] = 1;
//                        $parameters['L_PAYMENTREQUEST_0_AMT0'] = 25;

                        $parameters['L_BILLINGTYPE0'] = 'RecurringPayments';
                        $parameters['L_BILLINGAGREEMENTDESCRIPTION0'] = 'SGD 25 per month for 1 year for one year for internet subscription';
		}

		// Add the common parameters
                $returnURL = $HOME_URL . '/confirm.php?item=' . $postVar;
                $parameters['RETURNURL'] = $returnURL;

		$parameters['PAYMENTREQUEST_0_CURRENCYCODE'] = SGD;
		$parameters['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
		$parameters['CANCELURL'] = $HOME_URL . '/index.html';

		// Make the curl call and get the return as as a name value pair
		$returnVal = executeFunction($parameters);

		// If successful, redirect to PayPal site
		if (isset($returnVal['ACK']) && $returnVal['ACK'] == 'Success')
		{
			$query = array(
				'cmd' => '_express-checkout',
				'token' => $returnVal['TOKEN']
				);
			//echo "asd";
			$redirectURL = $SANDBOX_REDIRECT_URL . http_build_query($query);
//			echo $redirectURL;
			header('Location: ' . $redirectURL);

		}
		else
		{
			doErrorPrint($returnVal);
		}
	}
?>
