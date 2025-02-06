<?php
// This includes your login and session logic.
// Make sure this file also provides the $connection variable.
include("login.php"); 

// If a user is not logged in (or name is empty), redirect them to signup
if ($_SESSION['name'] == '') {
    header("location: signup.php");
    exit; // Good practice to exit after a header redirect
}
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Food Donate</title>
    <link rel="stylesheet" href="home.css">
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>
<header>
    <div class="logo">Donate <b style="color: #8aaee0;">Dish</b></div>
    <div class="hamburger">
        <div class="line"></div>
        <div class="line"></div>
        <div class="line"></div>
    </div>
    <nav class="nav-bar">
        <ul>
            <li><a href="home.html">Home</a></li>
            <li><a href="about.html" >About</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="profile.php" class="active">Profile</a></li>
        </ul>
    </nav>
</header>

<script>
    hamburger=document.querySelector(".hamburger");
    hamburger.onclick =function(){
        navBar=document.querySelector(".nav-bar");
        navBar.classList.toggle("active");
    }
</script>
  
<div class="profile">
    <div class="profilebox">
        <p class="headingline" style="text-align: center; font-size:30px;">
            <!-- You can add a small icon here if you’d like -->
            Profile
        </p>

        <div class="info" style="padding-left:10px;">
            <p>Name  : <?php echo $_SESSION['name']; ?></p><br>
            <p>Email : <?php echo $_SESSION['email']; ?></p><br>
            <p>Gender: <?php echo $_SESSION['gender']; ?></p><br>
            
            <a href="logout.php" 
               style="float: left; margin-top: 6px; border-radius:5px; background-color: #06C167; color: white; padding-left: 10px; padding-right: 10px;">
               Logout
            </a>
        </div>

        <br><br>
        <hr>
        <br>
        <p class="heading">Your donations</p>
        
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Food</th>
                            <th>Type</th>
                            <th>Category</th>
                            <th>Quantity</th> <!-- NEW COLUMN -->
                            <th>Date/Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // We assume $connection is available from login.php
                        $email  = $_SESSION['email'];
                        $query  = "SELECT * FROM food_donations WHERE email='$email'";
                        $result = mysqli_query($connection, $query);

                        if ($result) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>
                                        <td>".$row['food']."</td>
                                        <td>".$row['type']."</td>
                                        <td>".$row['category']."</td>
                                        <td>".$row['quantity']."</td>  <!-- Display Quantity -->
                                        <td>".$row['date']."</td>
                                      </tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>          
    </div>
</div>
</body>
</html>
