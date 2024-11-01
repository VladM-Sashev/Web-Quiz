<?php
$servername = "localhost";
$dbUsername = "kyka0xo_WebAppQuiz"; 
$password = "footballquiz";
$dbname = "kyka0xo_110209";
session_start();

// Create connection
$conn = new mysqli($servername, $dbUsername, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    
    // Using prepared statements to prevent SQL Injection
    $sql_check_user = $conn->prepare("SELECT * FROM User_Login WHERE username=?");
    $sql_check_user->bind_param("s", $username);
    $sql_check_user->execute();
    $result = $sql_check_user->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row["password"])) {
            $_SESSION["username"] = $username;
            header("Location: beginning.php");
            exit();
        } else {
             $errorMessage = "Incorrect password";
        }
    } else {
         $errorMessage = "User not found";
    }
    $sql_check_user->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style type="text/css">
     body {
            background-image: url('Images/back_login.jpg'); 
            background-size: cover;
            background-position: center;
        }
        #text{
            height: 25px;
            border-radius: 5px;
            padding: 4px;
            border: solid 2px #aaa;
            width: 100%;
            color: black;
        }
        #button{
            padding: 10px;
            width: 100px;
            color: black;
            border: none;
            cursor: pointer;
        }
        #box{
           background-image: url('Images/login_image.jpg'); 
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            margin: 20px auto;
            width: 430px;
            padding: 20px;
            color: #000;
            border: solid 2px black;
            positon: relative;
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
        #registration_link {
            text-decoration: underline; 
            color: #7FFF00;
        }
        #remember_me_label {
            color: #7FFF00;
            font-weight: bold; 
            display: inline-block; 
            margin-bottom: 10px; 
        }
        input[type="submit"] {
        background-color: white; 
        color: #fff;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: grey;
    }
    #error_message {
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
        <form method="post">
            <div style="font-size: 25px; margin: 10px;">Login</div>
            <label for="username" style="color: white;">Username:</label>
            <input id="text" type="text" name="username" required><br>
            <label for="password" style="color: white;">Password:</label>
            <input id="text" type="password" name="password" required><br>
            <input id="remember_me" type="checkbox" name="remember_me">
             <label for="remember_me" id="remember_me_label">Remember me</label><br>
            <input id="button" type="submit" value="Login"><br>
            <div id="error_message"><?php echo $errorMessage; ?></div>
            <a href="registration.php" id="registration_link">Click to make Registration</a><br>
        </form>
    </div>
</body>
</html>