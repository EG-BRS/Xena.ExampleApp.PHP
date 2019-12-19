<?php
	require('XenaClient.php');
    /* Include setup */
    require('common.php'); /* !REMEMBER TO EDIT THIS FILE ACCORDING TO YOUR SETUP! */

    /* Initialize xenaclient */
    $xenaclient = new XenaOAuth2Client(CLIENT_ID, CLIENT_SECRET);
    
    /* Do we have a authorization cookie? */
    if(!isset($_COOKIE[COOKIE_XENATOKEN])){
        /* Returning from authentication and authorization? */
        if (!isset($_GET['code']) && !isset($_GET['error']))
        {
			
            /* ...no, then authenticate */
            $auth_url = $xenaclient->getAuthenticationUrl(REDIRECT_URI);
            header('Location: ' . $auth_url);
            die('Redirect');

        }
        elseif (!isset($_GET['error']))
        {
            /* ...yes, then authorize and get a access token */
            $accessTokenParameters = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
			
            $tokenResponse = $xenaclient->getAccessToken($accessTokenParameters);
			
            if($tokenResponse['result']){
                $tokenResult = $tokenResponse['result'];
                $token = $tokenResult['access_token'];

                /* You would properly persist the token to session/storage at this point and re-use it the at next page-refresh during client initialization... */
                $xenaclient->setAccessToken($token);
                setcookie(COOKIE_XENATOKEN,$token);

            }else{
                /* We didn't get a proper token result */
                var_dump($tokenResponse);
            }
        }
        else
        {
            /* Something bad happend... or the user declined... */
            die("Error: " . $_GET['error'] .".");
        }    
    }else{
        $xenaclient->setAccessToken($_COOKIE[COOKIE_XENATOKEN]);
    }
?>
<html>
    <head>
        <link href="https://my.xena.biz/Content/css/xena-plugin.css" rel="stylesheet" />
        <script src="https://my.xena.biz/scripts/xena.plugin.js"></script>
    </head>
    <body>
		<strong>Your access_token payload:</strong>
		</br>
		<?php 
		$jwt_access_token = $_COOKIE["XenaPHPDemo_XenaToken"];

		$separator = '.';

		if (2 !== substr_count($jwt_access_token, $separator)) {
			throw new Exception("Incorrect access token format");
		}

		list($header, $payload, $signature) = explode($separator, $jwt_access_token);
		
		// output the JWT Access Token payload
		var_dump(base64_decode($payload));
		?>
		</br>
		</br>
        Your fiscalsetups:
        <ul>
        <?php
            $fiscalsetuplist = $xenaclient->fetch('https://test.xena.biz/Api/User/FiscalSetup','PageSize=100');
             foreach($fiscalsetuplist['result']['Entities'] as $fiscalsetup){
                 $name = $fiscalsetup['Address']['Name'];
                 echo "<li>$name</li>";
             }
        ?>
        </ul>
    </body>
</html>


