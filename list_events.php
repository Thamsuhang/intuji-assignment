<?php
require_once 'vendor/autoload.php';
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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap" rel="stylesheet">

    <script src="https://code.jquery.com/jquery-3.6.3.min.js"
            integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>


</head>
<body>
<div class="event-section-header">
    <div class="event-title-container">
        <h1>Your </h1>
        <img src="g_icon.svg" style="max-width: 50px">
        <h1>Events</h1>
    </div>
    <div class="event-title-container ">
        <a class="a-button" id="open-modal-btn">+</a>
        <a class="a-button red" href="disconnect.php">🛇</a>
    </div>

</div>

<section class="event-section">

    <div class="event-container">
        <?php if (!empty($events)): ?>
            <?php foreach (array_reverse($events->getItems()) as $event): ?>
                <div class="event-card" id="etc-<?= $event->getId() ?>">
                    <div class="event-title-container">
                        <h3><?= $event->getSummary() ?></h3>
                        <div class="event-actions">
                            <a href="<?= $event->getHtmlLink() ?>">
                                🔗
                            </a>
                            <span class="delete-button" id="<?= $event->getId() ?>">❌</span>

                        </div>

                    </div>

                    <p><strong>Start:</strong> <?= date("F jS, Y h:i:a", strtotime($event->start->dateTime)); ?></p>
                    <p><strong>End:</strong> <?= date("F jS, Y h:i:a", strtotime($event->end->dateTime)); ?></p>

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
<div id="modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="event-form">
            <div class="form-group">
                <label for="summary">Summary:</label>
                <input type="text" id="summary" name="summary" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <input type="text" id="description" name="description">
            </div>
            <div class="form-group">
                <label for="start">Start:</label>
                <input type="datetime-local" id="start" name="start" required>
            </div>
            <div class="form-group">
                <label for="end">End:</label>
                <input type="datetime-local" id="end" name="end" required>
            </div>
            <div class="form-group">
                <label for="attendees">Attendees (comma-separated emails):</label>
                <input type="text" id="attendees" name="attendees">
            </div>
            <div class="event-actions">
                <input type="submit" value="Submit" class="btn-submit">
            </div>
        </form>
        <div id="response-message"></div>
    </div>
</div>

<div id="modal-backdrop" class="modal-backdrop"></div>

<div></div>
<script src="custom.js"></script>
</body>
</html>
