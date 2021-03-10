<?php  
require 'config/config.php';

if (isset($_SESSION['username'])) {
	$userLoggedIn = $_SESSION['username'];
	$user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
	$user = mysqli_fetch_array($user_details_query);
}
else {
	header("Location: register.php");
}

?>

<html>
<head>
	<title>Social Media Application</title>

	<!-- Javascript -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="assets/js/bootstrap.js"></script>
	<script src="assets/js/bootbox.min.js"></script>
	<script src="assets/js/social.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>
	<script src="assets/js/jquery.Jcrop.js"></script>

	<!-- CSS -->
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="assets/css/style.css">
	<link rel="stylesheet" type="text/css" href="assets/css/jquery.Jcrop.css">

	<script>
		function getDropdownData(user, type) {

		if($(".dropdown_data_window").css("height") == "0px") {

			var pageName;

			if(type == 'notification') {

			}
			else if (type == 'message') {
				pageName = "ajax_load_messages.php";
				$("span").remove("#unread_message");
			}

			var ajaxreq = $.ajax({
				url: "includes/handlers/" + pageName,
				type: "POST",
				data: "page=1&userLoggedIn=" + user,
				cache: false,

				success: function(response) {
					$(".dropdown_data_window").html(response);
					$(".dropdown_data_window").css({"padding" : "0px", "height": "280px", "border" : "1px solid #DADADA", "background-color": "#fff" ,"border-radius": "0 0 8px 8px","border-top": "none","width": "300px","position":"absolute","right":"10px", "top": "40px","overflow": "scroll"});
					$("#dropdown_data_type").val(type);
				}

			});

		}
		else {
			$(".dropdown_data_window").html("");
			$(".dropdown_data_window").css({"padding" : "0px", "height": "0px", "border" : "none"});
		}

		}
	</script>

	<style>
	.dropdown_data_window {
		height: 280px; border-top: none; border-right: 1px solid rgb(218, 218, 218); border-bottom: 1px solid rgb(218, 218, 218); border-left: 1px solid rgb(218, 218, 218); border-image: initial; padding: 0px; background-color: rgb(255, 255, 255); border-radius: 0px 0px 8px 8px; width: 300px; position: absolute; right: 10px; top: 40px; overflow: scroll;
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

</head>
<body>

	<div class="top_bar"> 

		<div class="logo">
			<a href="index.php">Social Media Application</a>
		</div>

		

		<nav>
			<a href="<?php echo $userLoggedIn; ?>">
				<?php echo $user['first_name']; ?>
			</a>
			<a href="index.php">
				<i class="fa fa-home fa-lg"></i>
			</a>
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
				<i class="fa fa-envelope fa-lg"></i>
			</a>
			<a href="#">
				<i class="fa fa-bell fa-lg"></i>
			</a>
			<a href="requests.php">
				<i class="fa fa-users fa-lg"></i>
			</a>
			<a href="#">
				<i class="fa fa-cog fa-lg"></i>
			</a>
			<a href="includes/handlers/logout.php">
				<i class="fa fa-sign-out fa-lg"></i>
			</a>



		</nav>

		<div class="dropdown_data_window" style="height:0px; border:none;"></div>
		<input type="hidden" id="dropdown_data_type" value="">


    	</div>
         
		<script>
			var userLoggedIn = '<?php echo $userLoggedIn; ?>';

			$(document).ready(function() {

				$('.dropdown_data_window').scroll(function() {
					var inner_height = $('.dropdown_data_window').innerHeight(); //Div containing data
					var scroll_top = $('.dropdown_data_window').scrollTop();
					var page = $('.dropdown_data_window').find('.nextPageDropdownData').val();
					var noMoreData = $('.dropdown_data_window').find('.noMoreDropdownData').val();

					if ((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false') {

						var pageName; //Holds name of page to send ajax request to
						var type = $('#dropdown_data_type').val();


						if(type == 'notification')
							pageName = "ajax_load_notifications.php";
						else if(type = 'message')
							pageName = "ajax_load_messages.php"


						var ajaxReq = $.ajax({
							url: "includes/handlers/" + pageName,
							type: "POST",
							data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
							cache:false,

							success: function(response) {
								$('.dropdown_data_window').find('.nextPageDropdownData').remove(); //Removes current .nextpage 
								$('.dropdown_data_window').find('.noMoreDropdownData').remove(); //Removes current .nextpage 


								$('.dropdown_data_window').append(response);
							}
						});

					} //End if 

					return false;

				}); //End (window).scroll(function())


			});

	</script>


	</div>


	<div class="wrapper">