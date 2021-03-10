<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['profile_username']))
{
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "Select * from users where username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}

if(isset($_POST['remove_friend'])) 
{
	$user = new User($con, $userLoggedIn);
	$user->removeFriend($username);
}

if(isset($_POST['add_friend'])) 
{
	$user = new User($con, $userLoggedIn);
	$user->sendRequest($username);
}

if(isset($_POST['respond_request'])) 
{
	header("Location: request.php");
}

if(isset($_POST['post_message'])) {
	if(isset($_POST['message_body'])) {
	  $body = mysqli_real_escape_string($con, $_POST['message_body']);
	  $date = date("Y-m-d H:i:s");
	  $message_obj->sendMessage($username, $body, $date);
	}
  
	$link = '#profileTabs a[href="#messages_div"]';
	echo "<script> 
			$(function() {
				$('" . $link ."').tab('show');
			});
		  </script>";
  
  
  }

 ?>
    <style type="text/css">
	    .wrapper {
			margin-left: 0px;
			padding-left: 0px;
		}

		a {
		color: #20AAE5;
		text-decoration: none;
		}

		.profile_left {
			top: -10px;
			width: 17px;
			max-width: 240px;
			min-width: 150px;
			height: 100%;
			float: left;
			position: relative;
			background-color: #37B0E9;
			border-right: 10px solid #83D6FE;
			color: #CBEAF8;
			margin-right: 20px;
		}

		.profile_left img {
			min-width: none;
			width: 55%;
			margin: 20px;
			border: 5px solid #83D6FE;
			border-radius: 100px;
		}

		.profile_info {
			display: list-item;
			width: 100%;
			padding: 10px 0 10px 0;
			background-color: #2980b9;
		}

		.profile_info p {
			margin: 0 0 0 20px;
			word-wrap: break-word;
		}

		.danger {
			background-color: #e74c3c;
		}

		.warning {
			background-color: #f0ad4e;
		}

		.default {
			background-color: #bdc3c7;
		}

		.success {
			background-color: #2ecc71;
		}

		.info {
			background-color: #3498db;
		}

		.deep_blue {
			background-color: #0043f0;
		}

		.profile_left input[type="submit"] {
			width: 90%;
			height: 30px;
			border-radius: 5px;
			margin: 7px 0 0 7px;
			border: none;
			color: #fff;
		}

		.delete_button {
			height: 22px;
			width: 22px;
			padding: 0;
			float: right;
			border-radius: 4px;
			right: -15px;
			position: relative;
		}

		.profile_main_column {
			min-width:675px;
			float:left;
			width:70%;
			z-index:-1;
		}

		.profile_info_bottom {
			margin: 10 0 0 7px;
		}

		.tab-content {
			margin-top: 25px;
		}

		.message {
			border: 1px solid #000;
			border-radius: 5px;
			padding: 5px 10px;
			display: inline-block;
			color: #fff;
		}

		.message#blue {
			background-color: #3498db;
			border-color: #2980b9;
			float: right;
			margin-bottom: 5px;
		}
		.message#green {
			background-color: #2ecc71;
			border-color: #27ae60;
			float: left;
			margin-bottom: 5px;
		}

		.delete_button {
			height: 22px;
			width: 22px;
			padding: 0;
			float: right;
			border-radius: 4px;
			right: -15px;
			position: relative;
		}
	</style>
	<div class="profile_left">
	    <img src="<?php echo $user_array['profile_pic'];?>">

		<div class="profile_info">
		    <p><?php echo "Posts: " . $user_array['num_posts']; ?></p>
			<p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
			<p><?php echo "Friends: " . $num_friends ?></p>
		</div>

		<form action="<?php echo $username; ?>" method="POST">
		    <?php
			$profile_user_obj = new User($con, $username);
			if($profile_user_obj->isClosed()) {
				header("Location: user_closed.php");
			}

			$logged_in_user_obj = new User($con, $userLoggedIn);

			if($userLoggedIn != $username)
			{
				if($logged_in_user_obj->isFriend($username))
				{
					echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"><br>';
				}
				else if($logged_in_user_obj->didReceivedRequest($username))
				{
					echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"><br>';
				}
				else if($logged_in_user_obj->didsendRequest($username))
				{
					echo '<input type="submit" name="" class="default" value="Request Sent"><br>';
				}
				else
				{
					echo '<input type="submit" name="add_friend" class="success" value="Add Friend"><br>';
				}
			}

			?>
		</form>
		<input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_Form" value="Post Something">
		<?php 		
			if($userLoggedIn != $username) {
				echo '<div class="profile_info_bottom">';
				echo $logged_in_user_obj->getMutualFriends($username) . " Mutual friends";
				echo '</div>';
			}
		?>
	</div>

	<div class="profile_main_column column">
    
	<ul class="nav nav-tabs" role="tablist" id="profileTabs">
      <li role="presentation" class="active"><a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab">Newsfeed</a></li>
      <li role="presentation"><a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab">Messages</a></li>
    </ul>

    
    <div class="tab-content">

      <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
        <div class="posts_area"></div>
        <img id="loading" src="assets/images/icons/loading.gif">
      </div>
	  
	  <div role="tabpanel" class="tab-pane fade" id="messages_div">
	  <?php  
	   
          echo "<h4>You and <a href='" . $username ."'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

          echo "<div class='loaded_messages' id='scroll_messages'>";
            echo $message_obj->getMessages($username);
          echo "</div>";
        ?>

		<div class="message_post">
		<form action="" method="POST">
			<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
			<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
		</form>
        </div>

		<script>
          var div = document.getElementById("scroll_messages");
          div.scrollTop = div.scrollHeight;
        </script>
		</div>
      </div>

	</div>

<!-- Modal -->
	<div class="modal fade" id="post_Form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">

		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
			<span aria-hidden="true">&times;</span>
			</button>
			<h4 class="modal-title" id="exampleModalLabel">Post Something!</h4>
		  </div>
		  <div class="modal-body">
		    <p>This will appear on the user's profile page and also their newsfeed for your friend to see!</p>

			<form class="profile_post" action="" method="POST">
			    <div class="form-group">
				    <textarea class="form-control" name="post_body"></textarea>
					<input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
					<input type="hidden" name="user_to" value="<?php echo $username; ?>">
				</div>
			</form> 
		  </div>
		<div class="modal-footer">
			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
			<button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
		</div>
		</div>
	</div>
	</div>

	<script>
  var userLoggedIn = '<?php echo $userLoggedIn; ?>';
  var profileUsername = '<?php echo $username; ?>';

  $(document).ready(function() {

    $('#loading').show();

    //Original ajax request for loading first posts 
    $.ajax({
      url: "includes/handlers/ajax_load_profile_posts.php",
      type: "POST",
      data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
      cache:false,

      success: function(data) {
        $('#loading').hide();
        $('.posts_area').html(data);
      }
    });

    $(window).scroll(function() {
      var height = $('.posts_area').height(); //Div containing posts
      var scroll_top = $(this).scrollTop();
      var page = $('.posts_area').find('.nextPage').val();
      var noMorePosts = $('.posts_area').find('.noMorePosts').val();

      if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
        $('#loading').show();

        var ajaxReq = $.ajax({
          url: "includes/handlers/ajax_load_profile_posts.php",
          type: "POST",
          data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
          cache:false,

          success: function(response) {
            $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
            $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

            $('#loading').hide();
            $('.posts_area').append(response);
          }
        });

      } //End if 

      return false;

    }); //End (window).scroll(function())


  });

  </script>



	</div>
</body>
</html>