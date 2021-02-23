<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/post.php");

$limit = 10; //Number of posts to be loaded per call

$posts = new Post($con, $_REQUEST['userLoggedIn']);
$posts->loadPostsfriends($_REQUEST, $limit);
?>