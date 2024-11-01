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

if (isset($_POST['submit'])) {
    $question_id = $_POST['question_number'];
    $question_text = $_POST['question_text'];

    // Choices options and queries
    $choices = array();
    $choices[1] = $_POST['choice1'];
    $choices[2] = $_POST['choice2'];
    $choices[3] = $_POST['choice3'];

    $correct_choice = $_POST['correct_choice']; // Capture the correct choice

    $query = "INSERT INTO Questions(question_id, question_text) VALUES('$question_id', '$question_text')";
    $insert_row = $conn->query($query) or die($conn->error . __LINE__);

    // Verify the insertion
    if ($insert_row) {
        foreach ($choices as $choice => $value) {
            if ($value != '') {
                if ($correct_choice == $choice) {
                    $is_correct = 1;
                } else {
                    $is_correct = 0;
                }

                // Choices settings
                $query = "INSERT INTO Choices(question_id, is_correct, choice_text) VALUES('$question_id', '$is_correct', '$value')";
                $insert_row = $conn->query($query) or die($conn->error . __LINE__);

                if ($insert_row) {
                    continue;
                } else {
                    die('Error: (' . $conn->errno . ')' . $conn->error);
                }
            }
        }
        $message = 'Question successfully inserted!';
    }
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
    <script>
        // Enhanced validation function to check all fields
        function validateForm() {
            var questionText = document.forms["questionForm"]["question_text"].value;
            var choice1 = document.forms["questionForm"]["choice1"].value;
            var choice2 = document.forms["questionForm"]["choice2"].value;
            var choice3 = document.forms["questionForm"]["choice3"].value;
            var correctChoice = document.forms["questionForm"]["correct_choice"].value;

            if (questionText == "") {
                alert("Please fill in the question text.");
                return false;
            }

            if (choice1 == "" || choice2 == "" || choice3 == "") {
                alert("Please fill in all the choices.");
                return false;
            }

            if (correctChoice < 1 || correctChoice > 3) {
                alert("Please select a valid correct option number (between 1 and 3).");
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
            <h2>Insert a question</h2>
            <?php if (isset($message)) { echo '<p>' . $message . '</p>'; } ?>
            <form name="questionForm" method="post" action="add.php" onsubmit="return validateForm()">
                <p>
                    <label>Question number:</label>
                    <input type="number" value="<?php echo $next; ?>" name="question_number" readonly />
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
                    <input type="number" name="correct_choice" min="1" max="3" required />
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