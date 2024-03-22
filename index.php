<?php
require_once 'vendor/autoload.php'; // Include the Google API Client Library
if (isset($_GET['code'])) {
    session_start();
    // Add values to the session.
  
    if(!isset($_SESSION['token']) && empty($_SESSION['token'])) {
        $client = new Google_Client();
        $client->setAuthConfig('config.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $config = file_get_contents('config.json');
        $details = json_decode($config);
        $client->setRedirectUri($details->base_url.'index.php');
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Start new or resume existing session.
        $_SESSION['token'] = $token["access_token"]; // string
     
        header('Location: ' . filter_var($config->base_url.'list_events.php', FILTER_SANITIZE_URL));
    }


}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar Integration</title>
</head>
<body>
<h1>Google Calendar Integration</h1>
<a href="auth.php">Connect to Google Calendar</a>
</body>
</html>
