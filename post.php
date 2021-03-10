<?php  
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");

if(isset($_GET['id'])) {
	$id = $_GET['id'];
}
else {
	$id = 0;
}
?>
 <style>
	 	a {
		color: #20AAE5;
		text-decoration: none;
		}

		
		.column {
			background-color: #fff;
			padding: 10px;
			border: 1px solid #D3D3D3;
			border-radius: 5px;
			box-shadow: 2px 2px 1px #D3D3D3;
			z-index: -1;
		}

		.user_details {
			width: 250px;
			float: left;
			margin-bottom: 20px;
		}

		.user_details img {
			height: 120px;
			border-radius: 5px;
			margin-right: 5px;
		}

		.user_details_left_right {
			width: 120px;
			display: inline-table;
			position: absolute;
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

		<div class="posts_area">

			<?php 
				$post = new Post($con, $userLoggedIn);
				$post->getSinglePost($id);
			?>

		</div>

	</div>