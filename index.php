<?php
require_once __DIR__ . '/classes/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');
$students = $manager->getAllStudents();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';

function badgeClass($status) {
  $s = strtolower(trim($status));
  if ($s === 'active') return 'badge active';
  if ($s === 'on leave') return 'badge onleave';
  if ($s === 'graduated') return 'badge graduated';
  return 'badge inactive';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student List</title>
  <link rel="stylesheet" href="assests/css/style.css">
</head>
<body>
  <div class="page">
    <nav class="navbar">
      <div class="container">
        <div class="navbar-top">
          <div class="brand">STUDENT.IO</div>
          <div></div>
        </div>
        <div class="header-title">Student List</div>
      </div>
    </nav>

    <main class="main">
      <div class="container">
        <div class="card">
          <div class="card-top">
            <p>A list of all students currently enrolled including their name and email address.</p>
            <a class="btn" href="create.php">Add Student</a>
          </div>

          <?php if ($success): ?>
            <div class="msg success"><?= htmlspecialchars($success) ?></div>
          <?php endif; ?>

          <?php if ($error): ?>
            <div class="msg error"><?= htmlspecialchars($error) ?></div>
          <?php endif; ?>

          <div class="table-wrap">
            <table>
              <thead>
                <tr>
                  <th style="min-width:180px;">Name</th>
                  <th style="min-width:220px;">Email</th>
                  <th style="min-width:160px;">Phone</th>
                  <th style="min-width:140px;">Status</th>
                  <th style="min-width:140px; text-align:right;">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php if (empty($students)): ?>
                  <tr>
                    <td colspan="5">No students found.</td>
                  </tr>
                <?php else: ?>
                  <?php foreach ($students as $s): ?>
                    <tr>
                      <td><strong><?= htmlspecialchars($s['name']) ?></strong></td>
                      <td><?= htmlspecialchars($s['email']) ?></td>
                      <td><?= htmlspecialchars($s['phone']) ?></td>
                      <td>
                        <span class="<?= badgeClass($s['status']) ?>">
                          <?= htmlspecialchars($s['status']) ?>
                        </span>
                      </td>
                      <td style="text-align:right;">
                        <a class="action-link edit" href="edit.php?id=<?= urlencode($s['id']) ?>">Edit</a>
                        <a class="action-link delete"
                           href="delete.php?id=<?= urlencode($s['id']) ?>"
                           onclick="return confirm('Are you sure you want to delete this student?');">
                           Delete
                        </a>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

        </div>
      </div>
    </main>

    <footer class="footer">
      &copy; 2025 Student Management System.
    </footer>
  </div>
</body>
</html>
