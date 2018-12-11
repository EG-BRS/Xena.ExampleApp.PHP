<?php
    /* Include setup */
    include('common.php'); /* !REMEMBER TO EDIT THIS FILE ACCORDING TO YOUR SETUP! */

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

            /* You would properly persist the token to session/storage at this point and re-use it the at next page-refresh during client initialization... */
            $xenaclient->setAccessToken($token);
            setcookie('XenaPHPDemo_XenaToken',$token);

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