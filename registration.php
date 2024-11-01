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
$successMessage = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql_insert_user = "INSERT INTO User_Login (username, password) VALUES ('$username', '$hashed_password')";

    if ($conn->query($sql_insert_user) === TRUE) {
        $successMessage = "User registration successful"; 
    } else {
        echo "Error creating user: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Sign up</title>
    <style type="text/css">
        body {
            background-image: url('Images/SignUp.jpg'); 
            background-size: cover;
            background-position: center;
        }
        #text {
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid 2px #aaa;
            width: 100%;
            color: black;
        }
        #button {
            padding: 10px;
            width: 100px;
            color: black;
            background-color: white;
            border: 2px; /* Add border to the button */
            display: block;
            margin: 20px auto; /* Center the button horizontally */
        }
        #box {
            background-image: url('Images/login_image.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 20px auto;
            width: 430px;
            padding: 20px;
            color: #000;
            border: solid 2px black;
            margin-left: auto; 
            margin-right: 20px; 
            position: relative;
        }
        #label {
            color: #000;
        }
        a {
            text-decoration: none;
            color: #ff5733;
            font-weight: bold;
        }
        a:hover {
            text-decoration: underline;
            color: white;
        }
        #login_link {
            text-decoration: underline;
            color: #7FFF00;
        }
        #success_message {
            color: darkred;
            margin-top: 30px;
            font-weight: 900;
            position: absolute;
            top:0;
           left:0;
           width: 100%;
           text-align: center;
        }
    </style>
</head>
<body>
    <div id="box">
        <?php if ($successMessage !== ""): ?>
            <div id="success_message"><?php echo $successMessage; ?></div> 
        <?php endif; ?>
        <form method="post">
            <div style="font-size: 25px; margin: 10px;">Sign Up</div>
            <label for="username" style="color: white;">Username:</label>
            <input id="text" type="text" name="username" required><br>
            <label for="password" style="color: white;">Password:</label>
            <input id="text" type="password" name="password" required><br>
            <input id="button" type="submit" value="Register"><br>
            <a href="login.php" id="login_link">Click to Login</a><br>
        </form>
    </div>
</body>
</html>