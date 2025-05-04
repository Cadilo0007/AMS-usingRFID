<?php
session_start();

include('includes/header.php');
include('includes/navbar.php');
include('includes/footer.php');

include('database/dbconfig.php');

$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$start = ($page - 1) * $limit; // Starting record

// Query to retrieve departments for pagination
$query = "
    SELECT * FROM schedules
    LIMIT $start, $limit
";
$query_run = mysqli_query($connection, $query);

// Fetch total number of records for pagination
$total_query = "SELECT COUNT(*) AS total FROM schedules";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}
?>

        <!-- ========================= Main ==================== -->
        <div class="main">
            <div class="topbar">
                <div class="toggle">
                    <ion-icon name="menu-sharp"></ion-icon>
                </div>

                <div class="user">
                    <img src="assets/image/user-profile-icon-free-vector.jpg" alt="">
                    <span class="user-name">
                        <?php
                            echo $_SESSION['username'];
                        ?>
                    </span>
                </div>
            </div>
            <div class="detailstwo">
                <div class="attendanceView-Em">
                    <div class="cardHeader">
                        <caption><h2>Manage Schedule</h2></caption>
                        <button class="add" id="myBtn"><a class="btn">+Add New</a></button>
                    </div>
                    
                    <!-- Separate registration form -->
                    <div class="modal" id="myModal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <!-- Your registration form goes here -->
                            <div class="addEmployeeForm" id="addEmployeeForm">
                                <h2 style="color: #D81F26;">New Schedule</h2>
                                
                                <form action="code.php" method="POST">
                                    <div class="user-details">
                                        <div class="input-box">
                                            <label for="employeeNo">From:</label>
                                            <input type="time" id="from" name="from" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="to">To:</label>
                                            <input type="time" id="to" name="to" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="lastName">Employee Name:</label>
                                            <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                            <select id="empnamet" name="empname" placeholder="Select Employee" required>
                                                <option value="" disabled selected>--Select Employee--</option>
                                                <?php
                                                // Fetch employee names from the database
                                                $connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
                                                $emp_query = "SELECT id, CONCAT(firstname, ' ', lastname) as name FROM users_data";
                                                $emp_result = mysqli_query($connection, $emp_query);
                                                
                                                while ($emp = mysqli_fetch_assoc($emp_result)) {
                                                    echo '<option value="' . $emp['id'] . '">' . htmlspecialchars($emp['name']) . '</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>      
                                    <div class="button">
                                        <input type="submit" value="Save" name="schedulebtn">
                                    </div>
                                </form>

                            </div>
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
                                <td>Employee Name</td>
                                <td>Inclusive Time From</td>
                                <td>Inclusive Time To</td>
                                <td>Actions</td>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            // Fetch schedule data from the database
                            $schedule_query = "SELECT s.id, CONCAT(u.firstname, ' ', u.lastname) as name, s.from_time, s.to_time
                                            FROM schedules s
                                            LEFT JOIN users_data u ON s.user_id = u.id";
                            $schedule_result = mysqli_query($connection, $schedule_query);

                            if (mysqli_num_rows($schedule_result) > 0) {
                                while ($schedule = mysqli_fetch_assoc($schedule_result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($schedule['name']); ?></td>
                                        <td><?php echo date('g:i A', strtotime($schedule['from_time'])); ?></td>
                                        <td><?php echo date('g:i A', strtotime($schedule['to_time'])); ?></td>
                                        <td>
                                            <br>
                                            <form action="code.php" method="post">
                                                <input type="hidden" name="delete_id" value="<?php echo htmlspecialchars($schedule['id']); ?>">
                                                <a><button class="status delete" type="submit" name="deletesched_btn">Delete</button></a>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php
                                }
                            } else {
                                echo '<tr><td colspan="6">No schedules found</td></tr>';
                            }
                            ?>
                        </tbody>

                    </table>
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