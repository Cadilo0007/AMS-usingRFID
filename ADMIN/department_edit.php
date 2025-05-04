<?php
session_start();
include('includes/header.php');
include('includes/navbar.php');

// Database connection
$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if the edit button was clicked
if (isset($_POST['edit_btn'])) {
    $id = $_POST['edit_id'];
    
    // Use prepared statements for security
    $stmt = $connection->prepare("SELECT * FROM dept_category WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Department</title>
    <!-- Include your CSS files here -->
</head>
<body>
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
    <div class="detailstwo">
        <div class="attendanceView-Em">
            <div class="cardHeader">
                <caption><h2>Edit Department</h2></caption>
            </div>
            <div>      
                <form action="code.php" method="POST" enctype="multipart/form-data">
                    <div style="margin: 20px;">
                        <label for="deptname">Department Name:</label>
                        <input type="text" name="deptname" style="width: 100%; padding: 15px;" value="<?php echo htmlspecialchars($row['department_name']); ?>" required>
                    </div>
                    <div style="margin: 20px;">
                        <label for="deptabbr">Department Abbreviation:</label>
                        <input type="text" name="deptabbr" style="width: 100%; padding: 15px;" value="<?php echo htmlspecialchars($row['abbreviation']); ?>" required>
                    </div>
                    <div style="margin: 20px;">
                        <label for="dept_image">Dept Image:</label>
                        <input type="file" name="dept_image" style="width: 100%; padding: 15px;">
                        <img src="upload/department/<?php echo htmlspecialchars($row['image']); ?>" alt="Current Image" style="width: 100px; height: auto;">
                    </div>
                    <div class="button">
                        <a href="department.php" class="status delete" style="text-decoration: none;">CANCEL</a>
                        <button type="submit" name="update_dept" class="status breakin">UPDATE</button> 
                        <input type="hidden" name="edit_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('includes/scripts.php'); ?>
</body>
</html>
<?php
    } else {
        echo "No record found!";
    }
}
?>
