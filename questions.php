<?php
session_start(); 

// Reset lives and score if this is the first question of a new attempt
if (isset($_GET['n']) && $_GET['n'] == 1) {
    $_SESSION['lives'] = 3; // Reset lives to 3 for a new attempt
    $_SESSION['score'] = 0; // Optionally reset score as well
}


$servername = "localhost";
$username = "kyka0xo_WebAppQuiz";
$password = "footballquiz";
$dbname = "kyka0xo_110209";
session_start();

// Check if the logout parameter is set
if (isset($_GET['logout'])) {
    // Unset all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to login.php
    header('Location: login.php');
    exit();
}
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// Set Question numbers
$number = (int)$_GET['n'];

// Get the Question
$query = "SELECT * FROM `Questions` WHERE question_id = $number";

// Retrieve the results
$result = $conn->query($query) or die($conn->error . __LINE__);
$question = $result->fetch_assoc();

// Retrieve the Choices
$query = "SELECT * FROM `Choices` WHERE question_id = $number";

// Retrieve the results
$choices = $conn->query($query) or die($conn->error . __LINE__);

// Retrieve the total number of questions
$query = "SELECT COUNT(*) AS total FROM `Questions`";
$result = $conn->query($query) or die($conn->error . __LINE__);
$row = $result->fetch_assoc();
$total = $row['total'];
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome to the football world</title>
    <style>
    logout-button {
            background-color: red; 
            color: purple; 
            padding: 20px 30px; 
            text-decoration: none; 
            border-radius: 20px; 
            font-weight: bold; 
        }

        .logout-button:hover {
            background-color: darkred; 
        }
    body {
            background-image: url('Images/questions_background.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: Arial, sans-serif;
            
        }
        body {
            background-color: #f8f9fa; 
            font-family: 
        }

        .container {
            background-color: #fff; 
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            width: 80%; /* Adjust width as needed */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
        }

        .present {
            font-size: 18px;
            font-weight: bold;
        }

        .question {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .choices {
            list-style-type: none;
            padding: 0;
        }

        .choices li {
            margin-bottom: 10px;
        }

        .choices input[type="radio"] {
            margin-right: 10px;
        }

    input[type="submit"] {
            background-color: green; 
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

    input[type="submit"]:hover {
            background-color: #0056b3; 
        }
        
    </style>
  </head>

  <body>
    <header>
        <div style="text-align: left; margin-bottom: 40px;">
                <a href="?logout=1" class="logout-button">Log Out</a>
            </div>
      <div class="container">
        <h1>Football Quiz</h1>
      </div>
      
    </header>

    <main>
      <div class="container">
          <div id="timer" style="color: red; font-size: 20px;"></div>
        <div class="present"><?php echo $question['question_id'] . ' of ' . $total; ?></div>
        <p class="question">
          <?php echo $question['question_text']; ?>
        </p>
        <form method="post" action="process.php" id="quizForm">
                  <ul class="choices">
            <?php while ($row = $choices->fetch_assoc()): ?>
              <li>
                <input name="choice" type="radio" value="<?php echo $row['choice_id']; ?>" />
                <?php echo $row['choice_text']; ?>
              </li>
            <?php endwhile; ?>
          </ul>
          <input type="submit" value="Submit" />
          <input type="hidden" name="number" value="<?php echo $number; ?>"/>
          <p>Lives Remaining: <?php echo $_SESSION['lives']; ?></p>
        </form>
      </div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      let timeLeft = 30; // time in seconds
      const timerElement = document.getElementById('timer');
      timerElement.innerHTML = 'Time Remaining: ' + timeLeft;

      const timer = setInterval(() => {
        timeLeft--;
        timerElement.innerHTML = 'Time Remaining: ' + timeLeft;
        if (timeLeft <= 0) {
          clearInterval(timer);
          // Logic to figure out  what happens when the timer runs out
          //automatically submit the form
          document.forms[0].submit();
        }
      }, 1000);
      // Add event listener to the form submit
        document.getElementById('quizForm').addEventListener('submit', function(e) {
            // Check if a choice has been selected
            const choices = document.querySelectorAll('input[name="choice"]');
            const isSelected = Array.from(choices).some(radio => radio.checked);

            // If no choice is selected, prevent form submission and alert the user
            if (!isSelected) {
                e.preventDefault(); // Prevent form submission
                alert('Please select a choice before proceeding.');
            }
        });
    });
    </script>
  </body>
</html>

<?php
// Close the database connection
$conn->close();
?>