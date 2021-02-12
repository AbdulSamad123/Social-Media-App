<?php
ob_start(); //Turn on  Output Buffering
session_start();

$timezone = date_default_timezone_set("Asia/Karachi");

$con = mysqli_connect("localhost","root","","social"); //Connection Variable

if(mysqli_connect_errno())
{
    echo "Failed to Connect : " . mysqli_connect_errno();
}

?>