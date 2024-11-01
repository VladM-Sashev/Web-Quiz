<?php
$servername = "localhost";
$username = "kyka0xo_WebAppQuiz";
$password = "footballquiz";
$dbname = "kyka0xo_110209";
$session_start();

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// Table to store user scores and choices
$sql_user_scores = "CREATE TABLE User_Scores(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    question_id INT,
    user_answer VARCHAR(255) NOT NULL,
    is_correct BOOLEAN NOT NULL,
    score INT NOT NULL,
    FOREIGN KEY (question_id) REFERENCES Questions(question_id)
)";

if ($conn->query($sql_user_scores) === TRUE) {
    echo "User scores table created successfully\n";
} else {
    echo "Error creating User scores table: " . $conn->error . "\n";
}


// Table to store user login information
$sql_user_login = "CREATE TABLE User_Login(
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    UNIQUE KEY unique_username (username),
    UNIQUE KEY unique_email (email)
)";

if ($conn->query($sql_user_login) === TRUE) {
    echo "User login table created successfully\n";
} else {
    echo "Error creating User login table: " . $conn->error . "\n";
}

// SQL to create tables
$sql_create_questions = "CREATE TABLE Questions(
    question_id INT PRIMARY KEY AUTO_INCREMENT,
    question_text VARCHAR(255) NOT NULL)";

if ($conn->query($sql_create_questions) === TRUE) {
    echo "Questions table created successfully\n";
} else {
    echo "Error creating Questions table: " . $conn->error . "\n";
}

// Table to store options for each question
$sql_store_options = "CREATE TABLE Choices(
    choice_id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT,
    choice_text VARCHAR(255) NOT NULL,
    is_correct BOOLEAN NOT NULL,
    FOREIGN KEY (question_id) REFERENCES Questions(question_id))";

if ($conn->query($sql_store_options) === TRUE) {
    echo "Choices table created successfully\n";
} else {
    echo "Error creating Choices table: " . $conn->error . "\n";
}

// Table to store user responses
$sql_user_responses = "CREATE TABLE User_Answers(
    answer_id INT PRIMARY KEY AUTO_INCREMENT,
    question_id INT,
    user_answer VARCHAR(255) NOT NULL,
    is_correct BOOLEAN NOT NULL,
    FOREIGN KEY (question_id) REFERENCES Questions(question_id))";

if ($conn->query($sql_user_responses) === TRUE) {
    echo "User answers table created successfully\n";
} else {
    echo "Error creating User answers table: " . $conn->error . "\n";
}



// Determine and obtain the top 5 scorers
$sql_top_players = "SELECT username, score FROM User_Scores ORDER BY score DESC LIMIT 5";
$result = $conn->query($sql_top_players);
if ($result->num_rows > 0) {
    echo "<h2>Top 5 High-Scoring Players</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['username']} - Score: {$row['score']}</li>";
    }
    echo "</ul>";
} else {
    echo "No high-scoring players yet.";
}


// Inserting football questions into the Questions table
$football_questions = [
    "Who won the FIFA World Cup in 2022?",
    "Who is the England current captain?",
    "What is the name of the England goalkeeper?",
    "Who is the cuurent captain of Liverpool?",
    "What nationality is Paolo Maldini?",
    "What nationality is the football team of AC Milan?",
    "What is the name of the current captain of Bayern Munich?",
    "Which country won the FIFA World cup in 1994?",
    "Who was the longest manager of Manchester United?",
    "What nationality is Dimitar Berbatov?",
    "How many world cups Italy won in total?",
    "How many world cups England won in total?",
    "How many world cups Pele won in total?",
    "How many World Cup Germany won?",
    "How many World Cup Brasil won?",
    "Which club is Haaland play for?",
    "How many times Bayern Munich won Champions League?",
    "What team has won the most Champions League?",
    "How many times AC Milan won Champions League?",
    "In which club played Hristo Stoichkov?",
];

foreach ($football_questions as $question_text) {
    $sql_insert_question = "INSERT INTO Questions (question_text) VALUES ('$question_text')";
    if ($conn->query($sql_insert_question) === TRUE) {
        echo "Question inserted successfully: $question_text\n";
    } else {
        echo "Error inserting question: " . $conn->error . "\n";
    }
}
    
   
// Close the database connection
$conn->close();
?>