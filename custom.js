$(document).ready(function () {
    // delete functionality for events
    $('.delete-button').on('click', function () {
        const eventId = $(this).attr('id');
        const eventName = $('event-title-container h3').text();
        if (confirm(`Do you want to delete event ${eventName}?`)) {
            $.ajax({
                url: 'delete_event.php',
                type: 'POST',
                data: {event_id: eventId},
                dataType: 'json',
                success: function (response) {
                    if (response?.code == 200) {
                        // Remove the deleted event card from the UI
                        $('#etc-' + eventId).remove();
                    }
                    alert(response?.message);
                },
                error: function () {
                    alert('Error: Unable to delete event.');
                }
            });
        }
    });
});