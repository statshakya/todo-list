<?php
require_once 'config/database.php';
require_once 'model/calendar.php';
require_once 'login_verify.php';

$db = new Database();
$conn = $db->connect();
$calendar = new Calendar($conn);

$userid = $_SESSION['user_id'];

// Handle AJAX requests for add, update, delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');

    $action = $_POST['action'];

    if ($action === 'add') {
        $title = $_POST['title'] ?? '';
        $event_date = $_POST['event_date'] ?? '';

        if ($calendar->addEvent($userid, $title, '', $event_date)) {
            echo json_encode(['status' => 'success', 'message' => 'Event added']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to add event']);
        }
        exit;
    }

    if ($action === 'update') {
        $id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $event_date = $_POST['event_date'] ?? '';

        if ($calendar->updateEvent($id, $userid, $title, '', $event_date)) {
            echo json_encode(['status' => 'success', 'message' => 'Event updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update event']);
        }
        exit;
    }

    if ($action === 'delete') {
        $id = $_POST['id'] ?? 0;

        if ($calendar->deleteEvent($id, $userid)) {
            echo json_encode(['status' => 'success', 'message' => 'Event deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete event']);
        }
        exit;
    }
}

// If not an AJAX request, load all events to display
$events = $calendar->getAll($userid);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PlanPal - Calendar</title>
  <link rel="stylesheet" href="assets/style.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark" style="width:100%;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PlanPal</a>
      <div class="collapse navbar-collapse justify-content-end">
        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <?= htmlspecialchars($_SESSION['username'] ?? 'User') ?>
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

  <!-- Calendar Events -->
  <div class="container my-4">
    <h2 class="text-center mb-4">Your Events</h2>

    <!-- Add/Edit Event Form -->
    <form id="event-form" class="row g-3">
      <input type="hidden" id="event-id" name="id" />
      <div class="col-md-4">
        <input type="text" id="event-title" name="title" class="form-control" placeholder="Event Title" required />
      </div>
      <div class="col-md-4">
        <input type="date" id="event-date" name="event_date" class="form-control" required />
      </div>
      <div class="col-md-4 d-grid">
        <button type="submit" class="btn btn-primary">Save Event</button>
        <button type="button" id="cancel-edit" class="btn btn-secondary mt-2 d-none">Cancel Edit</button>
      </div>
    </form>

    <div id="event-alert" class="alert d-none mt-3"></div>

    <!-- Events List -->
    <div class="mt-4">
      <?php if (!empty($events)): ?>
        <ul class="list-group" id="events-list">
          <?php foreach ($events as $event): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center" data-id="<?= $event['id'] ?>">
              <span>
                <strong><?= htmlspecialchars($event['title']) ?></strong>
                <small class="text-muted">(<?= htmlspecialchars($event['event_date']) ?>)</small>
              </span>
              <span>
                <button class="btn btn-sm btn-warning me-1 edit-event" 
                        data-id="<?= $event['id'] ?>" 
                        data-title="<?= htmlspecialchars($event['title']) ?>" 
                        data-date="<?= $event['event_date'] ?>">
                  Edit
                </button>
                <button class="btn btn-sm btn-danger delete-event" data-id="<?= $event['id'] ?>">Delete</button>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php else: ?>
        <p class="text-muted">No events yet. Start by adding one above!</p>
      <?php endif; ?>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="JS/events.js"></script>
</body>
</html>
