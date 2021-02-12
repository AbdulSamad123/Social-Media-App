<?php
require 'config/config.php';
require 'includes/form_handlers/register_handler.php';
require 'includes/form_handlers/login_handler.php';

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
      <input type="email" name="log_email" placeholder="Email Address" value="<?php 
       if(isset($_SESSION['log_email']))
       {
          echo $_SESSION['log_email'];
       }
       ?>" required><br />
      <input type="password" name="log_password" placeholder="Password"><br />
      <input type="submit" name="login_button" value="Login"><br><br>  
      <?php if(in_array("Email or Password was incorrect<br>", $error_array)) echo "Email or Password was incorrect<br>"; ?>
    </form>

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