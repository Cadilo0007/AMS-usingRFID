<?php 
session_start();
include('assets/header.php');
include('assets/sidebar.php');

$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'];
$query = "
    SELECT users_data.*, dept_category.department_name AS department_name
    FROM users_data
    LEFT JOIN dept_category ON users_data.department = dept_category.id
    WHERE users_data.email = ?
";

$stmt = $connection->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $connection->error);
}

$stmt->bind_param("s", $email);
if (!$stmt->execute()) {
    die("Execute failed: " . $stmt->error);
}

$query_run = $stmt->get_result();
if (!$query_run) {
    die("Query failed: " . $stmt->error); // Debugging statement
}
?>


?>
<body>
    <div class="container">
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
                        <?php echo htmlspecialchars($_SESSION['email']); ?>
                    </span>
                </div>
            </div>
            <div class="detailstwo">
                <div class="attendanceView-Em">
                    <div class="cardHeader">
                        <caption><h2>My Account</h2></caption>
                    </div>
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
                            if ($query_run->num_rows > 0) {
                                while ($row = $query_run->fetch_assoc()) {
                            ?>
                                    <tr class="employeeNo">
                                        <td><?php echo htmlspecialchars($row['firstname']) . ' ' . htmlspecialchars($row['lastname']); ?></td>
                                        <td><?php echo htmlspecialchars($row['rfid_tag']); ?></td>
                                        <td><?php echo htmlspecialchars($row['number']); ?></td>
                                        <td><?php echo htmlspecialchars($row['designation']); ?></td>
                                        <td><?php echo htmlspecialchars($row['department_name']); ?></td>
                                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                                        <td>
                                            <form action="account_edit.php" method="post" class="button">
                                                <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                                                <button class="status edit" type="submit" name="accountedit_btn">View/Edit</button>
                                            </form>
                                        </td>
                                    </tr>
                            <?php
                                }
                            } else {
                                echo "<tr><td colspan='7'>No Record Found</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>    
            </div>
        </div>
    </div>    

    <!-- Close the prepared statement and connection -->
    <?php
    $stmt->close();
    $connection->close();
    ?>
</body>
<script src="../ADMIN/assets/js/main.js"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
</html>
