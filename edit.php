<?php
require_once __DIR__ . '/classes/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');

$id = $_GET['id'] ?? null;
if ($id === null) {
  header("Location: index.php?error=" . urlencode("Missing student id."));
  exit;
}

$student = $manager->getStudentById($id);
if (!$student) {
  header("Location: index.php?error=" . urlencode("Student not found."));
  exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  [$ok, $msg] = $manager->update($id, $_POST);
  if ($ok) {
    header("Location: index.php?success=" . urlencode($msg));
    exit;
  }
  $error = $msg;
  // keep user input if validation fails
  $student = array_merge($student, $_POST);
}

function val($arr, $key) {
  return htmlspecialchars($arr[$key] ?? '');
}
function sel2($arr, $value) {
  return (($arr['status'] ?? '') === $value) ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Edit Student</title>
  <link rel="stylesheet" href="assests/css/style.css" />
</head>
<body>
  <div class="page">
    <nav class="navbar">
      <div class="container">
        <div class="navbar-top">
          <div class="brand">STUDENT.IO</div>
          <div></div>
        </div>
        <div class="header-title">Edit Student</div>
      </div>
    </nav>

    <main class="main">
      <div class="container">
        <div class="card form-card">

          <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <h2 class="form-title">Student Information</h2>
          <p class="form-subtitle">Update the student's personal details and enrollment status.</p>

          <form method="POST">
            <div class="form-grid">
              <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" value="<?= val($student,'name') ?>" required />
              </div>

              <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" value="<?= val($student,'email') ?>" required />
              </div>

              <div class="field">
                <label for="phone">Phone Number</label>
                <input id="phone" name="phone" type="text" value="<?= val($student,'phone') ?>" required />
              </div>

              <div class="field">
                <label for="status">Enrollment Status</label>
                <select id="status" name="status" required>
                  <option value="Active" <?= sel2($student,'Active') ?>>Active</option>
                  <option value="On Leave" <?= sel2($student,'On Leave') ?>>On Leave</option>
                  <option value="Graduated" <?= sel2($student,'Graduated') ?>>Graduated</option>
                  <option value="Inactive" <?= sel2($student,'Inactive') ?>>Inactive</option>
                </select>
              </div>
            </div>

            <div class="form-footer">
              <a class="btn secondary" href="index.php">Cancel</a>
              <button class="btn" type="submit">Save Changes</button>
            </div>
          </form>

        </div>
      </div>
    </main>

    <footer class="footer">
      &copy; 2025 Student Management System.
    </footer>
  </div>
</body>
</html>
