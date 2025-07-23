<?php
require_once 'config/database.php';
require_once 'login_verify.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="theme-color" content="#062e3f" />
  <meta name="description" content="Your personal notes and file vault in PlanPal." />

  <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@300&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.0-2/css/all.min.css" integrity="sha256-46r060N2LrChLLb5zowXQ72/iKKNiw/lAmygmHExk/o=" crossorigin="anonymous" />
  <link rel="shortcut icon" type="image/png" href="assets/favicon.png" />
  <link rel="stylesheet" href="CSS/main.css" />
  <link rel="stylesheet" href="CSS/corner.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

  <title>Notes | PlanPal</title>
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

<div class="container mt-4">
  <h2>Your Notes</h2>

  <!-- Notes Form -->
  <form id="note-form" class="mb-3">
    <input type="hidden" name="note_id" id="note_id">
    <input type="text" class="form-control mb-2" id="note-title" name="title" placeholder="Note title" required>
    <textarea class="form-control mb-2" id="note-content" name="content" rows="3" placeholder="Write your note..." required></textarea>
    <button type="submit" class="btn btn-primary">Save Note</button>
  </form>

  <!-- Notes Display -->
  <div id="notes-list" class="row">
    <!-- Notes will load here -->
  </div>

  <!-- File Upload styled like a note card -->
  <div class="col-md-4 mb-3 note-item mt-4">
    <div class="card">
      <div class="card-body">
        <h5 class="card-title">Upload File</h5>
        <form id="file-upload-form" enctype="multipart/form-data">
          <input type="file" name="file" id="file-input" class="form-control mb-2" required>
          <button type="submit" class="btn btn-primary">Upload File</button>
        </form>
        <div id="upload-result" class="mt-2"></div>
      </div>
    </div>
  </div>
</div>

<p><span id="datetime"></span></p>
<script src="JS/time.js"></script>
<script src="js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="JS/notes.js"></script>

</body>
</html>
