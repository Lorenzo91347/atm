<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$message = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $amount = floatval($_POST['amount']);
    if ($_POST['action'] === 'Withdraw') {
        if ($amount > 0 && $amount <= $_SESSION['balance']) {
            $_SESSION['balance'] -= $amount;
            $message = "You have withdrawn $$amount.";
        } else {
            $message = "Invalid withdrawal amount.";
        }
    } elseif ($_POST['action'] === 'Deposit') {
        if ($amount > 0) {
            $_SESSION['balance'] += $amount;
            $message = "You have deposited $$amount.";
        } else {
            $message = "Invalid deposit amount.";
        }
    }
}

// Handle logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../atm/styles/main.css">
    <title>Simple ATM</title>
</head>

<body>
    <div class="atm-container">
        <h1>Simple ATM</h1>
        <div class="balance">
            Current Balance: $<?php echo number_format($_SESSION['balance'], 2); ?>
        </div>
        <div class="message">
            <?php echo $message; ?>
        </div>
        <form method="POST" action="">
            <input type="number" name="amount" step="0.01" min="0" placeholder="Enter amount" required>
            <br>
            <input type="submit" name="action" value="Withdraw">
            <input type="submit" name="action" value="Deposit">
        </form>
        <div class="logout">
            <a href="index.php?action=logout">Logout</a>
        </div>
    </div>
</body>

</html>