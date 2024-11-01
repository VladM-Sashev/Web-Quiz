<?php
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

// Array to store choices information
$questions_with_choices = [
    // Choices for "Who won the FIFA World Cup in 2022?"
    [
        "Who won the FIFA World Cup in 2022?", [
            ["Argentina", 1], // The correct answer
            ["Brazil", 0],
            ["Germany", 0],
            ["Spain", 0],
        ],
    ],
    // Choices for "Who is the England current captain?"
    [
        "Who is the England current captain?", [
            ["Harry Kane", 1], // The correct answer
            ["Kevin De Bruyne", 0],
            ["John McGinn", 0],
        ],
    ],
    // Choices for "What is the name of the England goalkeeper?"
 [
        "What is the name of the England goalkeeper?", [
            ["Jordan Pickford", 1], // The correct answer
            ["Ederson", 0],
            ["Alisson Becker", 0],
        ],
    ],
// Choices for "Who is the cuurent captain of Liverpool?"
[
        "Who is the cuurent captain of Liverpool?", [
            ["Virgil Van Dijk", 1], // The correct answer
            ["Christiano Ronaldo", 0],
            ["Muhamed Salah", 0],
        ],
    ],

// Choices for "What nationality is Paolo Maldini?"
[
        "What nationality is Paolo Maldini?", [
            ["American", 0], 
            ["Spanish", 0],
            ["Italian", 1], // The correct answer
        ],
    ],
// Choices for "What nationality is the football team of AC Milan"?

[
        "What nationality is the football team of AC Milan?", [
            ["Germany", 0], 
            ["Croatia", 0],
            ["Italia", 1], // The correct answer
        ],
    ],

// Choices for " What is the name of the current captain of Bayern Munich? "?

[
        "What is the name of the current captain of Bayern Munich?", [
            ["Manuel Neuer", 1], // The correct answer
            ["Davide Calabria", 0],
            ["John McGinn", 0], 
        ],
    ],
// Choices for "Which country won the FIFA World cup in 1994"?
[
        "Which country won the FIFA World cup in 1994?", [
            ["Germany", 0], 
            ["Brasil", 1], // The correct answer
            ["Italy", 0], 
        ],
    ],

// Choices for "Who was the longest manager of Manchester United?"
[
        "Who was the longest manager of Manchester United?", [
            ["Arsene Wenger", 0], 
            ["Alex Ferguson", 1], // The correct answer
            ["Jose Maurinho", 0], 
        ],
    ],

// Choices for "What nationality is Dimitar Berbatov?"

[
        "What nationality is Dimitar Berbatov?", [
            ["Serbian", 0], 
            ["Bulgarian", 1], // The correct answer
            ["Greek", 0], 
        ],
    ],

// Choices for "How many world cups Italy won in total?"
[
        "How many world cups Italy won in total?", [
            ["Four", 1], // The correct answer
            ["Six", 0], 
            ["Seven", 0], 
        ],
    ],
// Choices for "How many world cups England won in total?"
[
        "How many world cups England won in total?", [
            ["Three", 0], 
            ["One", 1], // The correct answer
            ["Two", 0], 
        ],
    ],
// Choices for "How many world cups Pele won in total?"
[
        "How many world cups Pele won in total?", [
            ["Three", 1], // The correct answer
            ["One", 0], 
            ["Two", 0], 
        ],
    ],

// Choices for "How many World Cup Germany won?"

[
        "How many World Cup Germany won?", [
            ["Five", 0], 
            ["Two", 0], 
            ["Four", 1], // The correct answer
        ],
    ],

// Choices for "How many World Cup Brasil won?"

[
        "How many World Cup Brasil won?", [
            ["Five", 1], // The correct answer
            ["Two", 0], 
            ["Four", 0], 
        ],
    ],
// Choices for "Which club is Haaland play for?"

[
        "Which club is Haaland play for?", [
            ["Liverpool", 0], 
            ["Manchester city", 1], // The correct answer
            ["Manchester United", 0], 
        ],
    ],
// Choices for "How many times Bayern Munich won Champions League?"

[
        "How many times Bayern Munich won Champions League?", [
            ["Five", 0], 
            ["Two", 0], 
            ["Six", 1], // The correct answer
        ],
    ],

// Choices for "What team has won the most Champions League?"

[
        "What team has won the most Champions League?", [
            ["Liverpool", 0], 
            ["Real Madrid ", 1], // The correct answer
            ["AC Milan", 0], 
        ],
    ],
// Choices for "How many times AC Milan won Champions League?"
[
        "How many times AC Milan won Champions League?", [
            ["Five", 0], 
            ["Seven", 1], // The correct answer
            ["Three", 0], 
        ],
    ],

// Choices for "In which club played Hristo Stoichkov?"
[
        "In which club played Hristo Stoichkov?", [
            ["Liverpool", 0], 
            ["Barcelona ", 1], // The correct answer
            ["Bayern Munich", 0], 
        ],
    ],


];
// Score variable

$_SESSION['score'] = 0;


foreach ($questions_with_choices as $question_info) {
    $question_text = $question_info[0];
    $choices_information = $question_info[1];

    // Get question_id based on question_text
    $stmt = $conn->prepare("SELECT question_id FROM Questions WHERE question_text = ?");
    $stmt->bind_param("s", $question_text);
    $stmt->execute();
    $stmt->store_result(); // Store the result set
    $stmt->bind_result($question_id);
    $stmt->fetch();
    $stmt->close();

    if (!$question_id) {
        echo "Error: Question not found in the Questions table: $question_text\n";
        continue;
    }

    // Insert choices into the Choices table
    foreach ($choices_information as $choice) {
        $choice_text = $choice[0];
        $is_correct = $choice[1];

        // Escape values to prevent SQL injection
        $choice_text = $conn->real_escape_string($choice_text);

        // Insert choice into the Choices table
        $sql_insert_choice = "INSERT INTO Choices (question_id, choice_text, is_correct) VALUES ($question_id, '$choice_text', $is_correct)";

        if ($conn->query($sql_insert_choice) === TRUE) {
            echo "Choice inserted successfully for question: $question_text\n";
        } else {
            echo "Error inserting choice: " . $conn->error . "\n";
        }
    }
}
// Storing the final score in the session variable
$_SESSION['score'] = $score;

// Close the database connection
$conn->close();
?>