<?php 
    require('config/config.php');
    require('includes/handlers/register_handler.php');
    require('includes/handlers/login_handler.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
    integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="http://parsleyjs.org/dist/parsley.js"></script>
    <title>Registration</title>
</head>
<body>
    <div class="container">
        <form id="validate_login_form" action="register.php" method="POST">
            <div class="row">
                <div class="col-s-6">
                    <div class="form-group">
                        <label>Email</label>
                        <input class="form-control" id="login_email" type="email" name="log_email" placeholder="Email Address" value="<?php 
                            if (isset($_SESSION['log_email'])) {
                                echo $_SESSION['log_email'];
                            }
                            ?>"
                        required data-parsley-type="email" data-parsley-trigger="keyup">
                    </div>
                </div>
            </div> 
            <div class="row">
                <div class="col-s-6">
                    <div class="form-group">
                        <label>Password</label>
                        <input class="form-control" id="login_password " type="password" name="log_password" placeholder="Password" 
                        required data-parsley-length="[6,16]" data-parsley-trigger="keyup">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-s-6">
                    <div class="form-group">
                        <button class="form-control btn btn-primary" id="login_button" type="submit" name="login_button" value="Login">Login</button>
                    </div>
                </div>
            </div>
            <?php if (in_array("Email or Password is incorrect<br>", $error_array)) echo "Email or Password is incorrect<br>"; ?>
            <?php if (in_array("Please fill all the fields<br>", $error_array)) echo "Please fill all the fields<br>"; ?>
        </form>
    </div>
    <div class="container">
    <form class="form-group" id="validate_register_form" action="register.php" method="POST">
        <input class="form-control" type="text" name="reg_fname" placeholder="First Name" value="<?php 
            if (isset($_SESSION['reg_fname'])) {
                echo $_SESSION['reg_fname'];
            }
        ?>">
        <br>
        <?php if (in_array("Your first name must be between 2 and 25 characters<br>", $error_array)) echo "Your first name must be between 2 and 25 characters<br>"; ?>

        <input class="form-control" type="text" name="reg_lname" placeholder="Last Name" value="<?php 
            if (isset($_SESSION['reg_lname'])) {
                echo $_SESSION['reg_lname'];
            }
        ?>">
        <br>
        <?php if (in_array("Your last name must be between 2 and 25 characters<br>", $error_array)) echo "Your last name must be between 2 and 25 characters<br>"; ?>

        <input class="form-control" type="text" name="reg_email" placeholder="Email" value="<?php 
            if (isset($_SESSION['reg_email'])) {
                echo $_SESSION['reg_email'];
            }
        ?>">
        <br>
        <input class="form-control" type="text" name="reg_email2" placeholder="Confirm email" value="<?php 
            if (isset($_SESSION['reg_email2'])) {
                echo $_SESSION['reg_email2'];
            }
        ?>">
        <br>
        <?php 
            if (in_array("Email already in use<br>", $error_array)) echo "Email already in use<br>";
            else if (in_array("Invalid email format<br>", $error_array)) echo "Invalid email format<br>";
            else if (in_array("Emails dont match<br>", $error_array)) echo "Emails dont match<br>" 
        ?>

        <input class="form-control" type="password" name="reg_password" placeholder="Password">
        <br>
        <input class="form-control" type="password" name="reg_password2" placeholder="Confirm password">
        <br>
        <?php 
            if (in_array("Your password do not match<br>", $error_array)) echo "Your password do not match<br>";
            else if (in_array("Your password can only contain english characters or numbers<br>", $error_array)) echo "Your password can only contain english characters or numbers<br>";
            else if (in_array("Your password must be between 5 and 30 characters<br>", $error_array)) echo "Your password must be between 5 and 30 characters<br>" 
        ?>

        <button class="btn btn-primary" type="submit" name="register_button" value="Register">Register</button>
        <br>
        <?php if (in_array("<span style='color: #14C800;'>You're all set to go ahead and login!</span> <br>", $error_array)) echo "<span style='color: #14C800;'>You're all set to go ahead and login!</span> <br>"; ?>

    </form>
    </div>
</body>
</html>
<script>
$(document).ready(function(){
    $('#validate_login_form').parsley();
    $('#validate_login_form').on('submit', function(event){
        event.preventDefault();
        if ($('#validate_login_form').parsley().isValid()){
            $.ajax({
                url: "register.php",
                method: "POST",
                data: $(this).serialize(),
                beforeSend: function(){
                    $('#login_button').attr('disabled', 'disabled');
                    $('#login_button').val('Submitting...');
                },
                success: function(data){
                    $('#validate_login_form')[0].reset();
                    $('#validate_login_form').parsley().reset();
                    $('#login_button').attr('disabled', false);
                    $('#login_button').val('Login');
                    alert(data);
                }
            });
        }
    });
})
</script>