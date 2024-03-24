$(document).ready(function () {
    const modal = $("#modal");
    const modalBackdrop = $("#modal-backdrop");
    const closeButton = $(".close");
    const openModalButton = $("#open-modal-btn");

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

    function openModal() {
        modal.show();
        modalBackdrop.show();
    }

    function closeModal() {
        modal.hide();
        modalBackdrop.hide();
        $("#event-form")[0].reset();
    }

    closeButton.on("click", function() {
        closeModal();
    });

    openModalButton.on("click", function() {
        openModal();
    });

    $(document).on("click", function(event) {
        if ($(event.target).is(modalBackdrop)) {
            console.log('chec')
            closeModal();
        }
    });

    $("#event-form").submit(function(event) {
        event.preventDefault();

        var formData = $(this).serialize();

        $.ajax({
            type: "POST",
            url: "create_event.php",
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