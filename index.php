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
		<strong>Your id_token payload:</strong>
		</br>
		<?php 
		$jwt_id_token = $_COOKIE["IdTokenCookie"];

		$separator = '.';

		if (2 !== substr_count($jwt_id_token, $separator)) {
			throw new Exception("Incorrect id token format");
		}

		list($header, $payload, $signature) = explode($separator, $jwt_id_token);
		
		// output the JWT Access Token payload
		var_dump(base64_decode($payload));
		?>
		</br>
		<strong>Your user info :</strong>
		</br>
		<?php 
		$userInfoCookieData = $_COOKIE["UserInfoEndpointCookie"];

		// output the data
		var_dump($userInfoCookieData);
		?>
		</br>
		</br>
        <p>Fiscal setups for logged user: 
		<?php 
			$cookieArray = explode(" ", $_COOKIE["UserInfoEndpointCookie"]);
			foreach($cookieArray as $cookieValue){
				if (strpos($cookieValue, 'preferred_username') !== false) {
					echo "<strong>" . explode("=", $cookieValue)[1] . "</strong>";
				}	
			}
		?>
		</p>
        <ul>
        <?php
            $fiscalsetuplist = $xenaclient->fetch('https://test.xena.biz/Api/User/FiscalSetup','PageSize=100');
			 echo "Number of fiscals: <strong>{$fiscalsetuplist['result']['Count']}</strong>";
             foreach($fiscalsetuplist['result']['Entities'] as $fiscalsetup){
                 $name = $fiscalsetup['Address']['Name'];
                 $id = $fiscalsetup['Id'];
                 echo "<li>Id: $id Fiscal name: $name</li>";
				 echo "Full response: ";
				 echo "<code>";
				 echo print_r($fiscalsetup);
				 echo "</code>";
             }
        ?>
        </ul>
		<br>
		<p>Learn more about <strong><a href="https://xena.biz/api-doc/general/#/">Xena API</a></strong> at <strong><a href="https://dev.xena.biz">https://dev.xena.biz</a></strong></p>
    </body>
</html>