<?php
require_once 'config/database.php';
require_once 'model/note.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$notes = new Note($conn);
$userid = $_SESSION['user_id'];
$notesdata = $notes->getAll($userid);
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
      border: 1px solid rgb(221, 195, 243);
    }

    .dropdown-item {
      color: rgb(218, 190, 241);
    }

    .dropdown-item:hover {
      background-color: #f1dafc;
      color: rgb(219, 186, 255);
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 0;
      background: #fdf6ff;
    }

    #header {
      background: rgb(207, 175, 245);
      color: white;
      padding: 20px;
      text-align: center;
    }

    .floating-btn {
      position: fixed;
      bottom: 20px;
      left: 50%;
      transform: translateX(-50%);
      background-color: #af8ece;
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      cursor: pointer;
      box-shadow: 0 5px 12px rgba(0, 0, 0, 0.2);
    }

    .event-form-box {
      position: fixed;
      bottom: 90px;
      left: 50%;
      transform: translateX(-50%);
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
      padding: 20px;
      width: 90%;
      max-width: 350px;
      display: none;
      z-index: 1000;
    }

    .event-form-box input,
    .event-form-box select,
    .event-form-box button {
      display: block;
      width: 100%;
      margin: 10px 0;
      padding: 10px;
      border-radius: 8px;
      border: 1px solid #ccc;
    }

    .event-form-box button {
      background-color: #7c3aed;
      color: white;
      border: none;
      cursor: pointer;
    }

    .event-list {
      padding: 20px;
    }

    .event-item {
      background: white;
      margin-bottom: 10px;
      padding: 15px;
      border-left: 5px solid #7c3aed;
      border-radius: 8px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .event-item small {
      color: gray;
    }

    .bg-af8ece {
      background-color: #af8ece !important;
      border: none !important;
    }

    .event-date {
      color: #7c3aed;
      font-weight: bold;
    }
  </style>
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
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a class="dropdown-item" href="profile.php">👤 Profile</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
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
        <input type="hidden" name="note_id" id="note_id">
        <input type="text" class="form-control mb-2" id="note-title" name="title" placeholder="Note title" required>
        <textarea class="form-control mb-2" id="note-content" name="content" rows="3" placeholder="Write your note..." required></textarea>
        <div class="col-md-4 mb-3 note-item mt-4" enctype="multipart/form-data">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Upload File</h5>
              <input type="file" name="file" id="file-input" class="form-control mb-2">
              <div id="upload-result" class="mt-2"></div>
            </div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">Save Note</button>
      </div>
    </form>

    <!-- Notes Display -->
    <div id="notes-list" class="row">
      <h2>All notes</h2>
      <div id="myUnOrdList">
        <ul class="todo-list">
          <?php

          if (!empty($notesdata)) {
            foreach ($notesdata as $notess):
          ?>
              <div class="todo standard-todo" data-id="<?= htmlspecialchars($notess['id']) ?>">
                <a class="edit-btn standard-button" href="edit-note.php?id=<?= $notess['id'] ?>"><i class="fas fa-edit"></i></a>
                <li class="todo-item" style="color:aliceblue;"><?= htmlspecialchars($notess['title']) ?></li>
                <button class="delete-btn standard-button delete-note" data-id="<?= $notess['id'] ?>"><i class="fas fa-trash"></i></button>
              </div>
          <?php endforeach;
          } else {
            echo '<div class="todo standard-todo"><li class="todo-item" style="color:aliceblue;">No tasks available.</li></div>';
          } ?>
        </ul>
      </div>
    </div>

    <!-- File Upload styled like a note card -->

  </div>

  <p><span id="datetime"></span></p>
  <script src="JS/time.js"></script>
  <script src="js/jquery.min.js"></script>
  <script src="js/jquery.validate.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

  <script type="text/javascript">
    jQuery(document).ready(function($) {

      $(document).ready(function() {
        // Initialize form validation
        $('#note-form').validate({
          errorElement: 'span',
          errorClass: 'validate-has-error',
          rules: {
            title: {
              required: true,
              minlength: 2
            },
            content: {
              required: true,
              minlength: 5
            }
          },
          messages: {
            title: {
              required: "Note title is required.",
              minlength: "Title must be at least 2 characters"
            },
            content: {
              required: "Note content is required.",
              minlength: "Content must be at least 5 characters"
            }
          },
          submitHandler: function(form) {
            // Create FormData object from the form
            var formData = new FormData(form);

            // Determine if this is a create or update action
            var noteId = $('#note_id').val();
            var fileInput = $('#file-input')[0];
            if (fileInput.files.length > 0) {
              formData.append('file', fileInput.files[0]);
            }
            var actionType = noteId ? 'update' : 'create';

            // Add the action to the form data
            formData.append('action', actionType);

            // If updating, include the note ID
            if (noteId) {
              formData.append('id', noteId);
            }

            // Disable submit button and show loading state
            $("button[type='submit']").prop("disabled", true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...');

            // Show upload status
            $('#upload-result').html('<div class="alert alert-info">Saving your note...</div>');

            // Make the AJAX request
            $.ajax({
              type: "POST",
              url: "ajax/notecontrol.php",
              data: formData,
              processData: false, // Required for FormData
              contentType: false, // Required for FormData
              dataType: 'json', // Expect JSON response
              success: function(response) {
                // Re-enable submit button
                $("button[type='submit']").prop("disabled", false).html('Save Note');

                // Check if response is valid
                if (response && response.status) {
                  if (response.status === 'success') {
                    // Show success message
                    var successMsg = 'Note ' + (actionType === 'create' ? 'created' : 'updated') + ' successfully!';
                    $('#upload-result').html('<div class="alert alert-success">' + successMsg + '</div>');

                    // Reset form if creating new note
                    if (actionType === 'create') {
                      $('#note-form')[0].reset();
                      $('#note_id').val('');
                    }

                    // Reload page after 1.5 seconds
                    setTimeout(function() {
                      if (response.redirect) {
                        window.location.href = response.redirect;
                      } else {
                        location.reload();
                      }
                    }, 1500);
                  } else {
                    // Show error message from server
                    var errorMsg = response.message || 'An error occurred while saving the note';
                    $('#upload-result').html('<div class="alert alert-danger">' + errorMsg + '</div>');
                  }
                } else {
                  // Invalid response format
                  $('#upload-result').html('<div class="alert alert-danger">Invalid server response</div>');
                }
              },
              error: function(xhr, status, error) {
                // Re-enable submit button
                $("button[type='submit']").prop("disabled", false).html('Save Note');

                // Show error message
                var errorMsg = "Error: ";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                  errorMsg += xhr.responseJSON.message;
                } else if (xhr.responseText) {
                  errorMsg += xhr.responseText;
                } else {
                  errorMsg += "Could not connect to server";
                }

                $('#upload-result').html('<div class="alert alert-danger">' + errorMsg + '</div>');

                // Log error to console
                console.error("AJAX Error:", status, error, xhr.responseText);
              }
            });

            return false; // Prevent default form submission
          }
        });

        // Delete note handler (assuming you have delete buttons with class 'delete-note')
        $(document).on('click', '.delete-note', function(e) {
          e.preventDefault();
          var noteId = $(this).data('id');

          if (confirm('Are you sure you want to delete this note?')) {
            $.ajax({
              type: "POST",
              url: "ajax/notecontrol.php",
              data: {
                action: 'delete',
                id: noteId
              },
              dataType: 'json',
              success: function(response) {
                if (response.status === 'success') {
                  // Remove note from UI or reload page
                  $('[data-note-id="' + noteId + '"]').fadeOut(300, function() {
                    $(this).remove();
                  });
                  // Or simply reload:
                  // location.reload();
                } else {
                  alert('Error: ' + (response.message || 'Failed to delete note'));
                }
              },
              error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Server error'));
              }
            });
          }
        });
      });
    });
  </script>

</body>

</html>