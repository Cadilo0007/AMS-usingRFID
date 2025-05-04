<?php
session_start();

// Establish database connection
$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Handle CSV download
if (isset($_GET['download']) && $_GET['download'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="attendance_data.csv"');
    header('Pragma: no-cache');
    header('Expires: 0');

    $output = fopen('php://output', 'w');
    $header = ['ID', 'RFID Tag', 'Name', 'Date', 'Time In', 'Lunch Start', 'Lunch End', 'Time Out'];
    fputcsv($output, $header);

    $query = "
        SELECT 
            attendance.id, 
            users_data.rfid_tag, 
            CONCAT(users_data.firstname, ' ', users_data.lastname) AS name, 
            attendance.date, 
            attendance.time_in, 
            attendance.lunch_start, 
            attendance.lunch_end, 
            attendance.time_out
        FROM 
            attendance
        LEFT JOIN 
            users_data ON attendance.user_id = users_data.id
    ";

    $result = mysqli_query($connection, $query);
    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

include('includes/header.php');
include('includes/navbar.php');
include('includes/footer.php');

// Count total records
$count_query = "SELECT COUNT(*) as total FROM attendance";
$count_result = mysqli_query($connection, $count_query);
$row = mysqli_fetch_assoc($count_result);
$total_records = $row['total'];

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page - 1) * $limit;
$total_pages = ceil($total_records / $limit);

$query = "
    SELECT 
        attendance.id, 
        users_data.rfid_tag, 
        CONCAT(users_data.firstname, ' ', users_data.lastname) AS name, 
        attendance.date, 
        attendance.time_in, 
        attendance.lunch_start, 
        attendance.lunch_end, 
        attendance.time_out
    FROM 
        attendance
    LEFT JOIN 
        users_data ON attendance.user_id = users_data.id
    LIMIT $start, $limit
";

$query_run = mysqli_query($connection, $query);
if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rfid_tag = $_POST['rfid_tag'];
    $password = $_POST['password'];
    $action = $_POST['action'];

    $query = "SELECT * FROM users_data WHERE rfid_tag = ? LIMIT 1";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $rfid_tag);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $user_id = $user['id'];
        $dateNow = date("Y-m-d");
        $timeNow = date("H:i");

        switch ($action) {
            case 'time_in':
                $attendanceQuery = "SELECT * FROM attendance WHERE user_id = ? AND date = ?";
                $stmt = $connection->prepare($attendanceQuery);
                $stmt->bind_param("is", $user_id, $dateNow);
                $stmt->execute();
                $existing = $stmt->get_result()->fetch_assoc();

                if (!$existing) {
                    $attendanceQuery = "INSERT INTO attendance (user_id, date, time_in) VALUES (?, ?, ?)";
                    $stmt = $connection->prepare($attendanceQuery);
                    $stmt->bind_param("iss", $user_id, $dateNow, $timeNow);
                    $stmt->execute();
                    echo "Time in recorded successfully.";
                } else {
                    echo "You have already recorded your time in for today.";
                }
                break;
            case 'time_out':
                $attendanceQuery = "UPDATE attendance SET time_out = ? WHERE user_id = ? AND date = ?";
                $stmt = $connection->prepare($attendanceQuery);
                $stmt->bind_param("sis", $timeNow, $user_id, $dateNow);
                $stmt->execute();
                echo "Time out recorded successfully.";
                break;
            case 'lunch_start':
                $attendanceQuery = "UPDATE attendance SET lunch_start = ? WHERE user_id = ? AND date = ?";
                $stmt = $connection->prepare($attendanceQuery);
                $stmt->bind_param("sis", $timeNow, $user_id, $dateNow);
                $stmt->execute();
                echo "Lunch start recorded successfully.";
                break;
            case 'lunch_end':
                $attendanceQuery = "UPDATE attendance SET lunch_end = ? WHERE user_id = ? AND date = ?";
                $stmt = $connection->prepare($attendanceQuery);
                $stmt->bind_param("sis", $timeNow, $user_id, $dateNow);
                $stmt->execute();
                echo "Lunch end recorded successfully.";
                break;
        }
    } else {
        echo "Invalid RFID Tag.";
    }
}

mysqli_close($connection);
?>


<!----------main------------>
<div class="main">
    <div class="topbar">
        <div class="toggle">
            <ion-icon name="menu-sharp"></ion-icon>
        </div>
        <div class="user">
            <img src="assets/image/user-profile-icon-free-vector.jpg" alt="">
            <span class="user-name">
                <?php echo $_SESSION['username']; ?>
            </span>
        </div>
    </div>
    
    <div class="detailstwo">
        <div class="attendanceView-Em">
            <div class="cardHeader">
                <caption><h2>Attendance View</h2></caption>
                <div class="add">
                    <a href="?download=csv" class="btn">Download</a>
                    <a href="Attendance_print.php" target="_blank" class="btn">Print Attendance</a>
                </div> 
            </div>
            <div class="cardHeader2">
                <label for="entriesSelect">
                    <a class="btn">Show
                        <select id="entriesSelect" onchange="updateTable()">
                            <option value="10" <?php echo $limit == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo $limit == 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo $limit == 50 ? 'selected' : ''; ?>>50</option>
                            <option value="100" <?php echo $limit == 100 ? 'selected' : ''; ?>>100</option>
                        </select>
                        entries
                    </a>
                </label>
                <div class="search">
                    <label>
                        <input type="search" placeholder="Search here" oninput="filterTable()"/>
                        <ion-icon name="search-sharp"></ion-icon>
                    </label>
                </div>
            </div>
            <table class="All">
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
                <tbody>
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
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
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
                </tbody>
            </table>
            <!-- Pagination Controls -->
            <div class="pagination">
                <p>Showing <?php echo ($start + 1); ?> to <?php echo min($start + $limit, $total_records); ?> of <?php echo $total_records; ?> entries</p>
                <ul>
                    <?php if ($page > 1): ?>
                        <li><a href="?page=<?php echo $page - 1; ?>&limit=<?php echo $limit; ?>">Previous</a></li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <li><a href="?page=<?php echo $i; ?>&limit=<?php echo $limit; ?>" <?php if ($i == $page) echo 'class="active"'; ?>><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($page < $total_pages): ?>
                        <li><a href="?page=<?php echo $page + 1; ?>&limit=<?php echo $limit; ?>">Next</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include('includes/scripts.php'); ?>
