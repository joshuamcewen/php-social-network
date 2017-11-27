// Set an initial limit to how many posts are loaded
var limit = 5;

// Function to retrieve posts and append to the page.
function retrievePosts(){
  // Retrieve contents of return_feed.php which returns posts as JSON. Send a limit as well.
  $.getJSON("api/recent.php?limit=" + limit, function(response) {

        $('ul#posts').html("");

        // For each post in the posts key pair, append to list.
        $.each(response.posts, function(index, post) {
          // If the user is an admin, show administrative options.
          $('ul#posts').append("<li>" +
                               "<div class='post-details'>" +
                               "<span class='username'>" + post.username + "</span>" +
                               "<span class='posted'>" + post.posted_at + "</span>" +
                               "</div><div class='post-body'>" + post.message + "</div>" +
                               "<div class='post-footer'>" +
                               "<span class='likes'><span class='icon like'></span>" + post.likes +"</span>" +
                               (response.admin == 1 ? "<a href='mute_user.php?username=" + post.username + "' class='mute'>Mute</a>" : "") +
                               (post.liked == 1 ? "<a data-action='unlike' data-post='" + post.post_id + "' class='unlike'>Unlike</a>" : "<a data-action='like' data-post='" + post.post_id + "'>Like</a>") +
                               "</div>" +
                               "</li>"
                             );
        });

        // If the total number of posts is greater than the limit, show option to load more.
        if(response.total > limit) {
          $('div#options').html("<a data-action='load'>Load more posts</a>");
        } else {
          $('div#options').html("No more posts");
        }
    });
  };

  $(document).ready(function(){
    // Character counting implementation for the feed textarea.
    $('textarea').keyup(function(){
      var chars_used = $('textarea').val().length;
      $('span#characters').text(140 - chars_used + " left");
    });
  });

  // Retrieve an updated feed every 3 seconds.
  setInterval(retrievePosts, 3000);

  // When the load button is pressed, increment limit and retrieve posts.
  // .on() used as event handlers don't handle appended elements.
  $(document).on('click', 'a[data-action="load"]', function(){
    limit += 5;
    retrievePosts();
  });

  // When the unlike button is pressed, extract the post ID from the data attribute
  // and execute the unlike_post script via jQuery.
  $(document).on('click', 'a[data-action="unlike"]', function(){
    var post_id = $(this).data('post');
    $.post("api/unlike.php", { post_id : post_id });

    // Refresh the feed to reflect changes.
    retrievePosts();
  });

  // When the like button is pressed, extract the post ID from the data attribute
  // and execute the like_post script via jQuery.
  $(document).on('click', 'a[data-action="like"]', function(){
    var post_id = $(this).data('post');
    $.post("api/like.php", { post_id : post_id });

    // Refresh the feed to reflect changes.
    retrievePosts();
  });
