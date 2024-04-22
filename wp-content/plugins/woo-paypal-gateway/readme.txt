=== Payment Gateway for PayPal on WooCommerce ===
Contributors: easypayment
Tags: PayPal Express Checkout, PayPal Pro, Braintree, PayPal Checkout
Requires at least: 3.3
Tested up to: 6.4.2
Stable tag: 7.2.1
Requires PHP: 7.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html

Easily enable PayPal payment methods for WooCommerce. Develop by Official PayPal Partner.

== Description ==

Easily add PayPal payment options to your WordPress / WooCommerce website.

* PayPal Express Checkout / PayPal Credit
* PayPal Pro ((website payments pro) / PayPal Payments Pro (DoDirectPayment)
* Braintree Payments ( Braintree drop in ui with Card + PayPal + PayPal Credit )
* PayPal Payments Advanced
* PayPal Pro Payflow (PayPal Manager / PayFlow Gateway)
* PayPal Express Checkout ( PayPal Smart Buttons ) REST API
* Real Time Order Satus Update ( PayPal IPN )

= Next Milestone = 

* PayPal Plus (Germany, Brazil, Mexico)
* PayPal Reporting
* PayPal Balance ( PayPal Dashboard )
* PayPal Order History ( PayPal Transaction History)

== Installation ==

= Automatic installation =

Automatic installation is the easiest option as WordPress handles the file transfers itself and you don\’t need to leave your web browser. To do an automatic install, log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.
In the search field type \"Payment Gateway for PayPal on WooCommerce\" and click Search Plugins. Once you\’ve found our plugin you can view details about it such as the the rating and description. Most importantly, of course, you can install it by simply clicking Install Now.

= Manual Installation =

1. Unzip the files and upload the folder into your plugins folder (/wp-content/plugins/) overwriting older versions if they exist
2. Activate the plugin in your WordPress admin area.

= Usage = 

1. Open the settings page for WooCommerce and click the "Checkout" tab
2. Click on the sub-item for PayPal Express Checkout. 
3. Enter your API credentials and adjust any other settings to suit your needs. 


== Screenshots ==

== Frequently asked questions ==

= How do I create sandbox accounts for testing? =
* Login at http://developer.paypal.com.  
* Click the Applications tab in the top menu.
* Click Sandbox Accounts in the left sidebar menu.
* Click the Create Account button to create a new sandbox account.

= Where do I get my API credentials? =

* Live credentials can be obtained by signing in to your live PayPal account here:  https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run
* Sandbox credentials can be obtained by viewing the sandbox account profile within your PayPal developer account, or by signing in with a sandbox account here:  https://www.sandbox.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run

= How to get phone numbers for PayPal Checkout orders?=

If you use PayPal Checkout, then you might have noticed that the checkout page does not contain the customer's phone number. If you have a PayPal business account, then you can require customer phone numbers from your PayPal account settings.

PayPal has the ability to provide the customer’s phone number which can be used to autopopulate your checkout page. We recommend you enable this option as it reduces the number of checkout fields your customer has to manually enter. It reduces friction and will improve conversion rates.


== Changelog ==

= 7.2.1 =
* Fix - Resolved PHP notice and some other issue.

= 7.2.0 =
* Fix - Resolved PHP notice and some other issue.

= 7.1.8 =
* Fix - Resolved issue related to phone number.

= 7.1.7 =
* Verification - WooCoomerce 7.7 compatibility.

= 7.1.6 =
* Verification - WooCoomerce 6.8.2 compatibility.

= 7.1.5 =
* Fix - Resolved issue with guest checkout in order review page.
* Fix - Display PayPal validation messages on checkout page.

= 7.1.4 =

* Tested with WC 7.2.0 

= 7.1.3 = 
* Coupon disable when PayPal checkout enable.

= 7.1.2 = 
* Adds Gift Card plugin compatibility.

= 7.1.1 = 
* Fix - Resolved issue with checkout page. hide other payment method on review page.

= 7.1.0 =
* Adds major changes related to PayPal SDK and use Latest PayPal JS.
* improved performance. 

= 7.0.0 =
* Upgrade PayPal Checkout.

= 6.0.1 =
* Resolve rounding issue.
* Resolve PayPal Payflow CC exp_year issue.

= 6.0.0 =
* Verification - WooCoomerce 6.8.2 compatibility.

= 5.0.8 - 08/09/2022
* Verification - WooCoomerce 6.7.0 compatibility.

= 5.0.7 - 04/13/2022
* Verification - WooCoomerce 6.4.0 compatibility.

= 5.0.6 - 03/11/2022
* Resolved PHP notice.

= 5.0.5 - 02/25/2022
* Resolved Body class issue on checkout page.

= 5.0.4 - 02/23/2022
* Verification - WordPress 6.2.1 compatibility.

= 5.0.3 - 02/15/2022
* Resolved multiple PayPal display when update checkout fields.

= 5.0.2 - 02/01/2022
* Resolved PHP Notice.

= 4.0.9 - 14/12/2021
* Remove Trademark 

= 2.0.0 - 08/04/2019 = 
* Add new PayPal Express Checkout Smart button

= 1.0.0 - 12/08/2017 =
* Feature - PayPal Express Checkout

= 1.0.1 - 12/08/2017 =

* PayPal IPN bug resolved.

= 1.0.2 - 12/12/2017 = 

* Add Pre-Order support and Payment token.

= 1.0.3 - 13/12/2017 = 

* Add PayPal Pro payment method.

= 1.0.4 - 15/12/2017 = 

* Add braintree Payment.
* Add icons for all payment methods.

= 1.0.5 - 17/12/2017 = 

* Add PayPal Pro
* Add PayPal Advanced
* Add PayPal Payflow
* Add PayPal Rest

= 1.0.6 - 24-12-2017 =
* WPML compability

= 1.0.7 - 06-01-2018 =
* Code optimizing and better error handling


== Upgrade Notice ==

Add new PayPal checkout ( PayPal Smart Button ) Payment method https://developer.paypal.com/docs/checkout/