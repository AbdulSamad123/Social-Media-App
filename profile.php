<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

if(isset($_GET['profile_username']))
{
	$username = $_GET['profile_username'];
	$user_details_query = mysqli_query($con, "Select * from users where username='$username'");
	$user_array = mysqli_fetch_array($user_details_query);

	$num_friends = (substr_count($user_array['friend_array'], ",")) - 1;
}

 ?>

    <style type="text/css">
	    .wrapper {
			margin-left: 0px;
			padding-left: 0px;
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
	</style>
	<div class="profile_left">
	    <img src="<?php echo $user_array['profile_pic'];?>">

		<div class="profile_info">
		    <p><?php echo "Posts: " . $user_array['num_posts']; ?></p>
			<p><?php echo "Likes: " . $user_array['num_likes']; ?></p>
			<p><?php echo "Friends: " . $num_friends ?></p>
		</div>

		<form action="<?php echo $username; ?>">
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
	
	</div>

	<div class="main_column column">
		<?php echo $username; ?>


	</div>




	</div>
</body>
</html>