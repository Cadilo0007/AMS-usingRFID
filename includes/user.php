<?php 
include('security.php'); // Ensure this handles session checks
include('database/dbconfig.php'); // Ensure the path is correct

if (!$connection) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Get the logged-in user's email from the session
$email = $_SESSION['email'];

// Fetch the user's ID based on the email
$user_query = "SELECT id FROM users_data WHERE email='$email'";
$user_result = mysqli_query($connection, $user_query);

if ($user_result) {
    $user_row = mysqli_fetch_assoc($user_result);
    $user_id = $user_row['id'];

    // Fetch attendance data for the logged-in user, joining with user data
    $query = "SELECT a.date, a.time_in, a.lunch_start, a.lunch_end, a.time_out, 
                     u.rfid_tag, u.firstname, u.middlename, u.lastname
              FROM attendance a
              JOIN users_data u ON a.user_id = u.id
              WHERE a.user_id = $user_id";
    $query_run = mysqli_query($connection, $query);

    // Debugging: Check if the query ran successfully
    if (!$query_run) {
        die("Query failed: " . mysqli_error($connection));
    }
} else {
    die("Failed to get user ID: " . mysqli_error($connection));
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Employee Cpanel</title>
    <link rel="icon" href="../ADMIN/assets/image/ICLOGO.png"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Questrial&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../ADMIN/assets/css/style4.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
  <div class="container">
        <div class="navigation"> 
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <img class="logo"src="../ADMIN/assets/image/ICLOGO.png" width="50px"/>
                        </span>
                        <span class="title">Isabela Colleges Inc.</span>
                        <h6>RFID Attendance Monitoring System</h6>
                    </a>
                </li>
                <hr>
                <li class="Active">
                        <a href="user.php" target="_self">
                            <span class="icon">
                                <ion-icon name="calendar-outline"></ion-icon>
                            </span>
                            <span class="title"><h3>Attendance</h3></span>
                        </a>
                </li>

                <li>
                    <a href="schedule.php" target="_self">
                        <span class="icon">
                            <ion-icon name="time-outline"></ion-icon>
                        </span>
                        <span class="title"><h3>Schedule</h3></span>
                    </a>
                </li>
                <li>
                    <a href="account.php" target="_self">
                        <span class="icon">
                            <ion-icon name="key-outline"></ion-icon>
                        </span>
                        <span class="title"><h3>Account</h3></span>
                    </a>
                </li>
                <hr>
                <li>
                    <a href="../logout.php" target="_self">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title"><h3>logout</h3></span>
                    </a>
                </li>
            </ul>
        </div>
         <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-sharp"></ion-icon>
                </div>

                <div class="user">
                    <span class="icon">
                        <ion-icon name="person-outline"></ion-icon>
                    </span>
                    <span class="title">
                        <?php
                            echo $_SESSION['email'];
                        ?>
                    </span>
                </div>

                
                
            </div>
            <div  class="about">
                <caption><h2>Dashboard</h2></caption>
            </div>


            <!-- ================ Attendance View ================= -->
            <div class="detailstwo">
                <div class="attendanceView">
                    <div class="cardHeader">
                        <caption><h2>Attendance View</h2></caption>
                        <button class="add"><a href="Attendance.html" class="btn">View All</a></button>
                    
                    </div>
                        <label for="entriesSelect"><a class="btn">Show
                                <select id="entriesSelect" onchange="updateTable()">
                                    <option value="10">10</option>
                                    <option value="25">25</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                entries</a>
                        </label>   
                        <table class="View">
                            <thead>
                                <tr>
                                    <td>RFID Number</td>
                                    <td>Name</td>
                                    <td>Date</td>
                                    <td>Time In</td>
                                    <td>Lunch Start</td>
                                    <td>Lunch End</td>
                                    <td>Time Out</td>
                                </tr>
                            </thead>
                            <?php
                            // Custom function to replace '12:00 AM' with an empty string
                            function formatTime($time) {
                                return ($time == '12:00 AM') ? '' : $time;
                            }
                            
                            if ($query_run && mysqli_num_rows($query_run) > 0) {
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    $date = new DateTime($row['date']);
                                    $formattedDate = $date->format('M d Y');
                                    $time_in = date('g:i A', strtotime($row['time_in']));
                                    $lunch_start = date('g:i A', strtotime($row['lunch_start']));
                                    $lunch_end = date('g:i A', strtotime($row['lunch_end']));
                                    $time_out = date('g:i A', strtotime($row['time_out']));
                                    
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['rfid_tag']); ?></td>
                                        <td><?php echo htmlspecialchars($row['firstname'] . ' ' . $row['middlename'] . ' ' . $row['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($formattedDate); ?></td>
                                        <td><span class="status timein"><?php echo formatTime($time_in); ?></span></td>
                                        <td><span class="status breakout"><?php echo formatTime($lunch_start); ?></span></td>
                                        <td><span class="status breakin"><?php echo formatTime($lunch_end); ?></span></td>
                                        <td><span class="status timeout"><?php echo formatTime($time_out); ?></span></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="7">No records found</td></tr>';
                            }
                            ?>
                        </table>

                </div>
            </div>
        </div>

    </div>    
</body>
<script src="../ADMIN/assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

</html>