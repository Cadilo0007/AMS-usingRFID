<?php
session_start();

include('includes/header.php');
include('includes/navbar.php');
include('includes/footer.php');

// Database connection
$connection = mysqli_connect("localhost", "root", "", "rfidattendance_db");
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['edit_btn'])) {
    $id = $_POST['edit_id'];
    
    // Fetch user data for editing
    $query = "SELECT * FROM users_data WHERE id='$id'";
    $query_run = mysqli_query($connection, $query);
    $row = mysqli_fetch_assoc($query_run);

        // Fetch departments
        $dept_query = "SELECT id, department_name FROM dept_category";
        $dept_query_run = mysqli_query($connection, $dept_query);
}
?>
<div class="main">
  <div class="topbar">
    <div class="toggle">
      <ion-icon name="menu-sharp"></ion-icon>
    </div>
    <div class="user">
      <img src="assets/image/user-profile-icon-free-vector.jpg" alt="">
      <span class="user-name">Admin Name</span>
    </div>
  </div>
  <div class="detailstwo">
    <div class="attendanceView-Em">
      <div class="cardHeader">
          <caption><h2>EDIT Employees</h2></caption>
      </div>
      <div>
        <?php if (isset($row)): ?>
          <form action="code.php" method="POST">
            <div class="" style="margin: 20px;">
              <label for="id">ID Number:</label>
              <input type="hidden" id="id" name="edit_id" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['id']); ?>" readonly>
            </div>
            <div class="" style="margin: 20px;">
              <label for="rfid_tag">RFID Number:</label>
              <input type="text" id="rfid_tag" name="edit_rfidNo" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['rfid_tag']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="edit_firstName" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['firstname']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="middleName">Middle Name:</label>
                <input type="text" id="middleName" name="edit_middleName" style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['middlename']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="edit_lastName" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['lastname']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="edit_dob" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['birthdate']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="sex">Gender:</label>
                <select id="gender" name="edit_gender" required style="width: 100%; padding:15px;">
                    <option value="male" <?php if ($row['gender'] == 'male') echo 'selected'; ?>>Male</option>
                    <option value="female" <?php if ($row['gender'] == 'female') echo 'selected'; ?>>Female</option>
                </select>
            </div>
            <div class="" style="margin: 20px;">
                <label for="contactNo">Contact Number:</label>
                <input type="tel" id="contactNo" name="edit_contact" style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['number']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="edit_email" style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['email']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="designation">Designation:</label>
                <input type="text" id="designation" name="edit_designation" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['designation']); ?>">
            </div>
            <div style="margin: 20px;">
                <label for="department">Department:</label>
                <select id="department" name="edit_department" required style="width: 100%; padding:15px;">
                    <option value="" disabled>Select Department</option>
                    <?php 
                    // Check if the department query was successful
                    if (mysqli_num_rows($dept_query_run) > 0) {
                        while ($dept_row = mysqli_fetch_assoc($dept_query_run)) {
                            $selected = ($row['department'] == $dept_row['id']) ? 'selected' : ''; // Check if the department matches user's department
                            echo '<option value="'.htmlspecialchars($dept_row['id']).'" '.$selected.'>'.htmlspecialchars($dept_row['department_name']).'</option>';
                        }
                    } else {
                        echo '<option value="">No departments available</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="" style="margin: 20px;">
                <label for="address">Complete Address:</label>
                <input type="text" id="address" name="edit_address" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['address']); ?>"> 
            </div>
            <h3>Personal Account</h3>
            <div class="" style="margin: 15px;">
                <div class="input-box">
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="edit_username" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['username']); ?>">
                </div>
            </div>
            <div class="" style="margin: 20px;">
                <label for="password">Password:</label>
                <input type="password" id="password" name="edit_password" required style="width: 100%; padding:15px;" value="<?php echo htmlspecialchars($row['password']); ?>">
            </div>
            <div class="" style="margin: 20px;">
                <label for="status">Status:</label>
                <select id="status" name="edit_status" required style="width: 100%; padding:15px;">
                    <option value="active" <?php if ($row['status'] == 'active') echo 'selected'; ?>>Active</option>
                    <option value="inactive" <?php if ($row['status'] == 'inactive') echo 'selected'; ?>>Inactive</option>
                </select>
            </div>
            <div class="button">
              <a href="register.php" class="status delete" style="text-decoration: none;">CANCEL</a>
              <button type="submit" name="updateuser_btn" class="status breakin">UPDATE</button> 
            </div>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>
<?php include('includes/scripts.php'); ?>
