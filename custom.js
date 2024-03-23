$(document).ready(function () {
    const modal = $("#modal");
    const modalBackdrop = $("#modal-backdrop");
    const closeButton = $(".close");
    const openModalButton = $("#open-modal-btn");

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



    // Function to open the modal
    function openModal() {
        modal.show();
        modalBackdrop.show();
    }

    // Function to close the modal
    function closeModal() {
        modal.hide();
        modalBackdrop.hide();
        $("#event-form")[0].reset();
    }

    // Event listener for the close button
    closeButton.on("click", function() {
        closeModal();
    });

    // Event listener for the open modal button
    openModalButton.on("click", function() {
        openModal();
    });

    // Close the modal when the user clicks outside of it
    $(document).on("click", function(event) {
        if ($(event.target).is(modalBackdrop)) {
            console.log('chec')
            closeModal();
        }
    });

    // Form submission
    $("#event-form").submit(function(event) {
        event.preventDefault();

        // Get form data
        var formData = $(this).serialize();

        // Send AJAX request
        $.ajax({
            type: "POST",
            url: "create_event.php", // Change to your PHP script that creates events
            data: formData,
            success: function(response) {
                var res = JSON.parse(response);

                alert(res.message);
                if(res.code == 200) {
                    $("#response-message").text(res.message);
                    location.reload();
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", status, error);
                $("#response-message").text("Error: " + xhr.responseText);
            }
        });
    });
});