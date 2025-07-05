<?php


require_once 'login_verify.php';
require_once 'config/database.php';
require_once 'model/User.php';

$db = new Database();
$conn = $db->connect();
$user = new User($conn);
$currentUser = $user->getById($_SESSION['user_id']); // fetch current data
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>JUST DO IT</title>

</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width:100%;">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">PlanPal</a>
    
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
            <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li><a class="dropdown-item" href="#">Profile</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>
    <div id="header">
        <div class="flexrow-container">
            <div class="standard-theme theme-selector"></div>
            <div class="light-theme theme-selector"></div>
            <div class="darker-theme theme-selector"></div>
        </div>
        <h1>Just do it.<div id="border"></div>
        </h1>
    </div><b></b>
 <!-- #region -->
<div class="container mt-5">
  <h3>Edit Profile</h3>

  <form id="profile-form">
    <input type="hidden" name="id" value="<?= $currentUser->id ?>">

    <div class="mb-3">
      <label for="name" class="form-label">Full Name</label>
      <input type="text" name="name" class="form-control" id="name" value="<?= htmlspecialchars($currentUser->name) ?>" >
    </div>

    <div class="mb-3">
      <label for="email" class="form-label">Email</label>
      <input type="email" name="email" class="form-control" id="email" value="<?= htmlspecialchars($currentUser->email) ?>" >
    </div>

    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <input type="text" name="username" class="form-control" id="username" value="<?= htmlspecialchars($currentUser->username) ?>" >
    </div>

    <div class="mb-3">
      <label for="password" class="form-label">New Password</label>
      <input type="password" name="password" class="form-control" id="password">
    </div>

    <div class="mb-3">
      <label for="confirm_password" class="form-label">Confirm Password</label>
      <input type="password" name="confirm_password" class="form-control" id="confirm_password">
    </div>

    <div id="profile_msg" class="mb-3"></div>
   <button class="todo-btn" type="submit" id="save-profile">I Got This!</button>
    <!-- <button type="submit" id="save-profile" class="btn btn-primary">Update Profile</button> -->
  </form>
</div>

    <!-- div for top left corner -->

    
    <script src="JS/main.js" type="text/javascript"> </script>
    <script src="js/jquery.min.js" type="text/javascript"> </script>
    <script src="js/jquery.validate.js" type="text/javascript"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<style>
.validate-has-error {
    color: red;
    font-size: 0.875em;
    margin-top: 0.25rem;
    display: block;
}
</style>
   <script>
jQuery(document).ready(function ($) {
    // Store original values
    const originalValues = {
        name: $('#name').val(),
        email: $('#email').val(),
        username: $('#username').val()
    };

    $('#profile-form').validate({
        errorElement: 'span',
        errorClass: 'validate-has-error',
        errorPlacement: function(error, element) {
            error.insertAfter(element);
        },
        rules: {
            name: { 
                required: true, 
                minlength: 2 
            },
            email: {
                required: true,
                email: true,
                remote: {
                    url: "ajax/tododata.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        action: "checkEmail",
                        email: function() {
                            // Only validate if email changed
                            const currentEmail = $('#email').val();
                            return currentEmail !== originalValues.email ? currentEmail : "no-change";
                        },
                        current_id: function() {
                            return $('[name="id"]').val();
                        }
                    }
                }
            },
            username: {
                required: true,
                minlength: 2,
                remote: {
                    url: "ajax/tododata.php",
                    type: "post",
                    dataType: 'json',
                    data: {
                        action: "checkUsername",
                        username: function() {
                            // Only validate if username changed
                            const currentUsername = $('#username').val();
                            return currentUsername !== originalValues.username ? currentUsername : "no-change";
                        },
                        current_id: function() {
                            return $('[name="id"]').val();
                        }
                    }
                }
            },
            password: {
                minlength: 4
            },
            confirm_password: {
                equalTo: '[name="password"]'
            }
        },
        messages: {
            email: { 
                remote: "Email already in use",
                required: "Email is required"
            },
            username: { 
                remote: "Username already taken",
                required: "Username is required"
            },
            confirm_password: { 
                equalTo: "Passwords do not match" 
            }
        },
        onkeyup: function(element) {
            // Only validate on keyup if the field has changed
            if ($(element).val() !== originalValues[element.name]) {
                $(element).valid();
            }
        },
        submitHandler: function(form) {
            // Your existing submit handler code
            // ...
        }
    });

    // Add custom method to skip validation for unchanged fields
    $.validator.addMethod("skipIfUnchanged", function(value, element) {
        const fieldName = $(element).attr('name');
        return value === originalValues[fieldName] || this.optional(element);
    }, "");

    // Update validation when fields change
    $('#profile-form input').on('change', function() {
        $(this).valid();
    });
});
   </script>
</body>

</html>