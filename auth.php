<?php
require_once 'vendor/autoload.php';

// Set up the Google Client
$client = new Google_Client();
try {
    $client->setAuthConfig('config.json');
    $client->addScope(Google_Service_Calendar::CALENDAR);
    $config = file_get_contents('config.json');
    $details = json_decode($config);
    $client->setRedirectUri($details->base_url.'index.php');
    $auth_url = $client->createAuthUrl();
    header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

} catch (\Google\Exception $e) {
    echo $e->getMessage();
}

