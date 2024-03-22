<?php
require_once 'vendor/autoload.php'; // Include the Google API Client Library
session_start();

// Set up the Google Client
$client = new Google_Client();
$client->setAuthConfig('config.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessToken($_SESSION['token']); // Use the stored access token

try {
// Create Calendar service
    $service = new Google_Service_Calendar($client);

// Get event ID from the request
    $eventId = $_POST['event_id']; // Assuming the event ID is passed via POST parameter

    if (!empty($eventId)) {
// Delete event
        $calendarId = 'primary';

        $service->events->delete($calendarId, $eventId);

// Respond with success message
        echo json_encode(['code' => 200, 'message' => "Event deleted successfully."]);
    } else {
        echo json_decode(['code' => 422, 'message' => "Missing or incorrect event Id."]);
    }
} catch (\Google\Service\Exception $e) {
    echo json_decode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
}