<?php
	session_unset();
	session_start();
	require 'commonFunctions.php';
        $token = "";
        if (isset($_REQUEST['token']))
        {
                $token = $_REQUEST['token'];
        }
	
        $getVar = $_GET["item"];
	
        if ($token != "" && ($getVar == "router" || $getVar == "internet"))
	{
		$_SESSION['item'] = $getVar;
                $method = "GetExpressCheckoutDetails";
		$parameters = setCommonParameters($method);
		$parameters['TOKEN'] = $token;
		
		$returnVal = executeFunction($parameters);
		
		if (isset($returnVal['ACK']) && $returnVal['ACK'] == 'Success')
		{
                        foreach ($returnVal as $key => $value)
                        {
				$_SESSION[$key] = $value;
//                                echo "Key: $key and Value: $value<br>";
                        }
?>		
<html>
<head>
        <title>Order Confirmation</title>
</head>
<body><h1>Internet Connections Ltd</h1>
        <p>
                Please review your order<br><br>Item purchased:
                <?php
                        if ($getVar == "router") { echo "Router";}
                        if ($getVar == "internet") { echo "12 Months of Internet Subscription";}
                ?>
                </br>
                Name: <?php echo $returnVal['SHIPTONAME'];?>
                </br>
                Email Id: <?php echo $returnVal['EMAIL'];?>
                </br>
                Amount: SGD <?php 
				if ($getVar == "router") 
					echo $returnVal['ITEMAMT']; 
				else echo "SGD 25 per month for 1 year";?>
                </br>
                <?php
                        if ($getVar == "router")
                        {
                                echo "Shipping Address:<br>" . $returnVal['SHIPTOSTREET'] . ",<br>" . $returnVal['SHIPTOCITY'] . ",<br>" . $returnVal['SHIPTOCOUNTRYNAME'] . " " . $returnVal['SHIPTOZIP'];
                        }
                ?>
        </p>
        <a href="https://ec2-52-25-14-252.us-west-2.compute.amazonaws.com"><img src="http://png-1.findicons.com/files/icons/114/puck/300/windows_close_program.png" width="60" height="60"></a>

        <a href="https://ec2-52-25-14-252.us-west-2.compute.amazonaws.com/finish_transaction.php"><img src="http://www.clker.com/cliparts/X/i/3/t/X/O/ok-button-md.png" width="60" height="60"></a>

        </body>
</head>


<?php

		}
		else
		{
			doErrorPrint($returnVal);
		}
	}
?>
