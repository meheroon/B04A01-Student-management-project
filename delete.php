<?php
require_once __DIR__ . '/classes/StudentManager.php';

$manager = new StudentManager(__DIR__ . '/students.json');

$id = $_GET['id'] ?? null;
if ($id === null) {
    header("Location: index.php?error=" . urlencode("Missing student id."));
    exit;
}

[$ok, $msg] = $manager->delete($id);
if ($ok) {
    header("Location: index.php?success=" . urlencode($msg));
} else {
    header("Location: index.php?error=" . urlencode($msg));
}
exit;
