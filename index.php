<?php
require_once "config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<?php include "includes/header.php"; ?>

<div class="container">

    <!-- Hero Section -->
    <div class="hero">

        <!-- Expense Tracker Header Image -->
        <img
            src="images/expense-header.png"
            alt="Expense Tracker"
            class="expense-header-image"
        >

        <h1>Personal Expense Tracker</h1>

        <p>
            Track your daily expenses, manage your spending,
            and keep your finances organized.
        </p>

        <?php if (isset($_SESSION['user_id'])): ?>

            <a href="dashboard.php" class="btn">
                Go to Dashboard
            </a>

        <?php else: ?>

            <a href="login.php" class="btn">
                Login
            </a>

            <a href="register.php" class="btn secondary">
                Create Account
            </a>

        <?php endif; ?>

    </div>


    <!-- Features Section -->
    <div class="features">

        <div class="card">
            <h3>Track Expenses</h3>

            <p>
                Add and manage your daily expenses
                easily in one place.
            </p>
        </div>


        <div class="card">
            <h3>Search & Filter</h3>

            <p>
                Find your expenses quickly by
                title or category.
            </p>
        </div>


        <div class="card">
            <h3>Secure Account</h3>

            <p>
                Your expenses are connected to
                your personal account.
            </p>
        </div>

    </div>

</div>

<?php include "includes/footer.php"; ?>