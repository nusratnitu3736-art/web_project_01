<?php

require_once "includes/auth.php";
require_once "config/database.php";
require_once "includes/header.php";

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare(
    "SELECT
        COUNT(*) AS total_count,
        COALESCE(SUM(amount), 0) AS total_amount
     FROM expenses
     WHERE user_id = ?"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
$summary = $result->fetch_assoc();

$total_count = $summary['total_count'];
$total_amount = $summary['total_amount'];

$stmt->close();

?>

<h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>

<div class="dashboard-grid">

    <div class="stat-card">
        <h3>Total Expenses</h3>
        <p>৳<?= number_format($total_amount, 2) ?></p>
    </div>

    <div class="stat-card">
        <h3>Number of Expenses</h3>
        <p><?= $total_count ?></p>
    </div>

</div>

<div class="dashboard-actions">

    <a href="add_expense.php" class="btn">
        + Add Expense
    </a>

    <a href="expenses.php" class="btn secondary">
        View Expenses
    </a>

</div>

<?php
require_once "includes/footer.php";
?>