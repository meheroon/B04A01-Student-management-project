<?php
require_once __DIR__ . '/classes/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  [$ok, $msg] = $manager->create($_POST);
  if ($ok) {
    header("Location: index.php?success=" . urlencode($msg));
    exit;
  }
  $error = $msg;
}

function old($key) {
  return htmlspecialchars($_POST[$key] ?? '');
}
function selected($value) {
  $current = $_POST['status'] ?? 'Active';
  return ($current === $value) ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Student</title>
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
        <div class="header-title">Student Create</div>
      </div>
    </nav>

    <main class="main">
      <div class="container">
        <div class="card form-card">

          <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <h2 class="form-title">Student Information</h2>
          <p class="form-subtitle">Add the student's personal details and enrollment status.</p>

          <form method="POST">
            <div class="form-grid">
              <div class="field">
                <label for="name">Name</label>
                <input id="name" name="name" type="text" placeholder="John Doe" value="<?= old('name') ?>" required />
              </div>

              <div class="field">
                <label for="email">Email address</label>
                <input id="email" name="email" type="email" placeholder="john@example.com" value="<?= old('email') ?>" required />
              </div>

              <div class="field">
                <label for="phone">Phone Number</label>
                <input id="phone" name="phone" type="text" placeholder="+880 1712-123456" value="<?= old('phone') ?>" required />
              </div>

              <div class="field">
                <label for="status">Enrollment Status</label>
                <select id="status" name="status" required>
                  <option value="Active" <?= selected('Active') ?>>Active</option>
                  <option value="On Leave" <?= selected('On Leave') ?>>On Leave</option>
                  <option value="Graduated" <?= selected('Graduated') ?>>Graduated</option>
                  <option value="Inactive" <?= selected('Inactive') ?>>Inactive</option>
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
