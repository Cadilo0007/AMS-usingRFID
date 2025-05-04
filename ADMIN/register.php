<?php
session_start();
include('database/dbconfig.php');
include('includes/header.php');
include('includes/navbar.php');
include('includes/footer.php');

// Include database connection
$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Fetch departments from the database
$dept_query = "SELECT id, department_name FROM dept_category";
$dept_query_run = mysqli_query($connection, $dept_query);

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$start = ($page - 1) * $limit; // Starting record

// Ensure this query is used for displaying the data in the table
$query = "
    SELECT users_data.*, dept_category.department_name AS department_name
    FROM users_data
    LEFT JOIN dept_category ON users_data.department = dept_category.id
    LIMIT $start, $limit
";

$query_run = mysqli_query($connection, $query);

if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}

// Fetch total number of records for pagination
$total_query = "SELECT COUNT(*) AS total FROM users_data";
$total_result = mysqli_query($connection, $total_query);
$total_row = mysqli_fetch_assoc($total_result);
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}
?>
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
                        <caption><h2>Employees</h2></caption>
                        <button class="add" id="myBtn"><a class="btn">+Add New</a></button>
                    </div>
                    <!-- Separate registration form -->
                    <div class="modal" id="myModal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <!-- Your registration form goes here -->
                            <div class="addEmployeeForm" id="addEmployeeForm">
                                <h2 style="color: #D81F26;">NEW EMPLOYEE</h2>
                                
                                <form action="code.php" method="POST">
                                    <h3>Personal Information</h3>
                                    <div class="user-details">
                                        <div class="input-box">
                                            <label for="rfidNo">RFID Number:</label>
                                            <input type="text" placeholder="Enter your RFID Number" id="rfidNo" name="rfidNo" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="firstName">First Name:</label>
                                            <input type="text" placeholder="Enter your First Name" id="firstName" name="firstName" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="middleName">Middle Name (optional):</label>
                                            <input type="text" placeholder="Enter your Middle Name" id="middleName" name="middleName">
                                        </div>
                                        <div class="input-box">
                                            <label for="lastName">Last Name:</label>
                                            <input type="text" placeholder="Enter your Last Name" id="lastName" name="lastName" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="dob">Date of Birth:</label>
                                            <input type="date"placeholder="Date of Birth" max="2010-12-31" id="dob" name="dob">
                                        </div>
                                        <div class="input-box">
                                            <label for="sex">Gender:</label>
                                            <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                            <select id="gender" name="gender" required>
                                                <option value="male">Male</option>
                                                <option value="female">Female</option>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <label for="contactNo">Contact Number (optional):</label>
                                            <input type="tel"placeholder="Enter your Contact Number" id="contactNo" name="contactNo">
                                        </div>
                                        <div class="input-box">
                                            <label for="email">Email Address (optional):</label>
                                            <input type="email" placeholder="Enter your Email Address" id="email" name="email">
                                        </div>
                                        <div class="input-box">
                                            <label for="designation">Designation:</label>
                                            <input type="text"placeholder="Designation" id="designation" name="designation">
                                        </div>
                                        <div class="input-box">
                                            <label for="department">Department:</label>
                                                <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                                <select id="department" name="department_name">
                                                    <option value="" disabled selected>Select Department</option>
                                                    <?php
                                                    // Check if any departments are fetched
                                                    if (mysqli_num_rows($dept_query_run) > 0) {
                                                        while ($row = mysqli_fetch_assoc($dept_query_run)) {
                                                            ?>
                                                            <option value="<?php echo htmlspecialchars($row['id']); ?>">
                                                                <?php echo htmlspecialchars($row['department_name']); ?>
                                                            </option>
                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled>No Departments Available</option>';
                                                    }
                                                    ?>
                                                </select>

                                        </div>
                                        <div class="input-box">
                                            <label for="address">Complete Address:</label>
                                            <input type="text"placeholder="Complete Address" id="address" name="address"> 
                                        </div>
                                    </div>    
                                        <h3>Personal Account</h3>
                                    <div class="user-details">    
                                        <div class="input-box">
                                            <label for="username">Username:</label>
                                            <input type="text" placeholder="username" id="username" name="username" required>
                                        </div>
                                        <div class="input-box">
                                            <label for="password">Password:</label>
                                            <input type="password"minlength="5" maxlength="13"  placeholder="Enter your Password" id="password" name="password" required>
                                        </div>
 
                                        <div class="input-box">
                                            <label for="status">Status:</label>
                                            <ion-icon name="chevron-down-circle-outline"></ion-icon>
                                            <select id="status" name="status" required>
                                                <option value="active">Active</option>
                                                <option value="inactive">Inactive</option>
                                            </select>
                                        </div>
                                        <div class="input-box">
                                            <label for="password">Confirm Password:</label>
                                            <input type="password"minlength="5" maxlength="13"  placeholder="Enter your Password" id="password" name="confirmpassword" required>
                                        </div>
                                    </div>      
                                        <div class="button">
                                          <input type="submit" value="Save" name="registerbtn">
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
                    <?php
                    if(isset($_SESSION['success']) && $_SESSION['success'] !='')
                    {
                      echo '<h2> ' . $_SESSION['success'] .' </h2>';
                      unset($_SESSION['success']);
                    }
                    if(isset($_SESSION['status']) && $_SESSION['status'] !='')
                    {
                      echo '<h2 class="bg-info>  ' . $_SESSION['status'] .' </h2>';
                      unset($_SESSION['status']);
                    }
                    ?>
                    <div>
                     <table class="All" id="dataTable">
                        <thead>
                            <tr>
                                <td>Name</td>
                                <td>RFID Number</td>
                                <td>Contact Number</td>
                                <td>Designation</td>
                                <td>Department</td>
                                <td>Status</td>
                                <td>Actions</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                // Check if there are any records
                                if (mysqli_num_rows($query_run) > 0) {
                                    while ($row = mysqli_fetch_assoc($query_run)) {
                                        
                            ?>

                                <tr class="employeeNo">
                                    <td><?php echo htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['rfid_tag']); ?></td>
                                    <td><?php echo htmlspecialchars($row['number']); ?></td>
                                    <td><?php echo htmlspecialchars($row['designation']); ?></td>
                                    <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td>
                                        <form action="register_edit.php" method="post" class="button">
                                            <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                            <a href=""><button class="status edit" type="submit" name="edit_btn">View/Edit</button></a>
                                        </form>
                                        <br>
                                        <form action="code.php" method="post">
                                            <input type="hidden" name="deleteuser_id" value="<?php echo $row['id']?>">
                                            <a href=""><button class="status delete" type="submit" name="deleteuser_btn">Delete</button></a>
                                        </form>
                                        
                                    </td>
                                </tr>
                                <?php
                                }
                            } else {
                                echo "<tr><td colspan='8'>No Record Found</td></tr>";
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
    </div>

<?php
include ('includes/scripts.php');
?>