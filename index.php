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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#6a98adff">
  <meta name="Description" content="A dynamic and aesthetic To-Do List WebApp.">

  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />
  <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
  <link rel="stylesheet" href="CSS/main.css">
  <link rel="stylesheet" href="CSS/corner.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <title>PlanPal </title>
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

<div id="header">
  <h1>PlanPal<div id="border"></div></h1>
</div>

<!-- Todo Form -->
<div id="form">
  <form id="todo-form">
    <div id="result_msg"></div>
    <input type="hidden" name="todo_id" id="todo_id" value="">
    <input class="todo-input" type="text" placeholder="Add a task." name="title">
    <input type="hidden" name="userid" value="<?php echo htmlspecialchars($userid); ?>">
    <button class="todo-btn" type="submit" id="submit">I Got This!</button>
  </form>
</div>

<!-- Todo Active List -->
<h2>On Progress</h2>
<div id="myUnOrdList">
  <ul class="todo-list">
    <?php 
    if (!empty($todos)) {
      foreach ($todos as $task): ?>
        <div class="todo standard-todo" data-id="<?= htmlspecialchars($task->id) ?>">
          <button class="edit-btn standard-button"><i class="fas fa-edit"></i></button>
          <li class="todo-item" style="color:aliceblue;"><?= htmlspecialchars($task->title) ?></li>
          <button class="check-btn standard-button"><i class="fas fa-check"></i></button>
          <button class="delete-btn standard-button"><i class="fas fa-trash"></i></button>
        </div>
    <?php endforeach;
    } else {
      echo '<div class="todo standard-todo"><li class="todo-item" style="color:aliceblue;">No tasks available.</li></div>';
    } ?>
  </ul>
</div>

<!-- Todo Completed List -->
<h2>Done</h2>
<div id="myUnOrdList">
  <ul class="todo-list">
    <?php 
    if (!empty($tododones)) {
      foreach ($tododones as $taskdone): ?>
        <div class="todo standard-todo" data-id="<?= htmlspecialchars($taskdone->id) ?>">
          <button class="edit-btn standard-button"><i class="fas fa-edit"></i></button>
          <li class="todo-item" style="color:aliceblue;"><?= htmlspecialchars($taskdone->title) ?></li>
          <button class="check-btn standard-button"><i class="fas fa-check"></i></button>
          <button class="delete-btn standard-button"><i class="fas fa-trash"></i></button>
        </div>
    <?php endforeach;
    } else {
      echo '<div class="todo standard-todo"><li class="todo-item" style="color:aliceblue;">No tasks available.</li></div>';
    } ?>
  </ul>
</div>

<p><span id="datetime"></span></p>
<script src="JS/time.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/todo.js"></script>

</body>
</html>
