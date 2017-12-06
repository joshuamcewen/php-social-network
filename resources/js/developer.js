// Load Google Charts library.
google.charts.load('current', {'packages':['corechart', 'controls']});

// When the charts API is loaded, execute the drawCharts function.
google.charts.setOnLoadCallback(drawDashboards);

function drawDashboards() {
  drawLikesDashboard();
  drawPetsDashboard();
  drawPostsDashboard();
  drawDaysDashboard();
}

// Track users selected for marketing messages.
var users = [];

$('document').ready(function(){
  // When the notification form is submitted, post through AJAX instead.
  $('form').submit(function(e) {
    e.preventDefault();
    var message = $('textarea#message').val();

    $.ajax({
      type: 'POST',
      url: "api/developer/notifications/create.php",
      data: {
              message : message,
              users: users
            },
      complete: function(jqXHR, textStatus) {
        if(jqXHR.status == 201) {
          $('div#errors').html("Your post was successful.");
        } else {
          $('div#errors').html("We couldn't post your notification at this time.");
        }
      }
    });
  });

  // Character counting implementation for the notification textarea.
  $('textarea').keyup(function(){
    var chars_used = $('textarea').val().length;
    $('span#characters').text(255 - chars_used + " left");
  });
});

// Create a dashboard for the likes controls and chart.
function drawLikesDashboard() {
  $.getJSON('api/developer/likes.php', function(response) {
    var dashboard = new google.visualization.Dashboard(document.getElementById('likes_dashboard'));

    var slider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'likes_slider',
          'options': {
            'filterColumnLabel': 'Total Likes',
            'ui': {'labelStacking': 'vertical'}
          }
        });


    var chart = new google.visualization.ChartWrapper({
      'chartType': 'PieChart',
      'containerId': 'likes_chart',
      'options': {
        'width': 500,
        'height': 240
      }
    });

    var data = new google.visualization.DataTable(response);

    dashboard.bind(slider, chart);
    dashboard.draw(data);

    google.visualization.events.addListener(slider, 'ready', filterHandler);

    // Add a listene to the slider. When changed, call the filterHandler function.
    google.visualization.events.addListener(slider, 'statechange', filterHandler);

    // An event handler for change of slider state.
    function filterHandler() {
      var table = chart.getDataTable();
      var rows = table.getNumberOfRows();

      // Clear the users array
      users = [];

      // Clear the display message in the notification form.
      $('#users').html('Notification will be sent to ');

      // For each data value, get the username property.
      for(var i = 0; i < rows; i++) {
        var username = table.getValue(i, 0);

        // Push the username to the users array.
        users.push(username);

        // Append the username to the display panel.
        $('#users').append("<b>" + username + "</b>");
        $('#users').append((i == rows - 1 ? '' : ', '));
      }
    }
  });
}

// Create a dashboard for the pets controls and chart.
function drawPetsDashboard() {
  $.getJSON('api/developer/pets.php', function(response) {
    var dashboard = new google.visualization.Dashboard(document.getElementById('pets_dashboard'));

    var slider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'pets_slider',
          'options': {
            'filterColumnLabel': 'Users',
            'ui': {'labelStacking': 'vertical'}
          }
        });


    var chart = new google.visualization.ChartWrapper({
      'chartType': 'ColumnChart',
      'containerId': 'pets_chart',
      'options': {
        'width': 500,
        'height': 240
      }
    });

    var data = new google.visualization.DataTable(response);

    dashboard.bind(slider, chart);
    dashboard.draw(data);
  });
}

// Create a dashboard for the posts controls and chart.
function drawPostsDashboard() {
  $.getJSON('api/developer/posts.php', function(response) {
    var dashboard = new google.visualization.Dashboard(document.getElementById('posts_dashboard'));

    var slider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'posts_slider',
          'options': {
            'filterColumnLabel': 'Total Posts',
            'ui': {'labelStacking': 'vertical'}
          }
        });


    var chart = new google.visualization.ChartWrapper({
      'chartType': 'PieChart',
      'containerId': 'posts_chart',
      'options': {
        'width': 500,
        'height': 240
      }
    });

    var data = new google.visualization.DataTable(response);

    dashboard.bind(slider, chart);
    dashboard.draw(data);
  });
}

// Create a dashboard for the days controls and chart.
function drawDaysDashboard() {
  $.getJSON('api/developer/days.php', function(response) {
    var dashboard = new google.visualization.Dashboard(document.getElementById('days_dashboard'));

    var slider = new google.visualization.ControlWrapper({
          'controlType': 'NumberRangeFilter',
          'containerId': 'days_slider',
          'options': {
            'filterColumnLabel': 'Total Posts',
            'ui': {'labelStacking': 'vertical'}
          }
        });


    var chart = new google.visualization.ChartWrapper({
      'chartType': 'PieChart',
      'containerId': 'days_chart',
      'options': {
        'width': 500,
        'height': 240,
        'pieHole': 0.4
      }
    });

    var data = new google.visualization.DataTable(response);

    dashboard.bind(slider, chart);
    dashboard.draw(data);
  });
}
