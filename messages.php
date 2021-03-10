<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['u']))
	$user_to = $_GET['u'];
else {
	$user_to = $message_obj->getMostRecentUser();
	if($user_to == false)
		$user_to = 'new';
}

if($user_to != "new")
	$user_to_obj = new User($con, $user_to);

if(isset($_POST['post_message'])) {

	if(isset($_POST['message_body'])) {
		$body = mysqli_real_escape_string($con, $_POST['message_body']);
		$date = date("Y-m-d H:i:s");
		$message_obj->sendMessage($user_to, $body, $date);
	}

}

 ?>

 <style>
	 #message_textarea {
		width: 80%;
		margin-right: 8px;
		border-radius: 5px;
		border: 1px solid #D3D3D3;
		font-size: 12px; 
	}

	#message_submit {
		height: 41px;
		width: 120px;
		margin: 0;
		position: absolute;
		border: none;
		background-color: #20AAE5;
		color: #156588;
		border-radius: 5px;
		font-family: 'Bellota-BoldItalic', sans-serif;
		text-shadow: #73B6E2 0.5px 0.5px 0px;

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

	.loaded_messages {
		height: 65%;
		min-height: 300px;
		max-height: 400px;
		overflow: scroll;
		margin-bottom: 10px;
	}

	.loaded_conversations {
	max-height: 216px;
	overflow: scroll;
	}

	.user_found_messages {
		border-bottom: 1px solid #D3D3D3;
		padding: 8px 8px 10px 8px;
	}

	.user_found_messages:hover {
		background-color: #D3D3D3;
	}

	.user_found_messages img {
		height: 35px;
		float: left;
	}

	#grey {
		color: #8C8C8C;
	}

 </style>

  <div class="user_details column">
		<a href="<?php echo $userLoggedIn; ?>">  <img src="<?php echo $user['profile_pic']; ?>"> </a>

		<div class="user_details_left_right">
			<a href="<?php echo $userLoggedIn; ?>">
			<?php 
			echo $user['first_name'] . " " . $user['last_name'];

			 ?>
			</a>
			<br>
			<?php echo "Posts: " . $user['num_posts']. "<br>"; 
			echo "Likes: " . $user['num_likes'];

			?>
		</div>
	</div>

	<div class="main_column column" id="main_column">
		<?php  
		if($user_to != "new"){
			echo "<h4>You and <a href='$user_to'>" . $user_to_obj->getFirstAndLastName() . "</a></h4><hr><br>";
			echo "<div class='loaded_messages' id='scroll_messages'>";
				echo $message_obj->getMessages($user_to);
			echo "</div>";
		}
		else {
			echo "<h4>New Message</h4>";
		}
		?>

		<div class="message_post">
			<form action="" method="POST">
				<?php
				if($user_to == "new") {
					echo "Select the friend you would like to message <br><br>";
					?> 
					To: <input type='text' onkeyup='getUsers(this.value, "<?php echo $userLoggedIn; ?>")' name='q' placeholder='Name' autocomplete='off' id='seach_text_input'>

					<?php
					echo "<div class='results'></div>";
				}
				else {
					echo "<textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>";
					echo "<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
				}

				?>
			</form>

		</div>

		<script>
		
			function getUsers(value, user) {
					$.post("includes/handlers/ajax_friend_search.php", {query:value, userLoggedIn:user}, function(data) {
						$(".results").html(data);
					});
				}

			var div = document.getElementById("scroll_messages");
			div.scrollTop = div.scrollHeight;
		</script>

	</div>

	<div class="user_details column" id="conversations">
			<h4>Conversations</h4>

			<div class="loaded_conversations">
				<?php echo $message_obj->getConvos(); ?>
			</div>
			<!-- <br>
			<a href="messages.php?u=new">New Message</a> -->

		</div> 
