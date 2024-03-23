<?php
require_once 'vendor/autoload.php'; // Include the Google API Client Library
session_start();

if (isset($_GET['code'])) {
    // Add values to the session.
    if (!isset($_SESSION['token'])) {
        $client = new Google_Client();
        $client->setAuthConfig('config.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $config = file_get_contents('config.json');
        $details = json_decode($config);
        $client->setRedirectUri($details->base_url . 'index.php');
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

        // Start new or resume existing session.
        $_SESSION['token'] = $token["access_token"]; // string

        header('Location: ' . filter_var($config->base_url . 'list_events.php', FILTER_SANITIZE_URL));
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar Integration</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Google Calendar Integration</h1>
<div class="index-container">

    <?php if (isset($_SESSION['token']) && !empty($_SESSION['token'])): ?>
        <a class="a-button" href="list_events.php">List Events</a>
        <a class="a-button red" href="disconnect.php">Disconnect</a>
    <?php else: ?>
        <a class="a-button" href="auth.php">Connect to Google Calendar</a>
    <?php endif; ?>
</div>
</body>
</html>
