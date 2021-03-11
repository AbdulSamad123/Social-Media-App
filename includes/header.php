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
		$(document).ready(function() {

		$('#search_text_input').focus(function() {
			if(window.matchMedia( "(min-width: 800px)" ).matches) {
				$(this).animate({width: '250px'}, 500);
			}
		});

		$('.button_holder').on('click', function() {
			document.search_form.submit();
		})

		//Button for profile post
		$('#submit_profile_post').click(function(){
			
			$.ajax({
				type: "POST",
				url: "includes/handlers/ajax_submit_profile_post.php",
				data: $('form.profile_post').serialize(),
				success: function(msg) {
					$("#post_form").modal('hide');
					location.reload();
				},
				error: function() {
					alert('Failure');
				}
			});

		});


		});

		$(document).click(function(e){

		if(e.target.class != "search_results" && e.target.id != "search_text_input") {

			$(".search_results").html("");
			$('.search_results_footer').html("");
			$('.search_results_footer').toggleClass("search_results_footer_empty");
			$('.search_results_footer').toggleClass("search_results_footer");
		}

		if(e.target.className != "dropdown_data_window") {

			$(".dropdown_data_window").html("");
			$(".dropdown_data_window").css({"padding" : "0px", "height" : "0px"});
		}


		});

		function getDropdownData(user, type) {

		if($(".dropdown_data_window").css("height") == "0px") {

			var pageName;

			if(type == 'notification') {
			    pageName = "ajax_load_notifications.php";
				$("span").remove("#unread_notifications");

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

		function getLiveSearchUsers(value, user) {

		$.post("includes/handlers/ajax_search.php", {query:value, userLoggedIn: user}, function(data) {

			if($(".search_results_footer_empty")[0]) {
				$(".search_results_footer_empty").toggleClass("search_results_footer");
				$(".search_results_footer_empty").toggleClass("search_results_footer_empty");
			}

			$('.search_results').html(data);
			$('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>");

			if(data == "") {
				$('.search_results_footer').html("");
				$('.search_results_footer').toggleClass("search_results_footer_empty");
				$('.search_results_footer').toggleClass("search_results_footer");
			}

		});

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

	.resultDisplay {
		padding: 5px 5px 0 5px;
		height: 70px;
		border-bottom: 1px solid #D3D3D3;
	}
	.resultDisplay a {
		float: none;
	}
	.resultDisplay:hover {
		background-color: #EBEBEB;
		text-decoration: none;
	}
	.liveSearchProfilePic img {
		height: 50px;
		border-radius: 25px;
		margin: 1px 12px 0 2px;
		float: left;
	}

	.liveSearchText {
		background-color: transparent;
		color: #1E96CA;
	}

	.liveSearchText p{
		margin-left: 10px;
		font-size: 12px;
	}

	.resultDisplayNotification {
		height: auto;
		color: #1485BD;
		padding-bottom: 7px;
	}

	.resultDisplayNotification img {
		height: 40px;
		border-radius: 5px;
		margin: 1px 12px 0px 2px;
		float: left;
	}

	.timestamp_smaller {
		font-size: 85%;
		margin: 0;
	}

	.search {
		margin: 8px 0 0 15%;
			
	}

	.search #search_text_input {
		border: thin solid #E5E5E5;
		float: left;
		height: 23px;
		outline: 0;
		width: 100px;
		border-top-right-radius: 0;
		border-bottom-right-radius: 0;
		border-top-left-radius: 3px;
		border-bottom-left-radius: 3px;
	}

	.button_holder {
		background-color: #F1F1F1;
		border: thin solid #e5e5e5;
		cursor: pointer;
		float: left;
		height: 23px;
		text-align: center;
		width: 50px;
		border-top-right-radius: 3px;
		border-bottom-right-radius: 3px;
	}

	.button_holder img {
		margin-top: 1px;
		width: 20px;
	}

	.search_results {
		background-color: #fff;
		border: 1px solid #DADADA;
		border-bottom: none;
		border-top: none;
		margin-top: 21px;
	}

	.search_results_footer{
		padding: 7px 4px 0px 4px;
		height: 30px;
		border: 1px solid #DADADA;
		border-top: none;
		background-color: #20AAE5;
		text-align: center;
	}

	.search_results_footer a {
		color: #fff;
	}

	.search_result {
		padding: 10px;
		height: 100px;
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

	.searchPageFriendButtons {
		float: right;
	}

	.searchPageFriendButtons input[type="submit"] {
		border: none;
		padding: 7px 12px;
		border-radius: 5px;
		color: #fff;
	}

	.result_profile_pic {
		float: left;
		margin-right: 10px;
	}

	#search_hr {
		margin-bottom: 0px;
	}
	</style>

</head>
<body>

	<div class="top_bar"> 

		<div class="logo">
			<a href="index.php">Social Media Application</a>
		</div>

		<div class="search">

		<form action="search.php" method="GET" name="search_form">
			<input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="q" placeholder="Search..." autocomplete="off" id="search_text_input">

			<div class="button_holder">
				<img src="assets/images/icons/magnifying_glass.png">
			</div>

		</form>

		<div class="search_results">
		</div>

		<div class="search_results_footer_empty">
		</div>



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
			<a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
				<i class="fa fa-bell fa-lg"></i>
			</a>
			<a href="requests.php">
				<i class="fa fa-users fa-lg"></i>
			</a>
			<a href="settings.php">
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