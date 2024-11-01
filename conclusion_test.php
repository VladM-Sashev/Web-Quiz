<?php
$servername = "localhost";
$username = "kyka0xo_WebAppQuiz"; // Database username
$password = "footballquiz"; // Database password
$dbname = "kyka0xo_110209"; // Database name
session_start(); // Start the session

// Create database connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to retrieve user's score based on correct answers
function getScore($username, $conn) {
    // Preparing of SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT COUNT(*) AS correct_answers FROM User_Scores WHERE username = ? AND is_correct = 1");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['correct_answers']; // Return the number of correct answers
    } else {
        return 0; // Return 0 if no records found
    }
}

// Assuming username is stored in session
if (isset($_SESSION['username'])) {
    $finalScore = getScore($_SESSION['username'], $conn); // Retrieve final score using the getScore function
} else {
    $finalScore = 0; // Default to 0 if username not set
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to the football world</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" />
</head>
<body>
<header>
    <div class="container">
        <h1>Football Quiz</h1>
        <style>
            .container {
    background-color: #f8f9fa; 
    border-radius: 10px; 
    padding: 20px; 
    margin: 20px auto; 
    width: 80%; 
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
}

.container h2 {
    color: #007bff; /
    margin-top: 0; 
}

.container p {
    margin-bottom: 10px; 
}

.container ul {
    list-style-type: none; 
    padding: 0; 
}

.container ul li {
    margin-bottom: 5px; 
}

.container a.start {
    display: inline-block; 
    padding: 5px 10px; 
    background-color: #28a745; 
    color: white; 
    text-decoration: none; 
    border-radius: 10px; 
    margin-top: 15px; 
}

.container a.start:hover {
    background-color: grey; 
}

.container h3 {
    color: purple; 
    margin-top: 20px; 
}

.container h3 + ul {
    margin-top: 5px; 
}

.container h2:last-of-type {
    margin-top: 40px; 
}
            
        </style>
    </div>
</header>

<main>
    <div class="container">
        <h2>The questions have finished</h2>
        <p>Congratulations! You have completed the quiz</p>
        <p>Final score: <?php echo $finalScore; ?></p>

        

        <h3>Top Five High Scores:</h3>
        <ul>
            <?php
            $query = "SELECT ul.username, SUM(us.is_correct) AS total_score
                      FROM User_Scores us 
                      JOIN User_Login ul ON us.username = ul.username 
                      GROUP BY us.username
                      ORDER BY total_score DESC 
                      LIMIT 5";
            $result = $conn->query($query);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<li>{$row['username']}: {$row['total_score']}</li>";
                }
            } else {
                echo "<li>No high scores found.</li>";
            }
            ?>
        </ul>

        <a href="questions.php?n=1" class="start">Try Again!</a>

        <h2>Question management instructions</h2>
        <ul>
            <li><a href="add.php" class="start"><strong>Insert Questions</strong></a></li>
            <li><a href="update.php" class="start"><strong>Update Questions</strong></a></li>
            <li><a href="delete.php" class="start"><strong>Delete Questions</strong></a></li>
        </ul>
    </div>
</main>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>