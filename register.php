<?php
    session_start();
    $con = mysqli_connect("localhost", "root", "", "social_network");
  
    if (mysqli_connect_errno()) {
        echo "Failed to connect: " . mysqli_connect_errno();
    }

    // Declaring variable to pervent errors
    $fname = ""; // first name
    $lname = ""; // last name
    $em = ""; // email
    $em2 = ""; // email 2
    $password = ""; // password
    $password2 = ""; // password2
    $date = ""; // sign up date
    $error_array = array(); // Holds error messages

    if (isset($_POST['register_button'])){

        // Registration form values
        // First name
        $fname = strip_tags($_POST['reg_fname']);
        $fname = str_replace(' ', '', $fname);
        $fname = ucfirst(strtolower($fname));
        $_SESSION['reg_fname'] = $fname;

        // Last name
        $lname = strip_tags($_POST['reg_lname']);
        $lname = str_replace(' ', '', $lname);
        $lname = ucfirst(strtolower($lname));
        $_SESSION['reg_lname'] = $lname;

        // email
        $em = strip_tags($_POST['reg_email']);
        $em = str_replace(' ', '', $em);
        $em = ucfirst(strtolower($em));
        $_SESSION['reg_email'] = $em;

        // email2
        $em2 = strip_tags($_POST['reg_email2']);
        $em2 = str_replace(' ', '', $em2);
        $em2 = ucfirst(strtolower($em2));
        $_SESSION['reg_email2'] = $em2;

        // Password
        $password = strip_tags($_POST['reg_password']);
        $password2 = strip_tags($_POST['reg_password2']);

        $date = date("Y-m-d");

        if ($em == $em2){
            // Check if email is in valid email
            if(filter_var($em, FILTER_VALIDATE_EMAIL)) {

                $em = filter_var($em, FILTER_VALIDATE_EMAIL);

                // Check if email already exists
                $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

                //  Count the number of rows returned
                $num_rows = mysqli_num_rows($e_check);

                if ($num_rows > 0) {
                    array_push($error_array, "Email already in use<br>") ;
                }
            }else {
                array_push($error_array, "Invalid email format<br>") ;
            }
        }else {
            array_push($error_array, "Emails dont match<br>") ;
        }

        if (strlen($fname) > 25 || strlen($fname) < 2) {
            array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
        }

        if (strlen($lname) > 25 || strlen($lname) < 2) {
            array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
        }

        if ($password != $password2) {
            array_push($error_array, "Your password do not match<br>");
        }else {
            if (preg_match('/[^A-Za-z0-9]/', $password)) {
                array_push($error_array, "Your password can only contain english characters or numbers<br>");
            }
        }

        if (empty($error_array)) {
            $password = md5($password);

            $username = strtolower($fname . "_" . $lname);
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

            $i = 0;
            while(mysqli_num_rows($check_username_query) != 0) {
                $i++;
                $username = $username . "_" . $i;
                $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");
            }

            $rand = rand(1,2);

            if ($rand == 1) {
                $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
            }
            else if ($rand == 2) {
                $profile_pic = "assets/images/profile_pics/defaults/head_red.png";
            }

            $query = mysqli_query($con, "INSERT INTO users (first_name, last_name, username, email, password, signup_date, profile_pic, num_posts, num_likes, user_closed, friend_array) VALUES ('$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

            array_push($error_array, "<span style='color: #14C800;'>You're all set to go ahead and login!</span> <br>");

            // clear the session variables
            $_SESSION['reg_fname'] = "";
            $_SESSION['reg_lname'] = "";
            $_SESSION['reg_email'] = "";
            $_SESSION['reg_email2'] = "";

        }
    }

    echo (mysqli_error($con));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration</title>
</head>
<body>
    <form action="register.php" method="POST">
        <input type="text" name="reg_fname" placeholder="First Name" value="<?php 
            if (isset($_SESSION['reg_fname'])) {
                echo $_SESSION['reg_fname'];
            }
        ?>" required>
        <br>
        <?php if (in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>

        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php 
            if (isset($_SESSION['reg_lname'])) {
                echo $_SESSION['reg_lname'];
            }
        ?>" required>
        <br>
        <?php if (in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

        <input type="text" name="reg_email" placeholder="Email" value="<?php 
            if (isset($_SESSION['reg_email'])) {
                echo $_SESSION['reg_email'];
            }
        ?>" required>
        <br>
        <input type="text" name="reg_email2" placeholder="Confirm email" value="<?php 
            if (isset($_SESSION['reg_email2'])) {
                echo $_SESSION['reg_email2'];
            }
        ?>" required>
        <br>
        <?php 
            if (in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
            else if (in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
            else if (in_array("Emails dont match<br>", $error_array)) echo "Emails dont match<br>" 
        ?>

        <input type="password" name="reg_password" placeholder="Password" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm password" required>
        <br>
        <?php 
            if (in_array("Your password do not match<br>", $error_array)) echo "Your password do not match<br>";
            else if (in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
            else if (in_array("Your password must be between 5 and 30 characters<br>", $error_array)) echo "Your password must be between 5 and 30 characters<br>" 
        ?>

        <input type="submit" name="register_button" value="Register">
        <br>
        <?php if (in_array("<span style='color: #14C800;'>You're all set to go ahead and login!</span> <br>", $error_array)) echo "<span style='color: #14C800;'>You're all set to go ahead and login!</span> <br>"; ?>

    </form>
</body>
</html>