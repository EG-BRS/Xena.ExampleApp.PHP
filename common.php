<?php
    /* Import client libraries */
    require('XenaClient.php');

    /* Cookie constants */
    const COOKIE_XENATOKEN = 'XenaPHPDemo_XenaToken';

    /* Declare project constants - CHANGE THESE ACCORDING TO YOUR APP! */
    const CLIENT_ID     = 'testclient';
    const CLIENT_SECRET = 'secret';     /* This should be kept secret;-) */
    const REDIRECT_URI  = 'http://localhost/Xena.ExampleApp/callback_xena.php';
?>