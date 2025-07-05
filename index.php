<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
require_once 'login_verify.php';
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
    <div id="form">
        <form id="todo-form">
            <div id="result_msg"></div>
            <input type="hidden" name="todo_id" id="todo_id" value="">
            <input class="todo-input" type="text" placeholder="Add a task." name="title">
            <button class="todo-btn" type="submit" id="submit">I Got This!</button>
        </form>
    </div>

    <!-- div for top left corner -->

    <p><span id="datetime"></span></p>
    <script src="JS/time.js"></script>
    </div>
    <h2>on progress</h2>
  <div id="myUnOrdList">
    <ul class="todo-list">
        <?php 
        if(!empty($todos)){
        foreach ($todos as $task): ?>
            <div class="todo standard-todo" data-id="<?= htmlspecialchars($task->id) ?>">
            <button class="edit-btn standard-button"><i class="fas fa-edit"></i></button>    
            <li class="todo-item" style="color:aliceblue;"><?= htmlspecialchars($task->title) ?></li>
                <button class="check-btn standard-button"><i class="fas fa-check"></i></button>
                <button class="delete-btn standard-button"><i class="fas fa-trash"></i></button>
            </div>
        <?php 
    endforeach; 
    }
    else{
        echo '<div class="todo standard-todo"><li class="todo-item" style="color:aliceblue;">No tasks available.</li></div>';
    }
    
    ?>
    </ul>
</div>
<h2>done</h2>
<div id="myUnOrdList">
    <ul class="todo-list">
        <?php 
        if(!empty($tododones)){
        foreach ($tododones as $taskdone): ?>
            <div class="todo standard-todo" data-id="<?= htmlspecialchars($taskdone->id) ?>">
                <button class="edit-btn standard-button"><i class="fas fa-edit"></i></button>
                <li class="todo-item" style="color:aliceblue;"><?= htmlspecialchars($taskdone->title) ?></li>
                <button class="check-btn standard-button"><i class="fas fa-check"></i></button>
                <button class="delete-btn standard-button"><i class="fas fa-trash"></i></button>
            </div>
        <?php endforeach;
        }
        else{
            echo '<div class="todo standard-todo"><li class="todo-item" style="color:aliceblue;">No tasks available.</li></div>';
        } 
        ?>
    </ul>
</div>
    <script src="JS/main.js" type="text/javascript"> </script>
    <script src="js/jquery.min.js" type="text/javascript"> </script>
    <script src="js/jquery.validate.js" type="text/javascript"> </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
    

jQuery(document).ready(function () {
   
    jQuery('#todo-form').validate({
        errorElement: 'span',
        errorClass: 'validate-has-error',
        rules: {
            title: {required: true, minlength: 2}
        },
        messages: {
            name: {required: "This field is required.", minlength: "Your name must consist of at least 2 characters"}
        },
        submitHandler: function (form) {
            var Frmval = jQuery("form#todo-form").serialize();
            jQuery("button#submit").attr("disabled", "true").html('adding...');
             var todoId = jQuery('#todo_id').val();
var actionType = todoId ? 'update' : 'add';
            jQuery.ajax({
                type: "POST",
                dataType: "JSON", 
                url: "ajax/tododata.php",
               data: "action=" + actionType + "&" + Frmval,
                success: function (data) {
                    var msg = eval(data);
                    jQuery("button#submit").removeAttr("disabled").html('I Got This!');
                    jQuery('div#result_msg').html(msg.message).css('display', 'block').addClass('alert alert-success').fadeOut(3000, function () {
        location.reload(); // reload page after fadeOut completes
    });
                }
            });
            return false;
        }
    });
});
jQuery(document).on('click', '.edit-btn', function () {
    var $todoDiv = jQuery(this).closest('.todo');
    var todoId = $todoDiv.data('id');
    var todoText = $todoDiv.find('.todo-item').text().trim();

    // Set form input values
    jQuery('#todo_id').val(todoId);
    jQuery('input[name="title"]').val(todoText);

    // Optionally change the submit button label
    jQuery('#submit').text('Update Task');
});

    jQuery(document).on('click', '.delete-btn', function () {
        var todoId = jQuery(this).closest('.todo').data('id');
        if (confirm('Are you sure you want to delete this task?')) {
            jQuery.ajax({
                type: "POST",
                url: "ajax/tododata.php",
                data: {action: 'delete', id: todoId},
                success: function (response) {
                    if (response.status === 'success') {
                        jQuery('.todo[data-id="' + todoId + '"]').remove().fadeOut(3000, function () {
        location.reload(); // reload page after fadeOut completes
    });
                    } else {
                        alert(response.message);
                    }
                },
                dataType: 'json'
            });
        }
    });

    jQuery(document).on('click', '.check-btn', function () {
        var todoId = jQuery(this).closest('.todo').data('id');
        jQuery.ajax({
            type: "POST",
            url: "ajax/tododata.php",
            data: {action: 'updateStatus', id: todoId, status: 1},
            success: function (response) {
                if (response.status === 'success') {
                    jQuery('.todo[data-id="' + todoId + '"]').addClass('completed').fadeOut(3000, function () {
        location.reload(); // reload page after fadeOut completes
    });
                } else {
                    alert(response.message);
                }
            },
            dataType: 'json'
        });
    });
</script>
</body>

</html>