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
    
    // Delete choices related to the question
    $query_delete_choices = "DELETE FROM Choices WHERE question_id = ?";
    $stmt = $conn->prepare($query_delete_choices);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();

    // Delete the question
    $query_delete_question = "DELETE FROM Questions WHERE question_id = ?";
    $stmt = $conn->prepare($query_delete_question);
    $stmt->bind_param("i", $question_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $message = 'Question and its choices successfully deleted!';
    } else {
        $message = 'Error or no question found with the provided ID.';
    }
    
    $stmt->close();
}

$query = "SELECT * FROM Questions";
$questions = $conn->query($query) or die($conn->error . __LINE__);
$total = $questions->num_rows;
$next = $total + 1;
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
</head>

<body>
    <header>
        <div class="container">
            <h1>Football Quiz</h1>
        </div>
    </header>

    <main>
        <div class="container">
            <h2>Delete a question</h2>
            <?php
            if (isset($message)) {
                echo '<p>' . $message . '</p>';
            }
            ?>
            <form method="post" action="delete.php">
                <p>
                    <label>Question number:</label>
                    <input type="number" value="<?php echo $next; ?>" name="question_number" />
                </p>
                <p>
                    <label>Question text:</label>
                    <input type="text" name="question_text" />
                </p>
                <p>
                    <label>Option #1:</label>
                    <input type="text" name="choice1" />
                </p>
                <p>
                    <label>Option #2: </label>
                    <input type="text" name="choice2" />
                </p>
                <p>
                    <label>Option #3: </label>
                    <input type="text" name="choice3" />
                </p>
                <p>
                    <label>Correct option number: </label>
                    <input type="number" name="correct_choice" />
                    
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