<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Tracker</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<nav class="navbar">
    <div class="nav-container">

        <a href="index.php" class="logo">ExpenseTracker</a>

        <div class="nav-links">

            <?php if (isset($_SESSION['user_id'])): ?>

                <a href="dashboard.php">Dashboard</a>
                <a href="expenses.php">Expenses</a>
                <a href="add_expense.php">Add Expense</a>
                <a href="logout.php">Logout</a>

            <?php else: ?>

                <a href="index.php">Home</a>
                <a href="login.php">Login</a>
                <a href="register.php">Register</a>

            <?php endif; ?>

        </div>

    </div>
</nav>

<main class="container">