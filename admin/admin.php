<?php
/**
 * admin.php
 */

// Always start the session before any HTML output
session_start();
ob_start();

// -- OPTIONAL: If you have a separate 'connect.php', include it here.
// -- But if you do, be sure you're not redefining the connection again below.
// include("connect.php");

// If you have your own connect logic, do it here:
//$connection = mysqli_connect("127.0.0.1", "root", "", "sample");
$connection = mysqli_connect("127.0.0.1", "root", "", "sample", 4307); // if port is 3307
// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in
if (!isset($_SESSION['name']) || empty($_SESSION['name'])) {
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
    <title>Admin Dashboard Panel</title>

    <!-- CSS Links -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"
    />
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css" />
    <link rel="stylesheet" href="admin.css" />

</head>
<body>
    <nav>
        <div class="logo-name">
            <div class="logo-image">
                <!-- <img src="images/logo.png" alt=""> -->
            </div>
            <span class="logo_name">Consumer</span>
        </div>

        <div class="menu-items">
            <ul class="nav-links">
                <li>
                    <a href="#">
                        <i class="uil uil-estate"></i>
                        <span class="link-name">Dashboard</span>
                    </a>
                </li>

                <li>
                    <a href="donate.php">
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

        <div class="dash-content">
            <div class="overview">
                <div class="title">
                    <i class="uil uil-tachometer-fast-alt"></i>
                    <span class="text">Dashboard</span>
                </div>

                <div class="boxes">
                    <!-- Total Users -->
                    <div class="box box1">
                        <i class="uil uil-user"></i>
                        <span class="text">Total users</span>
                        <?php
                        $query  = "SELECT COUNT(*) as count FROM login";
                        $result = mysqli_query($connection, $query);
                        $row    = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                    </div>

                    <!-- Total Donates -->
                    <div class="box box3">
                        <i class="uil uil-heart"></i>
                        <span class="text">Total donates</span>
                        <?php
                        $query  = "SELECT COUNT(*) as count FROM food_donations";
                        $result = mysqli_query($connection, $query);
                        $row    = mysqli_fetch_assoc($result);
                        echo "<span class=\"number\">".$row['count']."</span>";
                        ?>
                    </div>
                </div>
            </div>

            <div class="activity">
                <div class="title">
                    <i class="uil uil-clock-three"></i>
                    <span class="text">Recent Donations</span>
                </div>

                <div class="get">
                    <?php
                    // Retrieve location from session
                    $loc = $_SESSION['location'];
                    $id  = $_SESSION['Aid']; // Admin or delivery person ID?

                    // Fetch unassigned orders for that location
                    $sql = "SELECT * FROM food_donations 
                            WHERE assigned_to IS NULL 
                            AND location = '$loc'";

                    $result = mysqli_query($connection, $sql);
                    if (!$result) {
                        die("Error executing query: " . mysqli_error($connection));
                    }

                    $data = [];
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[] = $row;
                    }

                    // If the delivery person has taken an order, update the assigned_to field
                    if (isset($_POST['food']) && isset($_POST['delivery_person_id'])) {
                        $order_id = $_POST['order_id'];
                        $del_id   = $_POST['delivery_person_id'];

                        // Double check if the order was already assigned
                        $check_sql  = "SELECT * FROM food_donations 
                                       WHERE Fid = '$order_id' 
                                       AND assigned_to IS NOT NULL";
                        $check_res  = mysqli_query($connection, $check_sql);

                        if (mysqli_num_rows($check_res) > 0) {
                            // Order has already been assigned to someone else
                            die("Sorry, this order has already been assigned to someone else.");
                        }

                        // Assign the order
                        $update_sql = "UPDATE food_donations 
                                       SET assigned_to = '$del_id' 
                                       WHERE Fid = '$order_id'";
                        $update_res = mysqli_query($connection, $update_sql);

                        if (!$update_res) {
                            die("Error assigning order: " . mysqli_error($connection));
                        }

                        // Reload the page to prevent duplicate assignments
                        header('Location: ' . $_SERVER['REQUEST_URI']);
                        ob_end_flush();
                        exit;
                    }
                    ?>
                    
                    <!-- Display the unassigned orders in a table -->
                    <div class="table-container">
                        <div class="table-wrapper">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Food</th>
                                        <th>Category</th>
                                        <th>Phone No</th>
                                        <th>Date/Time</th>
                                        <th>Address</th>
                                        <th>Quantity</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($data as $row) { ?>
                                    <tr>
                                        <td data-label="Name"><?= $row['name']; ?></td>
                                        <td data-label="Food"><?= $row['food']; ?></td>
                                        <td data-label="Category"><?= $row['category']; ?></td>
                                        <td data-label="Phone No"><?= $row['phoneno']; ?></td>
                                        <td data-label="Date/Time"><?= $row['date']; ?></td>
                                        <td data-label="Address"><?= $row['address']; ?></td>
                                        <td data-label="Quantity"><?= $row['quantity']; ?></td>
                                        <td data-label="Action">
                                            <?php if (empty($row['assigned_to'])): ?>
                                                <form method="post" action="">
                                                    <input type="hidden" name="order_id" value="<?= $row['Fid'] ?>">
                                                    <input type="hidden" name="delivery_person_id" value="<?= $id ?>">
                                                    <button type="submit" name="food">Get Food</button>
                                                </form>
                                            <?php elseif ($row['assigned_to'] == $id): ?>
                                                <strong>Order assigned to you</strong>
                                            <?php else: ?>
                                                <em>Order assigned to another person</em>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END Display Table -->

                </div>
            </div>
        </div>
    </section>

    <!-- JS Script -->
    <script src="admin.js"></script>
</body>
</html>
