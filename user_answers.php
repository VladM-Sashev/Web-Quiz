<?php
$servername = "localhost";
$username = "kyka0xo_WebAppQuiz";
$password = "footballquiz";
$dbname = "kyka0xo_110209";
session_start();

// Sample array of user responses
$user_responses = [
    ["user_response" => "Argentina", "question_id" => 1],
    ["user_response" => "Harry Kane", "question_id" => 2],
    ["user_response" => "Jordan Pickford", "question_id" => 3],
    ["user_response" => "Virgil Van Dijk", "question_id" => 4],
    ["user_response" => "Italian", "question_id" => 5],
    ["user_response" => "Italia", "question_id" => 6],
    ["user_response" => "Manuel Neuer", "question_id" => 7],
    ["user_response" => "Brasil", "question_id" => 8],
    ["user_response" => "Alex Ferguson", "question_id" => 9],
    ["user_response" => "Bulgarian", "question_id" => 10],
    ["user_response" => "Four", "question_id" => 11],
    ["user_response" => "One", "question_id" => 12],
    ["user_response" => "Three", "question_id" => 13],
    ["user_response" => "Four", "question_id" => 14],
    ["user_response" => "Five", "question_id" => 15],
    ["user_response" => "Manchester city", "question_id" => 16],
    ["user_response" => "Six", "question_id" => 17],
    ["user_response" => "Real Madrid", "question_id" => 18],
    ["user_response" => "Seven", "question_id" => 19],
    ["user_response" => "Barcelona", "question_id" => 20],

];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Iterate over user responses
foreach ($user_responses as $user_response_data) {
    // User's response and question ID
    $user_response = $user_response_data["user_response"];
    $question_id = $user_response_data["question_id"];

    // Check if the user's response is correct by querying the Choices table
    $stmt = $conn->prepare("SELECT is_correct FROM Choices WHERE question_id = ? AND choice_text = ?");
    $stmt->bind_param("is", $question_id, $user_response);
    $stmt->execute();
    $stmt->bind_result($is_correct);
    $stmt->fetch();
    $stmt->close();

    // Insert user's response into the User_Answers table
    $sql_insert_response = "INSERT INTO User_Answers (question_id, user_answer, is_correct) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql_insert_response);

    if ($stmt) {
        // Bind parameters
        $stmt->bind_param("iss", $question_id, $user_response, $is_correct);

        if ($stmt->execute() === TRUE) {
            echo "User response inserted successfully.\n";
        } else {
            echo "Error inserting user response: " . $stmt->error . "\n";
        }

        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error . "\n";
    }
}

// Close the database connection
$conn->close();
?>