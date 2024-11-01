<?php
session_start();

// Capture the final score before resetting
$finalScore = isset($_SESSION['score']) ? $_SESSION['score'] : 0;

// Reset lives and score for a new attempt 
$_SESSION['lives'] = 3;
$_SESSION['score'] = 0;

$servername = "localhost";
$username = "kyka0xo_WebAppQuiz";
$password = "footballquiz";
$dbname = "kyka0xo_110209";
session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Game Over - Football Quiz</title>
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        .container {
            background-color: lightgrey;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            width: 80%;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        a {
            display: inline-block;
            padding: 10px 20px;
            background-color: green;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Game Over!</h1>
        <p> You can always try again and test your football knowledge!</p>
        <p>Your final score: <?php echo $finalScore; ?></p>
        <a href="questions.php?n=1">New Attempt</a>
        <a href="beginning.php">Back to Main Menu</a>
    </div>
</body>
</html>