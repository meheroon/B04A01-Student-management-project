<?php
require_once __DIR__ . '/classes/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');
$students = $manager->getAllStudents();

$success = $_GET['success'] ?? '';
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Student Management</title>
  <style>
    body{font-family: Arial, sans-serif; margin:20px;}
    table{width:100%; border-collapse:collapse;}
    th,td{border:1px solid #ddd; padding:10px; text-align:left;}
    th{background:#f5f5f5;}
    .actions a{margin-right:8px;}
    .msg{padding:10px; margin-bottom:10px; border-radius:6px;}
    .success{background:#e9f7ef; border:1px solid #a9dfbf;}
    .error{background:#fdecea; border:1px solid #f5b7b1;}
    .topbar{display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;}
    .btn{display:inline-block; padding:8px 12px; border:1px solid #333; text-decoration:none; border-radius:6px;}
  </style>
</head>
<body>

  <div class="topbar">
    <h2>Student List</h2>
    <a class="btn" href="create.php">+ Add Student</a>
  </div>

  <?php if ($success): ?>
    <div class="msg success"><?= htmlspecialchars($success) ?></div>
  <?php endif; ?>

  <?php if ($error): ?>
    <div class="msg error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Status</th><th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($students)): ?>
        <tr><td colspan="6">No students found.</td></tr>
      <?php else: ?>
        <?php foreach ($students as $s): ?>
          <tr>
            <td><?= htmlspecialchars($s['id']) ?></td>
            <td><?= htmlspecialchars($s['name']) ?></td>
            <td><?= htmlspecialchars($s['email']) ?></td>
            <td><?= htmlspecialchars($s['phone']) ?></td>
            <td><?= htmlspecialchars($s['status']) ?></td>
            <td class="actions">
              <a href="edit.php?id=<?= urlencode($s['id']) ?>">Edit</a>
              <a href="delete.php?id=<?= urlencode($s['id']) ?>"
                 onclick="return confirm('Are you sure you want to delete this student?');">
                 Delete
              </a>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

</body>
</html>
