<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
$db = new Database();
$conn = $db->connect();
$todo = new Todo($conn);
$todos = $todo->getAll_done();
$tododones = $todo->getAll_active();
// print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#062e3f">
    <meta name="Description" content="A dynamic and aesthetic To-Do List WebApp.">

    <!-- Google Font: Quick Sand -->
    <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">

    <!-- font awesome (https://fontawesome.com) for basic icons; source: https://cdnjs.com/libraries/font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css"
        integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

    <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
    <link rel="stylesheet" href="CSS/main.css">
    <link rel="stylesheet" href="CSS/corner.css">
    <title>Login</title>

</head>

<body>
    <div id="header">
        <div class="flexrow-container">
            <div class="standard-theme theme-selector"></div>
            <div class="light-theme theme-selector"></div>
            <div class="darker-theme theme-selector"></div>
        </div>
        <h1>Login<div id="border"></div>
        </h1>
    </div><b></b>
 <!-- #region -->
    <div id="form">
    <!-- Todo Form -->
   

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
            <button class="todo-btn" type="submit" id="login-submit">Login</button>
    </form>
    </div>
</div>




    <script src="JS/main.js" type="text/javascript"> </script>
    <script src="js/jquery.min.js" type="text/javascript"> </script>
    <script src="js/jquery.validate.js" type="text/javascript"> </script>

    <script type="text/javascript">
    

jQuery(document).ready(function () {
   
    jQuery('#login-form').validate({
        errorElement: 'span',
        errorClass: 'validate-has-error',
        rules: {
            username: {required: true, minlength: 2},
            password: {required: true, minlength: 2}
        },
        messages: {
            name: {required: "This field is required.", minlength: "Your name must consist of at least 2 characters"},
            passowrd: {required: "This field is required.", minlength: "Your name must consist of at least 2 characters"}
        },
        submitHandler: function (form) {
            var Frmval = jQuery("form#login-form").serialize();
            jQuery("button#login-submit").attr("disabled", "true").html('adding...');
var actionType = 'login';
            jQuery.ajax({
                type: "POST",
                dataType: "JSON", 
                url: "ajax/tododata.php",
               data: "action=" + actionType + "&" + Frmval,
                success: function (data) {
                    var msg = eval(data);
                    jQuery("button#login-submit").removeAttr("disabled").html('I Got This!');
                    jQuery('div#login_msg').html(msg.message).css('display', 'block').addClass('alert alert-success').fadeOut(3000, function () {
      window.location.href = "index.php";
    });
                }
            });
            return false;
        }
    });
});
</script>
</body>

</html>