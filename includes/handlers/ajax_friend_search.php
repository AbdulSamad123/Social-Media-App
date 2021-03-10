<?php  
include("../../config/config.php");
include("../classes/User.php");

$query = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

$names = explode(" ", $query);

if(strpos($query, "_") !== false) {
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
}
else if(count($names) == 2) {
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' AND last_name LIKE '%$names[1]%') AND user_closed='no' LIMIT 8");
}
else {
	$usersReturned = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '%$names[0]%' OR last_name LIKE '%$names[0]%') AND user_closed='no' LIMIT 8");
}
if($query != "") {
	while($row = mysqli_fetch_array($usersReturned)) {

		$user = new User($con, $userLoggedIn);

		if($row['username'] != $userLoggedIn) {
			$mutual_friends = $user->getMutualFriends($row['username']) . " friends in common";
		}
		else {
			$mutual_friends = "";
		}

		if($user->isFriend($row['username'])) {
			echo "<div class='resultDisplay' style='padding: 5px 5px 0 5px;	height: 60px; border-bottom: 1px solid #D3D3D3;'>
					<a href='messages.php?u=" . $row['username'] . "' style='color: #000; float: none;'>
						<div class='liveSearchProfilePic'>
							<img src='". $row['profile_pic'] . "' style='height: 50px; border-radius: 25px; margin: 1px 12px 0 2px; float: left;'>
						</div>

						<div class='liveSearchText' style='background-color: transparent; color: #1E96CA;'>
							".$row['first_name'] . " " . $row['last_name']. "
							<p style='margin: 0;margin-left: 10px;	font-size: 12px;'>". $row['username'] . "</p>
							<p id='grey'>".$mutual_friends . "</p>
						</div>
					</a>
				</div>";


		}


	}
}

?>