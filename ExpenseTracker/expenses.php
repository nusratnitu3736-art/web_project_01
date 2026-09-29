<?php

require_once "config/database.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* User must be logged in */
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category = isset($_GET['category']) ? trim($_GET['category']) : "";

/* --------------------------------
   GET EXPENSES
   -------------------------------- */

$sql = "SELECT id, title, amount, category, expense_date
        FROM expenses
        WHERE user_id = ?";

$params = [$user_id];
$types = "i";

if ($search !== "") {

    $sql .= " AND (title LIKE ? OR category LIKE ?)";

    $searchValue = "%" . $search . "%";

    $params[] = $searchValue;
    $params[] = $searchValue;

    $types .= "ss";
}

if ($category !== "") {

    $sql .= " AND category = ?";

    $params[] = $category;

    $types .= "s";
}

$sql .= " ORDER BY expense_date DESC, id DESC";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Database query error: " . $conn->error);
}

$stmt->bind_param($types, ...$params);

$stmt->execute();

$result = $stmt->get_result();


/* --------------------------------
   TOTAL EXPENSE
   -------------------------------- */

$total_sql = "SELECT COALESCE(SUM(amount), 0) AS total
              FROM expenses
              WHERE user_id = ?";

$total_stmt = $conn->prepare($total_sql);

$total_stmt->bind_param("i", $user_id);

$total_stmt->execute();

$total_result = $total_stmt->get_result();

$total_row = $total_result->fetch_assoc();

$total_expense = $total_row['total'] ?? 0;

?>

<?php include "includes/header.php"; ?>


<div class="container">

    <div class="page-header">

        <h1>My Expenses</h1>

        <a href="add_expense.php" class="btn">
            + Add Expense
        </a>

    </div>


    <!-- ==============================
         TOTAL EXPENSE
         ============================== -->

    <div class="expense-summary">

        <div class="summary-card">

            <h3>Total Expenses</h3>

            <p>
                ৳<?php echo number_format($total_expense, 2); ?>
            </p>

        </div>

    </div>


    <!-- ==============================
         SEARCH & FILTER
         ============================== -->

    <div class="filter-container">

        <form method="GET" action="expenses.php">

            <div class="filter-group">

                <div>

                    <label for="search">
                        Search
                    </label>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Search expense..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

                </div>


                <div>

                    <label for="category">
                        Category
                    </label>

                    <select
                        name="category"
                        id="category"
                    >

                        <option value="">
                            All Categories
                        </option>

                        <option
                            value="Food"
                            <?php
                            if ($category === "Food") {
                                echo "selected";
                            }
                            ?>
                        >
                            Food
                        </option>

                        <option
                            value="Transport"
                            <?php
                            if ($category === "Transport") {
                                echo "selected";
                            }
                            ?>
                        >
                            Transport
                        </option>
                        <option
                            value="Education"
                            <?php
                            if ($category === "Education") {
                                echo "selected";
                            }
                            ?>
                        >
                            Education
                        </option>

                        <option
                            value="Shopping"
                            <?php
                            if ($category === "Shopping") {
                                echo "selected";
                            }
                            ?>
                        >
                            Shopping
                        </option>

                        <option
                            value="Entertainment"
                            <?php
                            if ($category === "Entertainment") {
                                echo "selected";
                            }
                            ?>
                        >
                            Entertainment
                        </option>

                        <option
                            value="Other"
                            <?php
                            if ($category === "Other") {
                                echo "selected";
                            }
                            ?>
                        >
                            Other
                        </option>

                    </select>

                </div>


                <div class="filter-buttons">

                    <button
                        type="submit"
                        class="btn"
                    >
                        Search
                    </button>

                    <a
                        href="expenses.php"
                        class="btn btn-secondary"
                    >
                        Clear
                    </a>

                </div>

            </div>

        </form>

    </div>


    <!-- ==============================
         EXPENSE TABLE
         ============================== -->

    <div class="table-container">

        <h2>Expense List</h2>

        <?php if ($result->num_rows > 0): ?>

            <table class="expense-table">

                <thead>

                    <tr>

                        <th>Title</th>

                        <th>Amount</th>

                        <th>Category</th>

                        <th>Date</th>

                        <th>Actions</th>

                    </tr>

                </thead>


                <tbody>

                    <?php while ($expense = $result->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $expense['title']
                                );
                                ?>
                            </td>


                            <td>
                                ৳<?php
                                echo number_format(
                                    $expense['amount'],
                                    2
                                );
                                ?>
                            </td>


                            <td>

                                <span class="category-badge">

                                    <?php
                                    echo htmlspecialchars(
                                        $expense['category']
                                    );
                                    ?>

                                </span>

                            </td>


                            <td>
                                <?php
                                echo htmlspecialchars(
                                    date(
                                        "d M Y",
                                        strtotime(
                                            $expense['expense_date']
                                        )
                                    )
                                );
                                ?>

                            </td>


                            <td class="action-buttons">

                                <a
                                    href="edit_expense.php?id=<?php echo $expense['id']; ?>"
                                    class="btn btn-edit"
                                >
                                    Edit
                                </a>


                                <a
                                    href="delete_expense.php?id=<?php echo $expense['id']; ?>"
                                    class="btn btn-delete delete-btn"
                                >
                                    Delete
                                </a>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>


        <?php else: ?>

            <div class="no-expenses">

                <h3>No Expenses Found</h3>

                <p>
                    You haven't added any expenses yet.
                </p>

                <a
                    href="add_expense.php"
                    class="btn"
                >
                    + Add Your First Expense
                </a>

            </div>

        <?php endif; ?>

    </div>

</div>


<?php include "includes/footer.php"; ?>