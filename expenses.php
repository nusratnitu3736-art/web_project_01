<?php

require_once "includes/auth.php";
require_once "config/database.php";
require_once "includes/header.php";

$user_id = $_SESSION['user_id'];

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : "";

$category = isset($_GET['category'])
    ? trim($_GET['category'])
    : "";

$sql = "SELECT *
        FROM expenses
        WHERE user_id = ?";

$params = [$user_id];
$types = "i";

if ($search !== "") {

    $sql .= " AND (title LIKE ? OR description LIKE ?)";

    $search_value = "%" . $search . "%";

    $params[] = $search_value;
    $params[] = $search_value;

    $types .= "ss";
}

if ($category !== "") {

    $sql .= " AND category = ?";

    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY expense_date DESC, id DESC";

$stmt = $conn->prepare($sql);

$stmt->bind_param($types, ...$params);

$stmt->execute();

$result = $stmt->get_result();

?>

<h1>My Expenses</h1>

<div class="search-box">

    <form method="GET">

        <input
            type="text"
            name="search"
            placeholder="Search expenses..."
            value="<?= htmlspecialchars($search) ?>"
        >

        <select name="category">

            <option value="">All Categories</option>

            <option value="Food"
                <?= $category === "Food" ? "selected" : "" ?>>
                Food
            </option>

            <option value="Transport"
                <?= $category === "Transport" ? "selected" : "" ?>>
                Transport
            </option>

            <option value="Education"
                <?= $category === "Education" ? "selected" : "" ?>>
                Education
            </option>

            <option value="Shopping"
                <?= $category === "Shopping" ? "selected" : "" ?>>
                Shopping
            </option>

            <option value="Entertainment"
                <?= $category === "Entertainment" ? "selected" : "" ?>>
                Entertainment
            </option>

            <option value="Other"
                <?= $category === "Other" ? "selected" : "" ?>>
                Other
            </option>

        </select>

        <button type="submit" class="btn">
            Search
        </button>

        <a href="expenses.php" class="btn secondary">
            Reset
        </a>

    </form>

</div>

<a href="add_expense.php" class="btn">
    + Add Expense
</a>

<div class="table-container">

<table>

    <thead>

        <tr>
            <th>Title</th>
            <th>Amount</th>
            <th>Category</th>
            <th>Date</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>

    </thead>

    <tbody>

    <?php if ($result->num_rows > 0): ?>

        <?php while ($expense = $result->fetch_assoc()): ?>

            <tr>

                <td>
                    <?= htmlspecialchars($expense['title']) ?>
                </td>

                <td>
                    ৳<?= number_format($expense['amount'], 2) ?>
                </td>

                <td>
                    <?= htmlspecialchars($expense['category']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($expense['expense_date']) ?>
                </td>

                <td>
                    <?= htmlspecialchars($expense['description']) ?>
                </td>

                <td>

                    <a
                        href="edit_expense.php?id=<?= $expense['id'] ?>"
                        class="action-edit"
                    >
                        Edit
                    </a>

                    <a
                        href="delete_expense.php?id=<?= $expense['id'] ?>"
                        class="action-delete"
                        onclick="return confirm('Are you sure you want to delete this expense?');"
                    >
                        Delete
                    </a>

                </td>

            </tr>

        <?php endwhile; ?>

    <?php else: ?>
        <tr>
            <td colspan="6">
                No expenses found.
            </td>
        </tr>

    <?php endif; ?>

    </tbody>

</table>

</div>

<?php

$stmt->close();

require_once "includes/footer.php";

?>