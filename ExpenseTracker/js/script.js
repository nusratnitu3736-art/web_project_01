// ExpenseTracker JavaScript

document.addEventListener("DOMContentLoaded", function () {

    // Confirm before deleting an expense
    const deleteButtons = document.querySelectorAll(".delete-btn");

    deleteButtons.forEach(function (button) {
        button.addEventListener("click", function (event) {

            const confirmDelete = confirm(
                "Are you sure you want to delete this expense?"
            );

            if (!confirmDelete) {
                event.preventDefault();
            }
        });
    });


    // Password confirmation on registration
    const registerForm = document.querySelector("#registerForm");

    if (registerForm) {
        registerForm.addEventListener("submit", function (event) {

            const password = document.querySelector("#password");
            const confirmPassword =
                document.querySelector("#confirm_password");

            if (password && confirmPassword) {

                if (password.value.length < 6) {
                    alert("Password must contain at least 6 characters.");
                    event.preventDefault();
                    return;
                }

                if (password.value !== confirmPassword.value) {
                    alert("Passwords do not match.");
                    event.preventDefault();
                }
            }
        });
    }


    // Expense amount validation
    const expenseForm = document.querySelector("#expenseForm");

    if (expenseForm) {
        expenseForm.addEventListener("submit", function (event) {

            const amount = document.querySelector("#amount");

            if (amount && parseFloat(amount.value) <= 0) {
                alert("Please enter a valid expense amount.");
                event.preventDefault();
            }
        });
    }


    // Search expenses
    const searchInput = document.querySelector("#expenseSearch");
    const expenseRows = document.querySelectorAll(".expense-row");

    if (searchInput) {

        searchInput.addEventListener("input", function () {

            const searchText = searchInput.value.toLowerCase();

            expenseRows.forEach(function (row) {

                const rowText = row.textContent.toLowerCase();

                if (rowText.includes(searchText)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }

            });
        });
    }

});
