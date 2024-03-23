<?php

require_once 'vendor/autoload.php';
session_start();

// Set up the Google Client
$client = new Google_Client();
$time = new \Google\Service\Calendar\TimePeriod();

$client->setAuthConfig('config.json');
$client->addScope(Google_Service_Calendar::CALENDAR);
$client->setAccessToken($_SESSION['token']);

// Create Calendar service
$service = new Google_Service_Calendar($client);

// Function to validate email address
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Process form data
$summary = $_POST['summary'];
$description = $_POST['description'];
$start = $_POST['start'];
$end = $_POST['end'];
$attendees = explode(',', $_POST['attendees']);

$validAttendees = [];
foreach ($attendees as $email) {
    if (isValidEmail(trim($email))) {
        $validAttendees[] = trim($email);
    }
}

if (empty($validAttendees)) {
    echo json_encode(['code' => 422, 'message' => 'No valid email addresses provided for attendees.']);
    exit;
}
$startDateTime = new DateTime($start);
$endDateTime = new DateTime($end);



if($startDateTime > $endDateTime) {
    echo json_encode(['code' => 422, 'message' => 'End time must be greater than start time.']);
    exit;
}

$event = new Google_Service_Calendar_Event([
    'summary' => $summary,
    'description' => $description,
    'start' => [
        'dateTime' => $startDateTime->format('c'),
        'timeZone' => 'Asia/Kathmandu',
    ],
    'end' => [
        'dateTime' => $endDateTime->format('c'),
        'timeZone' => 'Asia/Kathmandu',
    ],
    'attendees' => []
]);

foreach ($validAttendees as $email) {
    $attendee = new Google_Service_Calendar_EventAttendee([
        'email' => $email,
    ]);
    $event->attendees[] = $attendee;
}

$calendarId = 'primary';
try {
    $event = $service->events->insert($calendarId, $event);
    echo json_encode(['code' => 200, 'message' => 'Event created successfully']);
} catch (Exception $e) {
    echo json_encode(['code' => 400, 'message' => 'Error creating event: ' . $e->getMessage()]);
}
?>
