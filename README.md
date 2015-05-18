# PayPalDemo
A simple use of Paypal's express checkout api

This simple consists allows a user to either pay for a router or sign up for a recurring bill for a period of 12 months. At this moment the user can only start the recurring bill but cannot edit or delete it afterwards. The items have a fixed cost.

This website consists of 6 files

  index.html - the landing page of the site. Allows the user to choose what he wants to pay for. 
  buy.php - it gets the item the user wants to purchase and executes the SetExpressCheckout call
  confirm.php - paypal redirects to this page after the SetExpressCheckout method. It executes the GetExpressCheckoutDetails method and provides a summary for the user. The user can confirm to pay or cancel the transaction.
  finish_trasaction.php - Responsible for executing the functions DoExpressCheckoutPayment or CreateRecurringPaymentsProfile (depending on the use case). 
  config.php - common config variables
  commonFunctions.php - a set of useful helper functions
