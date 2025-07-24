<?php
require_once 'config/database.php';
require_once 'model/note.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$noteModel = new Note($conn);

$id = $_GET['id'] ?? null;

if (!$id) {
    die('Invalid note ID');
}

$notedata = $noteModel->getById($id);

if (!$notedata) {
    die('Note not found');
}
// $tododones = $todo->getAll_active($userid);
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
<form id="note-form" class="mb-3" enctype="multipart/form-data">
  <div class="row">
    <input type="hidden" name="note_id" id="note_id" value="<?= htmlspecialchars($notedata['id']) ?>">

    <input type="text" class="form-control mb-2" id="note-title" name="title"
      placeholder="Note title" required value="<?= htmlspecialchars($notedata['title']) ?>">

    <textarea class="form-control mb-2" id="note-content" name="content" rows="3"
      placeholder="Write your note..." required><?= htmlspecialchars($notedata['content']) ?></textarea>

    <div class="col-md-4 mb-3 note-item mt-4">
      <div class="card">
        <div class="card-body">
          <h5 class="card-title">Upload File</h5>
          <input type="file" name="file" id="file-input" class="form-control mb-2">
          <div id="upload-result" class="mt-2">
            <?php if (!empty($notedata['fileupload']) && file_exists( $notedata['fileupload'])): ?>
  <div id="current-file" class="mt-2">
    <p>Current File:</p>
    <img src="<?= $notedata['fileupload'] ?>" alt="Note File" class="img-thumbnail" style="max-width: 200px;">
    <button type="button" id="delete-file" class="btn btn-sm btn-danger mt-2">Delete File</button>
    <input type="hidden" name="existing_file" value="<?= $notedata['fileupload'] ?>">
  </div>
<?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Note</button>
  </div>
</form>
 


  <!-- File Upload styled like a note card -->
  
</div>

<p><span id="datetime"></span></p>
<script src="JS/time.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="JS/notes.js"></script> -->

<script type="text/javascript">
$(document).ready(function () {
  $('#note-form').validate({
    errorElement: 'span',
    errorClass: 'validate-has-error',
    rules: {
      title: { required: true, minlength: 2 },
      content: { required: true, minlength: 5 }
    },
    messages: {
      title: { required: "Note title is required.", minlength: "Title must be at least 2 characters" },
      content: { required: "Note content is required.", minlength: "Content must be at least 5 characters" }
    },
    submitHandler: function (form) {
      var formData = new FormData(form);
      var noteId = $('#note_id').val();
      var fileInput = $('#file-input')[0];

      if (fileInput.files.length > 0) {
        formData.append('file', fileInput.files[0]);
      }

      var actionType = noteId ? 'update' : 'create';
      formData.append('action', actionType);

      if (noteId) formData.append('id', noteId);

      $("button[type='submit']").prop("disabled", true).html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
      );

      $('#upload-result').html('<div class="alert alert-info">Saving your note...</div>');

      $.ajax({
        type: "POST",
        url: "ajax/notecontrol.php",
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function (response) {
          $("button[type='submit']").prop("disabled", false).html('Save Note');

          if (response && response.status === 'success') {
            $('#upload-result').html(
              '<div class="alert alert-success">Note updated successfully!</div>'
            );

            setTimeout(function () {
              if (response.redirect) {
                window.location.href = response.redirect;
              } else {
                location.href = "notes-list.php"; // redirect back to list
              }
            }, 1500);
          } else {
            var errorMsg = response.message || 'An error occurred while saving the note';
            $('#upload-result').html('<div class="alert alert-danger">' + errorMsg + '</div>');
          }
        },
        error: function (xhr, status, error) {
          $("button[type='submit']").prop("disabled", false).html('Save Note');

          var errorMsg = "Error: ";
          if (xhr.responseJSON && xhr.responseJSON.message) {
            errorMsg += xhr.responseJSON.message;
          } else if (xhr.responseText) {
            errorMsg += xhr.responseText;
          } else {
            errorMsg += "Could not connect to server";
          }

          $('#upload-result').html('<div class="alert alert-danger">' + errorMsg + '</div>');
          console.error("AJAX Error:", status, error, xhr.responseText);
        }
      });

      return false;
    }
  });
  $('#delete-file').on('click', function() {
    if (confirm('Are you sure you want to delete this file?')) {
        const noteId = $('#note_id').val();
        $.ajax({
            type: 'POST',
            url: 'ajax/notecontrol.php',
            data: { action: 'deletefile', id: noteId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    $('#current-file').remove();
                    $('#upload-result').html('<div class="alert alert-info">File deleted. You can upload a new one.</div>');
                } else {
                    $('#upload-result').html('<div class="alert alert-danger">Could not delete file.</div>');
                }
            }
        });
    }
});
});

</script>

</body>
</html>
