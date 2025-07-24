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
    <title>Login | PlanPal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3468/3468371.png" type="image/x-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;800&family=Work+Sans:wght@300;500;900&family=Montserrat:wght@600&display=swap" rel="stylesheet">

    <!-- Styles -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Work Sans', sans-serif;
        }

        .container {
            display: flex;
            height: 100vh;
        }

        .left-panel {
            background-color: #af8eceff;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
        }

        .app-name {
            font-family: 'Playfair Display', serif;
            font-weight: 800;
            font-size: 5rem;
            text-align: center;
            margin-bottom: 30px;
            letter-spacing: 0.15em;
            user-select: none;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.4);
        }

        .left-panel img {
            width: 70%;
            max-width: 400px;
        }

        .right-panel {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            background-color: #fff;
        }

        .right-panel h2 {
            font-size: 2.5rem;
            font-family: 'Montserrat', sans-serif;
            margin-bottom: 10px;
        }

        .right-panel p {
            font-size: 1.1rem;
            color: #333;
            margin-bottom: 30px;
        }

        form {
            width: 100%;
            max-width: 350px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            margin-bottom: 20px;
            border-radius: 25px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }

        .form-group {
            position: relative;
        }

        .form-group .toggle-password {
            position: absolute;
            top: 12px;
            right: 20px;
            cursor: pointer;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #af8eceff;
            color: white;
            border: 2px solid #f7c6c7;
            border-radius: 25px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease, box-shadow 0.3s ease;
        }

        button:hover {
            background-color: #9c7fc6cc;
            box-shadow: 0 0 10px 2px #f7c6c7aa;
        }

        .signup-link {
            text-align: center;
            margin-top: 15px;
            font-size: 0.95rem;
        }

        .signup-link a {
            color: #007bff;
            text-decoration: none;
        }

        .signup-link a:hover {
            text-decoration: underline;
        }

        .elephant {
            width: 180px;
    margin-bottom: 15px;
    animation: bounce 2.5s infinite ease-in-out;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="left-panel">
        <h1 class="app-name">PlanPal</h1>
        <img src="images/girl-todolist.svg" alt="Cute Girl" />
    </div>
    <div class="right-panel">

        <!-- 🐘 Animated Elephant -->
        <img src="https://purpledaisydesign.com/wp-content/uploads/2021/05/N-2584-Cute-Baby-Elephant-colored.jpg" alt="Smiling Elephant" class="elephant" />

        <h2>Hello Lovely!</h2>
        <p>Welcome! Please login</p>
        <form id="login-form">
            <input type="text" name="username" placeholder="Email">
            <div class="form-group">
                <input type="password" name="password" id="password" placeholder="Password">
                <span class="toggle-password" onclick="togglePassword()">👁️</span>
            </div>
            <div id="login_msg"></div>
            <button type="submit" id="login-submit">Login</button>
            <div class="signup-link">
                <span>Don't have an account? </span>
                <a href="register.php">Sign up</a>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script>
function togglePassword() {
    const input = document.getElementById('password');
    input.type = input.type === 'password' ? 'text' : 'password';
}

jQuery(document).ready(function () {
    jQuery('#login-form').validate({
        rules: {
            username: { required: true, minlength: 2 },
            password: { required: true, minlength: 2 }
        },
        messages: {
            username: { required: "Enter username", minlength: "Min 2 characters" },
            password: { required: "Enter password", minlength: "Min 2 characters" }
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
                        jQuery('#login_msg').html(response.message).css("color", "green").fadeOut(3000, function () {
                            window.location.href = "index.php";
                        });
                    } else {
                        jQuery('#login_msg').html(response.message).css("color", "red").fadeOut(4000);
                    }
                },
                error: function () {
                    jQuery("#login-submit").removeAttr("disabled").html('Login');
                    jQuery('#login_msg').html("Something went wrong.").css("color", "red").fadeOut(4000);
                }
            });
            return false;
        }
    });
});
</script>
</body>
</html>
