<?php
$con = mysqli_connect("localhost","root","","social"); //Connection Variable

if(mysqli_connect_errno())
{
    echo "Failed to Connect : " . mysqli_connect_errno();
}

//Declaring the variables
$fname=""; //First Name
$lname=""; //Last Name
$em=""; //Email
$em2=""; //Confirm Email
$password=""; //Password
$password2=""; //Confirm Password
$date= ""; //Registartion Date
$error_array=""; //Holds the error Message

if(isset($_POST['register_button']))
{
    //Registration Form values

    //First Name
    $fname= strip_tags($_POST['reg_fname']); //Remove html code
    $fname= str_replace(' ','',$fname); //Remove spaces
    $fname= ucfirst(strtolower($fname)); //UpperCase First Letter

    //Last Name
    $lname= strip_tags($_POST['reg_lname']); //Remove html code
    $lname= str_replace(' ','',$lname); //Remove spaces
    $lname= ucfirst(strtolower($lname)); //UpperCase First Letter

    //Email
    $em= strip_tags($_POST['reg_email']); //Remove html code
    $em= str_replace(' ','',$em); //Remove spaces
    $em= ucfirst(strtolower($em)); //UpperCase First Letter

    //Confirm Email
    $em2= strip_tags($_POST['reg_email2']); //Remove html code
    $em2= str_replace(' ','',$em2); //Remove spaces
    $em2= ucfirst(strtolower($em2)); //UpperCase First Letter

    //Email
    $password= strip_tags($_POST['reg_password']); //Remove html code
    $password2= strip_tags($_POST['reg_password2']); //Remove html code

    $date= date("Y-m-d"); //Current date

    if($em == $em2)
    {
        //Check email if in valid format
        if(filter_var($em,FILTER_VALIDATE_EMAIL))
        {
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);
        }
        else
        {
            echo "Invalid Format";
        } 


    }
    else
    {
        echo "Email don't Match";
    }

}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Social Media App</title>
</head>
<body>
    <form action="register.php" method="post">
    <input type="text" name="reg_fname" placeholder="First Name" required>
    <br>
    <input type="text" name="reg_lname" placeholder="Last Name" required>
    <br>
    <input type="email" name="reg_email" placeholder="Email" required>
    <br>
    <input type="email" name="reg_email2" placeholder="Confirm Email" required>
    <br>
    <input type="password" name="reg_password" placeholder="Password" required>
    <br>
    <input type="password" name="reg_password2" placeholder="Confirm Password" required>
    <br>
    <input type="submit" name="register_button" value="Register" required>
    <br>
    </form>
</body>
</html>