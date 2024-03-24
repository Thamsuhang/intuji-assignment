<?php
require_once 'vendor/autoload.php';
session_start();

$client = new Google_Client();
$client->setAuthConfig('config.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessToken($_SESSION['token']); // Use the stored access token

try {
    $service = new Google_Service_Calendar($client);

    $eventId = $_POST['event_id'];

    if (!empty($eventId)) {
        $calendarId = 'primary';

        $service->events->delete($calendarId, $eventId);

        echo json_encode(['code' => 200, 'message' => "Event deleted successfully."]);
    } else {
        echo json_decode(['code' => 422, 'message' => "Missing or incorrect event Id."]);
    }
} catch (\Google\Service\Exception $e) {
    echo json_decode(['code' => $e->getCode(), 'message' => $e->getMessage()]);
}