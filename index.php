<?php
require_once 'config/database.php';
require_once 'model/tododata.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$todo = new Todo($conn);
$userid = $_SESSION['user_id'];
$todos = $todo->getAll_done($userid);
$tododones = $todo->getAll_active($userid);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#E4C3F5" />
  <meta name="description" content="A dynamic and aesthetic To-Do List WebApp." />

  <!-- Fonts and Icons -->
  <link href="https://fonts.googleapis.com/css2?family=Caveat&family=Work+Sans:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />
  <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />

  <!-- Custom CSS -->
  <link rel="stylesheet" href="CSS/main.css">
  <link rel="stylesheet" href="CSS/corner.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      font-family: 'Work Sans', sans-serif;
      background-color: #f8f2fc;
      color: #333;
    }

    .navbar {
      background: linear-gradient(90deg, #d6a4f0, #fbc2eb);
    }

    .navbar-brand {
      font-family: 'Caveat', cursive;
      font-size: 2rem;
      color: #fff;
    }

    .dropdown-menu {
      background-color: #fff0fc;
      border: 1px solidrgb(221, 195, 243);
    }

    .dropdown-item {
      color:rgb(218, 190, 241);
    }

    .dropdown-item:hover {
      background-color: #f1dafc;
      color:rgb(219, 186, 255);
    }

    
  </style>

  <title>PlanPal</title>
</head>

<body>

  <nav class="navbar navbar-expand-lg" style="width: 100%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PlanPal</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
              <li><a class="dropdown-item" href="index.php">📝 Todo List</a></li>
              <li><a class="dropdown-item" href="notes.php">📒 Notes</a></li>
              <li><a class="dropdown-item" href="calendar.php">📅 Calendar</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="profile.php">👤 Profile</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item" href="logout.php">🚪 Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

<body>
 <!-- Header Section -->
<div style="padding: 10px 20px; background-color: #fff;">
  <h2 style="color: #333333; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin-bottom: 0;" id="greeting">
    <!-- Greeting inserted here -->
  </h2>
  <p style="color: #555555; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; font-size: 1rem;" id="dateOnly">
    <!-- Date inserted here -->
  </p>
</div>

<script>
  const userName = "<?php echo htmlspecialchars($_SESSION['username'] ?? 'User'); ?>";

  function getGreeting() {
    const hour = new Date().getHours();
    if (hour < 12) return "Good morning";
    if (hour < 17) return "Good afternoon";
    return "Good evening";
  }

  function updateHeader() {
    const now = new Date();
    const weekdayFull = now.toLocaleDateString('en-GB', { weekday: 'short' }); // e.g. "Thu"
    const day = now.getDate();
    const month = now.toLocaleDateString('en-GB', { month: 'long' });
    const year = now.getFullYear();

    const weekdayDisplay = (weekdayFull === "Thu") ? "Thurs" : weekdayFull;

    const finalDateStr = `${weekdayDisplay} ${day} ${month} ${year}`;

    document.getElementById('greeting').textContent = `${getGreeting()}, ${userName} 👋`;
    document.getElementById('dateOnly').textContent = `Today, ${finalDateStr}`;
  }

  updateHeader();
  setInterval(updateHeader, 60000);
</script>

<div class="container mt-4">
  <!-- Search Bar -->
  <div class="d-flex align-items-center justify-content-start gap-2 mb-3">
  <!-- Category Filter Dropdown -->
  <select id="categoryFilter" class="form-select w-auto" onchange="filterTasks(this.value)">
    <option value="All">All</option>
    <option value="Personal">Personal</option>
    <option value="Work">Work</option>
    <option value="Study">Study</option>
    <option value="Fitness">Fitness</option>
    <option value="Others">Others</option>
  </select>

  <!-- Existing Search Bar -->
  <input type="text" id="searchInput" class="form-control" placeholder="Search tasks..." onkeyup="searchTasks()">
</div>


  <!-- Task List -->
<ul class="list-group" id="taskList">
  <!-- Sample task -->
  <li class="list-group-item d-flex align-items-center justify-content-between">
    <div class="form-check">
      <input class="form-check-input task-check" type="checkbox" id="task1">
      <input type="text" class="form-control-plaintext ms-2 task-text" value="Finish the UI update" readonly>
    </div>
    <button class="btn btn-primary px-3 py-2" onclick="editTask('task1')">Edit</button> 
  </li>

  <li class="list-group-item d-flex align-items-center justify-content-between">
    <div class="form-check">
      <input class="form-check-input task-check" type="checkbox" id="task2">
      <input type="text" class="form-control-plaintext ms-2 task-text" value="Check calendar logic" readonly>
    </div>
    <button class="btn btn-primary px-3 py-2" onclick="editTask('task2')">Edit</button> 
  </li>
</ul>



  <!-- Floating Form (Hidden by Default) -->
  <div id="task-form-box" class="task-form-box">
    <h3>Create New Task</h3>
    <input type="text" id="taskInput" placeholder="Add your new task!" required>
    <select id="taskType">
      <option value="Personal">Personal</option>
        <option value="Work">Work</option>
        <option value="Study">Study</option>
        <option value="Fitness">Fitness</option>
        <option value="Others">Others</option>
      </select>
    <input type="time" id="taskTime" required>
    <button onclick="addTask()">Add Task</button>
  </div>

  <!-- Floating Button -->
  <button class="floating-btn" onclick="toggleForm()">Create a Task +</button>

  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: #fdf6ff;
    }

    #header {
      background:rgb(207, 175, 245);
      color: white;
      padding: 20px;
      text-align: center;
    }

    .floating-btn {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #7c3aed;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 5px 12px rgba(0,0,0,0.2);
    }

    .task-form-box {
      position: fixed;
      bottom: 90px;
      left: 50%;
      transform: translateX(-50%);
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
      padding: 20px;
      width: 90%;
      max-width: 350px;
      display: none;
      z-index: 1000;
    }

    .task-form-box input,
    .task-form-box select,
    .task-form-box button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .task-form-box button {
      background-color: #7c3aed;
      color: white;
      border: none;
      cursor: pointer;
    }

    .task-list {
      padding: 20px;
    }

    .task-item {
      background: white;
      margin-bottom: 10px;
      padding: 15px;
      border-left: 5px solid #7c3aed;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }

    .task-item small {
      color: gray;
    }
  </style>

  <script>
    function filterTasks(category) {
  const tasks = document.querySelectorAll("#taskList li");

  tasks.forEach(task => {
    const taskCategory = task.getAttribute("data-category") || "";
    const matches = category === "All" || taskCategory.toLowerCase() === category.toLowerCase();
    task.style.display = matches ? "flex" : "none";
  });
}

    function toggleForm() {
      const formBox = document.getElementById("task-form-box");
      formBox.style.display = formBox.style.display === "block" ? "none" : "block";
    }

    let currentTaskId = null;

function addTask() {
  const input = document.getElementById("taskInput").value.trim();
  const type = document.getElementById("taskType").value;
  const time = document.getElementById("taskTime").value;

  if (!input || !time) {
    alert("Please enter task and time.");
    return;
  }

  if (currentTaskId) {
    // Update existing task
    // Make an AJAX call to update the task in the database
    $.ajax({
      url: "ajax/tododata.php?action=update",
      type: "POST",
      data: {
        id: currentTaskId,
        title: input,
        type: type,
        time: time
      },
      success: function (response) {
        alert("Task updated!");
        location.reload(); // reload to see updated task
      },
      error: function () {
        alert("Something went wrong while updating the task.");
      }
    });
  } else {
    // Add new task
    $.ajax({
      url: "ajax/tododata.php?action=add",
      type: "POST",
      data: {
        title: input,
        type: type,
        time: time
      },
      success: function (response) {
        alert("Task added!");
        location.reload(); // reload to see new task
      },
      error: function () {
        alert("Something went wrong while adding the task.");
      }
    });
  }

  // Clear and hide form
  document.getElementById("taskInput").value = "";
  document.getElementById("taskTime").value = "";
  currentTaskId = null; // Reset the current task ID
  toggleForm();
}
function editTask(taskId) {
  const taskText = document.querySelector(`#${taskId} .task-text`);
  const taskInput = document.getElementById("taskInput");
  const taskType = document.getElementById("taskType");
  const taskTime = document.getElementById("taskTime");

  // Populate the form with the current task details
  taskInput.value = taskText.value;
  // Assuming you have a way to determine the type and time, set them accordingly
  // For example, if you have a data attribute for type and time
  taskType.value = "Personal"; // Set this based on your logic
  taskTime.value = "12:00"; // Set this based on your logic

  // Show the form
  toggleForm();
}
  </script>
  
  <script>
    function toggleForm() {
  const formBox = document.getElementById("task-form-box");
  formBox.style.display = (formBox.style.display === "block") ? "none" : "block";
}

success: function (response) {
  // Assume response contains the new task ID from the server
  const taskList = document.getElementById("taskList");
  const taskId = response.id || `task-${Date.now()}`;

  const li = document.createElement("li");
  li.className = "list-group-item d-flex align-items-center justify-content-between flex-wrap";

  const content = document.createElement("div");
  content.className = "form-check";

  const checkbox = document.createElement("input");
  checkbox.type = "checkbox";
  checkbox.className = "form-check-input task-check";
  checkbox.id = taskId;

  const input = document.createElement("input");
  input.type = "text";
  input.className = "form-control-plaintext ms-2 task-text";
  input.value = `${inputText} (${type}, ${time})`;
  input.readOnly = true;

  const button = document.createElement("button");
  button.className = "btn btn-primary px-3 py-2";
  button.innerText = "Edit";
  button.onclick = function () {
    editTask(taskId); // You can build logic for editing
  };

  content.appendChild(checkbox);
  content.appendChild(input);
  li.appendChild(content);
  li.appendChild(button);
  taskList.appendChild(li);

  alert("Task added!");
};


function addTask() {
  const title = document.getElementById("taskInput").value;
  const type = document.getElementById("taskType").value;
  const time = document.getElementById("taskTime").value;

  if (!title || !time) {
    alert("Please fill in all fields.");
    return;
  }

  $.ajax({
    url: "ajax/tododata.php?action=add",
    type: "POST",
    data: {
      title: title,
      type: type,
      time: time
    },
    success: function (response) {
      alert("Task added!");
      location.reload(); // reload to see new task
    },
    error: function () {
      alert("Something went wrong while adding the task.");
    }
  });
}

  // Mark task done
  document.querySelectorAll('.task-check').forEach((checkbox) => {
    checkbox.addEventListener('change', function () {
      const taskText = this.closest('.form-check').querySelector('.task-text');
      if (this.checked) {
        taskText.style.textDecoration = "line-through";
        taskText.style.color = "#888";
      } else {
        taskText.style.textDecoration = "none";
        taskText.style.color = "#000";
      }
    });
  });

  function editTask(taskId) {
  const taskText = document.querySelector(`#${taskId} .task-text`);
  const taskInput = document.getElementById("taskInput");
  const taskType = document.getElementById("taskType");
  const taskTime = document.getElementById("taskTime");

  // Populate the form with the current task details
  taskInput.value = taskText.value;
  // Set the current task ID for updating
  currentTaskId = taskId; // Set this to the task ID

  // Show the form
  toggleForm();
}

  // Search filter
  document.getElementById("searchTask").addEventListener("keyup", function () {
    const searchTerm = this.value.toLowerCase();
    const tasks = document.querySelectorAll("#taskList li");

    tasks.forEach(task => {
      const taskText = task.querySelector(".task-text").value.toLowerCase();
      task.style.display = taskText.includes(searchTerm) ? "" : "none";
    });
  });
</script>

</body>


    <p><span id="datetime"></span></p>
    <script src="JS/time.js"></script>
    <script src="js/jquery.min.js"></script>
    <script src="js/jquery.validate.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="JS/todo.js"></script>

    <script type="text/javascript">
        jQuery(document).ready(function() {

            jQuery('#todo-form').validate({
                errorElement: 'span',
                errorClass: 'validate-has-error',
                rules: {
                    title: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    name: {
                        required: "This field is required.",
                        minlength: "Your name must consist of at least 2 characters"
                    }
                },
                submitHandler: function(form) {
                    var Frmval = jQuery("form#todo-form").serialize();
                    jQuery("button#submit").attr("disabled", "true").html('adding...');
                    var todoId = jQuery('#todo_id').val();
                    var actionType = todoId ? 'update' : 'add';
                    jQuery.ajax({
                        type: "POST",
                        dataType: "JSON",
                        url: "ajax/tododata.php",
                        data: "action=" + actionType + "&" + Frmval,
                        success: function(data) {
                            var msg = eval(data);
                            jQuery("button#submit").removeAttr("disabled").html('I Got This!');
                            jQuery('div#result_msg').html(msg.message).css('display', 'block').addClass('alert alert-success').fadeOut(3000, function() {
                                location.reload(); // reload page after fadeOut completes
                            });
                        }
                    });
                    return false;
                }
            });
        });
        jQuery(document).on('click', '.edit-btn', function() {
            var $todoDiv = jQuery(this).closest('.todo');
            var todoId = $todoDiv.data('id');
            var todoText = $todoDiv.find('.todo-item').text().trim();

            // Set form input values
            jQuery('#todo_id').val(todoId);
            jQuery('input[name="title"]').val(todoText);

            // Optionally change the submit button label
            jQuery('#submit').text('Update Task');
        });

        jQuery(document).on('click', '.delete-btn', function() {
            var todoId = jQuery(this).closest('.todo').data('id');
            if (confirm('Are you sure you want to delete this task?')) {
                jQuery.ajax({
                    type: "POST",
                    url: "ajax/tododata.php",
                    data: {
                        action: 'delete',
                        id: todoId
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            jQuery('.todo[data-id="' + todoId + '"]').remove().fadeOut(3000, function() {
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

        jQuery(document).on('click', '.check-btn', function() {
            var todoId = jQuery(this).closest('.todo').data('id');
            jQuery.ajax({
                type: "POST",
                url: "ajax/tododata.php",
                data: {
                    action: 'updateStatus',
                    id: todoId,
                    status: 1
                },
                success: function(response) {
                    if (response.status === 'success') {
                        jQuery('.todo[data-id="' + todoId + '"]').addClass('completed').fadeOut(3000, function() {
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