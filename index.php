<?php
$con = mysqli_connect("localhost","root","","social");

if(mysqli_connect_errno())
{
    echo "Failed to Connect : " . mysqli_connect_errno();
}

$query = mysqli_query($con,"Insert into test values ('', 'Ali')");


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media</title>
</head>
<body>
    <h1>Hello Woirld</h1>
</body>
</html>