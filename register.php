<?php
session_start();
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
$error_array=array(); //Holds the error Message

if(isset($_POST['register_button']))
{
    //Registration Form values

    //First Name
    $fname= strip_tags($_POST['reg_fname']); //Remove html code
    $fname= str_replace(' ','',$fname); //Remove spaces
    $fname= ucfirst(strtolower($fname)); //UpperCase First Letter
    $_SESSION['reg_fname'] = $fname; //Stores first name into session variable

    //Last Name
    $lname= strip_tags($_POST['reg_lname']); //Remove html code
    $lname= str_replace(' ','',$lname); //Remove spaces
    $lname= ucfirst(strtolower($lname)); //UpperCase First Letter
    $_SESSION['reg_lname'] = $lname; //Stores last name into session variable

    //Email
    $em= strip_tags($_POST['reg_email']); //Remove html code
    $em= str_replace(' ','',$em); //Remove spaces
    $em= ucfirst(strtolower($em)); //UpperCase First Letter
    $_SESSION['reg_emai'] = $em; //Stores email into session variable

    //Confirm Email
    $em2= strip_tags($_POST['reg_email2']); //Remove html code
    $em2= str_replace(' ','',$em2); //Remove spaces
    $em2= ucfirst(strtolower($em2)); //UpperCase First Letter
    $_SESSION['reg_emai2'] = $em2; //Stores email into session variable

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

            //Check if email already Exits
            $e_check = mysqli_query($con,"Select email from users where email='$em'");

            //Count the number of rows Returend
            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0)
            {
                array_push($error_array, "Email already in use<br>");
            }
        }
        else
        {
            array_push($error_array, "Invalid email format<br>");
        } 


    }
    else
    {
        array_push($error_array, "Email don't Match<br>");
    }

    if(strlen($fname) > 25 || strlen($fname) < 2)
    {
        array_push($error_array, "Your first name must be between 2 to 25 charaters<br>");
    }
    
    if(strlen($lname) > 25 || strlen($lname) < 2)
    {
        array_push($error_array, "Your last name must be between 2 to 25 charaters<br>");
    }

    if($password != $password2)
    {
        array_push($error_array, "Your Password donot Match<br>");
    } 
    else
    {
        if(preg_match('/[^A-Za-z0-9]/',$password))
        {
            array_push($error_array, "Your password can only contain english characters or numbers<br>");
        }
    }

    
    if(strlen($password) > 30 || strlen($password) < 5)
    {
        array_push($error_array, "Your password must be between 5 to 30 charaters<br>");
    }

    if(empty($error_array))
    {
        $password = md5($password);//Encryped password before sending to database

        //Generating username by concatenating first and last name 
        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "Select username from user where username='$username'");

        $i=0;
        //if username exists add number to username
        while(mysqli_num_rows($check_username_query) != 0)
        {
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con,"Select username from user where username='$username'");
        }

        //Profile picture assignment
        $rand = rand(1,2); //Random number between 1 and 2

        if($rand == 1)
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        else if($rand == 2)
            $profile_pic = "assets/images/profile_pics/defaults/head_green_sea.png"; 
            
        $query = mysqli_query($con, "insert into users values ('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");    
   
        array_push($error_array, "<span style='color: #14C800;'>You'ar all set! Goahead and login!</span><br>");

        //Clear session variables
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
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
    <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
    if(isset($_SESSION['reg_fname']))
    {
        echo $_SESSION['reg_fname'];
    }
    ?>" required>
    <br>
    <?php if(in_array("Your first name must be between 2 to 25 charaters<br>", $error_array)) echo "Your first name must be between 2 to 25 charaters<br>"; ?>
    <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
    if(isset($_SESSION['reg_lname']))
    {
        echo $_SESSION['reg_lname'];
    }
    ?>" required>
    <br>
    <?php if(in_array("Your last name must be between 2 to 25 charaters<br>", $error_array)) echo "Your last name must be between 2 to 25 charaters<br>"; ?>
    <input type="email" name="reg_email" placeholder="Email" value="<?php 
    if(isset($_SESSION['reg_email']))
    {
        echo $_SESSION['reg_email'];
    }
    ?>" required>
    <br>
    <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php 
    if(isset($_SESSION['reg_email2']))
    {
        echo $_SESSION['reg_email2'];
    }
    ?>" required>
    <br>
    <?php if(in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>"; 
    else if(in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
    else if(in_array("Email don't Match<br>", $error_array)) echo "Email don't Match<br>"; ?>
    <input type="password" name="reg_password" placeholder="Password" required>
    <br>
    <input type="password" name="reg_password2" placeholder="Confirm Password" required>
    <br>
    <?php if(in_array("Your Password donot Match<br>", $error_array)) echo "Your Password donot Match<br>"; 
    else if(in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
    else if(in_array("Your password must be between 5 to 30 charaters<br>", $error_array)) echo "Your password must be between 5 to 30 charaters<br>"; ?>
    <input type="submit" name="register_button" value="Register" required>
    <br>
    <?php if(in_array("<span style='color: #14C800;'>You'ar all set! Goahead and login!</span><br>", $error_array)) echo "<span style='color: #14C800;'>You'ar all set! Goahead and login!</span><br>"; ?>
    </form>
</body>
</html>