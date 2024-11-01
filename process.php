<?php
session_start();
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
// Initialize 3 lives
if (!isset($_SESSION['lives'])) {
    $_SESSION['lives'] = 3; // Starting with 3 lives
}

// Score operation
if (!isset($_SESSION['score'])) {
    $_SESSION['score'] = 0;
}

if ($_POST) {
    $number = $_POST['number'];
    $selected_choice = $_POST['choice'];
    $next = $number + 1;

    // Retrieve the correct choice for the current question
    $query = "SELECT * FROM Choices WHERE question_id=$number AND is_correct=1";
    $result = $conn->query($query) or die($conn->error . __LINE__);
    $row = $result->fetch_assoc();
    $correct_choice = $row['choice_id'];

    // Check if the selected choice matches the correct choice
    if ($correct_choice == $selected_choice) {
        // Answer is correct, increment score
        $_SESSION['score'] += 1;
    }
   else {
    // Answer is incorrect, decrement lives
    $_SESSION['lives'] -= 1;

    // Check if the user has run out of lives
    if ($_SESSION['lives'] <= 0) {
        // Redirect to a game over page or handle the game over scenario
        header("Location: GAME_OVER.php");
        exit();
    }
}
    // Insert user's score and choice into User_Scores table
     $username = $_SESSION['username']; 
    $is_correct = ($correct_choice == $selected_choice) ? 1 : 0;
    $score = ($is_correct) ? 1 : 0;
    $sql_insert_score = "INSERT INTO User_Scores (username, question_id, user_answer, is_correct, score) VALUES ('$username', $number, '$selected_choice', $is_correct, $score)";
    if ($conn->query($sql_insert_score) !== TRUE) {
        echo "Error inserting user score and choice: " . $conn->error . "\n";
    }

    // Checking if it's the last question
    $query = "SELECT COUNT(*) AS total FROM Questions";
    $result = $conn->query($query) or die($conn->error . __LINE__);
    $row = $result->fetch_assoc();
    $total_questions = $row['total'];

    if ($number >= $total_questions) {
        // Redirect to conclusion page if all questions answered
        header("Location: conclusion_test.php");
        exit();
    } else {
        // Redirect to next question
        header("Location: questions.php?n=" . $next);
        exit();
    }
}

$conn->close();
?>
