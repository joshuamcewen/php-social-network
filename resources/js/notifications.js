// Function to retrieve notifications and append to the page.
function retrieveNotifications(){
  // Retrieve contents of notifications/recent.php which returns
  // notifications as JSON.
  $.getJSON("api/notifications/recent.php", function(response) {

        $('ul#notifications').html("");

        // For each notification in the notifications key pair, append to list.
        $.each(response.notifications, function(index, notification) {
          $('ul#notifications').append("<li>" +
                               notification.message +
                               " <a data-action='acknowledge' data-notification ='" + notification.notification_id + "'>Acknowledge</a>" +
                               "</li>"
                             );
        });
    });
  };

  // Retrieve an updated notifications feed every 3 seconds.
  setInterval(retrieveNotifications, 3000);

  // When the acknowledge button is pressed, extract the notification ID from the data attribute
  // and execute the notifications/seen.php script via jQuery.
  $(document).on('click', 'a[data-action="acknowledge"]', function(){
    var notification_id = $(this).data('notification');

    $.ajax({
      type: 'POST',
      url: "api/notifications/seen.php",
      data: {
              notification_id : notification_id
            },
      complete: function(jqXHR, textStatus) {
        console.log(jqXHR);
      }
    });

    retrieveNotifications();
  });
