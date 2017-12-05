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
      'chartType': 'BarChart',
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
        'height': 240
      }
    });

    var data = new google.visualization.DataTable(response);

    dashboard.bind(slider, chart);
    dashboard.draw(data);
  });
}

// Every 3 seconds, poll the APIs and redraw the dashboards.
setInterval(drawDashboards, 5000);
