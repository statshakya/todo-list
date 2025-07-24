<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
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
  <meta name="Description" content="A dynamic and aesthetic To-Do List WebApp.">

  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css"
    integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />

  <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
  <link rel="stylesheet" href="CSS/main.css">
  <link rel="stylesheet" href="CSS/corner.css">

  <title>Register | PlanPal</title>
</head>

<body>
  <div id="header">
    <div class="flexrow-container">
      <div class="standard-theme theme-selector" title="Default Theme"></div>
      <div class="light-theme theme-selector" title="Light Theme"></div>
      <div class="darker-theme theme-selector" title="Dark Theme"></div>
    </div>
    <h2 class="brand-heading">PlanPal</h2>
    <h1>Register<div id="border"></div></h1>
  </div>

  <div id="form">
    <form id="register-form" method="POST" style="margin-top: 20px;">
      <div class="row">
        <div class="col-12">
          <label for="name">Name</label>
          <input class="todo-input" id="name" type="text" placeholder="Name" name="name">
        </div>
        <div class="col-12">
          <label for="email">Email</label>
          <input class="todo-input" id="email" type="email" placeholder="Email" name="email">
        </div>
        <div class="col-12">
          <label for="username">Username</label>
          <input class="todo-input" id="username" type="text" placeholder="Username" name="username">
        </div>
        <div class="col-12">
          <label for="password">Password</label>
          <input class="todo-input" id="password" type="password" placeholder="Password" name="password">
        </div>
        <div id="register_msg"></div>
        <button class="todo-btn" type="submit" id="register-submit">Register</button>
      </div>
    </form>
  </div>

  <script src="js/jquery.min.js" type="text/javascript"></script>
  <script src="js/jquery.validate.js" type="text/javascript"></script>
  <script src="JS/main.js" type="text/javascript"></script>

  <script type="text/javascript">
    jQuery(document).ready(function () {
      jQuery('#register-form').validate({
        errorElement: 'span',
        errorClass: 'validate-has-error',
        rules: {
          name: { required: true, minlength: 2 },
          email: { required: true, email: true },
          username: { required: true, minlength: 2 },
          password: { required: true, minlength: 4 }
        },
        messages: {
          name: { required: "Required", minlength: "Minimum 2 characters" },
          email: { required: "Required", email: "Enter valid email" },
          username: { required: "Required", minlength: "Minimum 2 characters" },
          password: { required: "Required", minlength: "Minimum 4 characters" }
        },
        submitHandler: function (form) {
          var Frmval = jQuery("#register-form").serialize();
          jQuery("#register-submit").attr("disabled", true).html('Registering...');

          jQuery.ajax({
            type: "POST",
            dataType: "JSON",
            url: "ajax/tododata.php",
            data: "action=register&" + Frmval,
            success: function (data) {
              jQuery("#register-submit").removeAttr("disabled").html('Register');
              jQuery('#register_msg').html(data.message)
                .addClass('alert alert-' + (data.status === 'success' ? 'success' : 'danger'))
                .fadeIn().delay(3000).fadeOut();

              if (data.status === 'success') {
                jQuery("#register-form")[0].reset();
                window.location.href = 'login.php';
              }
            }
          });

          return false;
        }
      });
    });
  </script>
</body>

</html>
