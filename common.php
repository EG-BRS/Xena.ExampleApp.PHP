<?php
    /* Cookie constants */
    const COOKIE_XENATOKEN = 'XenaPHPDemo_XenaToken';
	const COOKIE_TOKENS = 'XenaPHPDemo_Tokens';

	/* https://dev.xena.biz/xena-developer/development/get-started/installxenadeveloper */
    /* Declare project constants - CHANGE THESE ACCORDING TO YOUR APP! */
    const CLIENT_ID     = 'YOUR CLIENT ID'; //You can check connection with our test client: '9213946e-e688-4aab-8fc8-3d914a2ac275.xena.biz';
    const CLIENT_SECRET = 'YOUR CLIENT SECRET'; // 'Secret';
    const REDIRECT_URI  = 'http://localhost/callback_xena.php';
	
	/**
    * URL's
    * Can be found via https://logintest.xena.biz/.well-known/openid-configuration
	* These are set for test server. 
	* Change to https://login.xena.biz/ for production
    */
    const AUTHORIZATION_ENDPOINT = 'https://logintest.xena.biz/connect/authorize';
    const TOKEN_ENDPOINT         = 'https://logintest.xena.biz/connect/token';
?>