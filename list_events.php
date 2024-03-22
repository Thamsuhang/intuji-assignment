<?php
require_once 'vendor/autoload.php'; // Include the Google API Client Library
session_start();
if (isset($_SESSION['token']) && !empty($_SESSION['token'])) {
    $client = new Google_Client();
    try {
        $client->setAuthConfig('config.json');
        $client->addScope(Google_Service_Calendar::CALENDAR);
        $client->setAccessToken($_SESSION['token']); // Use the stored access token
        $service = new Google_Service_Calendar($client);
        $events = $service->events->listEvents('primary');
    } catch (\Google\Exception $e) {
        if ($e->getCode() == 401) {
            session_destroy();
        }
        $e->getMessage();
        die();
    }
}


?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

</head>
<body>
<section class="event-section">
    <h1>Your Events</h1>
    <div class="event-container">
        <?php if (!empty($events)): ?>
            <?php foreach (array_reverse($events->getItems()) as $event): ?>
                <div class="event-card" id="etc-<?=$event->getId()?>">
                    <div class="event-title-container" >
                        <h3><?= $event->getSummary() ?></h3>
                        <button class="delete-button" id="<?= $event->getId() ?>">Delete</button>
                    </div>

                    <p><strong>Start:</strong> <?= date("F jS, Y h:i:a", strtotime($event->start->dateTime)); ?></p>
                    <p><strong>Duration:</strong> <?= date("F jS, Y h:i:a", strtotime($event->end->dateTime)); ?></p>
                    <p><strong>Description:</strong></p>
                    <p>
                        <?= $event->getDescription() ?>
                    </p>
                    <p><strong>Attendees: </strong></p>
                    <?php foreach ($event->getAttendees() as $key => $attendee) {
                        echo $attendee->email . ($key == count($event->getAttendees()) - 1 ? '' : ', ');
                    } ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
        <p>Sorry You do not have any events.</p>
        <?php endif; ?>
    </div>
</section>


<script src="custom.js"></script>
</body>
</html>
