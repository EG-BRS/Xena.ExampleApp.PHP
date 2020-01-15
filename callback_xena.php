<?php
	require('XenaClient.php');
    /* Include setup */
    require('common.php'); /* !REMEMBER TO EDIT THIS FILE ACCORDING TO YOUR SETUP! */

    /* Initialize xenaclient */
    $xenaclient = new XenaOAuth2Client(CLIENT_ID, CLIENT_SECRET);
	
    /* Returning from authentication and authorization? */
    if (!isset($_POST['code']) && !isset($_POST['error']))
    {
        /* ...no, then authenticate */
        $auth_url = $xenaclient->getAuthenticationUrl(REDIRECT_URI);
        header('Location: ' . $auth_url);
        die('Dont use this directly');
    }
    elseif (!isset($_POST['error']))
    {
        $accessTokenParameters = array('code' => $_POST['code'], 'redirect_uri' => REDIRECT_URI);
        $tokenResponse = $xenaclient->getAccessToken($accessTokenParameters);

        if($tokenResponse['result']){
            $tokenResult = $tokenResponse['result'];
            $token = $tokenResult['access_token'];
			$refresh_token = $tokenResult['refresh_token'];
            $id_token = $tokenResult['id_token'];

            /* You would properly persist the encoded token to session/storage at this point and re-use it the at next page-refresh during client initialization... */
            $xenaclient->setAccessToken($token);
			
			/* You should encode the tokens so only the server can use them */
            setcookie(COOKIE_XENA_REFRESH_TOKEN, $refresh_token);
			setcookie(COOKIE_XENA_ACCESS_TOKEN, $token);
			setcookie(COOKIE_XENA_IDTOKEN, $id_token);
			
			// Gets user data from /connect/userinfo
			$userInfoEndpoint = $xenaclient->getUserInfo($token);
			$firstArrayElement = array_values($userInfoEndpoint)[0];
			// Join Params
			array_walk($firstArrayElement, create_function('&$i,$k','$i=" $k=\"$i\"";'));
			$userInfo = implode($firstArrayElement,"");
			setcookie(COOKIE_XENA_USERINFO, $userInfo);

            /* Go back! */
            header('Location: index.php');
            die('Redirect');

        }else
         {
            /* We didn't get a proper token result */
            var_dump($tokenResponse);
         }
    }
    else
    {
        /* Something bad happend... or the user declined... */
        die("Error: " . $_GET['error'] .".");
    }
?>