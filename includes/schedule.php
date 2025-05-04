<?php
session_start();
include('database/dbconfig.php'); 
$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Ensure the user is logged in
if (!isset($_SESSION['email'])) {
    header('Location: ../employeelogin.php');
    exit();
}

$email = $_SESSION['email'];

// Fetch user ID based on email
$user_query = "SELECT id FROM users_data WHERE email = ?";
$stmt = $connection->prepare($user_query);
$stmt->bind_param("s", $email);
$stmt->execute();
$user_result = $stmt->get_result();
$user_data = $user_result->fetch_assoc();
$user_id = $user_data['id'];

// Fetch schedule data for the logged-in user
$schedule_query = "SELECT * FROM schedules WHERE user_id = ?";
$stmt = $connection->prepare($schedule_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$schedule_result = $stmt->get_result();


include('assets/header.php');
include('assets/sidebar.php');
?>
<body>
  <div class="container">
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
            <div class="detailstwo">
                <div class="attendanceView-Em">
                    <div class="cardHeader2">
                        <caption><h2>My Schedule</h2></caption>
                    </div>
                    <table class="All">
                        <thead>
                            <tr>
                                <td>Employee Name</td>
                                <td>Inclusive Time From</td>
                                <td>Inclusive Time To</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($schedule_result && mysqli_num_rows($schedule_result) > 0) {
                                while ($schedule = mysqli_fetch_assoc($schedule_result)) {
                                    // Fetch employee details for the schedule
                                    $employee_query = "SELECT firstname, middlename, lastname FROM users_data WHERE id = ?";
                                    $stmt = $connection->prepare($employee_query);
                                    $stmt->bind_param("i", $schedule['user_id']);
                                    $stmt->execute();
                                    $employee_result = $stmt->get_result();
                                    $employee = $employee_result->fetch_assoc();

                                    $employee_name = htmlspecialchars($employee['firstname'] . ' ' . $employee['middlename'] . ' ' . $employee['lastname']);
                                    ?>
                                    <tr>
                                        <td><?php echo $employee_name; ?></td>
                                        <td><?php echo date('g:i A', strtotime($schedule['from_time'])); ?></td>
                                        <td><?php echo date('g:i A', strtotime($schedule['to_time'])); ?></td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="4">No schedules found</td></tr>';
                            }
                            ?>
                        </tbody>
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
