<?php
// donate.php

// Include the file that starts the session and sets up $connection
include("connect.php");

// Check if user is logged in
if (!isset($_SESSION['name']) || $_SESSION['name'] == '') {
    header("location: signin.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
  />
  <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
  <link rel="stylesheet" href="admin.css" />
  <title>Admin Dashboard Panel</title>
</head>
<body>
  <nav>
    <div class="logo-name">
      <div class="logo-image"></div>
      <span class="logo_name">Consumer</span>
    </div>

    <div class="menu-items">
      <ul class="nav-links">
        <li>
          <a href="admin.php">
            <i class="uil uil-estate"></i>
            <span class="link-name">Dashboard</span>
          </a>
        </li>

        <li>
          <a href="#">
            <i class="uil uil-heart"></i>
            <span class="link-name">Donates</span>
          </a>
        </li>

        <li>
          <a href="adminprofile.php">
            <i class="uil uil-user"></i>
            <span class="link-name">Profile</span>
          </a>
        </li>
      </ul>

      <ul class="logout-mode">
        <li>
          <a href="../logout.php">
            <i class="uil uil-signout"></i>
            <span class="link-name">Logout</span>
          </a>
        </li>
        <li class="mode">
          <a href="#">
            <i class="uil uil-moon"></i>
            <span class="link-name">Dark Mode</span>
          </a>
          <div class="mode-toggle">
            <span class="switch"></span>
          </div>
        </li>
      </ul>
    </div>
  </nav>

  <section class="dashboard">
    <div class="top">
      <i class="uil uil-bars sidebar-toggle"></i>
      <p class="logo">Donate <b style="color: #8aaee0;">Dish</b></p>
      <p class="user"></p>
    </div>

    <br /><br /><br />

    <div class="activity">
      <div class="location">
        <form method="post">
          <label for="location" class="logo">Select Location:</label>
          <select id="location" name="location">
            <option value="chennai">chennai</option>
            <option value="madurai">madurai</option>
            <option value="coimbatore">coimbatore</option>
          </select>
          <input type="submit" value="Get Details" style="background-color: #8aaee0; color: white; border: none; padding: 10px 15px; font-size: 16px; cursor: pointer; border-radius: 5px;">

        </form>
        <br>

        <?php
        // If user submitted the form
        if (isset($_POST['location'])) {
          $location = $_POST['location'];
          
          // Query the database for that location
          $sql = "SELECT * FROM food_donations WHERE location = '$location'";
          $result = mysqli_query($connection, $sql);

          if (!$result) {
            die("Query failed: " . mysqli_error($connection));
          }

          if (mysqli_num_rows($result) > 0) {
            echo "<div class='table-container'>";
            echo "<div class='table-wrapper'>";
            echo "<table class='table'>";
            echo "<thead>
                    <tr>
                      <th>Name</th>
                      <th>Food</th>
                      <th>Category</th>
                      <th>Phone No</th>
                      <th>Date/Time</th>
                      <th>Address</th>
                      <th>Quantity</th>
                    </tr>
                  </thead>
                  <tbody>";

            while ($row = mysqli_fetch_assoc($result)) {
              echo "<tr>
                      <td data-label='Name'>{$row['name']}</td>
                      <td data-label='Food'>{$row['food']}</td>
                      <td data-label='Category'>{$row['category']}</td>
                      <td data-label='PhoneNo'>{$row['phoneno']}</td>
                      <td data-label='Date'>{$row['date']}</td>
                      <td data-label='Address'>{$row['address']}</td>
                      <td data-label='Quantity'>{$row['quantity']}</td>
                    </tr>";
            }
            echo "</tbody></table></div></div>";
          } else {
            echo "<p>No results found for {$location}.</p>";
          }
        }
        ?>
      </div>
    </div>
  </section>

  <script src="admin.js"></script>
</body>
</html>
