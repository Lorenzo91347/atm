<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Initialize balance if not already set
if (!isset($_SESSION['balance'])) {
    $_SESSION['balance'] = 1000; // Starting balance
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
    <title>Simple ATM</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }

        .atm-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .atm-container input[type="number"] {
            padding: 10px;
            width: 100%;
            margin-bottom: 10px;
        }

        .atm-container input[type="submit"] {
            padding: 10px;
            width: 48%;
            margin: 2px 1%;
            border: none;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
        }

        .atm-container input[type="submit"]:hover {
            background-color: #0056b3;
        }

        .balance {
            font-size: 1.5em;
            margin-bottom: 20px;
        }

        .message {
            color: green;
            margin-bottom: 10px;
        }

        .logout {
            margin-top: 20px;
            font-size: 0.9em;
        }
    </style>
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