<?php
	require 'commonFunctions.php';
	$postVar = $_POST["item"];
	if ($postVar == "router" || $postVar == "internet")
	{
		$method = "SetExpressCheckout";
		$parameters = setCommonParameters($method);
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

			$returnURL = 'https://ec2-52-25-14-252.us-west-2.compute.amazonaws.com/confirm.php?item=' . $postVar;
                        $parameters['RETURNURL'] = $returnURL;

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

                        $returnURL = 'https://ec2-52-25-14-252.us-west-2.compute.amazonaws.com/confirm.php?item=' . $postVar;
                        $parameters['RETURNURL'] = $returnURL;

		}
		$parameters['PAYMENTREQUEST_0_CURRENCYCODE'] = SGD;
		$parameters['PAYMENTREQUEST_0_PAYMENTACTION'] = 'Sale';
		$parameters['CANCELURL'] = 'https://ec2-52-25-14-252.us-west-2.compute.amazonaws.com/index.html';

		$returnVal = executeFunction($parameters);

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
