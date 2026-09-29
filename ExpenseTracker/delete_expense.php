<?php

require_once "includes/auth.php";
require_once "config/database.php";

$user_id = $_SESSION['user_id'];

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($id > 0) {

    $stmt = $conn->prepare(
        "DELETE FROM expenses
         WHERE id = ? AND user_id = ?"
    );

    $stmt->bind_param(
        "ii",
        $id,
        $user_id
    );

    $stmt->execute();

    $stmt->close();
}

header("Location: expenses.php");
exit;

?>