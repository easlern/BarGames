<?php

/**
 * Example of retrieving an authentication token of the Facebook service
 *
 * PHP version 5.4
 *
 * @author     Benjamin Bender <bb@codepoet.de>
 * @author     David Desberg <david@daviddesberg.com>
 * @author     Pieter Hordijk <info@pieterhordijk.com>
 * @copyright  Copyright (c) 2012 The authors
 * @license    http://www.opensource.org/licenses/mit-license.html  MIT License
 */

use OAuth\OAuth2\Service\Facebook;
use OAuth\Common\Storage\Session;
use OAuth\Common\Consumer\Credentials;

require_once('code/startup.php');

/**
 * Bootstrap the example
 */
require_once ('setupOAuth.php');

// Session storage
$storage = new Session();

// Setup the credentials for the requests
$credentials = new Credentials(
    $servicesCredentials['facebook']['key'],
    $servicesCredentials['facebook']['secret'],
    $currentUri->getAbsoluteUri()
);

// Instantiate the Facebook service using the credentials, http client and storage mechanism for the token
/** @var $facebookService Facebook */
$facebookService = $serviceFactory->createService('facebook', $credentials, $storage, array());

if (!empty($_GET['code'])) {
    try{
        // This was a callback request from facebook, get the token
        $token = $facebookService->requestAccessToken($_GET['code']);

        // Send a request with it
        $result = json_decode($facebookService->request('/me'), true);

        // Redirect to the authentication success URL
        header ('Location: ' . $_SESSION['FACEBOOK_AUTHENTICATION_SUCCESSFUL_RETURN_URL']);
    }
    catch (Exception $e){
        echo 'Oops! ' . $e;
    }

} elseif (!empty($_GET['returnUrl'])) {
    $_SESSION['FACEBOOK_AUTHENTICATION_SUCCESSFUL_RETURN_URL'] = SanitizePlainText($_GET['returnUrl']);
    $url = $facebookService->getAuthorizationUri();
    header('Location: ' . $url);
} else {
    include ('authorize.html');
}

?>