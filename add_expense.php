<?php

require_once "includes/auth.php";
require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $title = trim($_POST['title']);
    $amount = $_POST['amount'];
    $category = trim($_POST['category']);
    $expense_date = $_POST['expense_date'];
    $description = trim($_POST['description']);

    if (
        $title === "" ||
        $amount === "" ||
        $category === "" ||
        $expense_date === ""
    ) {

        $error = "Please fill in all required fields.";

    } elseif (!is_numeric($amount) || $amount <= 0) {

        $error = "Amount must be a number greater than zero.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO expenses
            (user_id, title, amount, category, expense_date, description)
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "isdsss",
            $_SESSION['user_id'],
            $title,
            $amount,
            $category,
            $expense_date,
            $description
        );

        if ($stmt->execute()) {

            header("Location: expenses.php");
            exit;

        } else {

            $error = "Failed to add expense.";
        }

        $stmt->close();
    }
}

require_once "includes/header.php";

?>

<div class="form-container">

    <h2>Add Expense</h2>

    <?php if ($error): ?>

        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>

    <?php endif; ?>

    <form method="POST">

        <label>Expense Title</label>

        <input
            type="text"
            name="title"
            required
        >

        <label>Amount</label>

        <input
            type="number"
            name="amount"
            step="0.01"
            min="0.01"
            required
        >

        <label>Category</label>

        <select name="category" required>

            <option value="">Select Category</option>
            <option value="Food">Food</option>
            <option value="Transport">Transport</option>
            <option value="Education">Education</option>
            <option value="Shopping">Shopping</option>
            <option value="Entertainment">Entertainment</option>
            <option value="Other">Other</option>

        </select>

        <label>Date</label>

        <input
            type="date"
            name="expense_date"
            required
        >

        <label>Description</label>

        <textarea name="description"></textarea>

        <button type="submit" class="btn">
            Add Expense
        </button>

    </form>

</div>

<?php
require_once "includes/footer.php";
?>