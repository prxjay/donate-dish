<?php
// Include the database connection file
require_once 'connection.php'; // Ensure this is the correct path

if (isset($_POST['sign'])) {
    // Check if connection is established
    if (!$connection) {
        die("❌ Database connection error.");
    }

    // Get user input and sanitize it
    $username = mysqli_real_escape_string($connection, $_POST['name']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = $_POST['password']; // Don't escape passwords, hashing will take care of security
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists
    $sql = "SELECT * FROM login WHERE email = '$email'";
    $result = mysqli_query($connection, $sql);

    if (!$result) {
        die("❌ Query failed: " . mysqli_error($connection));
    }

    if (mysqli_num_rows($result) > 0) {
        echo "<h1><center>Account already exists</center></h1>";
    } else {
        // Insert new user
        $query = "INSERT INTO login (name, email, password, gender) VALUES ('$username', '$email', '$hashed_password', '$gender')";
        $query_run = mysqli_query($connection, $query);

        if ($query_run) {
            // Redirect to sign-in page after successful registration
            header("Location: signin.php");
            exit();
        } else {
            echo '<script type="text/javascript">alert("❌ Data not saved. Please try again.")</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css?family=Poppins:400,500,600,700&display=swap');

        body {
            background-color:rgb(142, 214, 234); /* Updated background color */
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
    </style>
    
</head>
<body>

    <div class="container">
        <div class="regform">
            <form action="" method="post">
                <p class="logo">Donate <b style="color: #8aaee0;">Dish</b></p>
                <p id="heading">Create your account</p>

                <div class="input">
                    <label class="textlabel" for="name">User Name</label>
                    <input type="text" id="name" name="name" required/>
                </div>

                <div class="input">
                    <label class="textlabel" for="email">Email</label>
                    <input type="email" id="email" name="email" required/>
                </div>

                <label class="textlabel" for="password">Password</label>
                <div class="password">
                    <input type="password" name="password" id="password" required/>
                    <i class="uil uil-eye-slash showHidePw" id="showpassword"></i>                
                </div>

                <div class="radio">
                    <input type="radio" name="gender" id="male" value="male" required/>
                    <label for="male">Male</label>
                    <input type="radio" name="gender" id="female" value="female">
                    <label for="female">Female</label>
                </div>

                <div class="btn">
                    <button type="submit" name="sign">Continue</button>
                </div>

                <div class="signin-up">
                    <p style="font-size: 20px; text-align: center;">Already have an account? <a href="signin.php"> Sign in</a></p>
                </div>
            </form>
        </div>
    </div>

    <script src="admin/login.js"></script>
</body>
</html>
