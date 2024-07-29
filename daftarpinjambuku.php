<?php
include 'db.php';

// Fetch loans
$loanResult = $conn->query("SELECT loans.loan_id, users.name AS user_name, books.title AS book_title, loans.loan_date, loans.returned 
                            FROM loans
                            JOIN users ON loans.user_id = users.user_id
                            JOIN books ON loans.book_id = books.book_id");
$loans = $loanResult->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Loan Transactions</title>
</head>
<body>
    <h1>Loan Transactions</h1>
    <table border="1">
        <tr>
            <th>Loan ID</th>
            <th>User Name</th>
            <th>Book Title</th>
            <th>Loan Date</th>
            <th>Returned</th>
        </tr>
        <?php foreach ($loans as $loan): ?>
            <tr>
                <td><?php echo $loan['loan_id']; ?></td>
                <td><?php echo $loan['user_name']; ?></td>
                <td><?php echo $loan['book_title']; ?></td>
                <td><?php echo $loan['loan_date']; ?></td>
                <td><?php echo $loan['returned'] ? 'Yes' : 'No'; ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
