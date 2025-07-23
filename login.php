<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
session_start();

$db = new Database();
$conn = $db->connect();
$todo = new Todo($conn);

$todos = $todo->getAll_done();
$tododones = $todo->getAll_active();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#062e3f">
    <meta name="description" content="A dynamic and aesthetic To-Do List WebApp.">
    <title>PlanPal | Login</title>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" crossorigin="anonymous" />
    <!-- Styles -->
    <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/corner.css">
</head>
<body>

<div id="header">
    <div class="flexrow-container">
        <div class="standard-theme theme-selector"></div>
        <div class="light-theme theme-selector"></div>
        <div class="darker-theme theme-selector"></div>
    </div>
    <h1>Login<div id="border"></div></h1>
</div>

<div id="form">
    <!-- Login Form -->
    <form id="login-form" style="margin-top: 20px;">
        <div class="row">
            <div class="col-12">
                <input class="todo-input" type="text" placeholder="Username" name="username">
            </div>
            <div class="col-12">
                <input class="todo-input" type="password" placeholder="Password" name="password">
            </div>
            <div id="login_msg"></div>
            <div class="col-12">
                <button class="todo-btn" type="submit" id="login-submit">Login</button>
            </div>
        </div>
    </form>
</div>

<!-- Scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="JS/main.js"></script>

<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery('#login-form').validate({
        errorElement: 'span',
        errorClass: 'validate-has-error',
        rules: {
            username: { required: true, minlength: 2 },
            password: { required: true, minlength: 2 }
        },
        messages: {
            username: {
                required: "Username is required.",
                minlength: "At least 2 characters."
            },
            password: {
                required: "Password is required.",
                minlength: "At least 2 characters."
            }
        },
        submitHandler: function (form) {
            const Frmval = jQuery(form).serialize();
            jQuery("#login-submit").attr("disabled", true).html('Logging in...');

            jQuery.ajax({
                type: "POST",
                dataType: "json",
                url: "ajax/tododata.php",
                data: "action=login&" + Frmval,
                success: function (response) {
                    jQuery("#login-submit").removeAttr("disabled").html('Login');

                    if (response.status === "success") {
                        jQuery('#login_msg').html(response.message).addClass('alert alert-success').fadeOut(3000, function () {
                            window.location.href = "index.php";
                        });
                    } else {
                        jQuery('#login_msg').html(response.message).addClass('alert alert-danger').show().fadeOut(4000);
                    }
                },
                error: function () {
                    jQuery("#login-submit").removeAttr("disabled").html('Login');
                    jQuery('#login_msg').html("Something went wrong.").addClass('alert alert-danger').show().fadeOut(4000);
                }
            });

            return false;
        }
    });
});
</script>
</body>
</html>
