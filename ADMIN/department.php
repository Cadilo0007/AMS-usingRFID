<?php
session_start();

include('includes/header.php');
include('includes/navbar.php');
include('includes/footer.php');

include('database/dbconfig.php'); // Ensure this file sets up $connection correctly

// Pagination variables
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10; // Records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Current page
$start = ($page - 1) * $limit; // Starting record

// Query to retrieve departments for pagination
$query = "
    SELECT * FROM dept_category
    LIMIT $start, $limit
";
$query_run = mysqli_query($connection, $query);

// Fetch total number of records for pagination
$total_query = "SELECT COUNT(*) AS total FROM dept_category";
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
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </span>
        </div>
    </div>
    <div class="about">
    </div>
    <!-- dept view -->
    <div class="detailstwo">
        <div class="attendanceView-Em">
            <div class="cardHeader">
                <caption><h2>Manage Department</h2></caption>
                <button class="add" id="myBtn"><a class="btn">+Add Department</a></button>
            </div>
            <!-- Separate registration form -->
            <div class="modal" id="myModal">
                <div class="modal-content" id="deptmodal">
                    <span class="close">&times;</span>
                    <div class="addEmployeeForm" id="addEmployeeForm">
                        <h2 style="color: #D81F26;">Add Department</h2>
                        <form action="code.php" method="POST" enctype="multipart/form-data">
                            <div class="user-details">
                                <div class="input-box">
                                    <label for="deptname">Department Name:</label>
                                    <input type="text" placeholder="Enter Department" id="deptname" name="deptname" required>
                                </div>
                                <div class="input-box">
                                    <label for="deptabbr">Department Abbreviation:</label>
                                    <input type="text" placeholder="Enter Abbreviation" id="deptabbr" name="deptabbr" required>
                                </div>
                                <div class="">
                                    <label for="dept_image" style="color:#ffff;">Dept Image</label>
                                    <input type="file" name="dept_image">
                                </div>
                            </div>
                            <div class="button">
                                <input type="submit" name="dept_save" value="Save">
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
            // Display success or status messages
            if (isset($_SESSION['success']) && $_SESSION['success'] != '') {
                echo '<h2>' . htmlspecialchars($_SESSION['success']) . '</h2>';
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['status']) && $_SESSION['status'] != '') {
                echo '<h2>' . htmlspecialchars($_SESSION['status']) . '</h2>';
                unset($_SESSION['status']);
            }
            ?>
            <table class="All">
                <thead>
                    <tr>
                        <td>Department</td>
                        <td>Abbreviation</td>
                        <td>Image</td>
                        <td>Status</td>
                    </tr>
                </thead>
                <tbody id="departmentTable">
                    <?php
                    if (mysqli_num_rows($query_run) > 0) {
                        while ($row = mysqli_fetch_assoc($query_run)) {
                            ?>
                            <tr class="employeeNo">
                                <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['abbreviation']); ?></td>
                                <td><img src="upload/department/<?php echo htmlspecialchars($row['image']); ?>" alt="Dept Image" style="width: 50px; height: auto;"></td>
                                <td>
                                    <form action="department_edit.php" method="POST" class="button">
                                        <input type="hidden" name="edit_id" value="<?php echo $row['id']; ?>">
                                        <a href="#"><button class="status edit" type="submit" name="edit_btn">View/Edit</button></a>
                                    </form>
                                    <br>
                                    <form action="code.php" method="post">
                                        <input type="hidden" name="deletedept_id" value="<?php echo $row['id']; ?>">
                                        <a href="#"><button class="status delete" type="submit" name="deletedept_btn">Delete</button></a>
                                    </form>
                                </td>
                            </tr>
                            <?php
                        }
                    } else {
                        echo "<tr><td colspan='4'>No Departments Found</td></tr>";
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
