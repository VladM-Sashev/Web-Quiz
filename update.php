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

$message = ""; // Initialize message variable

if (isset($_POST['submit'])) {
    $question_id = $_POST['question_number'];
    $question_text = $_POST['question_text'];
    $correct_choice = $_POST['correct_choice'];
    

    // Prepare statement for question update
    $stmt = $conn->prepare("UPDATE Questions SET question_text = ? WHERE question_id = ?");
    $stmt->bind_param("si", $question_text, $question_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $updated = true;
    } else {
        $updated = false; // No rows updated
    }

    // Update choices
    for ($choice = 1; $choice <= 3; $choice++) {
        $is_correct = ($correct_choice == $choice) ? 1 : 0;
        $choice_text = $_POST["choice$choice"];
        
        // Assuming choice_id matches choice number (1, 2, 3)
        $stmt = $conn->prepare("UPDATE Choices SET choice_text = ?, is_correct = ? WHERE question_id = ? AND choice_id = ?");
        $stmt->bind_param("siii", $choice_text, $is_correct, $question_id, $choice);
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $updated = true;
        }
    }

    $stmt->close();

    if ($updated) {
        $message = 'Question and choices successfully updated!';
    } else {
        $message = 'No changes made or error updating.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to the football world</title>
    <style>
        .container {
    width: 80%;
    margin: 0 auto;
    overflow: auto;
}

header {
    background-color: lightblue;
    color: black;
    padding: 25px 0;
    text-align: center;
}

header h1 {
    margin: 0;
    font-size: 40px;
}

main {
    padding: 15px 0;
}

form {
    background-color: lightblue;
    padding: 20px;
    border-radius: 5px;
}

form p {
    margin-bottom: 10px;
}

label {
    display: block;
    margin-bottom: 5px;
}

input[type='text'],
input[type='number'] {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

input[type='submit'] {
    background-color: green;
    color: #fff;
    padding: 10px 20px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

input[type='submit']:hover {
    background-color: grey;
}
    </style>
    <script>
        function validateForm() {
            var questionText = document.forms["updateForm"]["question_text"].value;
            var choice1 = document.forms["updateForm"]["choice1"].value;
            var choice2 = document.forms["updateForm"]["choice2"].value;
            var choice3 = document.forms["updateForm"]["choice3"].value;
            var correctChoice = document.forms["updateForm"]["correct_choice"].value;

            if (!questionText.trim() || !choice1.trim() || !choice2.trim() || !choice3.trim() || !correctChoice.trim()) {
                alert("Please fill in all fields.");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <header>
        <div class="container">
            <h1>Football Quiz</h1>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Update a question</h2>
            <?php if (!empty($message)) { echo "<p>$message</p>"; } ?>
            <form method="post" action="update.php" name="updateForm" onsubmit="return validateForm()">
                <p>
                    <label>Question number:</label>
                    <input type="number" name="question_number" required />
                </p>
                <p>
                    <label>Question text:</label>
                    <input type="text" name="question_text" required />
                </p>
                <p>
                    <label>Option #1:</label>
                    <input type="text" name="choice1" required />
                </p>
                <p>
                    <label>Option #2:</label>
                    <input type="text" name="choice2" required />
                </p>
                <p>
                    <label>Option #3:</label>
                    <input type="text" name="choice3" required />
                </p>
                <p>
                    <label>Correct option number:</label>
                    <input type="number" name="correct_choice" required min="1" max="3" />
                </p>
                <p>
                    <input type="submit" name="submit" value="Submit" />
                </p>
            </form>
        </div>
    </main>
</body>

</html>

<?php
// Close the database connection
$conn->close();
?>