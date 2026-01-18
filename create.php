<?php
require_once __DIR__ . '/classes/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    [$ok, $msg] = $manager->create($_POST);

    if ($ok) {
        header("Location: index.php?success=" . urlencode($msg));
        exit;
    } else {
        $error = $msg;
    }
}

function old($key) {
    return htmlspecialchars($_POST[$key] ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Student</title>
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
    <h2>Create Student</h2>

    <?php if ($error): ?>
      <div class="msg error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label>Name</label>
      <input type="text" name="name" value="<?= old('name') ?>" required>

      <label>Email</label>
      <input type="email" name="email" value="<?= old('email') ?>" required>

      <label>Phone</label>
      <input type="tel" name="phone" pattern="[0-9]+" value="<?= old('phone') ?>" required>

      <label>Status</label>
      <select name="status" required>
        <?php
          $statuses = ["Active", "On Leave", "Graduated", "Inactive"];
          $selected = $_POST['status'] ?? '';
          foreach ($statuses as $st) {
            $isSel = ($selected === $st) ? 'selected' : '';
            echo "<option value=\"".htmlspecialchars($st)."\" $isSel>".htmlspecialchars($st)."</option>";
          }
        ?>
      </select>

      <button class="btn" type="submit">Save</button>
    </form>
  </div>

</body>
</html>
