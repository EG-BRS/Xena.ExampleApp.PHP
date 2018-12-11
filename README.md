# Xena.ExampleApp.PHP

Example implementation of OAuth for Xena

Read more here:
https://dev.xena.biz/

Tested on PHP 5.6.35 and 7.1.16

# Notes on setting up the sample project

- Create the app in Xena's app store and create a client in the developer console. Remember to set granttype to Hybrid!
- Also remember to specify the redirect URL on your app in xena and make it sure it matches the path in common.php exactly!
- Create a secret in the developer console and add it along with the clientid to common.php