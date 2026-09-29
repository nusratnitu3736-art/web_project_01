<?php

require_once "includes/auth.php";
require_once "config/database.php";

$user_id = $_SESSION['user_id'];

$id = isset($_GET['id'])
    ? (int) $_GET['id']
    : 0;

if ($id <= 0) {
    header("Location: expenses.php");
    exit;
}

$stmt = $conn->prepare(
    "SELECT *
     FROM expenses
     WHERE id = ? AND user_id = ?"
);

$stmt->bind_param("ii", $id, $user_id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {

    header("Location: expenses.php");
    exit;
}

$expense = $result->fetch_assoc();

$stmt->close();

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

        $error = "Amount must be greater than zero.";

    } else {

        $stmt = $conn->prepare(
            "UPDATE expenses
             SET title = ?,
                 amount = ?,
                 category = ?,
                 expense_date = ?,
                 description = ?
             WHERE id = ? AND user_id = ?"
        );

        $stmt->bind_param(
            "sdsssii",
            $title,
            $amount,
            $category,
            $expense_date,
            $description,
            $id,
            $user_id
        );

        if ($stmt->execute()) {

            header("Location: expenses.php");
            exit;

        } else {

            $error = "Failed to update expense.";
        }

        $stmt->close();
    }
}

require_once "includes/header.php";

?>

<div class="form-container">

    <h2>Edit Expense</h2>

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
            value="<?= htmlspecialchars($expense['title']) ?>"
            required
        >

        <label>Amount</label>

        <input
            type="number"
            name="amount"
            step="0.01"
            min="0.01"
            value="<?= htmlspecialchars($expense['amount']) ?>"
            required
        >

        <label>Category</label>

        <select name="category" required>

            <?php

            $categories = [
                "Food",
                "Transport",
                "Education",
                "Shopping",
                "Entertainment",
                "Other"
            ];

            foreach ($categories as $cat):

            ?>

                <option
                    value="<?= $cat ?>"
                    <?= $expense['category'] === $cat ? "selected" : "" ?>
                >
                    <?= $cat ?>
                </option>

            <?php endforeach; ?>

        </select>

        <label>Date</label>

        <input
            type="date"
            name="expense_date"
            value="<?= htmlspecialchars($expense['expense_date']) ?>"
            required
        >

        <label>Description</label>

        <textarea name="description"><?= htmlspecialchars($expense['description']) ?></textarea>

        <button type="submit" class="btn">
            Update Expense
        </button>

    </form>

</div>

<?php
require_once "includes/footer.php";
?>