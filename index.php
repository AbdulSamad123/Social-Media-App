<?php 
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");


if(isset($_POST['post'])){
	$post = new Post($con, $userLoggedIn);
	$post->submitPost($_POST['post_text'], 'none');
}


 ?>

 <style>
     a {
    color: #20AAE5;
    text-decoration: none;
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

	<div class="main_column column">
		<form class="post_form" action="index.php" method="POST">
			<textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
			<input type="submit" name="post" id="post_button" value="Post">
			<hr>

		</form>
 
        <div class="post_area"></div>
		<img id="loading" src="assets/images/icons/loading.gif">

	</div>

	<script>
		var userLoggedIn = '<?php echo $userLoggedIn; ?>';
		$(document).ready(function() {
			$('#loading').show();

			//Orignal ajax request for loading  first post
			$.ajax({
				url: "includes/handlers/ajax_load_post.php",
				type: "POST",
				data: "page=1&userLoggedIn=" + userLoggedIn,
				cache:false,

				success: function(data) {
					$('#loading').hide();
					$('.post_area').html(data);
				}
			});

			$(window).scroll(function() {
				var height = $('.post_area').height(); //div containig post 
				var scroll_top = $(this).scrollTop();
				var page = $('.post_area').find('.nextPage').val();
				var noMorePost = $('.post_area').find('.noMorePost').val();

				if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePost == 'false')
				{
					$('#loading').show();
					alert("hello");

					var ajaxReq = $.ajax({
						url: "includes/handlers/ajax_load_post.php",
						type: "POST",
						data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
						cache:false,

						success: function(response) {
							$('.post_area').find('.nextPage').remove(); //Removes current nextpage
							$('.post_area').find('.noMorePost').remove(); //Removes current nextpage
							$('#loading').hide();
							$('.post_area').append(response);
						}
				    });
				}

				return false;
			});
		});
	</script>




	</div>
</body>
</html>




