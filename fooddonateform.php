<?php
// Start session at the very top
session_start();

// Include the correct database connection file
require_once 'connection.php';

// Ensure the user is logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
    header("Location: signin.php");
    exit();
}

// Get user email from session
$emailid = $_SESSION['email'];

if (isset($_POST['submit'])) {
    // Check if connection is established
    if (!$connection) {
        die("❌ Database connection error.");
    }

    // Get user input and sanitize
    $foodname = mysqli_real_escape_string($connection, $_POST['foodname']);
    $meal = mysqli_real_escape_string($connection, $_POST['meal']);
    $category = mysqli_real_escape_string($connection, $_POST['image-choice']);
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $phoneno = mysqli_real_escape_string($connection, $_POST['phoneno']);
    $district = mysqli_real_escape_string($connection, $_POST['district']);
    $address = mysqli_real_escape_string($connection, $_POST['address']);
    $name = mysqli_real_escape_string($connection, $_POST['name']);

    // Insert data into database
    $query = "INSERT INTO food_donations (email, food, type, category, phoneno, location, address, name, quantity) 
              VALUES ('$emailid', '$foodname', '$meal', '$category', '$phoneno', '$district', '$address', '$name', '$quantity')";

    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        echo '<script type="text/javascript">alert("✅ Data saved successfully!");</script>';
        header("Location: delivery.html");
        exit();
    } else {
        echo '<script type="text/javascript">alert("❌ Data not saved. Please try again.");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Donate</title>
    <link rel="stylesheet" href="loginstyle.css">

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
    <div class="container">
        <div class="regformf">
            <form action="" method="post">
                <p class="logo">Donate <b style="color: #8aaee0;">Dish</b></p>

                <div class="input">
                    <label for="foodname">Food Name:</label>
                    <input type="text" id="foodname" name="foodname" required/>
                </div>

                <div class="radio">
                    <label for="meal">Meal type :</label> 
                    <br><br>
                    <input type="radio" name="meal" id="veg" value="veg" required/>
                    <label for="veg" style="padding-right: 40px;">Veg</label>
                    <input type="radio" name="meal" id="Non-veg" value="Non-veg">
                    <label for="Non-veg">Non-veg</label>
                </div>

                <br>

                <div class="input">
                    <label for="food">Select the Category:</label>
                    <br><br>
                    <div class="image-radio-group">
                        <input type="radio" id="raw-food" name="image-choice" value="raw-food">
                        <label for="raw-food">
                            <img src="img/raw-food.png" alt="raw-food">
                        </label>
                        <input type="radio" id="cooked-food" name="image-choice" value="cooked-food" checked>
                        <label for="cooked-food">
                            <img src="img/cooked-food.png" alt="cooked-food">
                        </label>
                        <input type="radio" id="packed-food" name="image-choice" value="packed-food">
                        <label for="packed-food">
                            <img src="img/packed-food.png" alt="packed-food">
                        </label>
                    </div>
                </div>

                <div class="input">
                    <label for="quantity">Quantity (number of persons/kg):</label>
                    <input type="text" id="quantity" name="quantity" required/>
                </div>

                <b><p style="text-align: center;">Contact Details</p></b>

                <div class="input">
                    <div>
                        <label for="name">Name:</label>
                        <input type="text" id="name" name="name" value="<?php echo $_SESSION['name']; ?>" required/>
                    </div>
                    <div>
                        <label for="phoneno">Phone No:</label>
                        <input type="text" id="phoneno" name="phoneno" maxlength="10" pattern="[0-9]{10}" required />
                    </div>
                </div>

                <div class="input">
                    <label for="district">District:</label>
                    <select id="district" name="district" style="padding:10px;">
                        <option value="chennai">Chennai</option>
                        <option value="kancheepuram">Kancheepuram</option>
                        <option value="thiruvallur">Thiruvallur</option>
                        <option value="vellore">Vellore</option>
                        <option value="tiruvannamalai">Tiruvannamalai</option>
                        <option value="tiruppur">Tiruppur</option>
                        <option value="coimbatore">Coimbatore</option>
                        <option value="erode">Erode</option>
                        <option value="salem">Salem</option>
                        <option value="namakkal">Namakkal</option>
                        <option value="tiruchirappalli">Tiruchirappalli</option>
                        <option value="thanjavur">Thanjavur</option>
                        <option value="pudukkottai">Pudukkottai</option>
                        <option value="karur">Karur</option>
                        <option value="ariyalur">Ariyalur</option>
                        <option value="perambalur">Perambalur</option>
                        <option value="madurai" selected>Madurai</option>
                        <option value="virudhunagar">Virudhunagar</option>
                        <option value="dindigul">Dindigul</option>
                        <option value="ramanathapuram">Ramanathapuram</option>
                        <option value="sivaganga">Sivaganga</option>
                        <option value="thoothukkudi">Thoothukkudi</option>
                        <option value="tirunelveli">Tirunelveli</option>
                        <option value="tenkasi">Tenkasi</option>
                        <option value="kanniyakumari">Kanniyakumari</option>
                    </select> 

                    <label for="address" style="padding-left: 10px;">Address:</label>
                    <input type="text" id="address" name="address" required/><br>
                </div>

                <div class="btn">
                    <button type="submit" name="submit">Submit</button>
                </div>

            </form>
        </div>
    </div>
</body>
</html>
