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
    } else {
        $error = $msg;
        
        $student = array_merge($student, $_POST);
    }
}

function val($arr, $key) {
    return htmlspecialchars($arr[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Student</title>
  <style>
    body{font-family: Arial, sans-serif; margin:20px;}
    .box{max-width:520px;}
    label{display:block; margin-top:12px;}
    input,select{width:100%; padding:10px; margin-top:6px;}
    .btn{margin-top:14px; padding:10px 12px; border:1px solid #333; border-radius:6px; background:#fff; cursor:pointer;}
    .msg{padding:10px; margin-bottom:10px; border-radius:6px;}
    .error{background:#fdecea; border:1px solid #f5b7b1;}
    a{display:inline-block; margin-bottom:10px;}
  </style>
</head>
<body>

  <a href="index.php">← Back</a>
  <div class="box">
    <h2>Edit Student (ID: <?= htmlspecialchars($id) ?>)</h2>

    <?php if ($error): ?>
      <div class="msg error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Name</label>
      <input type="text" name="name" value="<?= val($student,'name') ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= val($student,'email') ?>" required>

      <label>Phone</label>
      <input type="tel" name="phone" pattern="[0-9]+" value="<?= val($student,'phone') ?>" required>

      <label>Status</label>
      <select name="status" required>
        <?php
          $statuses = ["Active", "On Leave", "Graduated", "Inactive"];
          foreach ($statuses as $st) {
            $isSel = (($student['status'] ?? '') === $st) ? 'selected' : '';
            echo "<option value=\"".htmlspecialchars($st)."\" $isSel>".htmlspecialchars($st)."</option>";
          }
        ?>
      </select>

      <button class="btn" type="submit">Update</button>
    </form>
  </div>

</body>
</html>
