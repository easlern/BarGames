<?php

/**
 * Bootstrap the library
 */
require_once ('vendor/autoload.php');

/**
 * Setup error reporting
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Setup the timezone
 */
ini_set('date.timezone', 'Europe/Amsterdam');

/**
 * Create a new instance of the URI class with the current URI, stripping the query string
 */
$uriFactory = new \OAuth\Common\Http\Uri\UriFactory();
$currentUri = $uriFactory->createFromSuperGlobalArray($_SERVER);
$currentUri->setQuery('');

/**
 * Load the credential for the different services
 */
/**
 * @var array A list of all the credentials to be used by the different services in the examples
 */
$servicesCredentials = array(
    'facebook' => array(
        'key'       => '300645526781245',
        'secret'    => '326770d8a4e393ca4e7d553cb3917c50',
    )
);

/** @var $serviceFactory \OAuth\ServiceFactory An OAuth service factory. */
$serviceFactory = new \OAuth\ServiceFactory();
