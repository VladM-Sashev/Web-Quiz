<?php
session_start(); 

// Reset lives and score when deciding to start a new quiz
$_SESSION['lives'] = 3;
$_SESSION['score'] = 0;

$_SESSION['attempt_id'] = uniqid(); // Generating a unique attempt ID for the user attempt
$_SESSION['score'] = 0; // Reset score for the new attempt

$servername = "localhost";
$username = "kyka0xo_WebAppQuiz";
$password = "footballquiz";
$dbname = "kyka0xo_110209";


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Query to fetch the total number of questions
$query = "SELECT COUNT(*) AS total FROM Questions";
$result = $conn->query($query);

// Check if the query executed successfully
if (!$result) {
    die("Error in query: " . $conn->error);
}

// Fetch the row containing the total count
$row = $result->fetch_assoc();
$all = $row['total'];

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the football world</title>
    <style>
      body{
            background-image: url('Images/beginning_background.jpg'); 
            background-size: cover;
            background-position: center;
        }
   #body, #html {
    height: 100%;
    margin: 0;
    padding: 0;
}

.container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    height: 100%;
}

#header, #main {
    width: 100%;
    text-align: center;
    padding: 20px;
    color: #fff; 
    text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5); 
}

#header {
    background-color: #333;
    color: #fff;
    padding: 20px;
}

#main {
    padding: 20px;
    background-color: rgba(0, 0, 0, 0.5);
}



#h2 {
    color: #333;
    font-size: 50px;
    margin-bottom: 10px;
}

#p {
    color: #666;
    margin-bottom: 20px;
}

#ul {
    list-style-type: none;
    padding: 0;
}

#li {
    margin-bottom: 10px;
}

.begin {
    display: inline-block;
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
}

.begin:hover {
    background-color: #0056b3;
}
    </style>
</head>

<body>
    <header>
        <div class="container">
            <a href="login.php">Logout</a>
            <br> Hello, <?php echo isset($_SESSION['username']) ? ($_SESSION['username']) : 'Guest'; ?></br>
            <h1>Football Quiz</h1>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Check Your football knowledge</h2>
            <p>
                Take this multiple-choice test to check your football knowledge
            </p>
            <ul>
                <li><strong>Number of Questions: </strong><?php echo $all; ?></li>
                <li><strong>Type: </strong>Three choices quiz</li>
                <li><strong> Time per question: 30 seconds </strong></li>
            </ul>
            <a href="questions.php?n=1" class="begin">Begin Quiz</a>
        </div>
    </main>

</body>
</html>
