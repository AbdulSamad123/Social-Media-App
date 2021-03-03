<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body>

    <style>
    * {
        font-size:12px;
        font-family:Arial, Helvetica, Sans-serif;
    }
    </style>

	<?php  
	require 'config/config.php';
	include("includes/classes/User.php");
	include("includes/classes/Post.php");

	if (isset($_SESSION['username'])) {
		$userLoggedIn = $_SESSION['username'];
		$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
		$user = mysqli_fetch_array($user_details_query);
	}
	else {
		header("Location: register.php");
	}

	?>
	<script>
		function toggle() {
			var element = document.getElementById("comment_section");

			if(element.style.display == "block") 
				element.style.display = "none";
			else 
				element.style.display = "block";
		}
	</script>

	<?php  
	//Get id of post
	if(isset($_GET['post_id'])) {
		$post_id = $_GET['post_id'];
	}

	$user_query = mysqli_query($con, "SELECT added_by, user_to FROM post WHERE id='$post_id'");
	$row = mysqli_fetch_array($user_query);

	$posted_to = $row['added_by'];

	if(isset($_POST['postComment' . $post_id])) {
		$post_body = $_POST['post_body'];
		$post_body = mysqli_escape_string($con, $post_body);
		$date_time_now = date("Y-m-d H:i:s");
		$insert_post = mysqli_query($con, "INSERT INTO comments VALUES ('', '$post_body', '$userLoggedIn', '$posted_to', '$date_time_now', 'no', '$post_id'");
		echo "<p>Comment Posted! </p>";
	}
	?>
	<form action="comment_frame.php?post_id=<?php echo $post_id; ?>" id="comment_form" name="postComment<?php echo $post_id; ?>" method="POST">
		<textarea name="post_body" style="border-color: #D3D3D3; width:85%; height:35px; border-radius: 5px; color: #616060; font-size: 14px; margin: 3px 5px 5px 5px;"></textarea>
		<input type="submit" name="postComment<?php echo $post_id; ?>" value="Post" style="border:none; background-color: #20AAE5; color:#156588; border-radius:5px; width:13%; height:35px; margin-top: 3px; position:absolute; font-family: 'Bellota-BoldItalic', sans-serif; text-shadow: #73B6E2 0.5px 0.5px 0px;">
	</form>

	<!-- Load comments -->

    <?php
    $get_comments = mysqli_query($con, "Select * from comments where post_id='$post_id' order by id asc");
    $count = mysqli_num_rows($get_comments);

    if($count != 0)
    {
        while($comment = mysqli_fetch_array($get_comments))
        {
            $comment_body = $comment['post_body'];
            $posted_to = $comment['posted_to'];
            $posted_by = $comment['posted_by'];
            $date_added = $comment['date_added'];
            $removed = $comment['removed'];

            //Time Frame
            $date_time_now =  date("Y-m-d H:i:s");
            $start_date = new DateTime($date_added);//Time of post
            $end_date = new DateTime($date_time_now);//Current Time
            $interval = $start_date->diff($end_date);//Difference between dates

            if($interval->y >= 1)
            {
                if($interval==1)
                {
                    $time_message = $interval->y . "year ago";//1 year ago
                    
                }
                else
                {
                    $time_message = $interval->y . "years ago"; //1+ year ago	
                }
            }
            elseif($interval-> m >= 1) 
            {
                if($interval->d == 0)
                {
                    $days = "ago";
                }
                elseif($interval->d == 1)
                {
                    $days = $interval->d . "day ago";
                }
                else {
                    $days = $interval->d . "days ago";
                }

                if($interval->m == 1)
                {
                    $time_message = $interval->m . "month". $days;
                }
                else {
                    $time_message = $interval->m . "months". $days; 
                }
            }
            elseif($interval->d >= 1)
            {
                if($interval->d == 1)
                {
                    $time_message = "yesterday";
                }
                else {
                    $time_message = $interval->d . "days ago";
                }
            } 
            elseif ($interval->h >=1) {
                if($interval->h == 1)
                {
                    $time_message = $interval->h . "hour ago";
                }
                else {
                    $time_message = $interval->h . "hours ago";
                }
            }
            elseif ($interval->i >=1) {
                if($interval->i == 1)
                {
                    $time_message = $interval->i . "minute ago";
                }
                else {
                    $time_message = $interval->i . "minutes ago";
                }
            }
            else {
                if($interval->s == 1)
                {
                    $time_message = "just now";
                }
                else {
                    $time_message = $interval->s . "seconds ago";
                }
            } 

            $user_obj = new User($con, $posted_by);
           ?>
               
            <div class="comment_section" style="padding: 0 5px 5px 5px;">
                <a href="<?php echo $posted_by?>" target="_parent"><img src="<?php echo $user_obj->getProfilePic(); ?>" title="<?php echo $posted_by; ?>" style="float:left;margin: 0 3px 3px 3px;border-radius: 3px;" height="30"></a>
                <a href="<?php echo $posted_by?>" target="_parent"><b><?php echo $user_obj->getFirstAndLastName(); ?></b></a>
                &nbsp;&nbsp;&nbsp;&nbsp; <?php echo $time_message. "<br>" . $comment_body; ?>
                <hr>
            </div>
           <?php 
        }
    }
    ?>





</body>
</html>